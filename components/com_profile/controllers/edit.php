<?php
/**
 * @package		com_profile
 * @subpackage	com_profile
 * @copyright	(C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
require_once(JPATH_ROOT .DS.'libraries'.DS.'joomla'.DS.'xfactory.php');


class ProfileControllerEdit extends JController
{
     /**
      *
      */
	public function display($cachable = false, $urlparams = false)
	{
		if ($_POST)
		{
		    $params = JRequest::getVar('params');
			$user	= JXFactory::getUser();
			$mainframe = JFactory::getApplication();
			$emailChange = false;
			
			if ($user->email != $params['email'])
			{
				$emailChange = true;
				$oldEmail = $user->email;
			}
			
			// Only process this if there is an email change
			if ($emailChange)
			{
				$dummy = new JXUser();
				$loadUser = $dummy->loadUserByEmail($params['email']);
				// Email is being used by another user;
				if ($loadUser === true && $dummy->id != $user->id)
				{
					$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit'), JText::_('COM_PROFILE_ACTION_EMAIL_USED'), 'error');
					return false;
				}
				else
				{
					// update invitation in case there are invitations from the previous email
					JModel::addIncludePath( JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'models' );					
					$usersInvite = JModel::getInstance('usersInvite', 'AccountModel' );
					$usersInvite->updateFromEmail($oldEmail, $params['email'], $user);					
				}
			}
			
			$params['password']	= JRequest::getVar('password', '', 'post', 'string', JREQUEST_ALLOWRAW);
			$params['password2']	= JRequest::getVar('confirm_password', '', 'post', 'string', JREQUEST_ALLOWRAW);
			
			// do a password safety check
			if( JString::strlen($params['password']) || JString::strlen($params['password2'])) 
			{
				// so that "0" can be used as password e.g.
				if($params['password'] != $params['password2']) 
				{
					$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit'), JText::_('COM_REGISTER_ERRMSG_PASSWORD_MISMATCH'), 'error');
					return false;
				}
			}
			
			$user->set('name', $params['name']);   
			$user->set('email', $params['email']);
			$user->setParam('language', $params['language']);
			$user->setParam('about_me', $params['about_me']);
			$user->setParam('timezone', $params['timezone']);
			$user->setParam('style', 	$params['style']);
			
			$user->bind($params);
			
			if ($user->save())
			{		
				$percentageFilled = $user->getCalculateCompletion();
				$user->setGettingStartedCompletion(JXUser::GSTARTED_COMPLETE_PROFILE, $percentageFilled);

				// Reload the user session
				$user->reloadSession();
				
				/* Redirect to clear the previous user values */
				$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit'), JText::_('COM_PROFILE_ACTION_SAVE_PROFILE_SUCCESS'));
			}
			else 
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit'), JText::_('COM_PROFILE_ACTION_SAVE_PROFILE_FAILED'), 'error');
			}
		}
		
		parent::display( null );
	}

	public function details() {
		$user = JXFactory::getUser();

		if ($_POST)
		{
			$mainframe = JFactory::getApplication();
			$detailModel = ProfileFactory::getModel('detail');
			$detailModel->removeDetails($user->id);

			$data = JRequest::getVar('params', array(), 'post', 'array');
			$success=true;

			foreach($data as $field=>$value) {
				$userDetail = JTable::getInstance( 'detailUser' , 'ProfileTable' );
				$userDetail->user_id = $user->id;
				$userDetail->field = $field;
				if(is_array($value)) {
					$value = array_values($value);
					$userDetail->value = json_encode($value);
				} else {
					$userDetail->value = $value;
				}
				$success = $userDetail->store() && $success;
			}
			$user->init($user);

			// $percentageFilled below will calculate only the required field
			// $percentageFilled = round($requiredIsFilled/count($requiredFields) * 100);
			$percentageFilled = $user->getCalculateCompletion();
			$user->setGettingStartedCompletion(JXUser::GSTARTED_COMPLETE_PROFILE, $percentageFilled);

			if($success)
			{
				/* Redirect to clear the previous user values */
				$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=details', false), JText::_('COM_PROFILE_ACTION_SAVE_DETAIL_SUCCESS'));
			}
			else
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=details', false), JText::_('COM_PROFILE_ACTION_SAVE_DETAIL_FAILED'), 'error');
			}
		}

		JRequest::setVar('view', 'detail');
		parent::display( null );
	}

	
	public function changeAvatar()
	{
		$my = JXFactory::getUser();
		$mainframe	= JFactory::getApplication();	
		
		if ($_POST)
		{
			$file = JRequest::getVar('avatar' , '' , 'FILES' , 'array');

			if( !JXImage::isValidType( $file['type'] ) )
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=changeAvatar', false), JText::_('COM_PROFILE_IMAGE_FILE_NOT_SUPPORTED'), 'error');
				exit;               
			}

			$saveAvatar = $this->_saveUploadedAvatar($my, $file['tmp_name'], $file['type']);

			if ($saveAvatar === true)
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=changeAvatar', false), JText::_('COM_PROFILE_ACTION_UPLOAD_AVATAR_SUCCESS'));
				exit;
			}
			else
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=changeAvatar', false), $saveAvatar, 'error');
				exit;
			}
		}
		
		JRequest::setVar('view', 'avatar');
		parent::display();
		
	}
	
	public function ajaxUploadAvatar()
	{
		$my = JXFactory::getUser();
		
		include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'uploader.php');
		jimport('joomla.filesystem.folder');
		
		// max file size in bytes
		$sizeLimit = 5 * 1024 * 1024;
		
		$uploader = new qqFileUploader(array('jpg','jpeg','gif','bmp','png'), $sizeLimit);
		
		// Build /YEAR/MONTH/DATE filepage
		$uploaderPath = 'images' . DS . 'avatar';
		
		$result = $uploader->handleUpload($uploaderPath.DS);

		if (isset($result['success']) && $result['success'])
		{
			// Get mime type
			if(function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
				$fileType = finfo_file($finfo, JPATH_ROOT.DS.$result['path']);
				finfo_close($finfo);
			} else {
				$fileType = @mime_content_type(JPATH_ROOT.DS.$result['path']);
			}
			
			$saveAvatar = $this->_saveUploadedAvatar($my, JPATH_ROOT.DS.$result['path'], $fileType);
			if ($saveAvatar !== true)
			{
                $result['error'] = 1;
				$result['message'] = $saveAvatar;
            }
			else
			{			
				$result['preview'] = $my->getThumbAvatarURL();
			}
		}
		
		
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit;
	}
	
	private function _saveUploadedAvatar($user, $uploadedFile, $fileType)
	{	
		//start image processing
		$imageMaxWidth	= 160;
		$thumbMaxHeight = $thumbMaxWidth = 64;

		// Get a hash for the file name.
		$fileName		= JUtility::getHash( $uploadedFile . time() );
		$hashFileName	= JString::substr( $fileName , 0 , 24 );

		//avatar store path
		$storage			= JPATH_ROOT . DS . 'images' . DS . 'avatar';
		$storageImage		= $storage . DS . $hashFileName . JXImage::getExtension( $fileType );
		$image				= 'images'. DS. 'avatar'.DS . $hashFileName . JXImage::getExtension( $fileType );

		//avatar thumbnail path
		$storageThumbnail	= $storage . DS . 'thumb_' . $hashFileName . JXImage::getExtension( $fileType );
		$thumbnail			= 'images'. DS.'avatar'.DS . 'thumb_' . $hashFileName . JXImage::getExtension( $fileType );

		// Generate full image
		list($currentWidth, $currentHeight) = getimagesize( $uploadedFile );
		$destHeight = intval( $imageMaxWidth / $currentWidth * $currentHeight);

		if(!JXImage::createThumb( $uploadedFile , $storageImage , $fileType, $imageMaxWidth, $destHeight  ))
		{
			return JText::sprintf('COM_PROFILE_ERROR_MOVING_UPLOADED_FILE' , $storageImage);
		}

		// Generate thumbnail
		if(!JXImage::createThumb( $storageImage , $storageThumbnail , $fileType ))
		{
			return JText::sprintf('COM_PROFILE_ERROR_MOVING_UPLOADED_FILE' , $storageThumbnail);
		}

		$this->_deleteUserCurrentAvatar($user);

		// Save avatar info into user params
		$user->setParam('avatar', $image);
		$user->setParam('avatar_thumb', $thumbnail);
		$user->save();

		// Set avatar getting started helper task as completed
		$user->setGettingStartedCompletion(JXUser::GSTARTED_ADD_AVATAR, 100);
		
		return true;
	}
	
	public function ajaxSaveThumbnail()
	{		
		$my = JXFactory::getUser();
		
		$filePath = JPATH_ROOT . DS . $my->getAvatarPath();
		$fileType = JXImage::getImageType($filePath);
		
		$thumbnailStore	= $my->getThumbAvatarPath();
		$originalFile	= JXImage::getImageFileName(JPATH_ROOT . DS . $thumbnailStore);
		$originalFilePath = JPATH_ROOT . DS . $thumbnailStore;
        $newFileName	= 'thumb_'.  JXImage::getHashName( $originalFile ).JXImage::getExtension($fileType);
		$thumbnailStore = str_replace($originalFile, $newFileName, $thumbnailStore);
		
		$xPos			= JRequest::getInt('x_pos', 0);
		$yPos			= JRequest::getInt('y_pos', 0);
		$imgWidth		= JRequest::getInt('width', 0);
		$imgHeight		= JRequest::getInt('height', 0);
				
		if (!JXImage::crop($filePath, JPATH_ROOT . DS . $thumbnailStore, $imgWidth, $imgHeight, $xPos, $yPos))
		{
			$this->_showUploadError(true, '0', $my->getThumbAvatarURL());
			exit;
		}
		
		$this->_deleteUserCurrentAvatar($my, false);
		
		$my->setParam('avatar_thumb', $thumbnailStore);
		$my->save();
		$this->_showUploadError(false, '1', $my->getThumbAvatarURL());
		exit;
	}
	
	public function ajaxRemoveAvatar()
	{		
		$my = JXFactory::getUser();		
		$this->_deleteUserCurrentAvatar($my);
		
		$my->setParam('avatar', '');
		$my->setParam('avatar_thumb', '');
		
		echo ($my->save()) ? '{"error": "0"}' : '{"error": "1"}';		
		exit;
	}
	
	public function ajaxUseDefAvatar()
	{
		$my = JXFactory::getUser();
		$selection = JRequest::getVar('selection', 0);
		$selection .= '.png';
		$result = array();
		
		if (in_array($selection, $my->getDefaultAvatars()))
		{			
			$this->_deleteUserCurrentAvatar($my);
            $image		= 'images'. DS.'avatar'.DS . $selection;
            $thumbnail	= 'images'. DS.'avatar'.DS . $selection;
			$my->setParam('avatar', $image);
			$my->setParam('avatar_thumb', $thumbnail);
			$my->save();
			
			// Set avatar getting started helper task as incomplete if user use default avatar
			$my->setGettingStartedCompletion(JXUser::GSTARTED_ADD_AVATAR, 0);
			
			$result['preview'] = $my->getThumbAvatarURL();
		}
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit;
	}
	
	// Just a function for repetitive usage
	// $user must be a JXUser initiation
	private function _deleteUserCurrentAvatar($user, $thumbOnly = true)
	{		
		if ($thumbOnly)
		{
			$originalFilePath = JPATH_ROOT . DS . $user->getAvatarPath();
			if (JFile::exists($originalFilePath) && !$user->isDefaultAvatar($originalFilePath))
			{
				JFile::delete($originalFilePath);
			}
		}

		// Remove previous avatar thumbnail
		$originalThumbPath = JPATH_ROOT . DS . $user->getThumbAvatarPath();
		if (JFile::exists($originalThumbPath) && !$user->isDefaultAvatarThumb($originalThumbPath))
		{
			JFile::delete($originalThumbPath);
		}
	}
	
	/**
	 *
	 */	 	
	public function notification() 
	{
		if ($_POST)
		{
			$notification = new Notifications();
			$notification->setNotification($_POST);
			
			$user = JXFactory::getUser();
			$notificationSetting = $notification->getNotification();
			
			foreach ($notificationSetting as $group => $typeInfo)
			{
				foreach ($typeInfo as $type => $typeSetting)
				{
					$globalNotification = $notification->getGlobalNotificationIndex( $type );
					$emailNotification = $notification->getEmailNotificationIndex( $type );

					$user->setParam($globalNotification, $notification->getGlobalNotificationSetting( $type ));
					$user->setParam($emailNotification, $notification->getEmailNotificationSetting( $type ));
				}
			}
			$user->save();
			
			$mainframe	= JFactory::getApplication();
			$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=notification', false), JText::_('COM_PROFILE_ACTION_SAVE_NOTIFICATION_SUCCESS'));
			exit;
		}
		
		JRequest::setVar('view', 'notification');
		parent::display();
	}
	/**
	 *
	 */	 	
	public function preference() 
	{
		JRequest::setVar('view', 'preference');
		parent::display();
	}

	public function applications() {
	     JRequest::setVar('view', 'applications');

		 $mainframe = JFactory::getApplication();
		 $apps = new Applications();

		 // this is for auto installation method
		 $redirectUri = JRequest::getVar('redirect_uri');
		 $clientId = JRequest::getVar('client_id');
		 $clientSecret = JRequest::getVar('client_secret');
		 $deviceId = JRequest::getVar('deviceId');

		 // if this is a request for grant/revoke
		 if ($_POST && $deviceId) {
			 switch (JRequest::getVar('deviceAction')) {
			 case 'grant':
				 $apps->grantAccess($deviceId);
				 break;
			 case 'revoke':
				 $apps->revokeAccess($deviceId);
				 break;
			 }
		 }
		 /**
		  * Important:
		  * This is auto installation mode, customize the app to send this parameter
		  */
		 else if ($clientId && $clientSecret && $redirectUri) {
			 header("Content-Type: application/json");
			 header("Cache-Control: no-store");

			 JRequest::setVar('installing', true);
			 if ($_POST || JRequest::getVar('silent')) {
				 $model = OauthFactory::getModel('Application');
				 // use library for the OAuth to standardize
				 require_once(JPATH_ROOT.DS.'components'.DS.'com_oauth'.DS.'libraries'.DS.'PDOOAuth2.inc');
				 $oauth = new PDOOAuth2();

				 if ($oauth->addClient($clientId, $clientSecret, $redirectUri)) {
					 $authData = array(
									   'client_id' => $clientId,
									   'response_type' => 'code',
									   'redirect_uri' => $redirectUri
									   );
					 $oauth->finishClientAuthorization(true, $authData);
					 /* if this is a silent request, give a silent feedback */
					 if (JRequest::getVar('silent')) {
						 echo json_encode(array('success'=>'true'));
						 exit;
					 }
					 JRequest::setVar('authorize', true);
					 JRequest::setVar('appName', $clientId);
					 $mainframe->enqueueMessage(JText::_('COM_OAUTH_LABEL_APPLICATION_INSTALL'));
				 }
				 else {
					 if (JRequest::getVar('silent')) {
						 echo json_encode(array('success'=> false,
												'error'=> JText::_('COM_OAUTH_LABEL_FAILED_TO_REGISTER')));
						 exit;
					 }
					 $mainframe->enqueueMessage(JText::_('COM_OAUTH_LABEL_FAILED_TO_REGISTER'), 'Error');
				 }
			 }
		 }
	     parent::display();
	}
	
	public function revokeDevice() {
		$appId = JRequest::getVar('consumerId');
		$apps = new Applications();
		$mainframe = JFactory::getApplication();
		if ($apps->removeDevice($appId)) {
			$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=applications', false), JText::_('COM_PROFILE_DEVICE_REMOVE_SUCCESS'));
		}
		else {
			$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=applications', false), JText::_('COM_PROFILE_DEVICE_REMOVE_ERROR'), 'error');
		}
		
	}

	private function _showUploadError( $hasError , $message , $thumbUrl = null, $albumId = null, $photoId = null )
	{
		$this->_outputJSONText( $hasError , $message , $thumbUrl, $albumId, $photoId );
	}
	
	private function _outputJSONText( $hasError, $text, $thumbUrl = null, $albumId = null, $photoId = null )
	{
		$nextUpload	= JRequest::getVar('nextupload' , '' , 'GET');

		echo '{';

		if( $hasError )
		{
			echo '"error": "1",';
		}
		else
		{
			echo '"error": "0",';
		}

		echo '"msg": "' . $text . '",';
		echo '"nextupload": "' . $nextUpload . '",';
		echo '"info": "' . $thumbUrl . "#" . $albumId . '",';
		echo '"photoId": "' . $photoId . '"';
		echo "}";
		exit;
	}
	
		
	private function _cleanValue($arrValues)
	{
		$num = count($arrValues);
		for ( $i = 0; $i < $num; $i++ )
		{
			$arrValues[$i] = trim($arrValues[$i]);
			if (empty($arrValues[$i]))
			{
				unset($arrValues[$i]);
			}
		}

		return $arrValues;
	}
}
