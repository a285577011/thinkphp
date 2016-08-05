<?php
/**
 * 对话推送
 */

namespace Common\Model;

use Think\Model;

class TalkPushModel extends Model
{

    public function getAllPush()
    {
        $new_talks = $this->where(array('uid' => get_uid(), 'status' => 0))->select();

        foreach ($new_talks as &$v) {
            $v['talk'] = D('Talk')->find($v['source_id']);
            $uids = D('Common/Talk')->decodeArrayByRec(explode(',', $v['talk']['uids']));
            $user = D('Common/Talk')->getFirstOtherUser($uids);
            $v['talk']['ico'] = $user['avatar64'];
        }
        unset($v);
        return $new_talks;
    }

    public function clearAll()
    {
        $this->clearTalkPush();
        $this->clearTalkMessagePush();
    }

    public function clearTalkPush($uid = 0)
    {
        $uid = $uid == 0 ? get_uid() : $uid;
        D('TalkPush')->where(array('uid' => $uid))->delete();
    }

    public function clearTalkMessagePush($uid = 0)
    {
        $uid = $uid == 0 ? get_uid() : $uid;
        D('TalkMessagePush')->where(array('uid' => $uid))->delete();
    }
}