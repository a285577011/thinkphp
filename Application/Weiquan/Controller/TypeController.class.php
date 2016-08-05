<?php

namespace Weiquan\Controller;

use Think\Controller;

class TypeController extends Controller {

    /**
     * imageBox
     * 
     */
    public function imageBox() {
        $data['unid'] = substr(strtoupper(md5(uniqid(mt_rand(), true))), 0, 8);
        $data['status'] = 1;
        $data['total'] = 9;
        // 设置渲染变量
        $var['unid'] = $data['unid'];

        $var['fileSizeLimit'] = floor(2 * 1024) . 'KB';
        $var['total'] = $data['total'];
        $this->assign($var);
        $data['html'] = $this->fetch('imagebox');
        exit(json_encode($data));
    }

    /**
     * fetchImage  渲染图片微博
     * @param $weibo
     * @return string
     * 
     */
    public function fetchImage($weibo) {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $tag = 'ttp:';
        switch (count($weibo_data['attach_ids'])) {
            case 1:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 175, 175);
                    $weibo_data['image'][$k_i]['id'] = $v_i;
                    //$bi = M('Picture')->where(array('status' => 1))->getById($v_i);
                    //print_r($v_i);die;
                    $weibo_data['image'][$k_i]['big'] = getThumbImageById($v_i);
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if (!strpos($pi, $tag))
                        $pi = '.' . $pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0] . 'x' . $hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 2:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 175, 175);
                    $weibo_data['image'][$k_i]['id'] = $v_i;
                    //$bi = M('Picture')->where(array('status' => 1))->getById($v_i);
                   // $weibo_data['image'][$k_i]['big'] = get_pic_src($bi['path']);
                    $weibo_data['image'][$k_i]['big'] = getThumbImageById($v_i);
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if (!strpos($pi, $tag))
                        $pi = '.' . $pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0] . 'x' . $hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 3:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 83, 83);
                    $weibo_data['image'][$k_i]['id'] = $v_i;
                   // $bi = M('Picture')->where(array('status' => 1))->getById($v_i);
                   // $weibo_data['image'][$k_i]['big'] = get_pic_src($bi['path']);
                    $weibo_data['image'][$k_i]['big'] = getThumbImageById($v_i);
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if (!strpos($pi, $tag))
                        $pi = '.' . $pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0] . 'x' . $hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            case 4:
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 83,83);
                    $weibo_data['image'][$k_i]['id'] = $v_i;
                    //$bi = M('Picture')->where(array('status' => 1))->getById($v_i);
                   // $weibo_data['image'][$k_i]['big'] = get_pic_src($bi['path']);
                    $weibo_data['image'][$k_i]['big'] = getThumbImageById($v_i);
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if (!strpos($pi, $tag))
                        $pi = '.' . $pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0] . 'x' . $hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
                break;
            default :
                foreach ($weibo_data['attach_ids'] as $k_i => $v_i) {
                    $weibo_data['image'][$k_i]['small'] = getThumbImageById($v_i, 83, 83);
                    $weibo_data['image'][$k_i]['id'] = $v_i;
                    //$bi = M('Picture')->where(array('status' => 1))->getById($v_i);
                    $weibo_data['image'][$k_i]['big'] =getThumbImageById($v_i);
                    $pi = $weibo_data['image'][$k_i]['big'];
                    $param['weibo'] = $weibo;
                    if (!strpos($pi, $tag))
                        $pi = '.' . $pi;
                    $hv = getimagesize($pi);
                    $weibo_data['image'][$k_i]['size'] = $hv[0] . 'x' . $hv[1];
                    $param['weibo']['weibo_data'] = $weibo_data;
                }
        }
        $this->assign('img_num', count($weibo_data['attach_ids']));
        $this->assign($param);
        return $this->fetch(T('Weiquan@Type/fetchimage'));
    }

    /**
     * fetchRepost   渲染转发微博
     * @param $weibo
     * @return string
     * 
     */
    public function fetchRepost($weibo) {
        $weibo_data = unserialize($weibo['data']);
        $weibo_data['attach_ids'] = explode(',', $weibo_data['attach_ids']);
        $source_weibo = D('Weiquan/Weiquan')->getWeiquanDetail($weibo_data['sourceId']);
        $source_weibo['user'] = query_user(array('uid', 'nickname', 'avatar32', 'space_url', 'rank_link', 'title'), $source_weibo['uid']);
        $param['weibo'] = $weibo;
        $param['weibo']['source_weibo'] = $source_weibo;
        $this->assign($param);
        return $this->fetch(T('Weiquan@Type/fetchrepost'));
    }

}
