<?php
/**
 * 文件模型
 * User: dpj
 * Date: 2015-01-20
 * 
 */
namespace Common\Model;

use Think\Model\AdvModel;
use Think\Upload;

class FileModel extends AdvModel {
    /**
     * 缓存key
     */
    protected $_skey_main = 'file_{id}'; // 主键
    
    /**
     * 获取主键缓存key
     * @param number $id
     */
    public function getCacheKeyMain($id){
        return str_replace('{id}', $id, $this->_skey_main);
    }
    
	/**
	 * 文件模型自动完成
	 * @var array
	 */
	protected $_auto = array(
		array('create_time', NOW_TIME, self::MODEL_INSERT),
	);

	/**
	 * 文件模型字段映射
	 * @var array
	 */
	protected $_map = array(
		'type' => 'mime',
	);
    
    protected $connection = 'DB_FILE'; // 选择数据库，用户分库
    //protected $connection = 'mysql://root:123456@127.0.0.1:3306/icn_file#utf8';
    
    /**
     * 分表配置
     */
	protected $partition = array (
			'field' => 'id', // 分表依据字段-主键id
			'type' => 'id', // id模数分表
			'expr' => 5000000, // 单表数量 500万
	        //'num' => 2 // 分表数量，id模数分表方式不用设置
	);
	
	/**
	 * 数据访问对象
	 * @param unknown $data
	 */
	public function getDao($data = array()) {
		$data = empty ( $data ) ? $_POST : $data;
		$table = $this->getPartitionTableName ( $data );
		return $this->table ( $table );
	}
	
	/**
	 * 获取文件信息
	 */
	public function getById($id){
	    $skey = $this->getCacheKeyMain($id);
	    $data = S($skey);
	    if(!$data){
	        $dao = $this->getDao(array('id' => $id));
	        $data = $dao->find($id);
	        S($skey, $data);
	    }
	    return $data;
	}
	
	/**
	 * 获取文件路径
	 * @param unknown $id
	 */
	public function getFilePath($id){
	    $info = $this->getById($id);
	    if(!$info){
	        return '';
	    }
	    return C('REMOTE_FILE_SERVER').$info['group'].'/'.$info['savepath'].$info['savename'];
	}


	/**
	 * 文件上传
	 * @param  array  $files   要上传的文件列表（通常是$_FILES数组）
	 * @param  array  $setting 文件上传配置
	 * @param  string $driver  上传驱动名称
	 * @param  array  $config  上传驱动配置
	 * @return array           文件上传成功后的信息
	 */
	public function upload($files, $setting, $driver = 'Local', $config = null){
        /* 上传文件 */
	    switch ($driver){
	        case 'Fastdfs':
	            $setting['hash'] = false;
	            $setting['subName'] = false;
	            //$setting['rootPath'] = '';
	            //$setting['savePath'] = '';
	            //$setting['saveName'] = '';
	            break;
	        default:
                $setting['callback'] = array($this, 'isFile');
                $setting['removeTrash'] = array($this, 'removeTrash');
                break;
	    }
        $Upload = new Upload($setting, $driver, $config);

        foreach ($files as $key => $file) {
            $ext = strtolower($file['ext']);
            if(in_array($ext, array('jpg','jpeg','bmp','png'))){
                hook('dealPicture',$file['tmp_name']);
            }
        }

        $info   = $Upload->upload($files);

        if($info){ //文件上传成功，记录文件信息
            foreach ($info as $key => &$value) {
                /* 已经存在文件记录 */
                if(isset($value['id']) && is_numeric($value['id'])){
                    continue;
                }

                /* 记录文件信息 */
                if(strtolower($driver)=='sae'){
                    $value['path'] = $config['rootPath'].'Picture/'.$value['savepath'].$value['savename']; //在模板里的url路径
                }else{
                    if(strtolower($driver) != 'local'){
                        $value['path'] = $value['url'];
                    }
                    else{
                        $value['path'] = (substr($setting['rootPath'], 1).$value['savepath'].$value['savename']);	//在模板里的url路径
                    }
                }

                $value['type'] = $driver;

                if(!$value['id']){
                    //TODO: 文件上传成功，但是记录文件信息失败，需记录日志
                    unset($info[$key]);
                }
            }

            foreach($info as &$t_info){
                if($t_info['type'] =='local'){
                    $t_info['path']=get_pic_src($t_info['path']);
                }
                else{
                    $t_info['path']=$t_info['path'];
                }


            }

            return $info; //文件上传成功
        } else {
            $this->error = $Upload->getError();
            return false;
        }
    }


	/**
	 * 下载指定文件
	 * @param  number  $root 文件存储根目录
	 * @param  integer $id   文件ID
	 * @param  string   $args     回调函数参数
	 * @return boolean       false-下载失败，否则输出下载文件
	 */
	public function download($root, $id, $callback = null, $args = null){
	    /* 获取下载文件信息 */
	    $file = $this->find($id);
	    if(!$file){
	        $this->error = L('_NO_THIS_FILE_IS_NOT_THERE_WITH_EXCLAMATION_');
	        return false;
	    }
	
	    /* 下载文件 */
	    switch ($file['location']) {
	        case 0: //下载本地文件
	            $file['rootpath'] = $root;
	            return $this->downLocalFile($file, $callback, $args);
	        case 1: //TODO: 下载远程FTP文件
	            break;
	        default:
	            $this->error = L('_UNSUPPORTED_FILE_STORAGE_TYPE_WITH_EXCLAMATION_');
	            return false;
	
	    }
	
	}
	
	/**
	 * 检测当前上传的文件是否已经存在
	 * @param  array   $file 文件上传数组
	 * @return boolean       文件信息， false - 不存在该文件
	 */
	public function isFile($file){
	    if(empty($file['md5'])){
	        throw new \Exception('缺少参数:md5');
	    }
	    /* 查找文件 */
	    $map = array('md5' => $file['md5']);
	    return $this->field(true)->where($map)->find();
	}
	
	/**
	 * 下载本地文件
	 * @param  array    $file     文件信息数组
	 * @param  callable $callback 下载回调函数，一般用于增加下载次数
	 * @param  string   $args     回调函数参数
	 * @return boolean            下载失败返回false
	 */
	private function downLocalFile($file, $callback = null, $args = null){
	    if(is_file($file['rootpath'].$file['savepath'].$file['savename'])){
	        /* 调用回调函数新增下载数 */
	        is_callable($callback) && call_user_func($callback, $args);
	
	        /* 执行下载 */ //TODO: 大文件断点续传
	        header("Content-Description: File Transfer");
	        header('Content-type: ' . $file['type']);
	        header('Content-Length:' . $file['size']);
	        if (preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT'])) { //for IE
	            header('Content-Disposition: attachment; filename="' . rawurlencode($file['name']) . '"');
	        } else {
	            header('Content-Disposition: attachment; filename="' . $file['name'] . '"');
	        }
	        readfile($file['rootpath'].$file['savepath'].$file['savename']);
	        exit;
	    } else {
	        $this->error = L('_FILE_HAS_BEEN_DELETED_WITH_EXCLAMATION_');
	        return false;
	    }
	}
	
}