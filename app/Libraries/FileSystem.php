<?php
namespace App\Libraries;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as ResizeImage;

class FileSystem
{
	public static function uploadImage($file, $path)
	{
		$extension = $file->extension();
		$name = $file->getClientOriginalName();
		if( in_array($file->getClientMimeType(), array('image/jpeg', 'image/jpg', 'image/gif', 'image/png')) )
		{
			$name = explode('.', $name);
			$name =time() . mt_rand(99, 9999) . '-' . Str::slug(current($name)) . '.' . $extension;
			if($file->storeAs($path, $name))
			{
				$path = '/uploads/' . $path . '/' . $name;

				if(file_exists(public_path($path)))
				{
					// FileSystem::correctImageOrientation(public_path($path));
					return $path;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}

	public static function correctImageOrientation($filename)
	{
		if (function_exists('exif_read_data')) {
			$ext = pathinfo($filename, PATHINFO_EXTENSION);
			if($ext == 'jpeg' || $ext == 'jpg')
			{
				$exif = exif_read_data($filename);
				if($exif && isset($exif['Orientation'])) {
					$orientation = $exif['Orientation'];
					if($orientation != 1){
						$img = imagecreatefromjpeg($filename);
						$deg = 0;
						switch ($orientation) {
							case 3:
								$deg = 180;
								break;
							case 6:
								$deg = 270;
								break;
							case 8:
								$deg = 90;
								break;
						}
						if ($deg) {
							$img = imagerotate($img, $deg, 0);       
						}
						// then rewrite the rotated image back to the disk as $filename
						imagejpeg($img, $filename, 95);
					} // if there is some rotation necessary
				} // if have the exif orientation info
			}
		} // if function exists     
	}

	public static function resizeImage($file, $name, $size)
	{
		$path = FileSystem::getOnlyPath($file);
		$path = $path . '/' . $name;
		$size = explode('*', $size);
		
		// ResizeImage::make(public_path($file))
		// 	->fit($size[0], $size[1], function ($constraint) {
		// 	    $constraint->upsize();
		// 	})
		// 	->save(public_path($path));

		$width = $size[0];
		$height = $size[1];
		$img = ResizeImage::make(public_path($file));
		$img->orientate();
		$img->height() > $img->width() ? $width=null : $height=null;
		$img->resize($width, $height, function ($constraint) {
		    $constraint->aspectRatio();
		})
		->save(public_path($path));
		
		return file_exists(public_path($path)) ? $path : null;
	}

	public static function cropImage($file, $name, $width, $height, $x, $y)
	{
		$path = FileSystem::getOnlyPath($file);
		$path = $path . '/' . $name;
		
		ResizeImage::make(public_path($file))
			->crop($width, $height, $x, $y)
			->save(public_path($path));
		
		return file_exists(public_path($path)) ? $path : null;
	}

	public static function saveBase64Image($path, $name, $data)
	{
		list($type) = explode(';', $data);

		if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
		    $data = substr($data, strpos($data, ',') + 1);
		    $type = strtolower($type[1]); // jpg, png, gif

		    if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
		        throw new \Exception('invalid image type');
		    }
		    $data = str_replace( ' ', '+', $data );
		    $data = base64_decode($data);

		    if ($data === false) {
		        throw new \Exception('base64_decode failed');
		    }
		} else {
		    throw new \Exception('did not match data URI with image data');
		}

		$path = FileSystem::getOnlyPath($path);

		file_put_contents(public_path($path . '/' . $name), $data);
		
		return file_exists(public_path($path . '/' . $name)) ? $path . '/' . $name : null;
	}

	public static function uploadFile($file, $path)
	{
		$extension = $file->extension();
		$name = $file->getClientOriginalName();
		if( in_array($file->getClientMimeType(),  array('image/jpeg', 'image/gif', 'image/png', 'application/pdf', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', ' application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/csv', 'text/plain', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/zip', 'image/svg+xml')) )
		{
			$name = explode('.', $name);
			$name = time() . mt_rand(99, 9999) . '-' . Str::slug(current($name)) . '.' . $extension;
			if($file->storeAs($path, $name))
			{
				$path = '/uploads/' . $path . '/' . $name;
				return file_exists(public_path($path)) ? $path : false;
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
	}

	public static function deleteFile($path)
	{
		if($path)
		{
			$path = public_path($path);
			if(file_exists($path))
			{
				unlink($path);

				$folderPath = FileSystem::getOnlyPath($path);
				$name = FileSystem::getFileNameFromPath($path);
				
				if(file_exists($folderPath . '/L-' . $name))
					unlink($folderPath . '/L-' . $name);
				if(file_exists($folderPath . '/M-' . $name))
					unlink($folderPath . '/M-' . $name);

				if(file_exists($folderPath . '/S-' . $name))
					unlink($folderPath . '/S-' . $name);
				
				return true;
			}
		}

		return false;
	}

	public static function getOnlyPath($path)
	{
		$names = explode('/', $path);
		unset($names[ count($names)-1 ]);
		
		return implode('/', $names);
	}

	public static function getFileNameFromPath($path)
	{
		$names = explode('/', $path);
		return end($names);
	}
	
	public static function getExtension($value)
	{
		return pathinfo($value, PATHINFO_EXTENSION);
	}

	public static function getAllSizeImages($file)
	{
		$multiple = is_array($file) ? $file : json_decode($file, true);
		$allFiles = $multiple && is_array($multiple) ? $multiple : ($file ? [$file] : null);		
		if($allFiles)
		{
			foreach($allFiles as $k => $a)
			{
				$name = FileSystem::getFileNameFromPath($a);
				$path = FileSystem::getOnlyPath($a);
				$allFiles[$k] = [
					'original' => $a,
					'large' => $a,
					'medium' => file_exists(public_path($path . '/M-' . $name)) ? $path . '/M-' . $name : "",
					'small' => file_exists(public_path($path . '/S-' . $name)) ? $path . '/S-' . $name : "",
				];
			}
			return $multiple && is_array($multiple) ? $allFiles : current($allFiles);
		}
		
		return null;
	}
}