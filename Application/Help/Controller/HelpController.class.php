<?php
/**
 * 帮助中心
 */

namespace Admin\Controller;


use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;
use Common\Model\ContentHandlerModel;

class HelpController extends AdminController{

    protected $helpModel;
    protected $helpCategoryModel;

    function _initialize()
    {
        parent::_initialize();
        $this->helpModel = D('Help/Help');
        $this->helpCategoryModel = D('Help/HelpCategory');
    }

    /**
     * 帮助中心分类
     * 
     */
    public function helpCategory()
    {
        //显示页面
        $builder = new AdminTreeListBuilder();

        $tree = $this->helpCategoryModel->getTree(0, 'id,title,sort,pid,status');

        //$list=$this->helpCategoryModel->getCategoryList(array('status'=>array('egt',0)));

        $builder->title(L('_HELP_CATEGORY_MANAGER_'))
            ->suggest(L('_HELP_SUGGEST_'))
            //->setStatusUrl(U('Help/setCategoryStatus'))
            ->buttonNew(U('Help/add'))
            ->setLevel(3)
            //->buttonEnable()->buttonDisable()->buttonDelete()
            //->keyId()
            //->keyText('title',L('_CATEGORY_NAME_'))
            //->keyText('sort',L('_SORT_'))
            //->keyStatus('status',L('_STATUS_'))
            //->keyDoActionEdit('Help/editCategory?id=###')
            ->data($tree)
            ->display();
    }
    


    /**分类添加
     * @param int $id
     * @param int $pid
     *
     */
    public function add($id = 0, $pid = 0)
    {
        $title=$id?L('_EDIT_'):L('_ADD_');
        if (IS_POST) {
            if ($this->helpCategoryModel->editData()) {
                S('SHOW_EDIT_BUTTON',null);
                $this->success($title.L('_SUCCESS_'), U('Help/helpCategory'));
            } else {
                $this->error($title.L('_FAIL_').$this->helpCategoryModel->getError());
            }
        } else {
            $builder = new AdminConfigBuilder();
    
            if ($id != 0) {
                $data = $this->helpCategoryModel->find($id);
            }
            
            if($pid!=0){
                $categorys = $this->helpCategoryModel->where(array('id'=>$pid,'status'=>array('egt',0)))->select();
            }
            $opt = array();
            foreach ($categorys as $category) {
                $opt[$category['id']] = $category['title'];
            }
            $builder->title($title.L('_CATEGORY_'))
            ->data($data)
            ->keyId()->keyText('title', L('_TITLE_'))
            ->keySelect('pid',L('_FATHER_CLASS_'), L('_FATHER_CLASS_SELECT_'), array('0' =>L('_TOP_CLASS_')) + $opt)->keyDefault('pid',$pid)
            ->keyInteger('sort',L('_SORT_'))->keyDefault('sort',0)
            ->keyStatus()->keyDefault('status',1)
            ->buttonSubmit(U('Help/add'))->buttonBack()
            ->display();
        }
    
    }

    /**分类编辑
     * @param int $id
     * 
     */
    public function editCategory($id = 0, $pid = 0)
    {
        $title=$id?L('_EDIT_'):L('_ADD_');
        if (IS_POST) {
            if ($this->helpCategoryModel->editData()) {
                S('SHOW_EDIT_BUTTON',null);
                $this->success($title.L('_SUCCESS_'), U('Help/helpCategory'));
            } else {
                $this->error($title.L('_FAIL_').$this->helpCategoryModel->getError());
            }
        } else {
            $builder = new AdminConfigBuilder();

            if ($id != 0) {
                $data = $this->helpCategoryModel->find($id);
            } else {
                $father_category_pid=$this->helpCategoryModel->where(array('id'=>$pid))->getField('pid');
                if($father_category_pid!=0){
                    $this->error(L('_ERROR_CATEGORY_HIERARCHY_'));
                }
            }
            if($pid!=0){
                $categorys = $this->helpCategoryModel->where(array('pid'=>0,'status'=>array('egt',0)))->select();
            }
            $opt = array();
            foreach ($categorys as $category) {
                $opt[$category['id']] = $category['title'];
            }
            $builder->title($title.L('_CATEGORY_'))
                ->data($data)
                ->keyId()->keyText('title', L('_TITLE_'))
                ->keySelect('pid',L('_FATHER_CLASS_'), L('_FATHER_CLASS_SELECT_'), array('0' =>L('_TOP_CLASS_')) + $opt)->keyDefault('pid',$pid)
                ->keyInteger('sort',L('_SORT_'))->keyDefault('sort',0)
                ->keyStatus()->keyDefault('status',1)
                ->buttonSubmit(U('Help/editCategory'))->buttonBack()
                ->display();
        }

    }

    /**
     * 设置帮助分类状态：删除=-1，禁用=0，启用=1
     * @param $ids
     * @param $status
     * 
     */
    public function setCategoryStatus($ids, $status)
    {
        !is_array($ids)&&$ids=explode(',',$ids);
        if($status==-1){
            if(in_array(1,$ids)){
                $this->error(L('_TIP_DELETE_CATEGORY_'));
            }
            $map['category']=array('in',$ids);
            $this->helpModel->where($map)->setField('category',1);
        }
        $builder = new AdminListBuilder();
        $builder->doSetStatus('HelpCategory', $ids, $status);
    }

