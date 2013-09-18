<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');
jimport('joomla.utilities.utility');
jimport('joomla.image.image');

// Disable jpeg warning | gd-jpeg, libjpeg: recoverable error: Premature end of JPEG
// that are produced by certain jpeg encoding
ini_set('gd.jpeg_ignore_warning', 1);

//require_once(JPATH_ROOT.DS.'components'.DS.'com_community'.DS.'libraries'.DS.'core.php');

class JXImage
{
	static protected $otherOption = array('quality' => 99);
	static protected $pngOption = array('quality' => 9);
	
	// Resize the given image to a dest path. Src must exist
	// If original size is smaller, do not resize just make a copy
	static public function resize($srcPath, $destPath, $destType, $destWidth, $destHeight, $sourceX	= 0, $sourceY = 0, $currentWidth = 0, $currentHeight = 0)
	{
		$jImage			= new JImage($srcPath);
		$resizedImage	= $jImage->resize($destWidth, $destHeight, true);
		$imageInfo		= $jImage->getImageFileProperties($srcPath);
		try 
		{
			$option = ($imageInfo->type == IMAGETYPE_PNG) ? self::$pngOption : self::$otherOption;
			$resizedImage->toFile($destPath, $imageInfo->type, $option);
		} 
		catch (Exception $ex) 
		{
			return false;
		}
		
		// @todo, need to verify that the $output is indeed a proper image data
		return true;
	}
	
	/**
	 * Method to create a thumbnail for an image
	 *
	 * @param	$srcPath	The original source of the image.
	 * @param	$destPath	The destination path for the image
	 * @param	$destType	The destination image type.
	 * @param	$destWidth	The width of the thumbnail.
	 * @param	$destHeight	The height of the thumbnail.
	 * 
	 * @return	bool		True on success.
	 */ 
	static public function createThumb($srcPath, $destPath, $destType, $destWidth=64, $destHeight=64)
	{
		// Get the image size for the current original photo	
		$jImage			= new JImage($srcPath);
		$imageInfo		= $jImage->getImageFileProperties($srcPath);
		$currentWidth	= $imageInfo->width;
		$currentHeight	= $imageInfo->height;
		
		// Find the correct x/y offset and source width/height. Crop the image squarely, at the center.
		if( $currentWidth == $currentHeight )
		{
			$sourceX = 0;
			$sourceY = 0;
		}
		else if( $currentWidth > $currentHeight )
		{
			$sourceX			= intval( ( $currentWidth - $currentHeight ) / 2 );
			$sourceY 			= 0;
			$currentWidth		= $currentHeight;
		}
		else
		{
			$sourceX		= 0;
			$sourceY		= intval( ( $currentHeight - $currentWidth ) / 2 );
			$currentHeight	= $currentWidth;
		}
		
		$jImage		= $jImage->crop($currentWidth, $currentHeight, $sourceX, $sourceY);
		$jImage->resize($destWidth, $destHeight, false);
		
		try 
		{
			$option = ($imageInfo->type == IMAGETYPE_PNG) ? self::$pngOption : self::$otherOption;
			$jImage->toFile($destPath, $imageInfo->type, $option);
		} 
		catch (Exception $ex) 
		{
			return false;
		}
		
		return true;
		// IF all else fails, we try to use GD
		//return JXImage::resize( $srcPath , $destPath , $destType , $destWidth , $destHeight , $sourceX , $sourceY , $currentWidth , $currentHeight);
	}
	
	public static function crop($srcPath, $destPath, $cropWidth, $cropHeight, $sourceX, $sourceY, $cropMaxWidth=64, $cropMaxHeight=64)
	{

		$jImage		= new JImage($srcPath);
		
		$imageInfo	= JImage::getImageFileProperties($srcPath);

		try {
			if ($cropWidth == 0 && $cropHeight == 0)
			{
				$cropThumb = $jImage->resize($cropMaxWidth, $cropMaxHeight);

			}
			else
			{
				$cropThumb = $jImage->crop($cropWidth , $cropHeight , $sourceX, $sourceY, true);
				if ($cropMaxWidth <= $cropWidth || $cropMaxHeight <= $cropHeight)
				{
					$cropThumb = $cropThumb->resize($cropMaxWidth, $cropMaxHeight);
				}
			}
			
			$option = ($imageInfo->type == IMAGETYPE_PNG) ? self::$pngOption : self::$otherOption;
			$cropThumb->toFile($destPath, $imageInfo->type, $option);
		}
		catch (Exception $ex)
		{
			return false;
		}
		
		return true;
	}

