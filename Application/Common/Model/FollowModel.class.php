<?php

/**
 * 关注
 * 关注者uid，uid_follow(谁关注)
 * 被关注者uid，follow_uid(关注谁)
 */

namespace Common\Model;

use Think\Model\AdvModel;

class FollowModel extends AdvModel {

    protected $partition = array(
        'field' => 'uid_follow', // 分表依据:关注者uid（fans表的分表依据是被关注者uid）
        'type' => 'mod',
        'num' => '2' //TODO 改为配置
    );
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT)
    );

    /**
     * 获取数据访问对象
     * @param array $data 传入分表字段键值
     */
    public function getDao($data = array()) {
        $data = empty($data) ? $_POST : $data;
        $table = $this->getPartitionTableName($data);
        return $this->table($table);
    }

    /*     * 关注
     * @param $uid
     * @return int|mixed
     */

    public function follow($uid) {
        $follow['uid_follow'] = is_login();
        $follow['follow_uid'] = $uid;
        if ($follow['uid_follow'] == $follow['follow_uid']) {
            //禁止关注和被关注都为同一个人的情况。
            return 0;
        }
        if ($this->where($follow)->count() > 0) {
            return 0;
        }
        $follow = $this->create($follow);

        clean_query_user_cache($uid, 'fans');
        clean_query_user_cache(is_login(), 'following');
        S('atUsersJson_' . is_login(), null);
        /**
         * @param $to_uid 接受消息的用户ID
         * @param string $content 内容
         * @param string $title 标题，默认为  您有新的消息
         * @param $url 链接地址，不提供则默认进入消息中心
         * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
         * @param int $type 消息类型，0系统，1用户，2应用
         */
        $user = query_user(array('id', 'nickname', 'space_url'));
        $this->S($follow['uid_follow'], $follow['follow_uid'], null);
        D('Message')->sendMessage($uid, L('_FANS_NUMBER_INCREASED_'), $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_'), 'Ucenter/Index/index', array('uid' => is_login()));
        return $this->add($follow);
    }

    /**
     * 检查 $uid_follow 是否关注了 $follow_uid
     * @param number $uid_follow 谁关注
     * @param number $follow_uid 关注谁
     * @return number
     */
    public function isFollow($uid_follow, $follow_uid) {
        if (!$uid_follow || !$follow_uid) {
            return 0;
        }
        $follow = $this->S($uid_follow, $follow_uid);       
        if ($follow === false) {
            $dao = $this->getDao(array('uid_follow' => $uid_follow));
            $follow = $dao->where(array('uid_follow' => $uid_follow, 'follow_uid' => $follow_uid))->count();
            $this->S($uid_follow, $follow_uid, $follow);
        }
        return intval($follow);
    }

    public function getFollow($uid_follow, $follow_uid) {
        $dao = $this->getDao(array('uid_follow' => $uid_follow));
        $follow = $dao->where(array('uid_follow' => $uid_follow, 'follow_uid' => $follow_uid))->find();
        return $follow;
    }

    public function S($uid_follow, $follow_uid, $data = '') {
        return S('Core_follow_' . $uid_follow . '_' . $follow_uid, $data);
    }

    /* 分表，获取粉丝移到FansModel */

    public function getFans($uid, $page, $fields, &$totalCount) {
        $map['follow_uid'] = $uid;
        $dao = $this->getDao(array('uid_follow' => $uid_follow));
        $fans = $dao->where($map)->field('uid_follow')->order('create_time desc')->page($page, 10)->select();
        $totalCount = $dao->where($map)->field('uid_follow')->order('create_time desc')->count();
        foreach ($fans as &$user) {
            $user['user'] = query_user($fields, $user['uid_follow']);
        }
        unset($user);
        return $fans;
    }

    public function getFollowing($uid, $page, $fields, &$totalCount) {
        $map['uid_follow'] = $uid;
        $fans = $this->where($map)->field('follow_uid')->order('create_time desc')->page($page, 10)->select();
        $totalCount = $this->where($map)->field('follow_uid')->order('create_time desc')->count();

        foreach ($fans as &$user) {
            $user['user'] = query_user($fields, $user['follow_uid']);
        }
        unset($user);
        return $fans;
    }

    /*     * 显示全部的好友
     * @param int $uid
     * @return mixed
     * 
     */

    public function getAllFriends($uid = 0) {
        if ($uid == 0) {
            $uid = is_login();
        }
        $model_follow = D('Follow');
        $i_follow = $model_follow->where(array('uid_follow' => $uid))->limit(999)->select();
        foreach ($i_follow as $key => $user) {
            if ($model_follow->where(array('follow_uid' => $uid, 'uid_follow' => $user['follow_uid']))->count()) {
                continue;
            } else {
                unset($i_follow[$key]);
            }
        }
        return $i_follow;
    }

    /**
     * 获取粉丝列表
     * @param number $uid 被关注者uid
     * @param array $param 其他筛选条件，包括排序、分页等等
     * @author dpj
     */
    public function getFollowList($uid = 0, $param = array(), $bySphnix = false) {
        !$uid && $uid = get_uid();
        if(!$bySphnix){
            // TODO 搜索引擎
            $dao = $this->getDao(array('uid_follow' => $uid));
    
            $total = $dao->where(array('uid_follow' => $uid))->count();
            $list = array();
            $ids = '';
            if ($total) {
                $param['where']['uid_follow'] = $uid;
                $param['field'] = 'follow_uid';
                // TODO 改用搜索引擎才能做分页 放后
                //!isset($param['page']) && $param['page'] = 1;
                //!isset($param['limit']) && $param['limit'] = 10;
                // TODO 原因?不再执行一次取不到分表
                $dao = $this->getDao(array('uid_follow' => $uid));
                $data = $dao->getList($param);
                foreach ($data as $k => $v) {
                    $list[] = $v['follow_uid'];
                }
                $ids = implode(',', $list);
            }
            // 返回结果与搜索引擎返回结果保持一致，total、ids、time、list
            $res['total'] = $total;
            $res['ids'] = $ids;
            $res['list'] = $list;
            return $res;
        }else{
            $sphnixParam = array();
            $sphnixParam['where'] = array( 'uid_follow' => $uid );
            $sphnixParam['where']['status'] = 1;
            $sphnixParam['where']['follow_status'] = 1;
            $sphnixParam['page'] = $param['page'] ?  : 1;
            $sphnixParam['limit'] = $param['limit'] ?  : 10;
            isset($param['where']['sort']) && $param['where']['sort'] && $sphnixParam['order'] = $param['where']['sort'];
            $list = D('Common/Sphinx')->search('userFollow userFollowDelta', $param['where']['nickname'] ? "@nickname {$param['where']['nickname']} | @remark_name {$param['where']['nickname']}" : '', $sphnixParam);
            $res['total'] = $list['total'];
            if ($list['total']) {
                foreach ($list['list'] as $k => $v) {
                    $res['list'][] = $list['list'][$k]['attrs']['follow_uid'];
                }
            }
            return $res;
        }
    }

    /**
     * 关注搜索
     * @param array $param
     */
    public function followSearch($param) {
        $sphinxModel = D('Sphinx');
        $kw = $param['keyword'];
        unset($param['keyword']);
        return $sphinxModel->search('follow', $kw, $param);
    }

    /*     * 关注
     * @param $uid_follow
     * @param $follow_uid
     * @return int|mixed
     */

    public function addFollow($uid_follow, $follow_uid, $invite = 0) {
        $follow['uid_follow'] = $uid_follow;
        $follow['follow_uid'] = $follow_uid;
        if (!$uid_follow || $follow['uid_follow'] == $follow['follow_uid']) {
            //禁止关注和被关注都为同一个人的情况。
            return 0;
        }
        //$dao = $this->getDao(array('uid_follow' => $follow['uid_follow']));

        if ($this->isFollow($follow['uid_follow'], $follow['follow_uid']) > 0) {
            return 0;
        }
        // TODO 待处理问题
        $dao = $this->getDao(array('uid_follow' => $follow['uid_follow']));
        $follow = $dao->create($follow);

        clean_query_user_cache($follow_uid, 'fans');
        clean_query_user_cache($uid_follow, 'following');
        $user = query_user(array('id', 'nickname', 'space_url'), $uid_follow);
        if ($uid_follow < $follow_uid) {
            $content = L('_SYSTEM_RECOMMENDED_USERS_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
        } else {
            $content = L('_NEW_USER_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
        }

        /**
         * @param $to_uid 接受消息的用户ID
         * @param string $content 内容
         * @param string $title 标题，默认为  您有新的消息
         * @param $url 链接地址，不提供则默认进入消息中心
         * @param $int $from_uid 发起消息的用户，根据用户自动确定左侧图标，如果为用户，则左侧显示头像
         * @param int $type 消息类型，0系统，1用户，2应用
         */
        D('Message')->sendMessage($follow_uid, L('_FANS_NUMBER_INCREASED_'), $content, 'Ucenter/Index/index', array('uid' => $uid_follow), $uid_follow);
        // TODO 更好的方式
        try {
            $dao->execute('begin');
            $r1 = $dao->add($follow);
            $r2 = D('Fans')->addFans($uid_follow, $follow_uid);
            if ($r1 && $r2) {
                D('NewFans')->addNewFans($uid_follow, $follow_uid);
                $this->S($uid_follow, $follow_uid, null);
                $dao->execute('commit');
            } else {
                $dao->execute('rollback');
                return false;
            }
        } catch (\Exception $e) {
            $dao->execute('rollback');
            die($e->getMessage());
        }
        M('UserStatistic')->where(array('uid'=>$uid_follow))->setInc('follow');
        M('UserStatistic')->where(array('uid'=>$follow_uid))->setInc('fans');
        M('Member')->where(array('uid'=>array('in',array($follow_uid,$uid_follow))))->save(array('update_time'=>time()));
        // 事务处理
//         $dao->startTrans();
//         $dao->add($follow);
//         $dao->commit();
//         $dao->callback();
        return true;
    }

    /**
     * 取消关注
     * @param $uid_follow
     * @param $follow_uid
     * @return mixed
     */
    public function unfollow($uid_follow, $follow_uid) {
        !$uid_follow && $uid_follow = is_login();
        $follow['uid_follow'] = $uid_follow;
        $follow['follow_uid'] = $follow_uid;
        if (!$uid_follow || $follow['uid_follow'] == $follow['follow_uid']) {
            //禁止关注和被关注都为同一个人的情况。
            return 0;
        }
        $dao = $this->getDao(array('uid_follow' => $follow['uid_follow']));
        if (!$this->isFollow($follow['uid_follow'], $follow['follow_uid'])) {
            return 0; // 未关注过
        }
        // TODO 待处理问题
        $dao = $this->getDao(array('uid_follow' => $follow['uid_follow']));

        clean_query_user_cache($follow_uid, 'fans');
        clean_query_user_cache($uid_follow, 'following');
        S($uid_follow . '_remark_' . $follow_uid, null); //清除备注缓存
        $user = query_user(array('id', 'nickname'), $uid_follow);
        if ($uid_follow < $follow_uid) {
            $content = L('_SYSTEM_RECOMMENDED_USERS_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
        } else {
            $content = L('_NEW_USER_') . $user['nickname'] . L('_CONCERN_YOU_WITH_PERIOD_');
        }

        D('Message')->sendMessage($follow_uid, L('_NUMBER_OF_FANS_'), $user['nickname'] . L('_CANCEL_YOUR_ATTENTION_WITH_PERIOD_'), 'Ucenter/Index/index', array('uid' => $uid_follow));
        // TODO 更好的方式
        try {
            $dao->execute('begin');
            $r1 = $dao->where($follow)->delete(); //删除关注
            $r2 = D('Fans')->delFans($uid_follow, $follow_uid); //删除粉丝
            if ($r1 && $r2) {
                D('NewFans')->delNewFans($uid_follow, $follow_uid);
                $this->S($uid_follow, $follow_uid, null);
                $dao->execute('commit');
            } else {
                $dao->execute('rollback');
                return false;
            }
        } catch (\Exception $e) {
            $dao->execute('rollback');
            die($e->getMessage());
        }
        M('UserStatistic')->where(array('uid'=>$uid_follow))->setDec('follow');
        M('UserStatistic')->where(array('uid'=>$follow_uid))->setDec('fans');
       
    	$sphinxModel=new SphinxModel();
        $sphinxModel->update('userFollow userFollowDelta',array('follow_status'=>-1),false,array('uid_follow'=>$uid_follow,'follow_uid'=>$follow_uid,'follow_status'=>1));
        $sphinxModel=new SphinxModel();
        $sphinxModel->update('userFans userFansDelta',array('status'=>-1),false,array('uid_follow'=>$uid_follow,'follow_uid'=>$follow_uid,'status'=>1));
       // M('Member')->where(array('uid'=>$uid_follow))->save(array('update_time'=>time()));
        return true;
    }

    /**
     * 获取关注数量
     * @param number $uid
     * @param number $param
     */
    public function getFollowCount($uid) {
        // TODO 缓存 write_query_user_cache
        $dao = $this->getDao(array('uid_follow' => $uid));
        $total = $dao->where(array('uid_follow' => $uid))->count();
        return $total;
    }

    /**
     * 获取共同关注
     * @param unknown $uid
     * @param unknown $param
     * huangyy
     */
    public function getTogtherFollow($uid, $param, $cn = false) {
        if (!$uid) {
            $this->error('参数错误!');
        }
        $followTable1 = $this->getPartitionTableName(array('uid_follow' => is_login()));
        $followTable2 = $this->getPartitionTableName(array('uid_follow' => $uid));
        $preFix = C('DB_PREFIX');
        $preFix && $followTable1 = str_replace(C('DB_PREFIX'), '', $followTable1);
        $list = array();
        $total = M($followTable1)->join("LEFT JOIN {$followTable2} AS F2 ON {$preFix}{$followTable1}.follow_uid=F2.follow_uid")->where(array($preFix . $followTable1 . '.uid_follow' => is_login(), 'F2.uid_follow' => $uid))->count();
        if ($cn) {
            return $total;
        }
        if ($total) {
            $data = M($followTable1)->join("LEFT JOIN {$followTable2} AS F2 ON {$preFix}{$followTable1}.follow_uid=F2.follow_uid")->where(array($preFix . $followTable1 . '.uid_follow' => is_login(), 'F2.uid_follow' => $uid))->page($param['page'], $param['limit']? : 10)->select();
            foreach ($data as $k => $v) {
                $list[] = $v['follow_uid'];
            }
        }
        // 返回结果与搜索引擎返回结果保持一致，total、ids、time、list
        $res['total'] = $total;
        $res['list'] = $list;
        //echo M($followTable1)->getLastSql();
        return $res;
    }

    /**
     *
     * @param unknown $followers关注者
     * @param unknown $followed被关注者
     * @param unknown $remark备注
     */
    public function addRemark($followers, $followed, $remark) {
        S($followers . '_remark_' . $followed, null);
        $dao = $this->getDao(array('uid_follow' => $followers));
        return $dao->where(array('follow_uid' => $followed, 'uid_follow' => $followers))->save(array('remark_name' => $remark));
    }

    /**
     * 获取备注
     * @param unknown $followers关注者
     * @param unknown $followed被关注者
     */
    public function getRemark($followers, $followed) {
        $remarkName = S($followers . '_remark_' . $followed);
        if (!$remarkName) {
            $dao = $this->getDao(array('uid_follow' => $followers));
            $remarkName = $dao->where(array('follow_uid' => $followed, 'uid_follow' => $followers))->getField('remark_name');
            S($followers . '_remark_' . $followed, $remarkName);
        }
        return $remarkName;
    }

    /**
     * 检查双方关注状态     
     * @param number $follow_uid 关注谁
     * @param number $uid_follow 谁关注
     * @return number （0未关注，1已关注，2被关注，3互相关注）
     */
    public function getFollowSts($follow_uid,$uid_follow) {
        $s1 = $this->isFollow($uid_follow, $follow_uid);
        $s2 = $this->isFollow($follow_uid, $uid_follow);
        $sts = 0;
        if ($s1 && $s2) {
            $sts = 3;
        } elseif ($s1) {
            $sts = 1;
        } elseif ($s2) {
            $sts = 2;
        }
        return $sts;
    }

}
