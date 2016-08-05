<?php
/**
 * 自增主键模型
 */
namespace Common\Model;

use Think\Model;

class AutoincrementModel extends Model {
    	
	public function getAutoincrementId($table){
	    return $this->add(array('table' => $table), array(), true);
	}
	
}