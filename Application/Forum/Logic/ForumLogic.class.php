<?php
namespace Forum\Logic;

class ForumLogic extends \Think\Model
{

	public function forumIndex($page = 1, $sort = 1)
	{
		$data = array();
		list ($data['list'], $data['_page']) = $this->getForumPost($page, $forumId, $sort); // 读取帖子列表
		$data['tags'] = $this->getHotTag(); // 热门标签
		$data['recForum'] = $this->getRecForum(); // 推荐板块
		$data['hotPost'] = $this->getHotPost(); // 热门帖子
		return $data;
	}

	public function getForumList($page = 1, $forumId = null, $sort = 1)
	{
		$data = array();
		$forums = M('Forum')->where(array(
				'status' => 1
		))
			->order('sort asc')
			->field('id,title')
			->select();
		$data['forums'] = $forums;
		// 标签
		$data['tags'] = $this->getHotTag(); // 热门标签
		$data['hotPost'] = $this->getHotPost(); // 热门帖子
		$data['status'] = D('ForumTag')->getStatus();
		list ($data['list'], $data['_page']) = $this->getForumPost($page, $forumId, $sort); // 读取帖子列表
		return $data;
	}

	public function getForumPost($page = 1, $forumId = null, $sort)
	{
		$forumPostWhere = array(
				'status' => 1
		);
		$forumId && $forumPostWhere['forum_id'] = $forumId;
		switch($sort)
		{
			case 1:
				$sortStr = 'create_time DESC';
				break;
			case 2:
				$sortStr = 'view_count DESC';
				break;
			default:
				$sortStr = 'create_time DESC';
				break;
		}
		$list = D('ForumPost')->where($forumPostWhere)
			->order($sortStr)
			->page($page, modC('_FORUM_HOME_PER_PAGE_COUNT_', 5, 'Forum'))
			->select();
		$list = D('ForumPost')->assignForumInfo($list);
		foreach($list as $k => $v)
		{
			$list[$k]['nickname'] = query_user(array(
					'nickname', 'avatar64'
			), $list[$k]['uid']);
		}
		$totalCount = D('ForumPost')->where($forumPostWhere)->count();
		$pageLink = getPageViewF($totalCount, modC('_FORUM_HOME_PER_PAGE_COUNT_', 5, 'Forum'), null, true, 10, false);
		return array(
				$list, $pageLink
		);
	}

	public function assignSuggestionTopics()
	{
		$posts = S('forum_suggestion_posts');
		if($posts === false)
		{
			$suggestion_topics = modC('SUGGESTION_POSTS', '23,24,25,26,27');
			$suggestion_topics = explode('|', $suggestion_topics);
			foreach($suggestion_topics as $s)
			{
				$post = M('ForumPost')->find($s);
				$post['cover'] = $this->get_pic($post['content']);
				$post['summary'] = mb_substr(text($post['content']), 0, 80, 'utf8') . '...';
				$posts[] = $post;
				S('forum_suggestion_posts', $posts, 60);
			}
		}
		
		// $this->assign ( 'suggestionPosts', $posts );
		return $posts;
	}

	/**
	 * 正则表达式获取html中首张图片
	 *
	 * @param
	 *        	$str_img
	 * @return mixed
	 */
	function get_pic($str_img)
	{
		preg_match_all("/<img.*\>/isU", $str_img, $ereg); // 正则表达式把图片的整个都获取出来了
		$img = $ereg[0][0]; // 图片
		$p = "#src=('|\")(.*)('|\")#isU"; // 正则表达式
		preg_match_all($p, $img, $img1);
		$img_path = $img1[2][0]; // 获取第一张图片路径
		return $img_path;
	}

