<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-27
 * Time: 上午10:21
 * 
 */

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;
use Common\Model\ContentHandlerModel;

class IsayController extends AdminController {

    protected $isayModel;
    protected $isayCommentModel;
    protected $isayLikeModel;
    protected $isayDetailModel;
    protected $isayCategoryModel;

    function _initialize() {
        parent::_initialize();
        $this->isayModel = D('Isay/Isay');
        $this->isayDetailModel = D('Isay/IsayDetail');
        $this->isayCategoryModel = D('Isay/IsayCategory');
        $this->isayCommentModel = D('Isay/IsayComment');
        $this->isayLikeModel = D('Isay/IsayLike');
    }

    public function isayCategory() {
        //显示页面
        $builder = new AdminTreeListBuilder();

        $tree = $this->isayCategoryModel->getTree(0, 'id,title,sort,pid,status');

        $builder->title(L('_ISAY_CATEGORY_MANAGER_'))
                ->suggest(L('_CATEGORY_MANAGER_SUGGEST_'))
                ->buttonNew(U('Isay/add'))
                ->data($tree)
                ->display();
    }

    /*     * 分类添加
     * @param int $id
     * @param int $pid
     * 
     */

    public function add($id = 0, $pid = 0) {
        $title = $id ? L('_EDIT_') : L('_ADD_');
        if (IS_POST) {
            if ($this->isayCategoryModel->editData()) {
                S('SHOW_EDIT_BUTTON', null);
                $this->success($title . L('_SUCCESS_'), U('Isay/isayCategory'));
            } else {
                $this->error($title . L('_FAIL_') . $this->isayCategoryModel->getError());
            }
        } else {
            $builder = new AdminConfigBuilder();

            if ($id != 0) {
                $data = $this->isayCategoryModel->find($id);
            } else {
                $father_category_pid = $this->isayCategoryModel->where(array('id' => $pid))->getField('pid');
                if ($father_category_pid != 0) {
                    $this->error(L('_ERROR_CATEGORY_HIERARCHY_'));
                }
            }
            if ($pid != 0) {
                $categorys = $this->isayCategoryModel->where(array('pid' => 0, 'status' => array('egt', 0)))->select();
            }
            $opt = array();
            foreach ($categorys as $category) {
                $opt[$category['id']] = $category['title'];
            }
            $builder->title($title . L('_CATEGORY_'))
                    ->data($data)
                    ->keyId()->keyText('title', L('_TITLE_'))
                    ->keySelect('pid', L('_FATHER_CLASS_'), L('_FATHER_CLASS_SELECT_'), array('0' => L('_TOP_CLASS_')) + $opt)->keyDefault('pid', $pid)
                    ->keyRadio('can_post', L('_PLAY_YN_'), '', array(0 => L('_NO_'), 1 => L('_YES_')))->keyDefault('can_post', 1)
                    ->keyRadio('need_audit', L('_PLAY_YN_AUDIT_'), '', array(0 => L('_NO_'), 1 => L('_YES_')))->keyDefault('need_audit', 1)
                    ->keyInteger('sort', L('_SORT_'))->keyDefault('sort', 0)
                    ->keyStatus()->keyDefault('status', 1)
                    ->buttonSubmit(U('Isay/add'))->buttonBack()
                    ->display();
        }
    }

    /**
     * 设置资讯分类状态：删除=-1，禁用=0，启用=1
     * @param $ids
     * @param $status
     * 
     */
    public function setStatus($ids, $status) {
        !is_array($ids) && $ids = explode(',', $ids);
        if (in_array(1, $ids)) {
            $this->error(L('_ERROR_CANNOT_'));
        }
        if ($status == 0 || $status == -1) {
            $map['category'] = array('in', $ids);
            $this->isayModel->where($map)->setField('category', 1);
        }
        $builder = new AdminListBuilder();
        $builder->doSetStatus('isayCategory', $ids, $status);
    }

//分类管理end

