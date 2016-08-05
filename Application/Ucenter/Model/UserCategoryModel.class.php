<?php
namespace Ucenter\Model;

use Think\Model;

class UserCategoryModel extends Model
{

    /*自动验证 */
    protected $_validate = array(
        array('title', '2,15', '关键字长度在2-15个字符之间', self::EXISTS_VALIDATE, 'length'),
        array('title', 'checkTitle', '关键字不可用', self::EXISTS_VALIDATE, 'callback'),
        array('type', array(1,2), '类型不正确', self::EXISTS_VALIDATE, 'in'),
    );

    /*自动完成 */
    protected $_auto = array(
        //array('code', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
        //array('code', 'getCode', self::MODEL_INSERT, 'callback'),
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
        array('status', 1, self::MODEL_INSERT),
        // array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
    );
    
    protected $_type = array(1 => '商品', 2 => '非商品');

    /*缓存key设置*/
    protected $_skey_main = 'user_category_{id}'; // 主键
    protected $_skey_title = 'user_category_{type}_{title}'; // 关键字
    protected $_skey_hot = 'user_category_hot_{type}_{num}'; // 首页商品微商关键词推荐40个
    
    /**
     * 获取类型
     */
    public function getType(){
        return $this->_type;
    }
    
    /**
     * 获取主键缓存key
     * @param number $id
     */
    public function getCacheKeyMain($id){
        return str_replace('{id}', $id, $this->_skey_main);
    }
    
    /**
     * 获取缓存key
     * @param number $id
     */
    public function getCacheKeyTitle($title, $type){
        return str_replace(array('{title}', '{type}'), array($title, $type), $this->_skey_title);
    }
    
    /**
     * 获取首页商品微商关键词推荐缓存key
     */
    public function getCacheKeyHot($type, $num){
        return str_replace(array('{type}', '{num}'), array($type, $num), $this->_skey_hot);
    }
    
    /**
     * 添加分类
     * @param array $data
     * @param string $title 分类名称
     * @param number $type 类型 1商品 2非商品
     * @param string $code
     */
    public function addCategory( $data ){
		if(!$data = $this->create($data)){
		    return false;
		}
        $info = $this->getByTitle($data['title'], $data['type']);
        if($info){
           return $info['id']; 
        }
        !$data['code'] && $data['code'] = $this->getCode($data['title']);
        $data['letter'] = strtoupper(substr($data['code'], 0, 1));        
        return $this->add($data);
    }

    /**
     * 根据主键获取信息
     * @param number $id
     */
    public function getById($id) {
        $skey = $this->getCacheKeyMain($id);
        $data = S($skey);
        if (!$data) {
            $data = $this->find($id);
            S($skey, $data);
        }
        return $data;
    }

    /**
     * 根据关键字获取信息
     * @param number $id
     */
    public function getByTitle($title, $type = 1) {
        $skey = $this->getCacheKeyTitle($title, $type);
        $data = S($skey);
        if (!$data) {
            $map['title'] = $title;
            $map['type'] = $type;
            $data = $this->where($map)->find();
            S($skey, $data);
        }
        return $data;
    }
    
    /**
     * 检测关键词是否可用
     * @param string $title
     */
    public function checkTitle( $title ){
        return true;
    }
    
    /**
     * 获取分类编码
     * @param string $title
     */
    public function getCode( $title ){
        // TODO 禁用编码检测
        $code = D('PinYin')->Pinyin($title);
        return $code;
    }
    
    /**
     * 推荐/热门关键词列表
     */
    public function getHot($type = 0, $num = 40){
        $skey = $this->getCacheKeyHot($type, $num);
        $data = S($skey);
        if(!$data){
            // TODO 暂时读库，待优化搜素引擎算法，从搜索引擎读取
            $param['where']['status'] = 1;
            $type && $param['where']['type'] = $type;
            $param['limit'] = $num;
            $param['field'] = 'id,title,letter,code,type,hot,recommend';
            $param['order'] = 'rand()';
            $data = $this->getList($param);
            
            /* if($data){
                foreach( $data as $k => &$v){
                    $v = $this->getById($v);
                }
                S($skey, $data, 600); // 缓存十分钟
            } */
            S($skey, $data, 600); // 缓存十分钟
        }
        return $data;
    }
    
    /**
     * 统一缓存处理
     * @param number $id 主键id
     */
    public function delCache($id = 0){
        
    }
    
}