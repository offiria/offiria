<?php

/**
 * @package		Offiria
 * @subpackage	Core
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamComment
{
	/**
	 * Return the comment HTML
	 * @param type $stream_id
	 */
	public static function getCommentSummaryHTML( $stream )
	{
		$tmpl	= new StreamTemplate();
		$model	= StreamFactory::getModel('stream');
		$data	= array();

		// Check if comment count is in the params, if not
		// grab it from the model and update the record
		$count = $stream->getParam('comment_count', null);
		if( $count == null )
		{
			$count  = $model->countComments( array('stream_id' => $stream->id) );
			$stream->setParam('comment_count', $count);
			$stream->store(true);
		}

		// If no comment, no need for query
		if($count > 0)
		{
			$data	= $model->getComments( array('message_id' =>$stream->id, 'order_by_desc' => 'id' ));
		}

		$last_comment = ($count > 0) ? $data[0]: null;

		// Reverse the data ordering
		$data = array_reverse($data);

		$tmpl->set('count', $count)
				->set('comment', $data)
				->set('like_count', $stream->countLike())
				->set('stream_id', $stream->id);

		// Direct messaging layout
		if($stream->type == 'direct') {
			$listTmpl = 'comment.direct.summary';
		} else {
			$listTmpl = 'comment.summary';
		}

		return $tmpl->fetch($listTmpl);

	}

	/**
	 *
	 */
	public static function deleteComments($stream_id)
	{
		$model	= StreamFactory::getModel('stream');
		$model->deleteComments($stream_id);
	}

	/**
	 *
	 */
	public static function getCommentsHTML( $stream_id )
	{
		$tmpl	= new StreamTemplate();
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getComments( array('message_id' =>$stream_id));
		$last_comment = (count($data) > 0) ? $data[0]: null;

		$tmpl->set('comments', $data)
			 ->set('stream_id', $stream_id);
		return $tmpl->fetch('comment.list');

	}
}
