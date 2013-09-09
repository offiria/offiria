<?php



class StreamMap
{
	/**
	 * Draw the triple zoomer map
	 * @param type $address
	 * @param type $width
	 * @param type $height 
	 */
	static public function drawZoomableMap($address, $width, $height){
		
		$tmpl = new StreamTemplate();
		$tmpl->set('address', $address)
				->set('width', $width)
				->set('height', $height);
		
		return $tmpl->fetch('map.zoom');
	}
}

