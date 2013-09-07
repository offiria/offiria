<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Administrator component
 */
class StreamViewCompany extends StreamView
{
	function display($tpl = null)
	{
		header('Content-Type: application/json');		

		/**
		 * Response values
		 * ======================
		 * 
		// profile: {
		// 	 name: string,
		// 	 id: int
		// },
		// messages: {
		// 	 message: {
		// 		type: string,
		// 		url: string,
		// 		body: {
		// 			parse: string,
		// 			plain: string
		// 		},
		// 		group_id: int (0 means public),
		// 		attachment: {
		// 			path: string,
		// 			filename: string,
		// 			mimetype: string,
		// 			web_url: string,
		// 			image: {
		// 				thumbnail_url: string,
		// 				url: string,
		// 				size: int
		// 			},
		// 			id: int
		// 	 	likes: {
		// 	 		count: int
		// 	 		user_id: string (csv)
		// 		},
		// 		time: {
		// 			created: datetime,
		// 			updated: datetime
		// 		},
		// 		
		// 	 }
		// }		
		// 			
		*/
		$streams = $this->getStream();
		$my = JXFactory::getUser();
		$output['profile']['username'] = $my->username;
		$output['profile']['id'] = $my->id;
			
		// $streams is filtered for pagination
		foreach ($streams as $stream) {
			$raws = json_decode($stream->raw);
			if (isset($raws->attachment)) {
				$files = array();
				foreach($raws->attachment as $file_id) {
					$file = JTable::getInstance( 'File' , 'StreamTable' );
					if (!empty($file_id) && $file->load($file_id)) {
						$files['filename'] = $file->filename;
						$files['download_url'] = JRoute::_(JURI::root().'index.php?option=com_stream&task=download&file_id='.$file->id, false);
						$files['mimetype'] = $file->mimetype;
						$files['created'] = $file->created;
						$files['id'] = $file->id;
						$fileParam = json_decode($stream->files);
						if (isset($file_param)) {
							$files['image']['thumbnail_path'] = $fileParam->thumb_path;
							$files['image']['image_url'] = $fileParam->preview_path;
						}
					}
				}
							
			}
			else {
				$files = '';
			}

			// format the output like the commented section above
			$output['messages']['message'][] = 
				array(
					  'type' => $stream->type,
					  'url' => 'url',
					  'body' => array(
									  'parse' => $stream->message,
									  ),
					  'group_id' => $stream->group_id,
					  'plain' => $stream->raw,
					  'attachment' => $files,
					  'likes' => $stream->likes,
					  'comment' => '',
					  'time' => array(
									  'created' => $stream->created,
									  'updated' => $stream->updated
									  ),				  
					  );
		}
		echo json_encode($output);
		exit;
	}
	
	/**
	 *
	 */	 	
	public function getStream( $filter = array() , $options = array() )
	{
		jimport('joomla.html.pagination');
		$app	= JFactory::getApplication();
		$jconfig = new JConfig();
		$html = '';
		
		$user = JXFactory::getUser();
		
		if( $mention = JRequest::getVar('mention', '') ){
			$filter['mention'] = '@'.$mention;
		}
		
		if( $user_id = JRequest::getVar('user_id', '') ){
			$filter['user_id'] = $user_id;
		}
		
		if( $search = JRequest::getVar('search', '') ){
			$filter['search'] = $search;
		}
		
		if( $group_id = JRequest::getVar('group_id', '') ){
			$filter['group_id'] = $group_id;
		}
		
		if( $limit_start = JRequest::getVar('limitstart', '') ){
			$filter['limitstart'] = $limit_start;
		}
		
		if( $overdue = JRequest::getVar('overdue', '') ){
			$date = new JDate();
			$filter['end_date'] = $date->toMySQL();
		}

		if( $tag = JRequest::getVar('tag', '') ){
			$filter['tag'] = $tag;
		}
		
		// Order by 'updated'
		$filter['order_by_desc'] = 'updated';

		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0) );
		
		$total	= $model->countStream( $filter );
		
		// Set $user if user_id filter is specified
		if(isset($filter['user_id'])){
			$user = JXFactory::getUser( $filter['user_id'] );
		}
		return $data;
	}
}