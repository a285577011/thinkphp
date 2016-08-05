<?php

namespace Weiquan\Widget;

use Think\Controller;

class UserWidget extends Controller {
    
    public function userCenterBanner($uid) {
        if($uid){
            $user_info=get_user_info($uid);
             $this->assign('user_info', $user_info);
        }else{
            $user_info=get_user_info(is_login()); 
             $this->assign('user_info', $user_info);
        }    
        $this->display(T('Weiquan@Widget/user/user_center_banner'));
    }

  

}
