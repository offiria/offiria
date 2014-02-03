<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.controller');

class StreamControllerComment extends JController
{
	/**
	 *
	 */	 	
	public function display($cachable = false, $urlparams = false){
		parent::display( null );
	}
	
	
	/**
	 *  Return HTML of all
	 */	 	
	public function showall()
	{
		$stream_id = JRequest::getVar('message_id');
		$html = StreamComment::getCommentsHTML($stream_id);
		header('Content-Type: text/html; charset=UTF-8');
		echo $html;
		exit;
	}
	
	/**
	 *  Add message
	 */	 	                       
	public function add()
	{
		$user	= JXFactory::getUser();
		
		// Store stream
		$comment	= JTable::getInstance( 'Comment' , 'StreamTable' );
		$message	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$message->load(JRequest::getVar('stream_id'));
		$user_id = JRequest::getVar('anon', false) ? JUserHelper::getUserId('anon') : $user->id;

		// People need to be able to read the message to add comment
		if( !$user->authorise('stream.message.read', $message) ){
			// No reason this code would ever get here!
		    exit;
		}

		$comment->bind( JRequest::get('POST', JREQUEST_ALLOWRAW) );
		
		$comment->raw = json_encode( JRequest::get('POST', JREQUEST_ALLOWRAW) );
		$comment->user_id = $user_id;
		$comment->group_id = $message->group_id;			
		$comment->store();
		
		// Update group stats, if it is a group message
		if( !empty($comment->group_id)){
			$group	= JTable::getInstance( 'Group' , 'StreamTable' );
			$group->load($comment->group_id);
			$group->setParam('last_comment', $comment->id);
			$group->store();
		}
		
		// Trigger Notification 
		StreamNotification::trigger( 'profile_post_comment', $comment );

		// If the updated date is set further than the current date, it is a pinned stream item and shouldn't be updated
		$now 		= new JDate();
		$updated 	= new JDate($message->updated);
		$preventUpdate = ($updated->toUnix() > $now->toUnix()); // Check pinned item status too? For now... naa
		
		// Update stream stats. Recalculate the count
		$this->_recalculateCommentCount($comment->stream_id, $preventUpdate);
		
		// Get the HTML code to append
		$tmpl = new StreamTemplate();
		header('Content-Type: text/html; charset=UTF-8');
		echo $tmpl->set('comment', $comment)->fetch('comment.item');
		exit;
	}
	
	/**
	 * Delete a message
	 */
	public function delete(){
		$my = JXFactory::getUser();
		$id = JRequest::getVar('comment_id');
		$oldComment = '';
		
		$comment	= JTable::getInstance( 'Comment' , 'StreamTable' );
		$comment->load($id);
		
		if( !$my->authorise('stream.comment.delete', $comment) ){
			// No reason this code would ever get here!
		    exit;
		}
		$oldComment = $comment->comment;
		$comment->delete();
		$this->_recalculateCommentCount($comment->stream_id, true);
		
		// Send back the original comment if it is the last one
		$data = array();
		$data['comment'] = $oldComment;
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	private function _recalculateCommentCount($message_id, $preventUpdate = false)
	{
		// Update stream stats. Recalculate the count
		$model = StreamFactory::getModel('stream');
		$stream = JTable::getInstance('Stream', 'StreamTable');
		$stream->load( $message_id ) ;
		$stream->setParam('comment_count', $model->countComments(array('stream_id' => $stream->id)));
		$stream->store( $preventUpdate );
	}

	/**
	 *  Like a comment
	 */
	public function like()
	{
		$user = JXFactory::getUser();
		$comment_id = JRequest::getVar('comment_id');

		$comment = JTable::getInstance('Comment', 'StreamTable');
		$comment->load($comment_id);

		$comment->like();
		$comment->store(true);

		$data = array();
		$data['label'] = JText::_('COM_STREAM_UNLIKE_LABEL');
		$data['count'] = $comment->getLikeCount();

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/**
	 * Unlike a comment
	 */
	public function unlike()
	{
		$user = JXFactory::getUser();
		$comment_id = JRequest::getVar('comment_id');

		$comment = JTable::getInstance('Comment', 'StreamTable');
		$comment->load($comment_id);
		$comment->unlike();

		$comment->store(true);

		$data = array();
		$data['label'] = JText::_('COM_STREAM_LIKE_LABEL');
		$data['count'] = $comment->getLikeCount();

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/**
	 * Get all current likes
	 */
	public function showlikes()
	{
		$comment_id = JRequest::getVar('comment_id');
		$comment = JTable::getInstance('Comment', 'StreamTable');
		$comment->load($comment_id);

		$likes = ($comment->likes) ? explode(',', $comment->likes ) : null;
		$likeUsers = array();
		$likesHTML = '';
		if ($likes) {
			foreach ($likes as $key => $val) {
				$likeUsers[] = JXFactory::getUser($val)->name;
			}

			$likesHTML = implode(", ", $likeUsers);
			$likesHTML = JXString::isPlural(count($likeUsers)) ? JText::sprintf('COM_STREAM_LIKE_THIS_MANY_LIST', $likesHTML) : JText::sprintf('COM_STREAM_LIKE_THIS_LIST', $likesHTML);
		}

		$data['likes'] = $likesHTML;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
}