    public function config() {
        $builder = new AdminConfigBuilder();
        $data = $builder->handleConfig();
        $default_position = <<<str
1:系统首页
2:推荐阅读
4:本类推荐
str;

        $builder->title(L('_ISAY_BASIC_CONF_'))->data($data);

        $builder->keyTextArea('ISAY_SHOW_POSITION', L('_GALLERY_CONF_'))->keyDefault('ISAY_SHOW_POSITION', $default_position)
                ->keyRadio('ISAY_ORDER_FIELD', L('_FRONT_LIST_SORT_'), L('_SORT_RULE_'), array('view' => L('_VIEWS_'), 'create_time' => L('_CREATE_TIME_'), 'update_time' => L('_UPDATE_TIME_')))->keyDefault('ISAY_ORDER_FIELD', 'create_time')
                ->keyRadio('ISAY_ORDER_TYPE', L('_LIST_SORT_STYLE_'), '', array('asc' => L('_ASC_'), 'desc' => L('_DESC_')))->keyDefault('ISAY_ORDER_TYPE', 'desc')
                ->keyInteger('ISAY_PAGE_NUM', '', L('_LIST_IN_PAGE_'))->keyDefault('ISAY_PAGE_NUM', '20')
                ->keyText('ISAY_SHOW_TITLE', L('_TITLE_NAME_'), L('_HOME_BLOCK_TITLE_'))->keyDefault('ISAY_SHOW_TITLE', L('_HOT_ISAY_'))
                ->keyText('ISAY_SHOW_COUNT', L('_ISAY_SHOWS_'), L('_TIP_ISAY_ARISE_'))->keyDefault('ISAY_SHOW_COUNT', 4)
                ->keyRadio('ISAY_SHOW_TYPE', L('_ISAY_SCREEN_'), '', array('1' => L('_BG_RECOMMEND_'), '0' => L('_EVERYTHING_')))->keyDefault('ISAY_SHOW_TYPE', 0)
                ->keyRadio('ISAY_SHOW_ORDER_FIELD', L('_SORT_VALUE_'), L('_TIP_SORT_VALUE_'), array('view' => L('_VIEWS_'), 'create_time' => L('_DELIVER_TIME_'), 'update_time' => L('_UPDATE_TIME_')))->keyDefault('ISAY_SHOW_ORDER_FIELD', 'view')
                ->keyRadio('ISAY_SHOW_ORDER_TYPE', L('_SORT_TYPE_'), L('_TIP_SORT_TYPE_'), array('desc' => L('_COUNTER_'), 'asc' => L('_DIRECT_')))->keyDefault('ISAY_SHOW_ORDER_TYPE', 'desc')
                ->keyText('ISAY_SHOW_CACHE_TIME', L('_CACHE_TIME_'), L('_TIP_CACHE_TIME_'))->keyDefault('ISAY_SHOW_CACHE_TIME', '600')
                ->keyRadio('ISAY_SHOW_LIKE', L('_ISAY_OPEN_CLOSE_LIKE_'), '', array(0 => L('_NO_'), 1 => L('_YES_')))->keyDefault('ISAY_SHOW_LIKE', 1)
                ->keyRadio('ISAY_SHOW_COMMENT', L('_ISAY_OPEN_CLOSE_COMMENT_'), '', array(0 => L('_NO_'), 1 => L('_YES_')))->keyDefault('ISAY_SHOW_COMMENT', 1)
                ->keyRadio('ISAY_COMMENT_ORDER', L('_COMMENTS_SORT_'), L('_DESC_DEFAULT_'), array(0 => L('_DESC_'), 1 => L('_ASC_')))->keyDefault('ISAY_COMMENT_ORDER', 0)
                ->keyText('ISAY_COMMENT_COUNT', L('_COMMENTS_PAGE_DISPLAY_COUNT_'), L('_COMMENTS_PAGE_DISPLAY_COUNT_DESC'))->keyDefault('ISAY_COMMENT_COUNT', 10)
                ->group(L('_BASIC_CONF_'), 'ISAY_SHOW_POSITION,ISAY_ORDER_FIELD,ISAY_ORDER_TYPE,ISAY_PAGE_NUM')
                ->group(L('_HOME_SHOW_CONF_'), 'ISAY_SHOW_COUNT,ISAY_SHOW_TITLE,ISAY_SHOW_TYPE,ISAY_SHOW_ORDER_TYPE,ISAY_SHOW_ORDER_FIELD,ISAY_SHOW_CACHE_TIME')
                ->group(L('_COMMENT_LIKE_CONF_'), 'ISAY_SHOW_COMMENT,ISAY_COMMENT_ORDER,ISAY_COMMENT_COUNT,ISAY_SHOW_LIKE')
                ->buttonSubmit()->buttonBack()
                ->display();
    }

