<?php

namespace Isay\Controller;

use Think\Controller;

class IndexController extends BaseController {

    protected $isayModel;
    protected $isayDetailModel;
    protected $isayLikeModel;
    protected $isayCommentModel;
    protected $isayCategoryModel;

    function _initialize() {
        if (isset($_POST['keywords'])) {
            $_GET['keywords'] = json_encode(trim($_POST['keywords']));
        }
        $keywords = $_GET['keywords'];

        $this->isayModel = D('Isay/Isay');
        $this->isayDetailModel = D('Isay/IsayDetail');
        $this->isayLikeModel = D('Isay/IsayLike');
        $this->isayCommentModel = D('Isay/IsayComment');
        $this->isayCategoryModel = D('Isay/IsayCategory');

        $tree = $this->isayCategoryModel->getTree(0, true, array('status' => 1));
        $this->assign('tree', $tree);
        $menu_list['left'][] = array('title' => L('_HOME_'), 'href' => U('Isay/Index/index'), 'tab' => 'home');
        foreach ($tree as $category) {
            $menu = array('tab' => 'category_' . $category['id'], 'title' => $category['title'], 'href' => U('Isay/index/index', array('category' => $category['id'], 'keywords' => $keywords)));
            if ($category['_']) {
                $menu['children'][] = array('title' => L('_EVERYTHING_'), 'href' => U('Isay/index/index', array('category' => $category['id'], 'keywords' => $keywords)));
                foreach ($category['_'] as $child)
                    $menu['children'][] = array('title' => $child['title'], 'href' => U('Isay/index/index', array('category' => $child['id'], 'keywords' => $keywords)));
            }
            $menu_list['left'][] = $menu;
        }
        $menu_list['right'] = array();
        if (is_login()) {
            $menu_list['right'][] = array('tab' => 'myIsay', 'title' => L('_MY_CONTRIBUTIONS_'), 'href' => U('Isay/index/my'));
        }

        $show_edit = S('SHOW_EDIT_BUTTON');
        if ($show_edit === false) {
            $map['can_post'] = 1;
            $map['status'] = 1;
            $show_edit = $this->isayCategoryModel->where($map)->count();
            S('SHOW_EDIT_BUTTON', $show_edit);
        }
        if ($show_edit) {
            $menu_list['right'][] = array('tab' => 'create', 'title' => '<i class="icon-edit"></i>' . L('_CONTRIBUTIONS_'), 'href' => is_login() ? U('Isay/index/edit') : "javascript:toast.error('" . L('_LOG_TIP_') . "')");
            $menu_list['right'][] = array('type' => 'search', 'input_title' => L('_INPUT_TIP_'), 'input_name' => 'keywords', 'from_method' => 'post', 'action' => U('Isay/index/index'));
        }
        $menu_list['first'] = array('title' => L('_NEWS_'));

        $this->assign('tab', 'home');
        $this->assign('sub_menu', $menu_list);
    }

    public function index($page = 1) {
        $page = I('get.p', '1', 'intval');
        if (json_decode($_GET['keywords']) != '') {
            $keywords = json_decode($_GET['keywords']);
            $this->assign('search_keywords', $keywords);
            $map['title|description'] = array('like', '%' . $keywords . '%');
        } else {
            $_GET['keywords'] = null;
        }
        /* 分类信息 */
        $category = I('category', 0, 'intval');
        $current = '';
        if ($category) {
            $this->_category($category);
            $cates = $this->isayCategoryModel->getCategoryList(array('pid' => $category, 'status' => 1));
            if (count($cates)) {
                $cates = array_column($cates, 'id');
                $cates = array_merge(array($category), $cates);
                $map['category'] = array('in', $cates);
            } else {
                $map['category'] = $category;
            }
            $now_category = $this->isayCategoryModel->find($category);
            $cid = $now_category['pid'] == 0 ? $now_category['id'] : $now_category['pid'];
            $current = 'category_' . $cid;
        }
        $map['dead_line'] = array('gt', time());
        $map['status'] = 1;

        $order_field = modC('ISAY_ORDER_FIELD', 'create_time', 'Isay');
        $order_type = modC('ISAY_ORDER_TYPE', 'desc', 'Isay');
        $order = 'sort desc,' . $order_field . ' ' . $order_type;


        /* 获取当前分类下资讯列表 */
        list($list, $totalCount) = $this->isayModel->getListByPage($map, $page, $order, '*', modC('ISAY_PAGE_NUM', 20, 'Isay'));
        foreach ($list as &$val) {
            $val['user'] = query_user(array('space_url', 'nickname'), $val['uid']);
        }
        unset($val);

        /* 模板赋值并渲染模板 */
        $this->assign('list', $list);
        $this->assign('category', $category);
        $this->assign('totalCount', $totalCount);
        $this->assign('page', $page);
        $this->assign('tab', $current);
        $this->display();
    }

