<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;
use Think\Model;

/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class IndexController extends Controller
{
    
    // 系统首页
    public function index()
    {
        $userCategoryModel = D('Ucenter/UserCategory');
        $memberModel = D('Common/Member');
        
        // 轮播广告
        $advs = D('Advs')->getAdv(9);
        
        // i.cn说 4条
        $isay_list = D('Isay/Isay')->getIsayHot(4);
        
        // 商品微商分类 40个
        $category_list_goods = $userCategoryModel->getHot(1, 40);
        
        // 非商品微商分类 40个
        $category_list_not_goods = $userCategoryModel->getHot(2, 40);
        
        // 最新微商 8个
        $member_newest = $memberModel->newestList(8);
        
        // 微商红人 8个
        $member_hot = $memberModel->hotList(16);
        
        // 本期一元爱拍 8个
        $ipai_list = D('Ipai/PaiProduct')->getIpaiHot(8);
        
        // 微商学院3个tab 各6个
        
        // 一手货源(暂无)
        
        // 微商资讯 8个

        $this->assign('advs', $advs);
        $this->assign('isay_list', $isay_list);
        $this->assign('category_list_goods', $category_list_goods);
        $this->assign('category_list_not_goods', $category_list_not_goods);
        $this->assign('member_newest', $member_newest);
        $this->assign('member_hot', $member_hot);
        $this->assign('ipai_list', $ipai_list);
        $this->display();
    }
    
    /**
     * 热门微商
     */
    public function hot(){
        $map = $this->setMap();
        $map['where']['status'] = 1;
        $map['page'] = I('p', 1, 'intval');
        //$total = D('Member')->where($map['where'])->getCount();

        $map['limit'] = 10;
        
        $res = D('Common/Member')->getMemberList($map);
        $total = $res['total'];
        $list = $res['list'];

        foreach ($list as $k => &$v) {
            $v = query_user(array('username', 'avatar', 'nickname', 'uid', 'catid', 'sex', 'tags', 'level', 'fans', 'following', 'signature', 'ipai', 'huoyuan', 'is_following', 'is_followed'), $v['id']);
        }
        unset($v);
        
        //$pages = getPageView($total, $map['limit'], I('request.'), true, true);

        $page = new \Think\Page($total, $map['limit'], I('request.'));
        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %GOTO_PAGE%');
            $page->style = 1;
            $page_bottom = $page->show();
        }

        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %DOWN_PAGE%');
            $page->style = 1;
            $page_top = $page->show();
        }
        $this->assign('page', $page_bottom ? $page_bottom : '');
        $this->assign('page_top', $page_top ? $page_top : '');
        $this->assign('total_page', $page->totalPages);
        $this->assign('now_page', $page->nowPage);

        $this->assign('tab','index');
        $this->assign('lists', $list);
        $this->display();
    }
    
    /**
     * 最新微商
     */
    public function newest(){
        $map = $this->setMap();
        $map['where']['status'] = 1;
        $map['page'] = I('p', 1, 'intval');

        //$total = D('Member')->getCount($map['where']);

        $map['limit'] = 64;
        $map['order'] = 'reg_time DESC';
        
        $res = D('Common/Member')->getMemberList($map);
        $total = $res['total'];
        $list = $res['list'];
        foreach ($list as &$v) {
            $v = query_user(array('username', 'avatar', 'nickname', 'uid', 'sex', 'catid', 'reg_time'), $v['id']);
        }
        unset($v);
        $page = new \Think\Page($total, $map['limit'], I('request.'));
        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %GOTO_PAGE%');
            $page->style = 1;
            $page_bottom = $page->show();
        }

        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %DOWN_PAGE%');
            $page->style = 1;
            $page_top = $page->show();
        }
        $this->assign('page', $page_bottom ? $page_bottom : '');
        $this->assign('page_top', $page_top ? $page_top : '');
        $this->assign('total_page', $page->totalPages);
        $this->assign('now_page', $page->nowPage);

        $this->assign('tab','index');
        $this->assign('list', $list);
        $this->display();
    }

    /**
     * 设置筛选条件
     */
    private function setMap()
    {
		$uid = I('uid', 0, 'intval');
		$keyword = I('keyword', '', 'op_t');
		$tagid = I('tagid', 0, 'intval');
		$catid = I('catid', 0, 'intval');
		$type = I('type', 0, 'intval');
		$push = I('push', 0, 'intval');
		$sex= I('sex', 0, 'intval');
		$city = I('city', 0, 'intval');
		$order = I('order', '', 'op_t');
		$sort = I('sort', '', 'op_t');
        $page_no = I('p', 1, 'intval');
        $page_size = 10; //默认10条
        
        $sort_arr = array('asc', 'desc');
        
		$map = array();
		$push == 1 ? $map['where']['hot'] = 1 : ($push == 2 ? $map['where']['hot'] = 0 : '');

		if($catid){//选择主分类
		    $map['where']['catid'] = $catid;
		    $this->assign('catid', $catid);
		}
		
		if($keyword !== ''){
	        // $tags = D('Ucenter/UserTag')->getAllTags(array('keyword'=>$keyword));
	        // 关键词主标签精确搜索
	        $catinfo1 = D('Ucenter/UserCategory')->getByTitle($keyword, 1);
	        $catinfo2 = D('Ucenter/UserCategory')->getByTitle($keyword, 2);
	        if(!$catinfo1 && !$catinfo2){
	            $map['where']['catid'] = -1;
	        }else{
	            $catinfo1 && $catids[] = $catinfo1['id'];
	            $catinfo2 && $catids[] = $catinfo2['id'];
	            $map['where']['catid'] = $catids;
	        }
	        // $map['where']['all_tag'] = explode(',', $tags['ids']);
            $this->assign('keywords', $keyword);
		}

        if($type){//选择类型
            $map['where']['type'] = $type;
            $this->assign('type', $type);
        }
        
        if($tagid){//选择副标签
            $map['where']['tags'] = $tagid;
            $this->assign('tagid', $tagid);
        }
        if($sex){//选择性别
            $map['where']['sex'] = $sex;
            $this->assign('sex', $catid);
        }
        if($city){//选择城市
            $map['where']['pos_city'] = $city;
            $this->assign('city', $city);
        }
        
        $filter = $this->_filter();
        
        // 分类
        $filter['cat_list'] = D('Ucenter/UserCategory')->getHot($type);
        
        switch($sort){
            case 'score':
                $map['order'] = 'score1 DESC';
                break;
            case 'fans':
                $map['order'] = 'fans DESC';
                break;
            case 'views':
                $map['order'] = 'views DESC';
                break;
            case 'comment':
                $map['order'] = 'comment DESC';
                break;
            default:
                //$map['order'] = 'reg_time DESC';
                break;
        }
        $this->assign($filter);
        return $map;
    }
    
    /**
     * 标签列表页
     */
    public function tag(){
        $letter = I('letter', '', 'op_t');
        $type = I('type', 1, 'intval');
        
        $map['where']['status'] = 1;
        $map['where']['type'] = $type;
        $map['order'] = 'sort DESC';
        $map['page'] = I('p', 1, 'intval');
        $map['limit'] = 240;
        $letter !== '' && $map['where']['letter'] = $letter;
        $total = D('Ucenter/UserCategory')->where($map['where'])->count();
        $list = D('Ucenter/UserCategory')->getList($map);
        foreach ($list as $k => &$v){
            $v = D('Ucenter/UserCategory')->getById($v);
        }
        
        $page = new \Think\Page($total, $map['limit'], I('request.'));
        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %GOTO_PAGE%');
            $page->style = 1;
            $page_bottom = $page->show();
        }

        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %DOWN_PAGE%');
            $page->style = 1;
            $page_top = $page->show();
        }
        $this->assign('page', $page_bottom ? $page_bottom : '');
        $this->assign('page_top', $page_top ? $page_top : '');
        $this->assign('total_page', $page->totalPages);
        $this->assign('now_page', $page->nowPage);

        $letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9');
        $types = D('Ucenter/UserCategory')->getType();
        
        $type_title = $types[$type];
        
        $this->assign('letters', $letters);
        $this->assign('list', $list);
        $this->assign('types', $types);
        $this->assign('type_title', $type_title);
        $this->display();
    }
    
    /**
     * 选择标签结果
     */
    public function tagDetail(){
        $map = $this->setMap();
        $page_no = I('p', 1, 'intval');
        $page_size = 10; //默认10条
        
        if($map['where']['catid']){
            $catinfo = D('Ucenter/UserCategory')->getById($map['where']['catid']);
            if(!$catinfo){
                E('页面不存在', 815);
            }
        }elseif($map['where']['tags']){
            $catinfo = D('Ucenter/UserTag')->getById($map['where']['tags']);
            if(!$catinfo){
                E('页面不存在', 815);
            }
        }
        
        $map['where']['status'] = 1;
        $map['page'] = I('p', 1, 'intval');
        $map['limit'] = 10;
        
        $res = D('Common/Member')->getMemberList($map);
        $total = $res['total'];
        $list = $res['list'];
        $districtModel = D('District');
        foreach ($list as $k => &$v){
            $v = query_user(array('username', 'avatar', 'nickname', 'uid', 'sex', 'catid', 'tags', 'level', 'fans', 'following', 'signature', 'pos_province', 'pos_city'), $v['id']);
            $v['province'] = $districtModel->getNameById($v['pos_province']);
            $v['city'] = $districtModel->getNameById($v['pos_city']);
        }
        unset($v);
        
        // TODO 相关搜索
        $recommendcat = D('Ucenter/UserCategory')->getHot($catinfo['type'], 20);
        $page = new \Think\Page($total, $map['limit'], I('request.'));
        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %GOTO_PAGE%');
            $page->style = 1;
            $page_bottom = $page->show();
        }

        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %DOWN_PAGE%');
            $page->style = 1;
            $page_top = $page->show();
        }
        $this->assign('page', $page_bottom ? $page_bottom : '');
        $this->assign('page_top', $page_top ? $page_top : '');
        $this->assign('total_page', $page->totalPages);
        $this->assign('now_page', $page->nowPage);
            
        $this->assign($this->_filter());

        $this->assign('recommendcat', $recommendcat);
        $this->assign('list', $list);
        $this->assign('total', $total);
        $this->assign('catinfo', $catinfo);
        $this->display();
    }
    
    /**
     * 搜索结果
     */
    public function search(){
        $map = $this->setMap();
        $page_no = I('p', 1, 'intval');
        $page_size = 10; //默认10条
        
        $sort_arr = array('asc', 'desc');
        
        $map['where']['status'] = 1;
        $uid = is_login();
        $keyword = I('get.keyword', '', 'op_t');
		
		//$map['field'] = 'uid';
		$map['page'] = $page_no;
		$map['limit'] = $page_size;
		
        $res = D('Common/Member')->getMemberList($map);
        
        /* if(!$res['total'] && $keyword !== ''){
            $keyword && $map['keyword'] = $keyword;
            unset($map['where']['all_tag']);
            $res = D('Common/Member')->getMemberList($map);
        } */
        
        $total = $res['total'];
        $list = $res['list'];
        $districtModel = D('District');
		foreach ($list as $k => &$v){
		    $v = query_user(array('uid', 'username', 'nickname', 'sex', 'catid', 'level', 'fans', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'is_following', 'is_followed', 'signature', 'pos_province', 'pos_city'), $v['id'], $uid);
		    $v['province'] = $districtModel->getNameById($v['pos_province']);
		    $v['city'] = $districtModel->getNameById($v['pos_city']);
		}
		unset($v);
        
        // TODO 相关搜索
        $recommendcat = D('Ucenter/UserCategory')->getHot(0, 20);

        $page = new \Think\Page($total, $map['limit'], I('request.'));
        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %FIRST% %LINK_PAGE% %END% %DOWN_PAGE% %GOTO_PAGE%');
            $page->style = 1;
            $page_bottom = $page->show();
        }

        if ($total > $map['limit']) {
            $page->setConfig('theme', '%UP_PAGE% %DOWN_PAGE%');
            $page->style = 1;
            $page_top = $page->show();
        }
        $this->assign('page', $page_bottom ? $page_bottom : '');
        $this->assign('page_top', $page_top ? $page_top : '');
        $this->assign('total_page', $page->totalPages);
        $this->assign('now_page', $page->nowPage);
            
        $this->assign($this->_filter());

        $this->assign('recommendcat', $recommendcat);
        $this->assign('list', $list);
        $this->assign('total', $total);
        $this->assign('keyword', $keyword);
        $this->display();
    }
    
    /**
     * 省市筛选数据
     */
    protected function _filter(){
        $param = I('get.', '', 'op_t');
        
        // 精确查找
        $filter['type_list'] = array(
                1 => array( 'name' => '全部微商', 'url' => U('', $param)),
                2 => array( 'name' => '主推微商', 'url' => U('', array_merge($param, array('push' => 1)))),
                3 => array( 'name' => '相关微商', 'url' => U('', array_merge($param, array('push' => 2)))),
                4 => array( 'name' => '商品微商', 'url' => U('', array_merge($param, array('type' => 1)))),
                5 => array( 'name' => '商品微商', 'url' => U('', array_merge($param, array('type' => 1, 'push' => 1)))),
                6 => array( 'name' => '商品微商', 'url' => U('', array_merge($param, array('type' => 1, 'push' => 2)))),
                7 => array( 'name' => '非商品微商', 'url' => U('', array_merge($param, array('type' => 2)))),
                8 => array( 'name' => '非商品微商', 'url' => U('', array_merge($param, array('type' => 2, 'push' => 1)))),
                9 => array( 'name' => '非商品微商', 'url' => U('', array_merge($param, array('type' => 2, 'push' => 2)))),
        );
        
        // 地区 TODO 缓存页面
        $filter['area_list']['hot'] = D('District')->getHotCity();
        $filter['area_list']['city'] = D('District')->getAllProvinceCity();
        
        // 性别
        $filter['sex_list'] = array(
                1 => array('name' => '男', 'url' => U('', array_merge($param, array('sex' => 1)))),
                2 => array('name' => '女', 'url' => U('', array_merge($param, array('sex' => 2)))),
        );
        
        // 等级\人气\评论
        $filter['sort_list'] = array(
                1 => array('name' => '等级', 'url' => U('', array_merge($param, array('sort' => 'score')))),
                2 => array('name' => '粉丝', 'url' => U('', array_merge($param, array('sort' => 'fans')))),
                3 => array('name' => '访问数量', 'url' => U('', array_merge($param, array('sort' => 'views')))),
        );
        return $filter;
    }

    protected function _initialize()
    {

        /*读取站点配置*/
        $config = api('Config/lists');
        C($config); //添加配置

        if (!C('WEB_SITE_CLOSE')) {
            $this->error(L('_ERROR_WEBSITE_CLOSED_'));
        }
    }
    
    public function collect(){
        vendor('QueryList.QueryList');
        //实例化一个采集对象
        $hj = new \QueryList('http://s.weibo.com/user/%25E5%25BE%25AE%25E4%25BF%25A1%25E5%258F%25B7&Refer=index',array('title'=>array('.person_name','text')));
        //输出结果：二维关联数组
        print_r($hj->jsonArr);
        //输出结果：JSON数据
        //echo $hj->getJSON();
    }


}