    //资讯列表start
    public function index($page = 1, $r = 20) {
        $aCate = I('cate', 0, 'intval');
        if ($aCate) {
            $cates = $this->isayCategoryModel->getCategoryList(array('pid' => $aCate));
            if (count($cates)) {
                $cates = array_column($cates, 'id');
                $cates = array_merge(array($aCate), $cates);
                $map['category'] = array('in', $cates);
            } else {
                $map['category'] = $aCate;
            }
        }
        $aDead = I('dead', 0, 'intval');
        if ($aDead) {
            $map['dead_line'] = array('elt', time());
        } else {
            $map['dead_line'] = array('gt', time());
        }
        $aPos = I('pos', 0, 'intval');
        /* 设置推荐位 */
        if ($aPos > 0) {
            $map[] = "position & {$aPos} = {$aPos}";
        }

        $map['status'] = 1;

        $positions = $this->_getPositions(1);

        list($list, $totalCount) = $this->isayModel->getListByPage($map, $page, 'update_time desc', '*', $r);
        $category = $this->isayCategoryModel->getCategoryList(array('status' => array('egt', 0)), 1);
        $category = array_combine(array_column($category, 'id'), $category);
        foreach ($list as &$val) {
            $val['category'] = '[' . $val['category'] . '] ' . $category[$val['category']]['title'];
        }
        unset($val);

        $optCategory = $category;
        foreach ($optCategory as &$val) {
            $val['value'] = $val['title'];
        }
        unset($val);

        $builder = new AdminListBuilder();
        $builder->title(L('_ISAY_LIST_'))
                ->data($list)
                ->setSelectPostUrl(U('Admin/Isay/index'))
                ->select('', 'cate', 'select', '', '', '', array_merge(array(array('id' => 0, 'value' => L('_EVERYTHING_'))), $optCategory))
                ->select('', 'dead', 'select', '', '', '', array(array('id' => 0, 'value' => L('_ISAY_CURRENT_')), array('id' => 1, 'value' => L('_ISAY_HISTORY_'))))
                ->select(L('_RECOMMENDATIONS_'), 'pos', 'select', '', '', '', array_merge(array(array('id' => 0, 'value' => L('_ALL_DEFECTIVE_'))), $positions))
                ->buttonNew(U('Isay/editIsay'))
                ->keyId()->keyUid()->keyText('title', L('_TITLE_'))->keyText('category', L('_CATEGORY_'))->keyText('description', L('_NOTE_'))->keyText('sort', L('_SORT_'))
                ->keyStatus()->keyTime('dead_line', L('_PERIOD_TO_'))->keyCreateTime()->keyUpdateTime()
                ->keyDoActionEdit('Isay/editIsay?id=###');
        if (!$aDead) {
            $builder->ajaxButton(U('Isay/setDead'), '', L('_SET_EXPIRE_'))->keyDoAction('Isay/setDead?ids=###', L('_SET_EXPIRE_'));
        }
        $builder->pagination($totalCount, $r)
                ->display();
    }

