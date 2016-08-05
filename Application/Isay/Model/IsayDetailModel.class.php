<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-27
 * Time: 下午3:30
 * 
 */

namespace Isay\Model;


use Common\Model\ContentHandlerModel;
use Think\Model;

class IsayDetailModel extends Model
{

    public function editData($data = array())
    {
        $contentHandler = new ContentHandlerModel();
      
        $data['content'] = $contentHandler->filterHtmlContent($data['content']);        
        if ($this->find($data['isay_id'])) {
            $res = $this->save($data);
        } else {
            $res = $this->add($data);
        }     
        return $res;
    }

    public function getData($id)
    {
        $contentHandler = new ContentHandlerModel();
        $res = $this->where(array('isay_id' => $id))->find();
        $res['content'] = $contentHandler->displayHtmlContent($res['content']);
        return $res;
    }

}