	static public function getExtension( $type )
	{
		$type = JString::strtolower($type);
	
		if( $type == 'image/png' || $type == 'image/x-png' )
		{
			return '.png';
		}
		elseif ( $type == 'image/gif')
		{
			return '.gif';
		}
		
		// We default to use jpeg
		return '.jpg';
	}
	
	static public function isValidType( $type )
	{
        $type = JString::strtolower($type);
        $validType = array('image/png', 'image/x-png', 'image/gif', 'image/jpeg', 'image/pjpeg');

        return in_array($type, $validType );
	}
	
	public function isMemoryNeededExceed($filename)
	{		
		$MB = pow(1024, 2);  // number of bytes in 1M
		$K64 = 65536;    // number of bytes in 64K
		$TWEAKFACTOR = 1.5; 
		$imageInfo = @getimagesize($filename);
		if($imageInfo){
			$memoryNeeded = round( ( $imageInfo[0] * $imageInfo[1]
												   * $imageInfo['bits']
												   * $imageInfo['channels'] / 8
									 + $K64
								   ) * $TWEAKFACTOR
								 );
			$memory_limit = ini_get('memory_limit') * pow(1024, 2);
			if ($memoryNeeded > $memory_limit){
				return false;
			}
		}
			
		return true;
	}
	
	
	public function isValid( $file )
	{		
		# JPEG:
		if( function_exists( 'imagecreatefromjpeg' ) )
		{
			$im = @imagecreatefromjpeg($file);
			if ($im !== false){ return true; }
		}
	
		if( function_exists( 'imagecreatefromgif' ) )
		{
			# GIF:
			$im = @imagecreatefromgif($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefrompng' ) )
		{
			# PNG:
			$im = @imagecreatefrompng($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromgd' ) )
		{
			# GD File:
			$im = @imagecreatefromgd($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromgd2' ) )
		{
			# GD2 File:
			$im = @imagecreatefromgd2($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromwbmp' ) )
		{
			# WBMP:
			$im = @imagecreatefromwbmp($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxbm' ) )
		{
			# XBM:
			$im = @imagecreatefromxbm($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxpm' ) )
		{
			# XPM:
			$im = @imagecreatefromxpm($file);
			if ($im !== false) { return true; }
		}
		
		// If all failed, this photo is invalid
		return false;
	}
	
	static public function open($file , $type)
	{
		// @rule: Test for JPG image extensions
		if( function_exists( 'imagecreatefromjpeg' ) && ( ( $type == 'image/jpg') || ( $type == 'image/jpeg' ) || ( $type == 'image/pjpeg' ) ) )
		{
			
			$im	= @imagecreatefromjpeg( $file );
	
			if( $im !== false ) { return $im; }
		}
		
		// @rule: Test for png image extensions
		if( function_exists( 'imagecreatefrompng' ) && ( ( $type == 'image/png') || ( $type == 'image/x-png' ) ) )
		{
			$im	= @imagecreatefrompng( $file );
	
			if( $im !== false ) { return $im; }
		}
	
		// @rule: Test for png image extensions
		if( function_exists( 'imagecreatefromgif' ) && ( ( $type == 'image/gif') ) )
		{
			$im	= @imagecreatefromgif( $file );
	
			if( $im !== false ) { return $im; }
		}
		
		if( function_exists( 'imagecreatefromgd' ) )
		{
			# GD File:
			$im = @imagecreatefromgd($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromgd2' ) )
		{
			# GD2 File:
			$im = @imagecreatefromgd2($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromwbmp' ) )
		{
			# WBMP:
			$im = @imagecreatefromwbmp($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxbm' ) )
		{
			# XBM:
			$im = @imagecreatefromxbm($file);
			if ($im !== false) { return true; }
		}
	
		if( function_exists( 'imagecreatefromxpm' ) )
		{
			# XPM:
			$im = @imagecreatefromxpm($file);
			if ($im !== false) { return true; }
		}
		
		// If all failed, this photo is invalid
		return false;
	}
	
	static public function getFileProperties( $source )
	{
		return JImage::getImageFileProperties($source);
	}
	
	/*
	 * Resize the thumbnail to respect the aspect ratio
	 */
	static public function resizeAspectRatio($source,$destination,$thumb_width,$thumb_height){
		$image = imagecreatefromjpeg($source);
		$filename = $destination;

		$width = imagesx($image);
		$height = imagesy($image);

		$original_aspect = $width / $height;
		$thumb_aspect = $thumb_width / $thumb_height;

		if($original_aspect >= $thumb_aspect) {
		   // If image is wider than thumbnail (in aspect ratio sense)
		   $new_height = $thumb_height;
		   $new_width = $width / ($height / $thumb_height);
		} else {
		   // If the thumbnail is wider than the image
		   $new_width = $thumb_width;
		   $new_height = $height / ($width / $thumb_width);
		}

		$thumb = imagecreatetruecolor($thumb_width, $thumb_height);

		// Resize and crop
		imagecopyresampled($thumb,
						   $image,
						   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
						   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
						   0, 0,
						   $new_width, $new_height,
						   $width, $height);
		imagejpeg($thumb, $filename, 80);
	}
	
	
	/**
	 * Rotate the source image and store it to dest path
	 * Return true if successful and false otherwise	 
	 */	 	
	static public function rotate( $srcPath, $destPath, $degrees )
	{
		// Set output quality
		$imgQuality		= 99;
		
		$info			= getimagesize( $srcPath );
    	$imgType		= image_type_to_mime_type($info[2]);
    	$rotate			= null;
    	
		if($imgType == 'image/png' && function_exists('imagecreatefrompng')){
			$source = imagecreatefrompng($srcPath);
			
			if($degrees == '90'){
				$rotatedImage = JXImage::rotatePNGImage($source);
			}else if($degrees == '-90'){
				$rotatedImage = JXImage::rotatePNGImage(JXImage::rotatePNGImage(JXImage::rotatePNGImage($source)));
			}
			
			ob_start();
			
			imagepng($rotatedImage);
			$output = ob_get_contents();
			
			ob_end_clean();
			
			// @todo, need to verify that the $output is indeed a proper image data
			return JFile::write( $destPath , $output );
		}
		else if (($imgType == 'image/jpeg') && function_exists('imagecreatefromjpeg') && function_exists('imagerotate'))
		{
			// @todo: Support rotation for other image type other than JPEG
			// Load
			$source = imagecreatefromjpeg($srcPath);
			
			// Rotate
			$rotate = imagerotate($source, $degrees, 0);
			
			if($rotate){
				// Output
				ob_start();
				
				// We default to use jpeg
				imagejpeg($rotate, null, $imgQuality);
				
				$output = ob_get_contents();
				ob_end_clean();
				
				// @todo, need to verify that the $output is indeed a proper image data
				return JFile::write( $destPath , $output );
			}
		}
		
		return false;
		
	}
	
	/**
	 * Rotate png image by 90 degree 
	 */
	static public function rotatePNGImage($image) {
		$width = imagesx($image);
		$height = imagesy($image);
		$newImage = imagecreatetruecolor($height, $width);
		imagealphablending($newImage, false);//drawing color -> alpha channel information, replacing the destination pixel
		imagesavealpha($newImage, true);
		
		for($w=0; $w<$width; $w++){
		  for($h=0; $h<$height; $h++) {
			  $ref = imagecolorat($image, $w, $h);
			  imagesetpixel($newImage, $h, ($width-1)-$w, $ref);//assign width size to the height to produce a 90 degree
		  }
		}
		return $newImage;
	}
	
	/**
	 * Detect image Orientation. Return false if not found	 
	 */
	static public function getOrientation($srcPath)
	{
		
		// Make sure the function exist
		if(!function_exists('exif_read_data')){
			return false;
		}

		$exif = array();
		
		try {
			$exif = @exif_read_data($srcPath);
		} catch (Exception $e) {
			return false;
		}

		// See if orientation data is there
		if(!isset($exif['Orientation'])){
			return false;
		}
		return $exif['Orientation'];
	}
	
	/**
	 * Method to add watermark on existing image.
	 * 
	 * @param	string	$backgroundImagePath	The path to the image that needs to be added with watermark.
	 * @param	string	$destinationPath		The path to the image output
	 * @param	string	$destinationType		The type of the output file
	 * @param	string	$watermarkImagePath		The path to the watermark image.
	 * @param	int		$positionX				The x position of where the watermark should be positioned.
	 * @param	int		$positionY				The y position of where the watermark should be positioned.
	 * 
	 * @return	bool	True on sucess.	 	 
	 **/	 	 	 	  	 	 	 	
	static public function addWatermark( $backgroundImagePath , $destinationPath , $destinationType , $watermarkImagePath , $positionX = 0 , $positionY = 0 , $deleteBackgroundImage = true )
	{
		// Set output quality
		$imgQuality	= 99;
		$pngQuality = ($imgQuality - 100) / 11.111111;
		$pngQuality = round(abs($pngQuality));
		
		$watermarkInfo	= getimagesize( $watermarkImagePath );
		$background		= JXImage::open( $backgroundImagePath , $destinationType );
		$watermark		= JXImage::open( $watermarkImagePath , $watermarkInfo['mime'] );
		list( $backgroundWidth , $backgroundHeight ) 		= getimagesize( $backgroundImagePath );
		
		// Try to make the watermark image transparent
		imagecolortransparent( $watermark ,imagecolorat( $watermark , 0 , 0 ) );

		// Get overlay image width and hight
		$watermarkWidth		= imagesx( $watermark );
		$watermarkHeight	= imagesy( $watermark );

		// Combine background image and watermark into a single output image
		imagecopymerge( $background , $watermark , $positionX , $positionY , 0 , 0 , $watermarkWidth , $watermarkHeight , 100 );
	
		// Output
		ob_start();
	
		// Test if type is png
		if( $destinationType == 'image/png' || $destinationType == 'image/x-png' )
		{
			imagepng($background, null, $pngQuality);
		}
		elseif ( $destinationType == 'image/gif')
		{
			imagegif( $background );
		}
		else
		{
			imagejpeg($background, null, $imgQuality);
		}
		
		$output = ob_get_contents();
		ob_end_clean();
		
		
		// Delete old image
		if( JFile::exists( $backgroundImagePath ) && $deleteBackgroundImage )
		{
			JFile::delete( $backgroundImagePath );
		}
		
		// Free any memory from the existing image resources
		imagedestroy( $background );
		imagedestroy( $watermark );
		
		return JFile::write( $destinationPath , $output );
	}

	/**
	 * Retrieve the proper x and y position depending on the user's choice of the watermark position.
	 **/
	static public function getPositions( $location , $imageWidth , $imageHeight , $watermarkWidth , $watermarkHeight )
	{
		$position	= new stdClass();
		
		// @rule: Get the appropriate X/Y position for the avatar
		switch( $location )
		{
			case 'top':
				$position->x	= ($imageWidth / 2) - ( $watermarkWidth / 2 );
				$position->y	= 0;
				break;
			case 'bottom':
				$position->x	= ($imageWidth / 2) - ( $watermarkWidth / 2 );
				$position->y	= $imageHeight - $watermarkHeight;
				break;
			case 'left':
				$position->x	= 0;
				$position->y	= ( $imageHeight / 2 ) - ($watermarkHeight / 2);
				break;
			case 'right':
				$position->x 	= $imageWidth - $watermarkWidth;
				$position->y	= ( $imageHeight / 2 ) - ($watermarkHeight / 2);
				break;
		}
		return $position;
	}
	
	/**
	 * Retrieves the appropriate image file name which is already hashed.
	 * 
	 * @param	string	$data	A unique data to be hashed
	 * 	 	 	 
	 **/	 	
	static public function getHashName( $data )
	{
		$name	= JUtility::getHash( $data );
		$name	= JString::substr( $name , 0 , 24 );
		
		return $name;
	}
	
	static public function getImageType( $file )
	{
		if (JFile::exists($file))
		{
			$fileInfo = self::getFileProperties( $file );
			
			return  $fileInfo->mime;
		}
		return false;
	}
	
	static public function getImageFileName( $file )
	{
		if (JFile::exists($file))
		{
			return JFile::getName($file);
		}
	}
}