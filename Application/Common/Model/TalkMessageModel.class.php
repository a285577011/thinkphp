<?php
/**
 * 对话消息
 */

namespace Common\Model;


use Think\Model;

class TalkMessageModel extends Model
{
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    /**添加消息
     * @param $content 内容
     * @param $uid 用户ID
     * @param $talk_id 聊天ID
     * @return bool|mixed
     * 
     */
    public function addMessage($content, $uid, $talk_id)
    {
        $message['content'] = op_t($content);
        $message['uid'] = $uid;
        $message['talk_id'] = $talk_id;
        $message = $this->create($message);
        D('Talk')->where(array('id'=>intval($talk_id)))->setField('update_time',time());
        $talk=D('Talk')->find($talk_id);
        $message['id']=$this->add($message);

        if(!$message){
            return false;
        }
        $this->sendMessagePush($talk, $message);


        return $message;
    }

    /**发小系统提示消息
     * @param $content 内容
     * @param $to_uids 发送过去的对象
     * @param $talk_id 消息id
     */
    public function sendMessage($content, $to_uids, $talk_id)
    {
        D('Message')->sendMessage($to_uids,L('_YOU_HAVE_A_NEW_CHAT_MESSAGE_'), L('_DIALOGUE_CONTENT_WITH_COLON_') . op_t($content), 'UserCenter/Message/talk', array('talk_id' => $talk_id) , is_login(), 1);
    }

    /**
     * @param $talk
     * @param $message
     * 
     */
    private function sendMessagePush($talk, $message)
    {
        $origin_member = D('Talk')->decodeArrayByRec(explode(',', $talk['uids']));
        foreach ($origin_member as $mem) {
            if ($mem != is_login()) {
                //不是自己则建立一个push
                $push['uid'] = $mem;
                $push['source_id'] = $message['id'];
                $push['create_time'] = time();
                $push['talk_id']=$talk['id'];
                D('TalkMessagePush')->add($push);
            }
        }
    }


}