	public function getForumPostDetail($id, $page = 1)
	{
		$data = array();
		if(! $id)
		{
			E(L('_ERROR_PARAM_'));
		}
		// 读取帖子内容
		$data['forumPost'] = D('ForumPost')->where(array(
				'id' => $id, 'status' => 1
		))->find();
		$data['forumPost'] = $this->formatForumPost($data['forumPost']);
		// 增加浏览次数
		D('ForumPost')->where(array(
				'id' => $id
		))->setInc('view_count');
		$data['forumPost']['forum'] = getForumNameById($data['forumPost']['forum_id']);
		! $data['forumPost']['forum'] && E(L('_ERROR_FORUM_NOT_FOUND_'));
		$data['forumPost']['forum']['background'] = $data['forumPost']['forum']['background'] ? getThumbImageById(
			$data['forumPost']['forum']['background'], 800, 'auto') : C('TMPL_PARSE_STRING.__IMG__') . '/default_bg.jpg';
		$data['list'] = query_user(array(
				'level', 'score3', 'nickname', 'signature', 'avatar64'
		), $data['forumPost']['uid']); // 获取用户信息
		list ($data['replyList'], $data['_page'], $data['replyTotalCount']) = $this->getReplyList(
			array(
					'post_id' => $id, 'status' => 1
			), $page); // 读取回复列表
		$data['showMainPost'] = $page == 1 ? true : false; // 判断是否需要显示1楼
		$data['isBookmark'] = D('ForumBookmark')->exists(is_login(), $id); // 判断是否已经收藏
		$data['isPointLike'] = D('ForumPointlike')->exists(is_login(), $id, 1); // 是否点赞
		$tagName = @explode(',', M('ForumTagLink')->where(array(
				'fid' => $id
		))->getField('tags'));
		array_filter($tagName) && $data['tagName'] = $tagName;
		$data['tags'] = $this->getHotTag(); // 热门标签
		$data['relatePost'] = $this->getRelatePost($id); // 相关文章 //
		                                                 // $replyLzlList=$this->lzllist($id,$page);//楼中楼
		return $data;
	}

	public function getReplyList($map = array(), $page)
	{
		$replyList = D('ForumPostReply')->getReplyList($map, 'create_time', $page, 
			modC('_FORUM_HOME_PER_PAGE_COUNT_', 5, 'Forum'));
		foreach($replyList as $k => $reply)
		{
			$replyList[$k]['content'] = D('Common/ContentHandler')->displayHtmlContent($replyList[$k]['content']);
			$replyList[$k]['useinfo'] = query_user(array(
					'nickname', 'avatar64'
			), $replyList[$k]['uid']);
			$replyList[$k]['isPointLikeReply'] = D('ForumPointlike')->exists(is_login(), $replyList[$k]['id'], 2); // 是否点赞
			$replyList[$k]['content'] = parse_expression($replyList[$k]['content']);
		}
		$replyTotalCount = D('ForumPostReply')->where($map)->count();
		$pageLink = getPageViewF($replyTotalCount, modC('_FORUM_HOME_PER_PAGE_COUNT_', 5, 'Forum'), null, true, 10, 
			false);
		return array(
				$replyList, $pageLink, $replyTotalCount
		);
	}

	public function formatForumPost($data)
	{
		! $data && E(L('_ERROR_POST_NOT_FOUND_'));
		$data['content'] = D('Common/ContentHandler')->displayHtmlContent($data['content']); // 过滤内容
		return $data;
	}

	public function lzllist($to_f_reply_id, $page = 1, $p = 1)
	{
		$limit = 5;
		$list = D('ForumLzlReply')->getLZLReplyList($to_f_reply_id, 'ctime asc', $page, $limit);
		$totalCount = D('forum_lzl_reply')->where('is_del=0 and to_f_reply_id=' . $to_f_reply_id)->count();
		$data['to_f_reply_id'] = $to_f_reply_id;
		$pageCount = ceil($totalCount / $limit);
		$html = getPageHtml('changePage', $pageCount, $data, $page);
	}

