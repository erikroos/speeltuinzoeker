<?php
class Image {
	public static function resizeAndConvertToPng($fileName) {
		
		// 1. rotate if needed
		$image = null;
		$exif = exif_read_data($fileName);
		if (!empty($exif['Orientation'])) {
			$imageResource = imagecreatefromstring(file_get_contents($fileName));
			switch ($exif['Orientation']) {
				case 3:
					$image = imagerotate($imageResource, 180, 0);
					break;
				case 6:
					$image = imagerotate($imageResource, -90, 0);
					break;
				case 8:
					$image = imagerotate($imageResource, 90, 0);
					break;
			}
		}
		if ($image != null) {
			imagedestroy($imageResource);
			imagepng($image, $fileName, 5);
			imagedestroy($image);
		}
		
		// 2. resize + to PNG
		list ($width, $height, $type, $attr) = getimagesize($fileName);
		if ($width > MAX_PHOTO_DIM || $height > MAX_PHOTO_DIM) {
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
		imagepng($dst, $fileName, 5);
		imagedestroy($dst);
		
	}
}