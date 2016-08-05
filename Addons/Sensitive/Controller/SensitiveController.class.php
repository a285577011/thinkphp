<?php
/**
 *
 * @author quick
 *
 */
namespace Addons\Sensitive\Controller;

use Admin\Controller\AddonsController;

class SensitiveController extends AddonsController
{

    public function addSensitive()
    {


        $this->display(T('Addons://Sensitive@Sensitive/edit'));
    }

    public function editSensitive()
    {
        $id = I('get.id', '');
        $current = U('/Admin/Addons/adminList/name/Advertising');
        $detail = D('Addons://Sensitive/Sensitive')->detail($id);
        $this->assign('info', $detail);
        $this->assign('current', $current);
        $this->display(T('Addons://Sensitive@Sensitive/edit'));
    }

    public function delSensitive()
    {
        $id = I('get.id', '');
        if (D('Addons://Sensitive/Sensitive')->del($id)) {
            S('replace_sensitive_words',null);
            $this->success('删除成功', Cookie('__forward__'));
        } else {
            $this->error(D('Addons://Sensitive/Sensitive')->getError());
        }
    }


    /* 禁用 */
    public function forbidden()
    {
        $id = I('get.id', '');
        if (D('Addons://Sensitive/Sensitive')->forbidden($id)) {
            S('replace_sensitive_words',null);
            $this->success('成功禁用该敏感词', Cookie('__forward__'));
        } else {
            $this->error(D('Addons://Sensitive/Sensitive')->getError());
        }
    }

    /* 启用 */
    public function off()
    {
        $id = I('get.id', '');
        if (D('Addons://Sensitive/Sensitive')->off($id)) {
            S('replace_sensitive_words',null);
            $this->success('成功启用该敏感词', Cookie('__forward__'));
        } else {
            $this->error(D('Addons://Sensitive/Sensitive')->getError());
        }
    }

    /**
     * batch  批量添加
     * @author:xjw129xjt xjt@ourstu.com
     */
    public function batch()
    {
        if(IS_POST){
            $titles = I('post.titles');
            $qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
            $titles = str_replace($qian,$hou,$titles);
            $titles = explode('|',$titles);
            $data=array();
            $time = time();
            foreach($titles as $v){
                if($v !=''){
                    $data[] = array('title'=>$v,'status'=>1,'create_time'=>$time);
                }

            }
            $res = D('Sensitive')->addAll($data);
            S('replace_sensitive_words',null);
            if($res){
                $this->success('批量添加成功',U('Admin/Addons/adminList',array('name'=>'Sensitive')));
            }else{
                $this->error('批量添加失败');
            }
        }
        else{
            $this->display(T('Addons://Sensitive@Sensitive/batch'));
        }

    }

    /**
     * 批量处理
     */
    public function savestatus()
    {
        $status = I('get.status');
        $ids = I('post.ids');

        if ($status == 1) {
            foreach ($ids as $id) {
                D('Addons://Sensitive/Sensitive')->off($id);
            }
            S('replace_sensitive_words',null);
            $this->success('成功启用该敏感词', Cookie('__forward__'));
        } elseif ($status == 1) {
            foreach ($ids as $id) {
                D('Addons://Sensitive/Sensitive')->forbidden($id);
            }
            S('replace_sensitive_words',null);
            $this->success('成功禁用该敏感词', Cookie('__forward__'));
        } else {
            foreach ($ids as $id) {
                D('Addons://Sensitive/Sensitive')->del($id);

            }
            S('replace_sensitive_words',null);
            $this->success('成功删除该敏感词', Cookie('__forward__'));
        }

    }

    /* 更新 */
    public function update()
    {
        $title = I('post.title');
       if(!$title){
           $this->error('名称不能为空');
       }

        $check = D('Sensitive')->where(array('title'=>$title))->find();
        if($check){
            $this->error('该词已经存在');
        }

        $res = D('Addons://Sensitive/Sensitive')->update();
        if (!$res) {
            $this->error(D('Addons://Sensitive/Sensitive')->getError());
        } else {
            if ($res['id']) {
                S('replace_sensitive_words',null);
                $this->success('更新成功', Cookie('__forward__'));
            } else {
                $this->success('新增成功', Cookie('__forward__'));
            }
        }
    }
}