	public function addTag($tags)
	{
		if(! $tags)
		{
			return false;
		}
		$tagArr = @explode(',', $tags);
		$ids = '';
		$model = M('ForumTag');
		foreach($tagArr as $v)
		{
			if($id = $model->where(array(
					'title' => $v
			))
				->field('id')
				->find())
			{
				$ids .= $id['id'] . ',';
			}
			else
			{
				$firstAbc = ord(getFirstChar($v));
				$ids .= $model->add(
					array(
							'create_time' => time(), 'title' => $v, 'first_abc' => $firstAbc
					)) . ',';
			}
		}
		return rtrim($ids, ',');
	}

	public function editTag($post_id, $tagsNameString, $forum_id)
	{
		$idsStr = $this->addTag($tagsNameString);
		M('ForumTagLink')->where(array(
				'fid' => $post_id, 'forum_id' => $forum_id
		))->save(array(
				'tags' => $idsStr
		));
	}

	public function getHotPost()
	{
		$data = S('Forum_HotPost'); // 热门帖子
		if(! $data)
		{
			$data = M('ForumPost')->field('id,title,content,uid,create_time')
				->order('view_count desc,reply_count desc,collect_count desc,point_like_count desc')
				->limit(5)
				->select();
			foreach($data as $k => $v)
			{
				$data[$k]['nickname'] = query_user(array(
						'nickname', 'avatar64'
				), $data[$k]['uid']);
			}
			S('Forum_HotPost', $data, 3600); // 缓存一个小时
		}
		return $data;
	}

	public function getRecForum()
	{
		$data = S('Forum_RecForum'); // 推荐板块
		if(! $data)
		{
			$data = M('Forum')->where(
				array(
						'id' => array(
								'in', modc('_FORM_BLOCK_RECOMMEND_')
						)
				))
				->field('id,title')
				->select();
			S('Forum_RecForum', $data, 3600); // 缓存一个小时
		}
		return $data;
	}

	public function getHotTag()
	{
		$data = S('Forum_HotTag');
		if(! $data)
		{ // 标签
			$data = D('ForumTag')->getTags(); // 热门标签
			S('Forum_HotTag', $data, 3600); // 缓存一个小时
		}
		return $data;
	}

	public function getActiveUser()
	{
		$active_user = S('Forum_ActiveUser');
		if($active_user === false)
		{
			$active_user = M('ForumPost')->field('uid,count(id) as post_count')
				->group('uid')
				->order('post_count desc')
				->limit(9)
				->select();
			foreach($active_user as &$u)
			{
				$u['user'] = query_user(array(
						'nickname', 'space_url', 'avatar64'
				), $u['uid']);
			}
			S('Forum_ActiveUser', $active_user, 3600); // 一小时
		}
	}

	public function getRelatePost($postId)
	{
		$postData = S('Forum_RelatePost');
		if(! $postData)
		{
			$forumId = M('ForumPost')->where(array(
					'id' => $postId
			))->getField('forum_id');
			if(! $forumId)
			{
				E(L('_ERROR___POST___NOT_FOUND_'));
			}
			$postData = M('ForumPost')->where(array(
					'forum_id' => $forumId
			))
				->field('id,title,uid,create_time')
				->order('create_time DESC')
				->limit(5)
				->select();
			foreach($postData as $k => $v)
			{
				if($postData[$k]['id'] == $postId)
				{
					unset($postData[$k]);
				}
				else
				{
					$postData[$k]['nickname'] = query_user(array(
							'nickname', 'avatar64'
					), $postData[$k]['uid']);
				}
			}
			S('Forum_RelatePost', $postData, 3600); // 缓存一个小时
		}
		return $postData;
	}

	public function getImgById($ids = '')
	{
		if(! $ids)
		{
			return null;
		}
		$idArr = explode(',', $ids);
		$imgSrc = '';
		foreach($idArr as $v)
		{
			$imgSrc .= '<img src="' . M('Picture')->where(array(
					'status' => 1, 'id' => $v
			))->getField('path') . '" />';
		}
		return $imgSrc;
	}
}
