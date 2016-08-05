<?php

return array(

    'switch'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'是否开启Fastdfs云存储：',//表单的文字
        'type'=>'radio',		 //表单的类型：text、textarea、checkbox、radio、select等
        'options'=>array(
            '1'=>'启用',
            '0'=>'禁用',
        ),
        'value'=>'0',
        'tip'=>'启用时请确保其他云储插件为禁用状态'
    ),

    'server'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'Fastdfs云存储的服务器地址：',//表单的文字
        'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',			 //表单的默认值
        'tip'=>'例如:http://10.0.0.188'
    ),

    'secrectKey'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'Fastdfs云存储的秘钥：',//表单的文字
        'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',			 //表单的默认值
        'tip'=>''
    ),

    'domain'=>array(//配置在表单中的键名 ,这个会是config[title]
        'title'=>'Fastdfs云服务器的空间对应的域名：',//表单的文字
        'type'=>'text',		 //表单的类型：text、textarea、checkbox、radio、select等
        'value'=>'',			 //表单的默认值
        'tip'=>'不带http://，例如icnimg.cn'
    ),

);