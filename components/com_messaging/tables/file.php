<?php
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE IF NOT EXISTS `[PREFIX]_msg_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `msg_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `filename` varchar(1024) NOT NULL,
  `mimetype` varchar(255) NOT NULL,
  `filesize` int(11) NOT NULL,
  `path` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
*/

class MessagingTableFile extends JTable
{
	var $id			= null;
	var $msg_id 	= null;
	var $user_id 	= null;
	var $created 	= null;
	var $filename	= null;
	var $mimetype	= null;
	var $filesize	= null;
	var $path		= null;
	
	/**
	 * Constructor
	 */
	public function __construct(&$db)
	{
		parent::__construct( '#__msg_files', 'id', $db );
	}
	
	public function load($keys = NULL, $reset = true)
	{
		return parent::load($keys);
	}
	
	public function bind($src, $ignore = array())
	{
    	if(empty($src)){
    		return;
		}
		
    	$ret = parent::bind($src, $ignore);
		
		return $ret;
	}
	
	public function store($updateNulls = false)
	{
		$now = new JDate();
		if( $this->created == null)
			$this->created =  $now->toMySQL();
		
		return parent::store($updateNulls);
	}
	
	/**
	 * Delete the files as well
	 */	 	
	public function delete($pk = NULL)
	{
		jimport('joomla.filesystem.file');
		JFile::delete(JPATH_ROOT.DS.$this->path);
		
		parent::delete($pk);
	}
	
	/**
	 * Return true if the user is allowed to download it
	 */	 	
	public function allowDownload($userid)
	{		
	}
	
	/**
	 * @todo: check for edit permission
	 */	 	
	public function allowEdit($userid)
	{
	}

	/**
	 * Guess the mimetype from the filename
	 */	 	
	function getMIMETypeFromFilename()
    {
    	$filename = $this->filename;
    	
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
            if(function_exists("mime_content_type"))
            {
                $fileSuffix = mime_content_type($filename);
            }

            return "unknown/" . trim($fileSuffix[0], ".");
        }
    }
}