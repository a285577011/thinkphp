<?php
/**
 * 粉丝
 * 关注者uid，uid_follow(谁关注)
 * 被关注者uid，follow_uid(关注谁)
 */
namespace Common\Model;

use Think\Model\AdvModel;

class FansModel extends AdvModel {
	
	protected $partition = array (
			'field' => 'follow_uid', // 分表依据:被关注者uid（follow表的分表依据是关注者uid）
			'type' => 'mod',
			'num' => '2' 
	);

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT)
    );
	
    /**
     * 获取数据访问对象
     * @param array $data 传入分表字段键值
     */
	public function getDao($data = array()) {
		$data = empty ( $data ) ? $_POST : $data;
		$table = $this->getPartitionTableName ( $data );
		return $this->table ( $table );
	}
	
	/**
	 * 添加粉丝记录
	 * @param number $uid_follow 谁关注
	 * @param number $follow_uid 关注谁
	 */
	public function addFans($uid_follow, $follow_uid){
	    $dao = $this->getDao ( array ( 'follow_uid' => $follow_uid ) );

	    $data['follow_uid'] = $follow_uid;
	    $data['uid_follow'] = $uid_follow;
	    $data = $dao->create($data);

	    $dao = $this->getDao ( array ( 'follow_uid' => $follow_uid ) );
	    return $dao->add($data);
	}
	
	/**
	 * 删除粉丝记录
	 * @param number $uid_follow 谁关注
	 * @param number $follow_uid 关注谁
	 */
	public function delFans($uid_follow, $follow_uid){
	    $dao = $this->getDao ( array ( 'follow_uid' => $follow_uid ) );

	    $data['follow_uid'] = $follow_uid;
	    $data['uid_follow'] = $uid_follow;

	    $dao = $this->getDao ( array ( 'follow_uid' => $follow_uid ) );
	    return $dao->where($data)->delete();
	}
	
	/**
	 * 获取粉丝列表
	 * @param number $uid 被关注者uid
	 * @param array $param 其他筛选条件，包括排序、分页等等
	 * @param $bySphnix 通过搜索引擎 默认通过数据库
	 */
	public function getFans($uid, $param = array(),$bySphnix=false){
	    if(!$bySphnix){
	    // TODO 搜索引擎		
		$total = $this->getFansCount($uid);
		$list = array();
		$ids = '';
		if($total){
    		$param['where']['follow_uid'] = $uid;
    		$param['field'] = 'uid_follow';
    		//!isset($param['page']) && $param['page'] = 1;
    		//!isset($param['limit']) && $param['limit'] = 10;
    		
    		// TODO ?不再执行一次取不到分表
    		$dao = $this->getDao ( array ( 'follow_uid' => $uid ) );
    	    $data = $dao->getList( $param );
    	    foreach($data as $k => $v){
    	        $list[] = $v['uid_follow'];
    	    }
    	    $ids = implode(',', $list);
		}
		// 返回结果与搜索引擎返回结果保持一致，total、ids、time、list
	    $res['total'] = $total;
	    $res['ids'] = $ids;
	    $res['list'] = $list;
	    return $res;
	    }
	    else{
	       $sphnixParam=array();
	       $sphnixParam['where']=array('follow_uid'=>$uid);
	       $sphnixParam['where']['status']=1;
	       $sphnixParam['page']=$param['page']?:1;
	       $sphnixParam['limit']=$param['limit']?:10;
	       isset($param['where']['sort'])&&$param['where']['sort']&&$sphnixParam['order']=$param['where']['sort'];
	       $list=D('Common/Sphinx')->search('userFans userFansDelta',$param['where']['nickname']?: '',$sphnixParam);
	       $res['total'] = $list['total'];
	       if($list['total']){
	           foreach ($list['list'] as $k=>$v){
	               $res['list'][]=$list['list'][$k]['attrs']['uid_follow'];
	           }
	       }
	      // $res['ids'] = $list['list']['attrs']['uid_follow'];
	       //$res['list'] = $list['total'];
	       return $res;
	       //echo '<pre>';
	      // print_r($list);die;
	    }
	}

	/**
	 * $uid_follow 是否被 $follow_uid 关注
	 * @param number $uid_follow 谁关注
	 * @param number $follow_uid 关注谁
	 */
    public function isFollow($uid_follow, $follow_uid){
        if(!$uid_follow || !$follow_uid || $uid_follow == $follow_uid){
            return 0;
        }
        $follow = $this->S($uid_follow, $follow_uid);
        if ($follow === false) {
            $dao = $this->getDao ( array ( 'follow_uid' => $follow_uid ) );
            $follow = $dao->where(array('uid_follow' => $uid_follow, 'follow_uid' => $follow_uid))->count();
            $this->S($uid_follow, $follow_uid, $follow);
        }
        return intval($follow);
    }

    public function S($uid_follow, $follow_uid, $data = '')
    {
        return S('Core_follow_' . $uid_follow . '_' . $follow_uid, $data);
    }
	
	/**
	 * 获取粉丝数量
	 * @param number $uid
	 * @param number $param
	 */
	public function getFansCount($uid){
	    // TODO 缓存 write_query_user_cache
		$dao = $this->getDao ( array ( 'follow_uid' => $uid ) );
		$total = $dao->where(array ( 'follow_uid' => $uid ))->count();
	    return $total;
	}
}