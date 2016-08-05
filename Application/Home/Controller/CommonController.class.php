<?php

namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller {

    public function uploadPictrue() {

        $file = A('Core/File');
        $file->uploadPicture();
    }

    //获取中国省份信息
    public function getProvince() {
        $pid = I('pid', 0, 'intval');  //默认的省份id
        if (!empty($pid)) {
            //$map['id'] = $pid;
        }
        $map['where']['level'] = 1;
        $map['where']['upid'] = 0;
        $map['field'] = '*';
        $list = D('District')->_list($map);

        $data = "<option value =''>-" . L('_PROVINCE_') . "-</option>";
        foreach ($list as $k => $vo) {
            $data .= "<option ";
            if ($pid == $vo['id']) {
                $data .= " selected ";
            }
            $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
        }
        $this->ajaxReturn($data);
    }

    //获取城市信息
    public function getCity() {
        $cid = I('cid', 0, 'intval');  //默认的城市id
        $pid = I('pid', 0, 'intval');  //传过来的省份id

        if (!empty($cid)) {
            //$map['id'] = $cid;
        }
        $map['where']['level'] = 2;
        $map['where']['upid'] = $pid;
        $map['field'] = '*';

        $list = D('District')->_list($map);

        $data = "<option value =''>-" . L('_CITY_') . "-</option>";
        foreach ($list as $k => $vo) {
            $data .= "<option ";
            if ($cid == $vo['id']) {
                $data .= " selected ";
            }
            $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
        }
        $this->ajaxReturn($data);
    }

    //获取区县市信息
    public function getDistrict() {
        $did = I('did', 0, 'intval');  //默认的城市id
        $cid = I('cid', 0, 'intval');  //传过来的城市id

        if (!empty($did)) {
            //$map['id'] = $did;
        }
        $map['where']['level'] = 3;
        $map['where']['upid'] = $cid;
        $map['field'] = '*';

        $list = D('District')->_list($map);

        $data = "<option value =''>-" . L('_DISTRICT_') . "-</option>";
        foreach ($list as $k => $vo) {
            $data .= "<option ";
            if ($did == $vo['id']) {
                $data .= " selected ";
            }
            $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
        }
        $this->ajaxReturn($data);
    }

}
