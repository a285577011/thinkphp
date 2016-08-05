<?php

namespace Common\Model;

use Think\Model;

class AdvsModel extends Model
{

    /*  展示数据  */
    public function getAdvList($name, $path)
    {

        $list = S('adv_list_' . $name . $path);
        if ($list === false) {
            $now_theme = modC('NOW_THEME', 'default', 'Theme');

            $advPos = D('Common/AdvPos')->getInfo($name, $path); //找到当前调用的广告位
            if ($advPos['theme'] != 'all' && !in_array($now_theme, explode(',', $advPos['theme']))) {
                return null;
            }

            $advMap['pos_id'] = $advPos['id'];
            $advMap['status'] = 1;
            $advMap['start_time'] = array('lt', time());
            $advMap['end_time'] = array('gt', time());
            $data = $this->where($advMap)->order('sort asc')->select();


            foreach ($data as &$v) {
                $d = json_decode($v['data'], true);
                if (!empty($d)) {
                    $v = array_merge($d, $v);

                }
            }
            unset($v);
            S('adv_list_' . $name . $path, $list);
        }

        return $data;
    }

    /*——————————————————分隔线————————————————*/


    /**
     * 根据广告位获取广告列表
     * @param number $pos_id
     */
    public function getAdv($pos_id = 0){
        $skey = 'adv_list_' . $pos_id;
        $data = S($skey);
        if ($data === false) {
            $map['status'] = 1;
            $pos_id && $map['pos_id'] = $pos_id;
            $data = $this->where($map)->order('sort desc')->select();
        
            foreach ($data as &$v) {
                $detail = json_decode($v['data'], true);
                if (!empty($detail)) {
                    $v = array_merge($v, $detail);
                    $v['pic'] = pic($v['pic']);
                }
                unset($v['data'],$v['sort'],$v['pos_id'],$v['click_count'],$v['status'],$v['create_time'],$v['start_time'],$v['end_time']);
            }
            unset($v);
            S($skey, $data);
        }
        return $data;
    }
}