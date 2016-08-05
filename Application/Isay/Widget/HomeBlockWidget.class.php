<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-30
 * Time: 下午1:28
 * 
 */

namespace Isay\Widget;


use Think\Controller;

class HomeBlockWidget extends Controller
{
    public function render()
    {
        $this->assignIsay();
        import_lang('Isay');
        $this->display(T('Application://Isay@Widget/homeblock'));
    }

    private function assignIsay()
    {
        $num = modC('ISAY_SHOW_COUNT', 4, 'Isay');
        $type = modC('ISAY_SHOW_TYPE', 0, 'Isay');
        $field = modC('ISAY_SHOW_ORDER_FIELD', 'view', 'Isay');
        $order = modC('ISAY_SHOW_ORDER_TYPE', 'desc', 'Isay');
        $cache = modC('ISAY_SHOW_CACHE_TIME', 600, 'Isay');
        $list = S('isay_home_data');
        if (!$list) {
            if ($type) {
                /**
                 * 获取推荐位数据列表
                 * @param  number $pos 推荐位 1-系统首页，2-推荐阅读，4-本类推荐
                 * @param  number $category 分类ID
                 * @param  number $limit 列表行数
                 * @param  boolean $filed 查询字段
                 * @param order 排序
                 * @return array             数据列表
                 */
                $list = D('Isay/Isay')->position(1, null, $num, true, $field . ' ' . $order);
            } else {
                $map = array('status' => 1, 'dead_line' => array('gt', time()));
                $list = D('Isay/Isay')->getList($map, $field . ' ' . $order, $num);
            }
            foreach ($list as &$v) {
                $v['user'] = query_user(array('space_url', 'nickname'), $v['uid']);
            }
            unset($v);
            if (!$list) {
                $list = 1;
            }
            S('isay_home_data', $list, $cache);
        }
        unset($v);
        if ($list == 1) {
            $list = null;
        }
        $this->assign('isay_lists', $list);
    }
} 