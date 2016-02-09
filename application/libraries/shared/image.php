<?php

/**
 * Class to control various image related functions
 *
 * @author Hemant Mann
 */
namespace Shared;

class Image {
	/**
	 * @param string $image Name of the image
	 * @param int $width Width of the image
	 * @param int $height Height of the image
	 *
	 * @return boolean|string Thumbnail Name
	 */
	public static function resize($image, $width, $height) {
		$path = APP_PATH . "/public/assets/uploads/images";

		if (file_exists($image)) {
		    $filename = pathinfo($image, PATHINFO_FILENAME);
		    $extension = pathinfo($image, PATHINFO_EXTENSION);
		    
		    if ($filename && $extension) {
		        $thumbnail = "{$filename}-{$width}x{$height}.{$extension}";
		        if (!file_exists("{$path}/{$thumbnail}")) {
		            $imagine = new \Imagine\Gd\Imagine();
		            $size = new \Imagine\Image\Box($width, $height);
		            $mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
		            $imagine->open("{$image}")->resize($size)->save("{$path}/{$thumbnail}");
		            //$imagine->open("{$path}/{$image}")->thumbnail($size, $mode)->save("{$path}/resize/{$thumbnail}");
		        }
		        return "{$path}/{$thumbnail}";
		    }
		}
		return false;
	}

	public static function resource($file) {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'png':
                $res = imagecreatefrompng($file);
                break;

            case 'jpg':
            case 'jpeg':
                $res = imagecreatefromjpeg($file);
                break;
        }
        return $res;
	}
}
