<?php
namespace App\Controller;

/**
 * curl 函数
 * @param string $url 请求的地址
 * @param string $type POST/GET/post/get
 * @param array $data 要传输的数据
 * @param string $err_msg 可选的错误信息（引用传递）
 * @param int $timeout 超时时间
 * @param array 证书信息
 */
/* function GoCurl($url, $type, $data = false, &$err_msg = null, $timeout = 20, $cert_info = array())
{
	$type = strtoupper($type);
	if ($type == 'GET' && is_array($data)) {
		$data = http_build_query($data);
	}

	$option = array();

	if ( $type == 'POST' ) {
		$option[CURLOPT_POST] = 1;
	}
	if ($data) {
		if ($type == 'POST') {
			$option[CURLOPT_POSTFIELDS] = $data;
		} elseif ($type == 'GET') {
			$url = strpos($url, '?') !== false ? $url.'&'.$data :  $url.'?'.$data;
		}
	}

	$option[CURLOPT_URL]            = $url;
	$option[CURLOPT_FOLLOWLOCATION] = TRUE;
	$option[CURLOPT_MAXREDIRS]      = 4;
	$option[CURLOPT_RETURNTRANSFER] = TRUE;
	$option[CURLOPT_TIMEOUT]        = $timeout;

	//设置证书信息
	if(!empty($cert_info) && !empty($cert_info['cert_file'])) {
		$option[CURLOPT_SSLCERT]       = $cert_info['cert_file'];
		$option[CURLOPT_SSLCERTPASSWD] = $cert_info['cert_pass'];
		$option[CURLOPT_SSLCERTTYPE]   = $cert_info['cert_type'];
	}

	//设置CA
	if(!empty($cert_info['ca_file'])) {
		// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
		$option[CURLOPT_SSL_VERIFYPEER] = 1;
		$option[CURLOPT_CAINFO] = $cert_info['ca_file'];
	} else {
		// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。1需要设置CURLOPT_CAINFO
		$option[CURLOPT_SSL_VERIFYPEER] = 0;
	}

	$ch = curl_init();
	curl_setopt_array($ch, $option);
	$response = curl_exec($ch);
	$curl_no  = curl_errno($ch);
	$curl_err = curl_error($ch);
	curl_close($ch);

	// error_log
	if($curl_no > 0) {
		if($err_msg !== null) {
			$err_msg = '('.$curl_no.')'.$curl_err;
		}
	}
	return $response;
}

$url   = 'http://www.icnfile.cn/';
$data = array(
		'file1'=>'@/Uploads/Picture/2015-11-27/5657f3105cf10.jpg',
		'file2'=>'@/Uploads/Picture/2015-11-27/5657f3105cf10_348_70.jpg'
);
$json = GoCurl($url, 'POST', $data, $error_msg);

var_dump($json);exit;

$array = json_decode($json, true);

print_r($array); */

class IndexController extends BaseController
{

    public function index ()
    {
    	import('Org.Net.HttpCurl');
    	\HttpCurl::get('http://www.icnfile.cn/');
    	$res = \HttpCurl::post('http://www.icnfile.cn/', array(
    			'abc'=>'123',
    			'efg'=>'567'
    	));
    	
    	
    	$res = \HttpCurl::post('http://www.icnfile.cn/', array(
    			'image[]'=>'@'.'./Uploads/Picture/2015-11-27/5657f3105cf10.jpg',
    			'image[]'=>'@'.'./Uploads/Picture/2015-11-27/5657f3105cf10_348_70.jpg'
    	));
    	var_dump($res);
    	exit;
        $this->result = array('result'=>0, 'msg'=>'非法访问',  'data' => (object)array());
        exit;
    }
}