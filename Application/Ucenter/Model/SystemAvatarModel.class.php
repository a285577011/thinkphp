<?php
namespace Ucenter\Model;
use Think\Model;

class SystemAvatarModel extends Model
{
    protected $_validate = array(
        array('path', 'require', '缺少图片对象')
    );
   const CACHE_KEY="sys_avatar";
   protected static $statusConf=array(1=>'启用',0=>'禁用');
    /**
     * 保存系统头像
     * @param array $data
     */
    public function saveAvatar($data=array()){
        $data['driver'] = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $data = $this->create($data);
        if(!$data){
            return false;
        }
        $info = $this->where(array('path'=>$data['path']))->find();
        if($info){
            $res = $this->save($data);
        }else{
            $res = $this->add($data);
        }
        S(self::CACHE_KEY, null);
        return $res;
    }
    public function getList($limit=false){
        $data=S(self::CACHE_KEY);
        if(!$data){
            if($limit){
                $data=$this->limit(0,$limit)->select();
            }
            else{
                $data=$this->select();
            }
            $data=self::formatData($data);
            S(self::CACHE_KEY, $data);
        }
        return $data;
    }
    public static function formatData($data){
        if(!$data){
            return false;
        }
        foreach ($data as $k=>$v){
            $data[$k]['statusCn']=self::$statusConf[$data[$k]['status']];
            $data[$k]['imgUrl']=self::getAvatar($data[$k]['path'], $data[$k]['driver']);
        }
        return $data;
        
    }
    public static function getAvatar($path,$driver,$size = 256){
        $avatarObject = new \Ucenter\Widget\UploadAvatarWidget();
        if ($path) {
        
            if($driver == 'local'){
                $avatar_path = "http://".DOMAIN."/Uploads/Avatar".$avatar['path'];
                return $avatarObject->getImageUrlByPath($avatar_path, $size);
            }else{
                $new_img = $path;
                $name = get_addon_class($driver);
                if (class_exists($name)) {
                    $class = new $name();
                    if (method_exists($class, 'thumb')) {
                        $new_img =  $class->thumb($path,$size,$size);
                    }
                }
                return $new_img;
            }
        } else {
            return false;

        }
    }
    public function changeStatus($id,$status){
        $msg='';
        if(!$id){
            return '请选择数据';
        }
        $id=array_map('intval',(array)$id);
        foreach ($id as $v){
            if(!$this->where(array('id'=>$v))->save(array('status'=>$status))){
                $msg.="ID.".$v.'的图片状态无变化或者更新失败!';
            }
        }
        S(self::CACHE_KEY, null);
        $msg OR $msg='更新成功!';     
        return $msg;
    }
}