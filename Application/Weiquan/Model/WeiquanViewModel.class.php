<?php
namespace Weiquan\Model;

use Think\Model;
use Think\Hook;
use Think\Model\ViewModel;

require_once('./Application/Weiquan/Common/function.php');

class WeiquanViewModel extends ViewModel
{   
    protected  $followTable;
    protected $weiQuanTable='weiquan';
    public $viewFields = array();
    public  function setFollowTable($uid){
        $this->followTable=D('Follow')->getPartitionTableName((array('uid_follow' => $uid)));
        $this->followTable=str_replace(C('DB_PREFIX'), '', $this->followTable);
        $this->viewFields["{$this->followTable}"]=array('follow_uid','uid_follow');
        $this->viewFields["{$this->weiQuanTable}"]=array('id','_on'=>$this->followTable.'.follow_uid='.$this->weiQuanTable.'.uid');
    }

}