    //分类管理end

    /**
     * 帮助中心配置
     * \
     */
    public function config()
    {
        $builder=new AdminConfigBuilder();
        $data=$builder->handleConfig();

        $builder->title(L('_HELP_BASIC_CONF_'))
            ->data($data);

        $builder->keyText('HELP_CATEGORY_TITLE',L('_HELP_TOP_TITLE_'))->keyDefault('HELP_CATEGORY_TITLE',L('_HELP_INTRO_'))
            ->buttonSubmit()->buttonBack()
            ->display();
    }


    //帮助帮助列表start
    public function index($page=1,$r=20)
    {
        $aCate=I('cate',0,'intval');
        if($aCate==-1){
            $map['category']=0;
        }else if($aCate!=0){
            $map['category']=$aCate;
        }
        $map['status']=array('neq',-1);

        list($list,$totalCount)=$this->helpModel->getListByPage($map,$page,'sort asc,update_time desc','*',$r);
        $category=$this->helpCategoryModel->getCategoryList(array('status'=>array('egt',0)));
        $category=array_combine(array_column($category,'id'),$category);
        foreach($list as &$val){
            if($val['category']){
                $val['category']='['.$val['category'].'] '.$category[$val['category']]['title'];
            }else{
                $val['category']=L('_NOT_CATEGORIZED_');
            }
        }
        unset($val);

        $optCategory=$category;
        foreach($optCategory as &$val){
            $val['value']=$val['title'];
        }
        unset($val);

        $builder=new AdminListBuilder();
        $builder->title(L('_HELP_LIST_'))
            ->data($list)
            ->buttonNew(U('Help/editHelp'))
            ->setStatusUrl(U('Help/setHelpStatus'))
            ->buttonEnable()->buttonDisable()->buttonDelete()
            ->setSelectPostUrl(U('Admin/Help/index'))
            ->select('','cate','select','','','',array_merge(array(array('id'=>0,'value'=>L('_EVERYTHING_'))),$optCategory,array(array('id'=>-1,'value'=>L('_NOT_CATEGORIZED_')))))
            ->keyId()->keyUid()->keyLink('title',L('_TITLE_'),'Help/Index/index?id=###')->keyText('category',L('_CATEGORY_'),L('_OPTIONAL_'))->keyText('sort',L('_SORT_'))
            ->keyStatus()->keyCreateTime()->keyUpdateTime()
            ->keyDoActionEdit('Help/editHelp?id=###')
            ->pagination($totalCount,$r)
            ->display();
    }

    public function setHelpStatus($ids,$status=1)
    {
        !is_array($ids)&&$ids=explode(',',$ids);
        $builder = new AdminListBuilder();
        $builder->doSetStatus('Help', $ids, $status);
    }

    /**
     * 编辑帮助中心帮助
     * 
     */
    public function editHelp()
    {
        $aId=I('id',0,'intval');
        $title=$aId?L("_EDIT_"):L("_ADD_");
        if(IS_POST){
            $aId&&$data['id']=$aId;
            $data['uid']=I('post.uid',get_uid(),'intval');
            $data['title']=I('post.title','','text');
            $data['content']=$_POST['content'];
            $data['category']=I('post.category',0,'intval');
            $data['sort']=I('post.sort',0,'intval');
            $data['status']=I('post.status',1,'intval');
            if(!mb_strlen($data['title'],'utf-8')){
                $this->error(L('_TIP_TITLE_EMPTY_'));
            }
            $result=$this->helpModel->editData($data);
            if($result){
                $aId=$aId?$aId:$result;
                $this->success($title.L('_SUCCESS_'),U('Help/editHelp',array('id'=>$aId)));
            }else{
                $this->error($title.L('_FAIL_'),$this->helpModel->getError());
            }
        }else{
            if($aId){
                $data=$this->helpModel->find($aId);
            }
            $category=$this->helpCategoryModel->getCategoryList(array('status'=>array('egt',-1)), 1);
            $options=array(0=>L('_NO_CATEGORY_'));
            foreach($category as $val){
                $options[$val['id']]=$val['title_show'];
            }
            $builder=new AdminConfigBuilder();
            $builder->title($title.L('_NEWS_'))
                ->data($data)
                ->keyId()
                ->keyReadOnly('uid',L('_PUBLISHER_'))->keyDefault('uid',get_uid())
                ->keyText('title',L('_TITLE_'))
                ->keyEditor('content',L('_CONTENT_'),'','all',array('width' => '850px', 'height' => '600px'))
                ->keySelect('category',L('_CATEGORY_'),'',$options)
                ->keyInteger('sort',L('_SORT_'))->keyDefault('sort',0)
                ->keyStatus()->keyDefault('status',1)
                ->buttonSubmit()->buttonBack()
                ->display();
        }
    }
} 