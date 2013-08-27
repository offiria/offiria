<?php

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');


class ProfileModelDetail extends JModel
{
	public function getDetails($userid){

		$db = JFactory::getDBO();
		$query  = 'SELECT * FROM ' . $db->nameQuote('#__user_details')
				. ' WHERE ' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $userid );
		$db->setQuery($query);

		$params = array();
		foreach($db->loadAssocList() as $detail) {
			$params[$detail['field']]=$detail['value'];
		}

		return $params;
	}

	public function removeDetails($userid){
		$db = $this->getDBO();

		//remove all the user details
		$query	= 'DELETE FROM ' . $db->nameQuote( '#__user_details' ) . ' WHERE ' . $db->nameQuote( 'user_id' ) . '=' . $db->Quote( $userid );
		$db->setQuery($query);
		$db->query();

		if(!$db->getErrorNum()){
			return true;
		}else{
			JError::raiseError( 500, $db->stderr());
			return false;
		}
	}
}