    //待审核列表
    public function audit($page = 1, $r = 20) {
        $aAudit = I('audit', 0, 'intval');
        if ($aAudit == 3) {
            $map['status'] = array('in', array(-1, 2));
        } elseif ($aAudit == 2) {
            $map['dead_line'] = array('elt', time());
            $map['status'] = 2;
        } elseif ($aAudit == 1) {
            $map['status'] = -1;
        } else {
            $map['status'] = 2;
            $map['dead_line'] = array('gt', time());
        }
        list($list, $totalCount) = $this->isayModel->getListByPage($map, $page, 'update_time desc', '*', $r);
        $cates = array_column($list, 'category');
        $category = $this->isayCategoryModel->getCategoryList(array('id' => array('in', $cates), 'status' => 1), 1);
        $category = array_combine(array_column($category, 'id'), $category);
        foreach ($list as &$val) {
            $val['category'] = '[' . $val['category'] . '] ' . $category[$val['category']]['title'];
        }
        unset($val);

        $builder = new AdminListBuilder();

        $builder->title(L('_AUDIT_LIST_'))
                ->data($list)
                ->setStatusUrl(U('Isay/setIsayStatus'))
                ->buttonEnable(null, L('_AUDIT_SUCCESS_'))
                ->buttonModalPopup(U('Isay/doAudit'), null, L('_AUDIT_UNSUCCESS_'), array('data-title' => L('_AUDIT_FAIL_REASON_'), 'target-form' => 'ids'))
                ->setSelectPostUrl(U('Admin/Isay/audit'))
                ->select('', 'audit', 'select', '', '', '', array(array('id' => 0, 'value' => L('_AUDIT_READY_')), array('id' => 1, 'value' => L('_AUDIT_FAIL_')), array('id' => 2, 'value' => L('_EXPIRE_AND_UNAUDITED_')), array('id' => 3, 'value' => L('_AUDIT_ALL_'))))
                ->keyId()->keyUid()->keyText('title', L('_TITLE_'))->keyText('category', L('_CATEGORY_'))->keyText('description', L('_NOTE_'))->keyText('sort', L('_SORT_'));
        if ($aAudit == 1) {
            $builder->keyText('reason', L('_FAULT_REASON_'));
        }
        $builder->keyTime('dead_line', L('_PERIOD_TO_'))->keyCreateTime()->keyUpdateTime()
                ->keyDoActionEdit('Isay/editIsay?id=###')
                ->pagination($totalCount, $r)
                ->display();
    }

    /**
     * 审核失败原因设置
     * 
     */
    public function doAudit() {
        if (IS_POST) {
            $ids = I('post.ids', '', 'text');
            $ids = explode(',', $ids);
            $reason = I('post.reason', '', 'text');
            $res = $this->isayModel->where(array('id' => array('in', $ids)))->setField(array('reason' => $reason, 'status' => -1));
            if ($res) {
                $result['status'] = 1;
                $result['url'] = U('Admin/Isay/audit');
                //发送消息
                $messageModel = D('Common/Message');
                foreach ($ids as $val) {
                    $isay = $this->isayModel->getData($val);
                    $tip = L('_YOUR_ISAY_') . '【' . $isay['title'] . '】' . L('_FAIL_AND_REASON_') . $reason;
                    $messageModel->sendMessage($isay['uid'], L('_ISAY_AUDIT_FAIL_'), $tip, 'Isay/Index/detail', array('id' => $val), is_login(), 2);
                }
                //发送消息 end
            } else {
                $result['status'] = 0;
                $result['info'] = L('_OPERATE_FAIL_');
            }
            $this->ajaxReturn($result);
        } else {
            $ids = I('ids');
            $ids = implode(',', $ids);
            $this->assign('ids', $ids);
            $this->display(T('Isay@Admin/audit'));
        }
    }

    public function setIsayStatus($ids, $status = 1) {
        !is_array($ids) && $ids = explode(',', $ids);
        $builder = new AdminListBuilder();
        S('isay_home_data', null);
        //发送消息
        $messageModel = D('Common/Message');
        foreach ($ids as $val) {
            $isay = $this->isayModel->getData($val);
            $tip = L('_YOUR_ISAY_') . '【' . $isay['title'] . '】' . L('_AUDIT_SUCCESS_') . '。';
            $messageModel->sendMessage($isay['uid'], L('_ISAY_AUDIT_SUCCESS_'), $tip, 'Isay/Index/detail', array('id' => $val), is_login(), 2);
        }
        //发送消息 end
        $builder->doSetStatus('Isay', $ids, $status);
    }

