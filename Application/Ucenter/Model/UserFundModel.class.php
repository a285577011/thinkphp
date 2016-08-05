<?php

namespace Ucenter\Model;

use Think\Model;

class UserFundModel extends Model {

    public function getFund($uid) {
        return $this->where(array('uid' => $uid))->find();
    }

    public function getUserBalance($uid) {
        $rs = $this->where(array('uid' => $uid))->getField('balance');
        return $rs;
    }

    public function getUserFrozen($uid) {
        $rs = $this->where(array('uid' => $uid))->getField('frozen');
        return $rs;
    }

    /**
     * 更新Balance字段
     * @param type $uid
     * @param type $type 0增加1减少
     * @param type $num
     */
    public function upBalance($uid, $type, $num) {
        if ($type == 0) {
            return $this->where("uid=$uid")->setInc('balance', $num);
        }
        if ($type == 1) {
            return $this->where("uid=$uid")->setDec('balance', $num);
        }
        return FALSE;
    }

    /**
     * 更新Frozen字段
     * @param type $uid
     * @param type $type 0增加1减少
     * @param type $num
     */
    public function upFrozen($uid, $type, $num) {
        if ($type == 0) {
            return $this->where("uid=$uid")->setInc('frozen', $num);
        }
        if ($type == 1) {
            return $this->where("uid=$uid")->setDec('frozen', $num);
        }
        return FALSE;
    }

    /**
     * 对等增加余额减少冻结资金
     * @param type $uid
     * @param type $num
     * @return type
     * @author zhangby
     */
    public function upAddBalanceAndCutFrozen($uid, $num) {
        return $this->execute("UPDATE __PREFIX__user_fund SET `balance`=`balance`+$num,`frozen`=`frozen`-$num WHERE `uid`=$uid");       
    }
    
    /**
     * 对等减少余额增加冻结资金
     * @param type $uid
     * @param type $num
     * @return type
     * @author zhangby
     */
     public function upCutBalanceAndAddFrozen($uid, $num) {
        return $this->execute("UPDATE __PREFIX__user_fund SET `balance`=`balance`+$num,`frozen`=`frozen`-$num WHERE `uid`=$uid");       
    }

}
