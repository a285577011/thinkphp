<?php
/**
 * 帮助中心
 */
namespace Help\Controller;
use Think\Controller;

class IndexController extends Controller
{

    protected $helpModel;

    protected $helpCategoryModel;

    function _initialize ()
    {
        $this->helpModel = D('Help/Help');
        $this->helpCategoryModel = D('Help/HelpCategory');
        
        $catTitle = modC('HELP_CATEGORY_TITLE', L('_MODULE_'), 'Help');
        
        $sub_menu['left'][] = array( 'tab' => 'home', 'title' => $catTitle, 'href' => U('index') );
        $sub_menu['first'] = array( 'title' => L('_MODULE_') );
        $this->assign('sub_menu', $sub_menu);
        $this->assign('current', 'home');
    }

    /**
     * 首页
     */
    public function index ()
    {
        $catList = $this->helpCategoryModel->getTree();
        
        $data = array();
        foreach ($catList as $k => $v){
            $data[$k] = array('id' => $v['id'], 'title' => $v['title']);
            $sub = array();
            foreach ($v['_'] as $ck => $cv){
                $sub[] = array('id' => $cv['id'], 'title' => $cv['title'], 'artlist' => $this->helpModel->where(array('category'=>$cv['id']))->getField('id,title,update_time'));
                //$sub[$sk]['artlist'] = $this->helpModel->where(array('category'=>$cv['id']))->getField('id,title');
                $data[$k]['sub'] = $sub;
            }
        }
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 分类列表页，公告与其他分类模板不同
     * @param number $page
     */
    public function lists($page=1)
    {
        /* 获取当前分类下资讯列表 */
        list($list,$totalCount) = $this->helpModel->getListByPage($map,$page,'update_time desc','*',modC('NEWS_PAGE_NUM',20,'News'));
        foreach($list as &$val){
            if($val['dead_line']<=time()){
                $val['audit_status']= '<span style="color: #7f7b80;">'.L('_EXPIRE_').'</span>';
            }else{
                if($val['status']==1){
                    $val['audit_status']='<span style="color: green;">'.L('_AUDIT_SUCCESS_').'</span>';
                }elseif($val['status']==2){
                    $val['audit_status']='<span style="color:#4D9EFF;">'.L('_AUDIT_READY_').'</span>';
                }elseif($val['status']==-1){
                    $val['audit_status']='<span style="color: #b5b5b5;">'.L('_AUDIT_FAIL_').'</span>';
                }
            }

        }
        unset($val);
        /* 模板赋值并渲染模板 */
        $this->assign('list', $list);
        $this->assign('totalCount',$totalCount);
        
        $this->display();
    }

    /**
     * 内容详情页，公告与其他分类模板不同
     */
    public function detail()
    {
        $aId=I('id',0,'intval');

        /* 标识正确性检测 */
        if (!($aId && is_numeric($aId))) {
            $this->error(L('_ERROR_ID_'));
        }

        $info=$this->helpModel->getData($aId);
        if($info['dead_line']<=time()&&!check_auth('News/Index/edit',$info['uid'])){
            $this->error(L('_ERROR_EXPIRE_'));
        }
        $author=query_user(array('uid','space_url','nickname','avatar64','signature'),$info['uid']);
        $author['help_count']=$this->helpModel->where(array('uid'=>$info['uid']))->count();
        /* 获取模板 */
        if (!empty($info['detail']['template'])) { //已定制模板
            $tmpl = 'Index/tmpl/'.$info['detail']['template'];
        } else { //使用默认模板
            $tmpl = 'Index/tmpl/detail';
        }

        $this->_category($info['category']);

        /* 更新浏览数 */
        $map = array('id' => $aId);
        $this->helpModel->where($map)->setInc('view');
        /* 模板赋值并渲染模板 */
        $this->assign('author',$author);
        $this->assign('info', $info);
        $this->setTitle('{$info.title|text} —— {:L("_MODULE_")}');
        $this->setDescription('{$info.description|text} ——{:L("_MODULE_")}');
        $this->display($tmpl);
    }
    
    /**
     * 意见反馈
     */
    public function feedback(){
        $this->display();
    }

    private function _category($id=0)
    {
        $now_category=$this->newsCategoryModel->getTree($id,'id,title,pid,sort',array('status'=>1));
        $this->assign('now_category',$now_category);
        return $now_category;
    }
} 