<?php
/*------------------------------------------------------------------------
# mod_sp_weather - Weather Module by JoomShaper.com
# ------------------------------------------------------------------------
# author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class modSPWeatherHelper
{    
	private $data = array();
	private $forecast = array();
	private $woeid;
	#private $location;
	private $wlocation;
	private $params;
	#private $moduleID;
	#private $moduledir;
	private $nightIDs = array(27,29,31,33);
	private $iconURL = 'http://l.yimg.com/os/mit/media/m/weather/images/icons/l/%d%s-100567.png';

	/**
	* Init Class Params
	* 
	* @param object $params
	* @param int $id
	*/
	public function __construct($params, $wlocation)	#$id)
	{
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$this->params    = $params;
		$this->wlocation = $wlocation;
		$this->moduledir = 'Weather'; //basename(dirname(__FILE__));
		$this->getWoeId();
		$this->data      = $this->_getWeatherData();
		$this->forecast  = $this->_getForecastData();
	}


	/**
	* Error Container array
	* 
	* @var array
	*/
	private $errors = array();

	/**
	* Get Errors, If index is null errors stored as numeric array.
	* 
	* @param int | string $index    default is NULL
	* @return mixed
	*/
	public function error($index=null)
	{
		if( !empty($this->errors) )
		{
			if( is_null($index) ) return  $this->errors; 
			else
			{
				if( is_null($this->errors[$index]) ) return false;
				else return  $this->errors[$index]; 
			} 
		} 
		else return false;
	}


	/**
	* Set errors in error variable. If index is null errors stored as numeric array.
	* 
	* @param mixed $msg
	* @param mixed $index     default is null. 
	*/
	public function setError($msg, $index=null)
	{
		if( is_null($index) ) $this->errors[] = $msg;
		else $this->errors[$index] = $msg;

	}

	/**
	* PHP CURL function
	* 
	* @param string $url
	* @param array $query   default is array
	* @return string
	*/
	private function getCurl($url, $query=array())
	{
		$requestURL =  $url;

		if( !empty($query) and is_array($query) ) $requestURL .= '?'. http_build_query($query,'','&');

		if (function_exists('curl_init'))
		{
			// initializing connection
			$curl = curl_init();
			// saves us before putting directly results of request
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			// url to get
			curl_setopt($curl, CURLOPT_URL, $requestURL );
			// timeout in seconds
			curl_setopt($curl, CURLOPT_TIMEOUT, 60);
			// set useragent
			curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			if( strtolower(substr( $requestURL , 0, 5))==='https' )
			{
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
			}
			// execute curl
			$data = curl_exec($curl);
			// closing connection
			$error = trim(curl_error($curl)); 
			curl_close($curl);
			if( !empty($error) ) $this->setError(sprintf(JText::_('COM_STREAM_INVITATION_ALERT'), $error));
			return $data;
		}
		else
		{
			$this->setError(JText::_('COM_ACCOUNT_WEATHER_CURL_ERROR'));
		}
	}


	/**
	* Simple caching function
	* @version  1.3
	* @param string $file
	* @param string | array $datafn                  e.g:  functionname |  array( object, function) ,
	* @param array  $datafnarg    default is array  e.g:   array( arg1, arg2, ...) ,       
	* @param mixed $time         default is 900  = 15 min
	* @param mixed $onerror      string function or array(object, method )
	* @return string
	*/
	private function Cache( $file,  $datafn, $datafnarg=array(), $time=900, $onerror='')
	{
		if (is_writable(JPATH_CACHE))
		{
			// check cache dir or create cache dir
			if (!JFolder::exists(JPATH_CACHE.'/'.$this->moduledir))
			{

				JFolder::create(JPATH_CACHE.'/'.$this->moduledir.'/'); 
			}

			$cache_file = JPATH_CACHE.'/'.$this->moduledir.'/'.$this->moduledir.'-'.$file;

			// check cache file, if not then write cache file
			if ( !JFile::exists($cache_file) )
			{

				$data =  call_user_func_array($datafn, $datafnarg);
				JFile::write($cache_file, $data);
			}  
			// if cache file expires, then write cache
			elseif ( filesize($cache_file) == 0 || ((filemtime($cache_file) + (int) $time ) < time()) )
			{
				$data =  call_user_func_array($datafn, $datafnarg);
				JFile::write($cache_file, $data);
			}
			// read cache file
			$data =  JFile::read($cache_file);
			$params['file'] = $cache_file;
			$params['data'] = $data;
			if( !empty($onerror) ) call_user_func($onerror, $params);
			return $data;
		} else {
			return   call_user_func_array($datafn, $datafnarg);
		}
	}


	private function onDataError($params)
	{
		$data = json_decode($params['data'],true);

		if( isset($data['code']) and $data['code']==500 )
		{
			JFile::Delete($params['file']); 
			$this->setError(JText::_('COM_ACCOUNT_WEATHER_RETRIVE_ERROR'));
		} 

	}


	private function onForecastError($params)
	{
		$data = json_decode($params['data'],true);
		if( empty($data['query']['results']['item']['forecast']) )
		{
			JFile::Delete($params['file']); 
			$this->setError(JText::_('COM_ACCOUNT_WEATHER_FRETRIVE_ERROR'));
		} 

	}


	private function onWoeIdError($params)
	{
		$data = json_decode($params['data'],true);
		if( is_null( $data['query']['results'] ) ){
			JFile::Delete($params['file']); 
			$this->setError(sprintf(JText::_('COM_ACCOUNT_WEATHER_WOEID_ERROR'), $this->wlocation));
		}
	}

	private function onLocationError($params)
	{
		$data = json_decode($params['data'],true);
		if( is_null( $data['query']['results'] ) ){
			JFile::Delete($params['file']); 
			$this->setError(sprintf(JText::_('COM_ACCOUNT_WEATHER_LOC_ERROR'), $this->wlocation));
		}

	}

	private function makeYQL($query, $param=array('format'=>'json'))
	{
		$url   = 'http://query.yahooapis.com/v1/public/yql?q=';
		$url2   = rawurlencode($query);
		$url   .= str_replace('%2A','*', $url2 );
		$url   .= '&'.http_build_query($param,'','&');


		return $url;
	}



	/**
	* Get Location woe ID
	* 
	*/
	private function getWoeId()
	{
		$query = "select woeid from geo.places(1) where text='".$this->wlocation."'";
		$URL = $this->makeYQL($query);


		if( $this->params->get('weather_useCache')==='1' )
		{
			$data = $this->Cache(
				'woeid.json',
				array($this,'getCurl'),
				array($URL),
				(60*60*60),
				array($this,'onWoeIdError')
			);
		} else {
			$data = $this->getCurl($URL);
		}

		$data = json_decode($data,true);
		
		$this->woeid = $data['query']['results']['place']['woeid'];
	}


	/**
	* Get place Location 
	* 
	*/
	private function getLocation()
	{
		$query = 'select id from xml where url="http://xoap.weather.com/search/search?where='.$this->wlocation.'" and itemPath="search.loc" limit 1';
		$URL = $this->makeYQL($query);
		if( $this->params->get('weather_useCache')==='1' )
		{
			$data = $this->Cache(
				'location.json',
				array($this,'getCurl'),
				array($URL),
				(60*60*60),
				array($this,'onLocationError')
			);
		} else {
			$data = $this->getCurl($URL);
		}
		$data = json_decode($data,true);
		$this->location = $data['query']['results']['loc']['id'];
	}


	/**
	* Get Weather data
	* 
	*/
	private function _getWeatherData()
	{


		$query = 'select * from xml where url="http://weather.yahooapis.com/forecastrss?w='.$this->woeid.'&u='.$this->params->get('weather_tempUnit').'"';

		$URL = $this->makeYQL($query);
		if( $this->params->get('weather_useCache')==='1' )
		{
			$data = $this->Cache(
				'weather.json',
				array($this,'getCurl'),
				array($URL),
				(int) $this->params->get('weather_cacheTime'),
				array($this,'onDataError')
			);
		} else {
			$data = $this->getCurl($URL);
		}

		return json_decode($data,true);
	}

	/**
	* Get Weather data
	* 
	*/
	private function _getForecastData()
	{


		$data = $this->data;

		$location = explode('_',$this->data['query']['results']['rss']['channel']['item']['guid']['content']);
		$this->location =  $location[0];
		$query = 'SELECT * FROM rss WHERE url="http://xml.weather.yahoo.com/forecastrss/'.$this->location.'&d='.$this->params->get('weather_forecast').'_'.strtolower($this->params->get('weather_tempUnit')).'.xml"';
		$URL = $this->makeYQL($query);

		if( $this->params->get('weather_useCache')==='1' )
		{
			$data = $this->Cache(
				'forecast.json',
				array($this,'getCurl'),
				array($URL),
				(int) $this->params->get('weather_cacheTime'),
				array($this,'onForecastError')
			);

		} else {
			$data = $this->getCurl($URL);
		}
		$data = json_decode($data,true);
		return $data['query']['results']['item']['forecast'];
	}


	/**
	* Convert numeric number to language
	* 
	* @param int | string $number
	* @return language formatted text
	*/
	public function Numeric2Lang($number, $prefix = 'SP_WEATHER_SP_')
	{
		$number = (array) str_split($number);
		$formated = '';
		foreach($number as $no)
		{
			if (ctype_digit($no)) {
				$formated.=JText::_($prefix . $no);    
			} else $formated.=$no;


		}
		return $formated;
	}


	/**
	* Weather condition text converter
	* 
	* @param string $text
	* @return string
	*/
	public function txt2lng($text)
	{
		$trans = array(" " => "_", "/" => "_", "(" => "", ')'=>'');
		//return $text;
		$text = strtr($text, $trans);
		return JText::_('SP_WEATHER_'.strtoupper($text));
	}

	/**
	* Convert temparature
	* 
	* @param mixed $value
	* @param mixed $unit
	* @param mixed $tempType
	*/

	public function convertUnit($value, $unit)
	{    
		$txt  = $this->Numeric2Lang($value);
		$txt .= ( strtolower($unit)=='c') ? JText::_('SP_WEATHER_C') : JText::_('SP_WEATHER_F');
		return $txt;
	}    

	/**
	* weather condition to icon file name
	* 
	* @param mixed $icon
	* @param mixed $path
	*/
	public function icon($condition)
	{
		$condition = (int) $condition;
		$at = in_array($condition, $this->nightIDs, true)?'n':'d';
		$icon =  sprintf($this->iconURL,$condition,$at);
		return  $icon;
	} 

	/**
	* Run function to load data from source
	* @return string
	*/
	public function getData()
	{
		return $this->data;
	}

	/**
	* Run function to load data from source
	* @return string
	*/
	public function getForecastData()
	{
		return $this->forecast;
	}
}
?>