<?php

namespace Addons\Sensitive;

use Common\Controller\Addon;
use Think\Db;

/**
 * 敏感词插件
 * @author quick
 */

class SensitiveAddon extends Addon
{

    public $info = array(
        'name' => 'Sensitive',
        'title' => '敏感词',
        'description' => '敏感词过滤插件',
        'status' => 1,
        'author' => 'i.cn',
        'version' => '0.1'
    );

    public $addon_path = './Addons/Sensitive/';

    /**
     * 配置列表页面
     * @var unknown_type
     */
    public $admin_list = array(
        'listKey' => array(
            'title' => '名称',
            'status' => '状态',
            'create_time' => '创建时间',

        ),
        'model' => 'Sensitive',
        'order' => 'id asc'
    );
    public $custom_adminlist = 'adminlist.html';

    /**
     * (non-PHPdoc)
     * 安装函数
     * @see \Common\Controller\Addons::install()
     */
    public function table_name()
    {
        $db_prefix = C('DB_PREFIX');
        return $db_prefix;
    }


    public function install()
    {
        $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `{$this->table_name()}sensitive` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

SQL;
        D()->execute($sql);


        return true;
    }

    /**
     * (non-PHPdoc)
     * 卸载函数
     * @see \Common\Controller\Addons::uninstall()
     */
    public function uninstall()
    {
        $db_prefix = C('DB_PREFIX');
        $sql = "DROP TABLE IF EXISTS `" . $this->table_name() . "sensitive`;";
        D()->execute($sql);
        return true;
    }

    //实现的广告钩子
    public function AdminIndex($param)
    {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        if ($config['display'])
            $this->display('widget');
    }

    public function parseContent($param)
    {

        $config = $this->getConfig();
        if ($config['is_open']) {
            $replace_words = S('replace_sensitive_words');
            if(empty($replace_words)){
                $words = D('Sensitive')->where(array('status'=>1))->select();
                $words = getSubByKey($words,'title');
                $replace_words = array_combine($words, array_fill(0, count($words), '***'));
                S('replace_sensitive_words',$replace_words);
            }

            !empty($replace_words) && $param['content'] = strtr( $param['content'], $replace_words);

        }

        return  $param;
    }

}