    public function editIsay() {
        $aId = I('id', 0, 'intval');
        $title = $aId ? L('_EDIT_') : L('_ADD_');
        if (IS_POST) {
            $aId && $data['id'] = $aId;
            $data['uid'] = I('post.uid', get_uid(), 'intval');
            $data['title'] = I('post.title', '', 'op_t');
            $data['content'] = I('post.content', '', 'filter_content');
            $data['category'] = I('post.category', 0, 'intval');
            $data['description'] = I('post.description', '', 'op_t');
            $data['cover'] = I('post.cover', 0, 'intval');
            $data['view'] = I('post.view', 0, 'intval');
            $data['comment'] = I('post.comment', 0, 'intval');
            $data['collection'] = I('post.collection', 0, 'intval');
            $data['sort'] = I('post.sort', 0, 'intval');
            $data['dead_line'] = I('post.dead_line', 2147483640, 'intval');
            if ($data['dead_line'] == 0) {
                $data['dead_line'] = 2147483640;
            }
            //$data['template']=I('post.template','','op_t');
            $data['status'] = I('post.status', 1, 'intval');
            $data['source'] = I('post.source', '', 'op_t');
            $data['position'] = 0;
            $position = I('post.position', '', 'op_t');
            $position = explode(',', $position);
            foreach ($position as $val) {
                $data['position']+=intval($val);
            }
            $this->_checkOk($data);
            $result = $this->isayModel->editData($data);
            if ($result) {
                S('isay_home_data', null);
                $aId = $aId ? $aId : $result;
                $this->success($title . L('_SUCCESS_'), U('Isay/editIsay', array('id' => $aId)));
            } else {
                $this->error($title . L('_SUCCESS_'), $this->isayModel->getError());
            }
        } else {
            $position_options = $this->_getPositions();
            if ($aId) {
                $data = $this->isayModel->find($aId);
                $detail = $this->isayDetailModel->find($aId);
                $data['content'] = $detail['content'];
                //$data['template']=$detail['template'];
                $position = array();
                foreach ($position_options as $key => $val) {
                    if ($key & $data['position']) {
                        $position[] = $key;
                    }
                }
                $data['position'] = implode(',', $position);
            }
            $category = $this->isayCategoryModel->getCategoryList(array('status' => array('egt', 0)), 1);
            $options = array();
            foreach ($category as $val) {
                $options[$val['id']] = $val['title'];
            }
            $builder = new AdminConfigBuilder();
            $builder->title($title . L('_ISAY_'))
                    ->data($data)
                    ->keyId()
                    ->keyReadOnly('uid', L('_PUBLISHER_'))->keyDefault('uid', get_uid())
                    ->keyText('title', L('_TITLE_'))
                    ->keyEditor('content', L('_CONTENT_'), '', 'all', array('width' => '700px', 'height' => '400px'))
                    ->keySelect('category', L('_CATEGORY_'), '', $options)
                    ->keyTextArea('description', L('_NOTE_'))
                    ->keySingleImage('cover', L('_COVER_'))
                    ->keyInteger('view', L('_VIEWS_'))->keyDefault('view', 0)
                    ->keyInteger('comment', L('_COMMENTS_'))->keyDefault('comment', 0)
                    ->keyInteger('collection', L('_COLLECTS_'))->keyDefault('collection', 0)
                    ->keyInteger('sort', L('_SORT_'))->keyDefault('sort', 0)
                    ->keyTime('dead_line', L('_PERIOD_TO_'))->keyDefault('dead_line', 2147483640)
                    //->keyText('template',L('_TEMPLATE_'))
                    ->keyText('source', L('_SOURCE_'), L('_SOURCE_ADDRESS_'))
                    ->keyCheckBox('position', L('_RECOMMENDATIONS_'), L('_TIP_RECOMMENDATIONS_'), $position_options)
                    ->keyStatus()->keyDefault('status', 1)
                    ->group(L('_BASIS_'), 'id,uid,title,cover,content,category')
                    //->group(L('_EXTEND_'),'description,view,comment,sort,dead_line,position,source,template,status')
                    ->group(L('_EXTEND_'), 'description,view,comment,sort,dead_line,position,source,status')
                    ->buttonSubmit()->buttonBack()
                    ->display();
        }
    }

    public function setDead($ids) {
        !is_array($ids) && $ids = explode(',', $ids);
        $res = $this->isayModel->setDead($ids);
        if ($res) {
            //发送消息
            $messageModel = D('Common/Message');
            foreach ($ids as $val) {
                $isay = $this->isayModel->getData($val);
                $tip = L('_YOUR_ISAY_') . '【' . $isay['title'] . '】' . L('_SET_TO_EXPIRE_') . '。';
                $messageModel->sendMessage($isay['uid'], L('_ISAY_TO_EXPIRE_'), $tip, 'Isay/Index/detail', array('id' => $val), is_login(), 2);
            }
            //发送消息 end
            S('isay_home_data', null);
            $this->success(L('_SUCCESS_TIP_'), U('Isay/index'));
        } else {
            $this->error(L('_OPERATE_FAIL_') . $this->isayModel->getError());
        }
    }

