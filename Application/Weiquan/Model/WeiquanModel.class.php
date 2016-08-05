<?php
namespace Weiquan\Model;

use Think\Model;
use Think\Hook;

require_once('./Application/Weiquan/Common/function.php');

class WeiquanModel extends Model
{   
    
    protected $_validate = array(
        array('content', '0,99999', '内容不能为空', self::EXISTS_VALIDATE, 'length'),
        array('content', '0,500', '内容太长', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );
    
    protected $_skey_weiquan = 'weiquan_{id}'; // 微商圈明细(适用web)
    protected $_skey_weiquan_api = 'weiquan_api_{id}'; // 微商圈明细(适用接口)
    
    protected $_type = array('repost', 'feed', 'image', 'share', 'video');  // 微商圈类型(转发、文字、图片、分享、视频)
    
    /**
     * 获取主键缓存key
     * @param number $id
     */
    public function getCacheKeyMain($id){
        return str_replace('{id}', $id, $this->_skey_weiquan);
    }
    
    /**
     * 获取主键缓存key(用于api)
     * @param number $id
     */
    public function getCacheKeyApi($id){
        return str_replace('{id}', $id, $this->_skey_weiquan_api);
    }
    
    /**
     * 获取微商圈类型
     * @return multitype:string
     */
    public function getType(){
        return $this->_type;
    }

    public function addWeiquan($uid, $content = '', $type = 'feed', $feed_data = array(), $from = '', $aAttachIds)
    {
        $aContent = I('post.content', '', 'html');
        if($content == ''){
            $content = str_replace(' ', '/nb', $aContent);
        }
        else{
            $content = str_replace(' ', '/nb', $content);
        }
        $content = nl2br($content);
        $content = str_replace('<br />', '/br ', $content);
        $content = text($content);
        //$topic = get_topic($content);

        //写入数据库
        $data = array('uid' => $uid, 'content' => $content, 'type' => $type, 'data' => serialize($feed_data), 'from' => $from);
        $data = $this->create($data);
        if(!$feed_data && !$content){
            //E('内容不能为空');
            $this->error = '内容不能为空';
            return false;
        }
        if (!$data) return false;
        $weiquan_id = $this->add($data);

        //返回微博编号
        return $weiquan_id;
    }


    public function getWeiquanCount($map)
    {
            return $this->where($map)->count();
    }

    public function getWeiquanList($param = null, $showUser = false)
    {
        $uid = $param['where']['uid'] ? $param['where']['uid'] : is_login();
        if (! $showUser) {
            ! empty($param['field']) && $this->field($param['field']);
            ! empty($param['where']) && $this->where($param['where']);
            ! empty($param['limit']) && $this->limit($param['limit']);
            empty($param['order']) && $param['order'] = 'create_time desc';
            ! empty($param['page']) && $this->page($param['page'], empty($param['count']) ? 10 : $param['count']);
            $this->order($param['order']);
            $list = empty($param['field']) ? $this->field('id')->select() : $this->select(); // 减少字段查询
            empty($param['field']) && $list = getSubByKey($list, 'id');
            unset($param['where']['id']);
            $total = $this->getWeiquanCount($param['where']);
            return array( $list, $total );
        }
        // $myFollowUid=D('Common/Sphinx')->search('weiquanFollow
        // weiquanFollowDelta','@uid_follow '.is_login().' | @uid
        // '.is_login(),$sphnixParam);
        $sphinxModel = D('Common/Sphinx');
        $where['where']['status'] = 1;
        $where['where']['uid_follow'] = $uid;
        $myFollowUid = $sphinxModel->search('myFollowUid myFollowUidDelta', '', $where);
        $uids = array();
        if ($myFollowUid['total']) {
            foreach ($myFollowUid['list'] as $k => $v) {
                $uids[] = $myFollowUid['list'][$k]['attrs']['follow_uid'];
            }
        }
        array_push($uids, $uid);
        $sphnixParam = array();
        $sphnixParam['where']['uid'] = $uids;
        $sphnixParam['where']['status'] = 1;
        $param['where']['id'] && $sphnixParam['where']['weiquan_id'] = $param['where']['id'];
        $sphnixParam['order'] = $param['order'] ?  : 'create_time desc';
        $sphnixParam['page'] = $param['page'] ? $param['page'] : 1;
        $sphnixParam['limit'] = $sphnixParam['page'] == 1 ? 10 : 30;
        $sphinxModel = new \Common\Model\SphinxModel();
        $list = $sphinxModel->search('weiquan weiquanDelta', '', $sphnixParam);
        $data = array();
        if ($list['total']) {
            foreach ($list['list'] as $k => $v) {
                $data[] = $list['list'][$k]['attrs']['weiquan_id'];
            }
        }
        return array( $data, $list['total']);
    }


    public function getWeiquanDetail($id)
    {
        $weibo = S($this->getCacheKeyMain($id));
        $check_empty = empty($weibo);
        if ($check_empty) {
            $weibo = $this->where(array('status' => 1, 'id' => $id))->find();
            if (!$weibo) {
                return null;
            }
            $weibo_data = unserialize($weibo['data']);
            $class_exists = true;

            if (!in_array($weibo['type'], $this->_type)) {
                $class_exists = class_exists('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon');
            }
            $weibo['content'] = parse_topic(parse_weibo_content($weibo['content']));
            if ($weibo['type'] === 'feed' || $weibo['type'] == '' || !$class_exists) {
                $fetchContent = "<p class='word-wrap'>" . $weibo['content'] . "</p>";

            } elseif ($weibo['type'] === 'repost') {
                $fetchContent = A('Weiquan/Type')->fetchRepost($weibo);
            } elseif ($weibo['type'] === 'image') {
                $fetchContent = A('Weiquan/Type')->fetchImage($weibo);
            } elseif ($weibo['type'] === 'share') {
                $fetchContent = R('Weiquan/Share/getFetchHtml', array('param' => unserialize($weibo['data']), 'weiquan' => $weibo), 'Widget');
            }else{
                $fetchContent = Hook::exec('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon', 'fetch' . ucfirst($weibo['type']), $weibo);
            }
            $weibo = array(
                'id' => intval($weibo['id']),
                'content' => strval($weibo['content']),
                'create_time' => intval($weibo['create_time']),
                'type' => $weibo['type'],
                'data' => unserialize($weibo['data']),
                'weiquan_data' => $weibo_data,
                'comment_count' => intval($weibo['comment_count']),
                'repost_count' => intval($weibo['repost_count']),
                'like_count' => intval($weibo['like_count']),
                'can_delete' => 0,
                'is_top' => $weibo['is_top'],
                'uid' => $weibo['uid'],
                'fetchContent' => $fetchContent,
                'from' => $weibo['from']

            );
            S($this->getCacheKeyMain($id), $weibo, 60 * 60);
        }

        $weibo['fetchContent'] = parse_at_users($weibo['fetchContent']);
        $weibo['user'] =  get_user_info($weibo['uid']);// query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), $weibo['uid']);
        $weibo['can_delete'] = $this->canDeleteWeiquan($weibo);


        // 判断转发的原微博是否已经删除
        if ($weibo['type'] == 'repost') {
            $source_weibo = $this->getWeiquanDetail($weibo['weiquan_data']['sourceId']);
            if (!$source_weibo['uid']) {
                if (!$check_empty) {
                    S($this->getCacheKeyMain($id), null);
                    // TODO app是否有转发功能，有是否需要删除缓存
                    $weibo = $this->getWeiquanDetail($id);
                }
            }
        }
        return $weibo;
    }

    /**
     * 简化的明细
     * @param int $id
     */
    public function getDetail($id)
    {
    
        $data = S($this->getCacheKeyApi($id));
    
        $check_empty = empty($data);
        if ($check_empty) {
            $data = $this->where(array('status' => 1, 'id' => $id))->find();
            if (!$data) {
                return null;
            }
            $data['data'] = unserialize($data['data']);

            if (!in_array($data['type'], $this->_type)) {
                // TODO
            }

            if($data['type'] == 'image'){
                $data['attachment'] = $this->fetchImage($data['data']['attach_ids']);
            }elseif($data['type'] == 'video'){
                $data['attachment'] = $this->fetchVideo($data['data']['attach_ids']);
            }elseif($data['type'] == 'repost'){
                $data['repost_source'] = $this->getDetail($data['data']['sourceId']);
                if (!$data['repost_source']) {
                    if (!$check_empty) {
                        S($this->getCacheKeyMain($id), null);
                    }
                }
            }
            unset($data['data']);
            $data['content'] = parse_topic(parse_weibo_content($data['content']));
            S($this->getCacheKeyApi($id), $data, 60 * 60);
        }
        return $data;
    }

    private function canDeleteWeiquan($weibo)
    {
        //如果是管理员，则可以删除微博
        if (check_auth('Weiquan/Index/doDelWeiquan', $weibo['uid'])) {
            return true;
        }

        //返回，不能删除微博
        return false;
    }


    public function deleteWeiquan($weiquan_id)
    {
        $weibo = $this->getWeiquanDetail($weiquan_id);


        //从数据库中删除微博、以及附属评论
        $result = $this->where(array('id' => $weiquan_id))->save(array('status' => -1, 'comment_count' => 0));
        D('Weiquan/WeiquanComment')->where(array('weiquan_id' => $weiquan_id))->setField('status', -1);

        if ($weibo['type'] == 'repost') {
            $this->where(array('id' => $weibo['weibo_data']['sourceId']))->setDec('repost_count');
            S($this->getCacheKeyMain($weibo['weiquan_data']['sourceId']), null); // 删除转发来源的缓存
            // TODO api接口缓存
        }

        S($this->getCacheKeyMain($weiquan_id), null);
        
        D('Common/Sphinx')->update('weiquan weiquanDelta',array('status'=>-1),false,array('weiquan_id'=>$weiquan_id));//更新SPHINX
        return $result;
    }
    
    

    public function getSupportedPeople($weiquan_id, $user_fields = array('nickname', 'space_url', 'avatar128', 'space_link'), $num = 8)
    {
        $user_fields == null ? array('nickname', 'space_url', 'avatar128', 'space_link') : $user_fields;
        $supported = D('Support')->getSupportedUser('Weiquan', 'weiquan', $weiquan_id, $user_fields, $num);

        return $supported;
    }

    /**
     * fetchImage  获取附件
     * @param $attachment
     * @return string
     *
     */
    public function fetchImage($attachment)
    {
        $attachment = explode(',', $attachment);
        $tag='ttp:';
        switch(count($attachment)){
            case 1:
                foreach ($attachment as $k_i => $v_i) {
                    $data[$k_i]['big']  = get_pic_src(D('Common/File')->getFilePath($v_i)) ;
                    $data[$k_i]['small'] = getThumbImageById($v_i, 1000, 1000);
                    /* $pi = $data[$k_i]['big'];
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $data[$k_i]['size'] = $hv[0].'x'.$hv[1]; */
                    $param = $data;
                }
                break;
            case 2:
                foreach ($attachment as $k_i => $v_i) {
                    $data[$k_i]['big']  = get_pic_src(D('Common/File')->getFilePath($v_i)) ;
                    $data[$k_i]['small'] = getThumbImageById($v_i, 350, 350);
                    /* $pi = $data[$k_i]['big'];
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $data[$k_i]['size'] = $hv[0].'x'.$hv[1]; */
                    $param = $data;
                }
                break;
            case 3:
                foreach ($attachment as $k_i => $v_i) {
                    $data[$k_i]['big']  = get_pic_src(D('Common/File')->getFilePath($v_i)) ;
                    $data[$k_i]['small'] = getThumbImageById($v_i, 300, 300);
                    /* $pi = $data[$k_i]['big'];
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $data[$k_i]['size'] = $hv[0].'x'.$hv[1]; */
                    $param = $data;
                }
                break;
            case 4:
                foreach ($attachment as $k_i => $v_i) {
                    $data[$k_i]['big']  = get_pic_src(D('Common/File')->getFilePath($v_i)) ;
                    $data[$k_i]['small'] = getThumbImageById($v_i, 300, 300);
                    /* $pi = $data[$k_i]['big'];
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $data[$k_i]['size'] = $hv[0].'x'.$hv[1]; */
                    $param = $data;
                }
                break;
            default :
                foreach ($attachment as $k_i => $v_i) {
                    $data[$k_i]['big']  = get_pic_src(D('Common/File')->getFilePath($v_i)) ;
                    $data[$k_i]['small'] = getThumbImageById($v_i, 200, 200);
                    /* $pi = $data[$k_i]['big'];
                    if(!strpos($pi,$tag))
                        $pi = '.'.$pi;
                    $hv = getimagesize($pi);
                    $data[$k_i]['size'] = $hv[0].'x'.$hv[1]; */
                    $param = $data;
                }
        }
        return $param;
    }
    
    /**
     * 获取视频附件
     * @param string $attachment
     */
    public function fetchVideo($attachment)
    {
        $attachment = explode(',', $attachment);
        if(!$attachment){
            return array();
        }
        $data = array();
        //foreach ($attachment as $k => $v) {
        // 固定格式，一个视频,一张截图
        $data[0]['video'] = D('Common/File')->getFilePath($attachment[0]);
        $data[0]['cover'] = D('Common/File')->getFilePath($attachment[1]);
        //}
        return $data;
    }

}