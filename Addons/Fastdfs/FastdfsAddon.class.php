<?php
namespace Addons\Fastdfs;
use Common\Controller\Addon;

class FastdfsAddon extends Addon
{

    public $info = array(
            'name' => 'Fastdfs',
            'title' => 'Fastdfs远程存储',
            'description' => 'Fastdfs远程存储',
            'status' => 1,
            'author' => 'dpj',
            'version' => '1.0.0'
    );

    public function install ()
    {
        return true;
    }

    public function uninstall ()
    {
        return true;
    }

    /**
     * uploadDriver 上传驱动，必需，用于确定插件是否是上传驱动
     * 
     * @return bool
     * @author :dpj
     */
    public function uploadDriver ()
    {
        return true;
    }

    /**
     * uploadConfig 获取上传驱动的配置
     * 
     * @return array
     * @author :dpj
     */
    public function uploadConfig ()
    {
        $config = $this->getConfig();
        return $uploadConfig = array(
                'server' => $config['server'],
                'domain' => $config['domain'],
                'timeout' => 3600
        );
    }

    /**
     * uploadDealFile 处理上传参数
     * 
     * @param $file
     * @author :dpj
     */
    public function uploadDealFile (&$file)
    {
        $file['fastdfs_key'] = str_replace('./', '', $file['rootPath']) . $file['savepath'] . $file['savename'];
    }

    /**
     * crop 裁剪图片
     * 
     * @param $path
     * @param $crop
     * @return string
     * @author :dpj
     */
    public function crop($path, $crop)
    {
        $config = $this->uploadConfig();
        $url = $config['server'].'?action=crop&path='.$path.'&crop='.$crop;
        
        // 解析crop参数
        $crop = explode(',', $crop);
        $x = $crop[0];
        $y = $crop[1];
        $width = $crop[2];
        $height = $crop[3];

        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
        $con = curl_exec($ch);
        $res = json_decode($con, true);
        if($res['status'] && $res['data'][0]){
            return $res['data'][0];
        }
        exit;
        
        $imageInfo = json_decode($imageInfo);
        /* var_dump($imageInfo);exit;
        // 生成将单位换算成为像素
        $x = floor($x * $imageInfo->width);
        $y = floor($y * $imageInfo->height);
        $width = floor($width * $imageInfo->width);
        $height = floor($height * $imageInfo->height); */
        
        // 返回新文件的路径
        return $imageInfo;
    }

    /**
     * thumb 取缩略图
     * 
     * @param $path
     * @param string $width            
     * @param string $height            
     * @return string
     * @author :dpj
     */
    public function thumb ($path, $width = '', $height = '')
    {
        if (is_numeric($path)){
            $path = D('Common/File')->getFilePath($path);
        }
        
        if(!$width || !$height){
            return $path;
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION); // 获取文件后缀名
        
        return str_replace('.'.$ext, '', $path) . '_' . $width . 'X' . $height . '.' . $ext;
    }

    /**
     * 保存远程文件到本地
     * @param unknown $url
     * @param unknown $savePath 没用
     * @return boolean|string
     */
    public function uploadRemote ($url, $savePath = '')
    {
        $config = $this->uploadConfig();
        $access_key = $config['accessKey'];
        $secret_key = $config['secrectKey'];
        
        $url = $config['server'].'?action=remote&url='.$url;
        
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, $url);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT,10);
        $con = curl_exec($ch);
        $res = json_decode($con, true);
        if($res['status'] && $res['data'][0]){
            return $res['data'][0]['id'];
        }
        exit;

        // 以下仅供参考，安全问题
        $access_token = $this->generate_access_token($access_key, $secret_key, $url);
        
        $header[] = 'Content-Type: application/json';
        $header[] = 'Authorization: Fastdfs ' . $access_token;
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_NOBODY, 1);
        $con = curl_exec($curl);
        if ($con === false) {
            return false;
        } else {
            return $con;
        }
    }
    

    private function urlsafe_base64_encode ($str)
    {
        $find = array( "+", "/" );
        $replace = array( "-", "_" );
        return str_replace($find, $replace, base64_encode($str));
    }

    private function generate_access_token ($access_key, $secret_key, $url, $params = '')
    {
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'];
        $access = $path;
        if (isset($parsed_url['query'])) {
            $access .= "?" . $parsed_url['query'];
        }
        $access .= "\n";
        if ($params) {
            if (is_array($params)) {
                $params = http_build_query($params);
            }
            $access .= $params;
        }
        $digest = hash_hmac('sha1', $access, $secret_key, true);
        return $access_key . ':' . $this->urlsafe_base64_encode($digest);
    }

    public function uploadBase64 ($base64, $savePath)
    {
        
        // return $this->upload($base64);
        $savePath = ltrim($savePath, '/');
        $savePath = str_replace('/', '_', $savePath);
        $config = $this->uploadConfig();
        $access_key = $config['accessKey'];
        $secret_key = $config['secrectKey'];
        
        $access['scope'] = $config['bucket'];
        $access['saveKey'] = $savePath;
        $access['deadline'] = time() + 3600;
        $json = json_encode($access);
        $b = $this->urlsafe_base64_encode($json);
        $sign = hash_hmac('sha1', $b, $secret_key, true);
        $encodedSign = $this->urlsafe_base64_encode($sign);
        $uploadToken = $access_key . ':' . $encodedSign . ':' . $b;
        
        $url = $config['server'].'/putb64/-1';
        $header[] = 'Content-Type: application/octet-stream';
        $header[] = 'Authorization: UpToken ' . $uploadToken;
        
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($curl, CURLOPT_HEADER, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        
        curl_setopt($curl, CURLOPT_POSTFIELDS, $base64);
        $con = curl_exec($curl);
        if ($con === false) {
            return false;
            // echo 'CURL ERROR: ' . curl_error($curl);
        } else {
            return "http://{$config['domain']}/{$savePath}";
        }
    }

    public function water ($path)
    {
        $water_img = get_cover(modC('PICTURE_WATER_IMG', '', 'config'), 'path');
        $water_img = is_bool(strpos($water_img, 'http://')) ? 'http://' . str_replace('//', '/', $_SERVER['HTTP_HOST'] . '/' . $water_img) : $water_img;
        $water_img = $this->urlsafe_base64_encode($water_img);
        if (strpos($path, '?') === false) {
            $path = $path . '?watermark/1/image/' . $water_img;
        } else {
            $path = $path . '/watermark/1/image/' . $water_img;
        }
        return $path;
    }
}