<?php
class Image {
	public static function resizeAndConvertToPng($fileName) {
		
		list($width, $height, $type, $attr) = getimagesize($fileName);
		if ($width > MAX_PHOTO_DIM || $height > MAX_PHOTO_DIM) {
			$target_filename = $fileName;
			$ratio = $width / $height;
			if ($ratio > 1) {
				$new_width = MAX_PHOTO_DIM;
				$new_height = MAX_PHOTO_DIM / $ratio;
			} else {
				$new_width = MAX_PHOTO_DIM * $ratio;
				$new_height = MAX_PHOTO_DIM;
			}
		} else {
			$new_width = $width;
			$new_height = $height;
		}
		
		$src = imagecreatefromstring(file_get_contents($fileName));
		$dst = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagedestroy($src);
		imagepng($dst, $target_filename, 5);
		imagedestroy($dst);
	}
}