    public function setIsayCommentStatus($ids, $status = 1) {
        !is_array($ids) && $ids = explode(',', $ids);
        $builder = new AdminListBuilder();
        //删除缓存
        foreach($ids as $v){
          S( $this->isayCommentModel->getCacheKey($v), null);  
        }
        
        //发送消息 end
        $builder->doSetStatus('IsayComment', $ids, $status);
    }

    //评论列表
    public function comment($page = 1, $r = 20) {
        $aStatus = I('audit', -1, 'intval');
        $map = array();
        if($aStatus>=0){
          $map['status'] = $aStatus;  
        }

        $totalCount = $this->isayCommentModel->getCommentRowsCount($map);
        $list = $this->isayCommentModel->getCommentRows($map, 'create_time DESC', $page, $r);

        $this->_set_comment_content($list);

        $builder = new AdminListBuilder();
        $builder->title(L('_COMMENT_LIST_'))
                ->data($list)
                ->buttonSetStatus(U('Isay/setIsayCommentStatus'),1, L('_AUDIT_SUCCESS_'))
                ->buttonSetStatus(U('Isay/setIsayCommentStatus'),2, L('_AUDIT_UNSUCCESS_'))
                ->buttonSetStatus(U('Isay/setIsayCommentStatus'),3, L('_FRONT_HIDDEN_'))
                ->setSelectPostUrl(U('Admin/Isay/comment'))
                ->select('', 'audit', 'select', '', '', '', array(array('id' => -1, 'value' => L('_ALL_')),array('id' => 0, 'value' => L('_AUDIT_READY_')), array('id' => 1, 'value' => L('_AUDIT_SUCCESS_')), array('id' => 2, 'value' => L('_AUDIT_FAIL_')), array('id' => 3, 'value' => L('_HIDE_DISPLAY_'))))
                ->keyId()->keyUid()->keyText('title', L('_TITLE_'))->keyText('content', L('_CONTENT_'));

        $builder->keyCreateTime()->keyText('status', L('_STATUS_'))                
                ->pagination($totalCount, $r)
                ->display();
    }

    private function _checkOk($data = array()) {
        if (!mb_strlen($data['title'], 'utf-8')) {
            $this->error(L('_TIP_TITLE_EMPTY_'));
        }
        if (mb_strlen($data['content'], 'utf-8') < 20) {
            $this->error(L('_TIP_CONTENT_LENGTH_'));
        }
        return true;
    }

    private function _getPositions($type = 0) {
        $default_position = <<<str
1:系统首页
2:推荐阅读
4:本类推荐
str;
        $positons = modC('ISAY_SHOW_POSITION', $default_position, 'Isay');
        $positons = str_replace("\r", '', $positons);
        $positons = explode("\n", $positons);
        $result = array();
        if ($type) {
            foreach ($positons as $v) {
                $temp = explode(':', $v);
                $result[] = array('id' => $temp[0], 'value' => $temp[1]);
            }
        } else {
            foreach ($positons as $v) {
                $temp = explode(':', $v);
                $result[$temp[0]] = $temp[1];
            }
        }

        return $result;
    }

    private function _set_comment_content(&$list) {
        foreach ($list as &$v) {
            if ($v['isay_id']) {
                $body = $this->isayModel->getData($v['isay_id']);
                $v['title'] = '【' . L('_ISAY_') . '】 ' . $body['title'];
            } else if ($v['pid']) {
                $body = $this->isayCommentModel->getComment($v['pid']);
                $v['title'] = '【' . L('_COMMENT_') . '】 ' . $body['content'];
            }

            switch ($v['status']) {
                case 0:
                    $v['status'] = L('_AUDIT_READY_');
                    break;
                case 1:
                    $v['status'] = L('_AUDIT_SUCCESS_');
                    break;
                case 2:
                    $v['status'] = L('_AUDIT_FAIL_');
                    break;
                case 3:
                    $v['status'] = L('_HIDE_DISPLAY_');
                    break;
            }
        }
    }

}
