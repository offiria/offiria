<?php
class JConfig {
	public $offline = '0';
	public $offline_message = 'This site is down for maintenance.<br /> Please check back again soon.';
	public $sitename = 'sitename';
	public $editor = 'tinymce';
	public $list_limit = '10';
	public $access = '1';
	public $debug = '0';
	public $debug_lang = '0';
	public $dbtype = 'mysqli';
	public $host = 'localhost';
	public $user = 'root';
	public $password = '';
	public $db = 'offiria';
	public $dbprefix = 'off_';
	public $live_site = '';
	public $secret = 'XQJR8eWrZvLKCisl';
	public $gzip = '1';
	public $error_reporting = 'maximum';
	public $helpurl = 'http://help.joomla.org/proxy/index.php?option=com_help&keyref=Help{major}{minor}:{keyref}';
	public $ftp_host = '127.0.0.1';
	public $ftp_port = '21';
	public $ftp_user = '';
	public $ftp_pass = '';
	public $ftp_root = '';
	public $ftp_enable = '0';
	public $offset = 'Asia/Kuala_Lumpur';
	public $offset_user = 'UTC';
	public $mailer = 'mail';
	public $mailfrom = 'user@example.com';
	public $fromname = 'Offiria';
	public $sendmail = '/usr/sbin/sendmail';
	public $smtpauth = '0';
	public $smtpuser = '';
	public $smtppass = '';
	public $smtphost = 'localhost';
	public $smtpsecure = 'none';
	public $smtpport = '25';
	public $caching = '0';
	public $cache_handler = 'file';
	public $cachetime = '15';
	public $MetaDesc = '';
	public $MetaKeys = '';
	public $MetaAuthor = '1';
	public $sef = '1';
	public $sef_rewrite = '0';
	public $sef_suffix = '0';
	public $unicodeslugs = '0';
	public $feed_limit = '10';
	public $log_path = '/log';
	public $tmp_path = '/tmp';
	public $lifetime = '1000';
	public $session_handler = 'database';
	public $MetaRights = '';
	public $sitename_pagetitles = '0';
	public $force_ssl = '0';
	public $feed_email = 'author';
	public $cookie_domain = '';
	public $cookie_path = '';
	public $max_users = '10000';
	public $plan = 'starter';
	public $default_timezone = 'Asia/Kuala_Lumpur';
	public $default_language = 'en-GB';
	public $allow_invite = '1';
	public $limit_email_domain = '';
	public $allow_anon = '1';
	public $robots = '';
	public $display_offline_message = '1';
	public $offline_image = '';
	public $captcha = '0';
	public $style = 'blue';
	public $postfix_domain = '.offiria.com';
	public $module_module_invite_guest = '1';

	public $module_module_members_birthday = '1';

	public $module_event_module_attendee = '1';

	public $module_file_module_list = '1';

	public $module_file_module_storagestats = '1';

	public $module_group_module_eventslist = '1';

	public $module_group_module_groups = '1';

	public $module_group_module_info = '1';

	public $module_group_module_memberlist = '1';

	public $module_group_module_milestones = '1';

	public $module_stream_tag_trending = '1';

	public $module_todo_module_pending = '1';
}