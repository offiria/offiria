<?php

/*
CREATE TABLE IF NOT EXISTS `[prefix]_users_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` datetime NOT NULL,
  `blog` datetime NOT NULL,
  `groups` datetime NOT NULL,
  `events` datetime NOT NULL,
  `todo` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
*/

class StreamTableActivity extends JTable
{
	var $id = null;
	var $message = null;
	var $blog = null;
	var $groups = null;
	var $events = null;
	var $todo = null;

	/**
	 * Constructor
	 */
	public function __construct( &$db )	{
		parent::__construct( '#__users_activity', 'id', $db );
	}

	/**
	 * Update the action by the user
	 * Action that been tracked is the action that encourages user participation
	 * @param String $type
	 * available options:
	 * 		  - message
	 * 		  - blog
	 * 		  - groups
	 * 		  - events
	 * 		  - todo
	 * @return Boolean true on success
	 */
	public function update($user_id, $type) {
		/* update the user activity */
		if (!$this->load(array('user_id'=>$user_id))) {
			return;
		}
		$xdate = new JXDate();
		/* bind the selected type with current time */
		$this->bind(array( $type=>$xdate->toMySQL() ));
		return $this->store();
	}

	/**
	 * Add user should be called whenever a new registration is made. Only.
	 * @return Boolean true on success
	 */
	public function addUser($user_id) {
		$this->bind(array( $this->user_id = $user_id ));
		return $this->store();
	}
}