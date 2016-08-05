<?php

namespace Common\Model;

use Think\Model;

class AdvPosModel extends Model
{
    protected $tableName = 'adv_pos';

    public function getInfo($name, $path)
    {
        $adv_pos = S('adv_pos_by_pos_' .$path. $name);
        if ($adv_pos === false) {
            $adv_pos = $this->where(array('name' => $name, 'path' => $path, 'status' => 1))->find();
            S('adv_pos_by_pos_'  .$path. $name,$adv_pos);
        }
        return $adv_pos;
    }

    /*——————————————————分隔线————————————————*/

    public function switchType($type)
    {
        switch ($type) {
            case 1:
                $return = '单图';
                break;
            case 2:
                $return = '轮播';
                break;
            case 3:
                $return = '文字链接';
                break;
            case 4:
                $return = '代码';
                break;
            default:
                $return = '其他';

        }
        return $return;
    }
    protected function _after_select(&$result, $options)
    {
        foreach ($result as &$record) {
            $this->_after_find($record, $options);
        }
    }

    /**
     * 新增或更新一个文档
     * @return boolean fasle 失败 ， int  成功 返回完整的数据
     */
    public function update()
    {
        $_POST['theme'] = $_POST['theme'] ? $_POST['theme'] : 'all';
        /* 获取数据对象 */
        $data = $this->create();
        if (empty($data)) {
            return false;
        }
        /* 添加或新增基础内容 */
        if (empty($data['id'])) { //新增数据
            $id = $this->add(); //添加基础内容
            if (!$id) {
                $this->error = '新增广告内容出错！';
                return false;
            }
        } else { //更新数据
            $status = $this->save(); //更新基础内容

            $this->clearCacheById($data['id']);

            if (false === $status) {
                $this->error = '更新广告内容出错！';
                return false;
            }
        }

        //内容添加或更新完成
        return $data;

    }

    /* 禁用 */
    public function forbidden($id)
    {
        $this->clearCacheById($id);
        return $this->save(array('id' => $id, 'status' => '0'));
    }

    /* 启用 */
    public function off($id)
    {
        $this->clearCacheById($id);
        return $this->save(array('id' => $id, 'status' => '1'));
    }

    /* 删除 */
    public function del($id)
    {
        $this->clearCacheById($id);
        return $this->delete($id);
    }

    /* 获取编辑数据 */
    public function detail($id)
    {
        $data = $this->find($id);
        return $data;
    }

    public function clearCacheById($id)
    {
        $info = $this->detail($id);
        S('advertising_by_pos_' . $info['pos'], null);
    }


}