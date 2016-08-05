<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Cli\Controller;

use Think\Controller;
use Think\Model;

/**
 * 采集
 */
class IndexController extends Controller
{
    
    public function updateInfo(){
        print_r($this);exit;
        set_time_limit(0);
        
        $page = I('get.p', 0, 'intval');
        $total_page = $page + 100; // 每个进程跑100页数据
        $member = M('member');
        $ucenterMember = M('ucenterMember');
        $mTag = D('Ucenter/UserTag');
        $mTagLink = D('Ucenter/UserTagLink');
        $mAvatar = M('avatar');
        $mQrcode = M('user_config');
        $collectWeixinqun = M('collectWeixinqun');
        $collectWeixinqunDetail = M('collectWeixinqunDetail');
        
        for ($p = $page; $p < $total_page; $p ++){
            $page_size = 100; // 每次n条
            $uid_start = 1000000 + $p * $page_size;
            $uid_end = $uid_start + $page_size;
            echo '-----'.$p."页------uid:{$uid_start}~{$uid_end}\n";
            $time = time();
            $lists = $ucenterMember->where("id>{$uid_start} AND id<={$uid_end}")->field('id,sid')->select();
            foreach ($lists as $k => $v){
                //echo $v['id']."\n";
                // 头像
                if($mAvatar->where(array('uid'=>$v['id']))->count() == 0){
                    $weixinquan = $collectWeixinqun->where('uid='.$v['sid'])->field('avatar,city')->find();
                    if(strpos($weixinquan['avatar'], 'http://') === false) $weixinquan['avatar'] = 'http://www.weixinqun.com/'.$weixinquan['avatar'];
                    if(strpos($weixinquan['avatar'], '?'))
                        $imgstr = substr( $weixinquan['avatar'] , 0, strrpos( $weixinquan['avatar'], '?' ) );
                    else
                        $imgstr = $weixinquan['avatar'];
                    $img = json_decode($this->fopeUrl('http://10.0.0.188?url='.$imgstr), true);
                    if($img['data'][0]){
                        $avatar = array();
                        $avatar['path'] = $img['data'][0]['id'];
                        $avatar['uid'] = $v['id'];
                        $avatar['driver'] = 'Fastdfs';
                        $avatar['create_time'] = time();
                        $avatar['status'] = 1;
                        $mAvatar->add($avatar, '', true);
                    }
                    echo $v['id'].'---'.$imgstr."\n";
                }
                
                //二维码、标签
                $weixinquandetail = $collectWeixinqunDetail->where('uid='.$v['sid'])->field('qrcode,tags')->find();
                
                if($mQrcode->where(array('uid'=>$v['id'], 'name'=>'qrcode'))->count() == 0){
                    if(strpos($weixinquandetail['qrcode'], 'http://') === false) $weixinquandetail['qrcode'] = 'http://www.weixinqun.com/'.$weixinquandetail['qrcode'];
                    if(strpos($weixinquandetail['qrcode'], '?'))
                        $imgstr = substr( $weixinquandetail['qrcode'] , 0, strrpos( $weixinquandetail['qrcode'], '?' ) );
                    else
                        $imgstr = $weixinquandetail['qrcode'];
                    $img = json_decode($this->fopeUrl('http://10.0.0.188?url='.$imgstr), true);
                    if($img['data'][0]){
                        $config = array();
                        $config['uid'] = $v['id'];
                        $config['name'] = 'qrcode';
                        $config['role_id'] = 1;
                        $config['value'] = $img['data'][0]['id'];
                        $mQrcode->add($config, '', true);
                    }
                    echo $v['id'].'---'.$imgstr."\n";
                }
                
                // 标签数据处理
                // $tags = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(\.)/",',','红包，加粉，赚钱'); //处理.号
                //if(strpos($weixinquandetail['tags'], '。') || strpos($weixinquandetail['tags'], '、') || $mTagLink->where(array('uid'=>$v['id']))->count() == 0){
                if($mTagLink->where(array('uid'=>$v['id']))->count() == 0){
                    $user_tag = preg_replace("/(\n)|(\s)|(\t)|(\')|(')|(，)|(、)|(。)/",',',$weixinquandetail['tags']);
                    $user_tag = trim(preg_replace('/(,)+/i',',',$user_tag), ','); // 去除多个连续空格, 头尾空格
                    if($user_tag){
                        $tags = explode(',', $user_tag);
                        $tags = array_slice($tags, 0, 4); // 取数组前4个
                        $ids = array();
                        foreach ($tags as $t) {
                            $ids[] = $mTag->insertData(array('title' => trim($t), 'status' => 1));
                        }
                        if (!empty($ids)) {
                            $data = array();
                            $data['uid'] = $v['id'];
                            $data['tags'] = implode(',', $ids);
                            $mTagLink->add($data);
                        }
                    }
                }
            }
        }
        echo "end\n";
    }
    
    private function fopeUrl($url)
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl_handle, CURLOPT_FAILONERROR,1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check'); //引用垃圾邮件检查
        $file_content = curl_exec($curl_handle);
        curl_close($curl_handle);
        return $file_content;
    }
}