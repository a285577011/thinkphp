<?php
/**
 * @author: hongchao
 * @since: 16-02-03
 * 
 */
namespace Forum\Model;
use Think\Model;

class ForumTagModel extends Model
{

    protected $_status = array(
            '1' => 'tagred',
            '2' => 'tagblue',
            '3' => 'taggreen',
            '4' => 'taghui'
    );

    public function getTags ()
    {
        $result = $this->where(array('id'=>array('in',modc('_FORM_BLOCK_HOT_'))))->field('title, status')->select();
        return $result;
    }

    public function getStatus ()
    {
        return $this->_status;
    }
    public function getTagNameById($id){
        return $this->where(array('id'=>$id))->getField('title');
    }
}