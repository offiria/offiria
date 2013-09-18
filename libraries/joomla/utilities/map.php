<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class JMap
{
	const GEOCODE_URL = 'http://maps.google.com/maps/api/geocode/json?';
	const STATICMAP_URL = 'http://maps.google.com/maps/api/staticmap?';
	
	/**
	 * Restrive the data from cache table instead	 
	 */	 	
	private function _getCachedAddressData($address)
	{
		$location	=& JTable::getInstance( 'LocationCache' , 'CTable' );
		$location->load( $address );
		$location->store();
		return $location->data;
	}
	
	/**
	 * Fetch google map data refere to
	 * http://code.google.com/apis/maps/documentation/geocoding/#Geocoding	 
	 */	 	
	public static function getAddressData($address)
	{
		
		$url = JMap::GEOCODE_URL . 'address='.urlencode($address) .'&sensor=false';

		$options = new JRegistry();
		$transport = new JHttpTransportCurl($options);
		$http = new JHttp($options, $transport);

		$response =  $http->get( $url );
		$content = $response->body;
	
		$status = null;	
		if(!empty($content))
		{
			$status = json_decode($content);
		}

		return $status;
	}
	
	/**
	 * Given a string address, we will try to validate and see if google think 
	 * it is allright
	 */	 	
	public static function validateAddress($address)
	{
		
		$content = JMap::getAddressData($address);
		$isValid = false;
		if(!empty($content)){
			$isValid = $content->status == 'OK';
		}
		return $isValid;
	}
	
	/**
	 * Return the code that will generate static map
	 * if width is '0', then it will draw at 100% width	 
	 */	 	
	public function drawStaticMap($address, $width, $height)
	{
		$elementid = 'map-'. md5($address);
		$elementid = substr($elementid, 0, 16);
		
		$data = JMap::getAddressData($address);
		
		
		
		$url = JMap::STATICMAP_URL . 'center=';
		$url .= urlencode($address).'&zoom=14&maptype=roadmap&sensor=false';
		
		if($data->status == 'OK'){
			$lat = $data->results[0]->geometry->location->lat;
			$long = $data->results[0]->geometry->location->lng;
			$url .= '&markers=color:blue|'.$lat.','.$long;
		}
		
		$html = '';
		if( !empty($width)){
			// No need for fancy javascript if the exact size is known
			$html = '<img src="' . $url .'&size='.  $width .'x'. $height.'" id="'.$elementid.'"/>';
			
		}else {
			// If we don't know the height, need some fancy javascript to calculate the width 
			// and fetch the image via javascript
			$html  = '<div style="width:100%;height:'.$height.'px" id="'.$elementid.'-wrap">
						<img src="" id="'.$elementid.'"/>
					  </div>';
			$html .= '<script type="text/javascript">'
					.'joms.jQuery(document).ready( function() {';
					
			if($width != 0)
				$html .= 'var width 	= \'' . $width . '\';';
			else
				$html .= 'var width 	= joms.jQuery(\'#'.$elementid.'-wrap\').width();';
	
	
			$html .= 'var height 	=' . $height . ';'
					.'var url		= \'' . $url .'&size=\' + width + \'x'.$height.'\';'
					.'joms.jQuery(\'#'.$elementid.'\').attr(\'src\', url);'
					.'});'
					.'</script>';
		}
		return $html;
	}
	
	/**
	 * Draw the triple zoomer map
	 * @param type $address
	 * @param type $width
	 * @param type $height 
	 */
	static public function drawZoomableMap($address, $width, $height){
		ob_start();
		?>
		<div class="map" style="display: block;">
			<div class="mapFade" style="width:<?php echo $width;?>px;height:<?php echo $height;?>px">
				<div class="mapHeatzone" style="top: <?php echo (($height - 40)/2)-20 ?>px; left: <?php echo (($width - 30)/2) ?>px;">&nbsp;</div>
				<div class="mapFiller"></div>
				<img src="http://maps.google.com/maps/api/staticmap?center=<?php echo urlencode($address); ?>&amp;zoom=14&amp;size=<?php echo $width; ?>x<?php echo $height; ?>&amp;sensor=false&amp;markers=color:red|<?php echo urlencode($address); ?>">
				<img src="http://maps.google.com/maps/api/staticmap?center=<?php echo urlencode($address); ?>&amp;zoom=5&amp;size=<?php echo $width; ?>x<?php echo $height; ?>&amp;sensor=false&amp;markers=color:red|<?php echo urlencode($address); ?>" style="display: block;">
				<img src="http://maps.google.com/maps/api/staticmap?center=<?php echo urlencode($address); ?>&amp;zoom=2&amp;size=<?php echo $width; ?>x<?php echo $height; ?>&amp;sensor=false&amp;markers=color:red|<?php echo urlencode($address); ?>" style="display: block;">
			</div>
			<small class="mapLoc"><span><?php echo $address; ?></span></small>
			<small class="mapBigger"><a href="https://www.google.com/maps/preview#!q=<?php echo $address; ?>" target="_blank"><?php echo JText::_('View larger map');?></a></small>
			<div class="clear"></div>
		</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	/**
	 * Draw google map on the target element
	 * It will add marker at the address	 
	 */	 	
	public function drawMap($targetId, $address, $showMarker = true, $title = '', $info = '')
	{
		// need to attach the google map js once. It doesn't really need
		// to be attached in the <head> section
		static $mapScriptLoaded = false;
		$html = '';
		if(!$mapScriptLoaded)
		{
			$html = '<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>';
			$mapScriptLoaded = true;
		}
		CFactory::load( 'helpers' , 'string' );

		$html  .= '<script type="text/javascript">'
  				.'$(document).ready( function() {'
  				.'S.maps.initialize(\''.$targetId.'\', \''. addslashes( $address ) .'\', \''. addslashes( $title ).'\', \''.$info.'\');'
		  		.'});'
  				.'</script>';
  		return $html;
	}
	
	// Return content of the given url
	static public function getContent($url , $raw = false , $headerOnly = false)
	{
		if (!$url)
			return false;
		
		if (function_exists('curl_init'))
		{
			$ch			= curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HEADER, true );
			
			if($raw){
				curl_setopt($ch, CURLOPT_BINARYTRANSFER, true );
			}

			$response	= curl_exec($ch);
			
			$curl_errno	= curl_errno($ch);
			$curl_error	= curl_error($ch);
			
			if ($curl_errno!=0)
			{
				$mainframe	= JFactory::getApplication();
				$err		= 'CURL error : '.$curl_errno.' '.$curl_error;
				$mainframe->enqueueMessage($err, 'error');
			}
			
			$code		= curl_getinfo( $ch , CURLINFO_HTTP_CODE );

			// For redirects, we need to handle this properly instead of using CURLOPT_FOLLOWLOCATION
			// as it doesn't work with safe_mode or openbase_dir set.
			if( $code == 301 || $code == 302 )
			{
				list( $headers , $body ) = explode( "\r\n\r\n" , $response , 2 );
				
				preg_match( "/(Location:|URI:)(.*?)\n/" , $headers , $matches );
				
				if( !empty( $matches ) && isset( $matches[2] ) )
				{
					$url	= JString::trim( $matches[2] );
					curl_setopt( $ch , CURLOPT_URL , $url );
					curl_setopt( $ch , CURLOPT_RETURNTRANSFER, 1);
					curl_setopt( $ch , CURLOPT_HEADER, true );
					$response	= curl_exec( $ch );
				}
			}
			
			if(!$raw){
				list( $headers , $body )	= explode( "\r\n\r\n" , $response , 2 );
			}
			
			$ret	= $raw ? $response : $body;
			$ret	= $headerOnly ? $headers : $ret;
			
			curl_close($ch);
			return $ret;
		}
	
		// CURL unavailable on this install
		return false;
	}
	
	/**
	 * Add marker point to the given address
	 */	 	 	
	public function addMarker($targetId, $address, $title ='', $info = '' )
	{
		$html = '';
		$data = JMap::getAddressData($address);
		if($data){
			if($data->status == 'OK')
			{
				$lat = $data->results[0]->geometry->location->lat;
				$lng = $data->results[0]->geometry->location->lng;
				
				$html  = '<script type="text/javascript">'
  				.'joms.jQuery(document).ready( function() {'
  				.'joms.maps.addMarker(\''.$targetId.'\', '.$lat.', '.$lng.', \''.$title.'\', \''.$info.'\');'
		  		.'});'
  				.'</script>'; 
			}
  		}
  		return $html;
	}
        /**
         * Get the Formated address from google
         */
        public function getFormatedAdd($address)
        {
            $data = JMap::getAddressData($address);

            return $data->results[0]->formatted_address;
        }
}