    public function detail() {
        $aId = I('id', 0, 'intval');
        $page = I('get.p', 0, 'intval');

        /* 标识正确性检测 */
        if (!($aId && is_numeric($aId))) {
            $this->error(L('_ERROR_ID_'));
        }

        $info = $this->isayModel->getData($aId);
        if ($info['dead_line'] <= time() && !check_auth('Isay/Index/edit', $info['uid'])) {
            $this->error(L('_ERROR_EXPIRE_'));
        }
        $author = query_user(array('uid', 'space_url', 'nickname', 'avatar64', 'signature'), $info['uid']);
        $author['isay_count'] = $this->isayModel->where(array('uid' => $info['uid']))->count();

        $this->_category($info['category']);

        /* 更新浏览数 */
        $this->isayModel->where(array('id' => $aId))->setInc('view');
        /* 模板赋值并渲染模板 */
        $this->assign('author', $author);
        $this->assign('info', $info);
        $this->assign('page', $page);
        $this->setTitle('{$info.title|text} —— {:L("_MODULE_")}');
        $this->setDescription('{$info.description|text} ——{:L("_MODULE_")}');
        $this->display();
    }

    /**
      public function my($page = 1) {
      $this->_needLogin();
      $map['uid'] = get_uid();
      //获取当前分类下资讯列表
      list($list, $totalCount) = $this->isayModel->getListByPage($map, $page, 'update_time desc', '*', modC('NEWS_PAGE_NUM', 20, 'Isay'));
      foreach ($list as &$val) {
      if ($val['dead_line'] <= time()) {
      $val['audit_status'] = '<span style="color: #7f7b80;">' . L('_EXPIRE_') . '</span>';
      } else {
      if ($val['status'] == 1) {
      $val['audit_status'] = '<span style="color: green;">' . L('_AUDIT_SUCCESS_') . '</span>';
      } elseif ($val['status'] == 2) {
      $val['audit_status'] = '<span style="color:#4D9EFF;">' . L('_AUDIT_READY_') . '</span>';
      } elseif ($val['status'] == -1) {
      $val['audit_status'] = '<span style="color: #b5b5b5;">' . L('_AUDIT_FAIL_') . '</span>';
      }
      }
      }
      unset($val);
      //模板赋值并渲染模板
      $this->assign('list', $list);
      $this->assign('totalCount', $totalCount);

      $this->assign('tab', 'myIsay');
      $this->display();
      }

      public function edit() {
      $this->_needLogin();
      if (IS_POST) {
      $this->_doEdit();
      } else {
      $aId = I('id', 0, 'intval');
      if ($aId) {
      $data = $this->isayModel->getData($aId);
      if (!check_auth('Isay/Index/edit', -1)) {
      if ($data['uid'] == is_login()) {
      if ($data['status'] == 1) {
      $this->error(L('_ERROR_EDIT_DENY_'));
      }
      } else {
      $this->error(L('_ERROR_EDIT_LIMIT_'));
      }
      }
      $this->assign('data', $data);
      } else {
      $this->checkAuth('Isay/Index/add', -1, L('_ERROR_CONTRIBUTION_LIMIT_'));
      }
      $title = $aId ? L('_EDIT_') : L('_ADD_');
      $category = $this->isayCategoryModel->getCategoryList(array('status' => 1, 'can_post' => 1), 1);
      $this->assign('category', $category);
      $this->assign('title', $title);
      }
      $this->assign('tab', 'create');
      $this->display();
      }

      private function _doEdit() {
      $aId = I('post.id', 0, 'intval');
      $data['category'] = I('post.category', 0, 'intval');

      if ($aId) {
      $data['id'] = $aId;
      $now_data = $this->isayModel->getData($aId);
      if (!check_auth('Isay/Index/edit', -1)) {
      if ($now_data['uid'] == is_login()) {
      if ($now_data['status'] == 1) {
      $this->error(L('_ERROR_EDIT_DENY_'));
      }
      } else {
      $this->error(L('_ERROR_EDIT_LIMIT_'));
      }
      }
      $category = $this->isayCategoryModel->where(array('status' => 1, 'id' => $data['category']))->find();
      if ($category) {
      if ($category['can_post']) {
      if ($category['need_audit'] && !check_auth('Admin/Isay/setIsayStatus')) {
      $data['status'] = 2;
      } else {
      $data['status'] = 1;
      }
      } else {
      $this->error(L('_ERROR_CONTRIBUTION_DENY_'));
      }
      } else {
      $this->error(L('_ERROR_NONE_'));
      }
      $data['template'] = $now_data['detail']['template']? : '';
      } else {
      $this->checkAuth('Isay/Index/add', -1, L('_ERROR_CONTRIBUTION_LIMIT_'));
      $this->checkActionLimit('add_isay', 'Isay', 0, is_login(), true);
      $data['uid'] = get_uid();
      $data['sort'] = $data['position'] = $data['view'] = $data['comment'] = $data['collection'] = 0;
      $category = $this->isayCategoryModel->where(array('status' => 1, 'id' => $data['category']))->find();
      if ($category) {
      if ($category['can_post']) {
      if ($category['need_audit'] && !check_auth('Admin/Isay/setIsayStatus')) {
      $data['status'] = 2;
      } else {
      $data['status'] = 1;
      }
      } else {
      $this->error(L('_ERROR_CONTRIBUTION_DENY_'));
      }
      } else {
      $this->error(L('_ERROR_NONE_'));
      }
      $data['template'] = '';
      }
      $data['title'] = I('post.title', '', 'text');
      $data['cover'] = I('post.cover', 0, 'intval');
      $data['description'] = I('post.description', '', 'text');
      $data['dead_line'] = I('post.dead_line', '', 'text');
      if ($data['dead_line'] == '') {
      $data['dead_line'] = 2147483640;
      } else {
      $data['dead_line'] = strtotime($data['dead_line']);
      }
      $data['source'] = I('post.source', '', 'text');
      $data['content'] = I('post.content', '', 'filter_content');

      if (!mb_strlen($data['title'], 'utf-8')) {
      $this->error(L('_TIP_TITLE_EMPTY_'));
      }
      if (mb_strlen($data['content'], 'utf-8') < 20) {
      $this->error(L('_TIP_CONTENT_LENGTH_'));
      }

      $res = $this->isayModel->editData($data);
      $title = $aId ? L('_EDIT_') : L('_ADD_');
      if ($res) {
      if (!$aId) {
      $aId = $res;
      if ($category['need_audit'] && !check_auth('Admin/Isay/setIsayStatus')) {
      $this->success($title . L('_TIP_SUCCESS_') . cookie('score_tip') . L('_TIP_AUDIT_'), U('Isay/Index/detail', array('id' => $aId)));
      }
      }
      $this->success($title . L('_TIP_SUCCESS_') . cookie('score_tip'), U('Isay/Index/detail', array('id' => $aId)));
      } else {
      $this->error($title . L('_TIP_FAIL_') . $this->isayModel->getError());
      }
      }
     */
    private function _category($id = 0) {
        $now_category = $this->isayCategoryModel->getTree($id, 'id,title,pid,sort', array('status' => 1));
        $this->assign('now_category', $now_category);
        return $now_category;
    }

    private function _needLogin() {
        if (!is_login()) {
            $this->error(L('_TIP_LOGIN_'));
        }
    }

}
