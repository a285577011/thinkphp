<?php

namespace Weiquan\Widget;

use Think\Controller;

class CommonWidget extends Controller {
    /* 显示指定分类的同级分类或子分类列表 */

    public function pagenav() {
        
        $this->display(T('Weiquan@Widget/page_nav'));
    }


}
