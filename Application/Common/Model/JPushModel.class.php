<?php
/**
 * JPush极光推送
 * platform	必填	推送平台设置 ，JPush 当前支持 Android, iOS, Windows Phone 三个平台的推送。其关键字分别为："android", "ios", "winphone"。
 * audience	必填	推送设备指定 ，JPush 提供了多种方式，比如：别名、标签、注册ID、分群、广播等。
 * notification	可选	通知（客户端显示在通知栏）内容体。是被推送到客户端的内容。与 message 一起二者必须有其一，可以二者并存
 * message	可选	消息（客户端不显示）内容体。是被推送到客户端的内容。与 notification 一起二者必须有其一，可以二者并存
 * options	可选	推送参数
 */
namespace Common\Model;

use Think\Model;

class JPushModel extends Model {
  
    private $app_key = '2b343dd95941cb31c0bb532f';            //待发送的应用程序(appKey)，只能填一个。
    private $master_secret = 'edf25d35eecfafa045b5e1e3';      //主密码
    private $url = "https://api.jpush.cn/v3/push";            //推送的地址
 
    //若实例化的时候传入相应的值则按新的相应值进行
    public function __construct( $app_key=null, $master_secret=null, $url=null) {
        //$app_key && $this->app_key = $app_key;
        //$master_secret && $this->master_secret = $master_secret;
        //$url && $this->url = $url;
        vendor('JPush/JPush');
        $this->jpush = new \JPush($this->app_key, $this->master_secret);
    }
    
    /**
     * 推送通知
     * @param string $receiver
     * @param string $title
     * @param string $content
     * @param string $m_time
     */
    public function pushNotice($receiver='all', $title='', $content='', $m_time=86400){
        try {
            $result = $this->jpush->push()
            ->setPlatform(array('ios', 'android'))
            //->addAlias('alias1')
            //->addTag(array('tag1', 'tag2'))
            ->setNotificationAlert($content)
            ->addAllAudience()
            //->setMessage("msg content", 'msg title', 'type', array("key1"=>"value1", "key2"=>"value2"))
            ->setOptions(null, $m_time, null, false)
            ->send();
        } catch ( \APIRequestException $e) {
            return;
        }
        return;
    }

    /**
     * 广播推送自定义消息
     * @param string $receiver
     * @param string $title
     * @param string $content
     * @param unknown $extras
     * @param string $m_time
     */
    public function pushMess($receiver='all', $title='', $content='', $extras, $m_time=86400){
        try {
            $result = $this->jpush->push()
            ->setPlatform(array('ios', 'android'))
            ->addAllAudience()
            ->setMessage($content, $title, 'type', $extras)
            ->setOptions(null, $m_time, null, false)
            ->send();
        } catch ( \APIRequestException $e) {
            return;
        }
        return;
    }

    /**
     * 别名推送自定义消息
     * @param string $alias 字符串或数组
     * @param string $title
     * @param string $content
     * @param unknown $extras
     * @param string $m_time
     */
    public function pushMessAlias($alias, $title='', $content='', $extras, $m_time=86400){
        try {
            $result = $this->jpush->push()
            ->setPlatform(array('ios', 'android'))
            ->addAlias($alias)
            ->setMessage($content, $title, 'type', $extras)
            ->setOptions(null, $m_time, null, false)
            ->send();
        } catch ( \APIRequestException $e) {
            return;
        }
        
        return;
    }
    
}