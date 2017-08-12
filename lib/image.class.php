<?php
class Image {
	public static function resizeAndConvertToPng($fileName) {

        $exif = exif_read_data($fileName);
		
		// 1. resize + to PNG
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

        // 2. rotate if needed (can only be done on small images (otherwise 500 error) so we have to do it AFTER step 1)
        $dst = null;
        if (!empty($exif['Orientation'])) {
            $src = imagecreatefromstring(file_get_contents($fileName));
            switch ($exif['Orientation']) {
                case 3:
                    try {
                        $dst = imagerotate($src, 180, 0);
                    } catch (Exception $e) {
                        // prevent 500 error
                    }
                    break;
                case 6:
                    try {
                        $dst = imagerotate($src, -90, 0);
                    } catch (Exception $e) {
                        // prevent 500 error
                    }
                    break;
                case 8:
                    try {
                        $dst = imagerotate($src, 90, 0);
                    } catch (Exception $e) {
                        // prevent 500 error
                    }
                    break;
            }
        }
        if ($dst != null) {
            imagedestroy($src);
            imagepng($dst, $fileName, 5);
            imagedestroy($dst);
        }
		
	}
}