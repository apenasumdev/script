<?php


namespace App\Controllers\API;


use Lite\Http\Controller;

class ImageController extends Controller
{
	public function upload(){

		$this->checkPermissions();

		$image = $_FILES['image'];

		$dest_path = ROOT_PATH .
					DIRECTORY_SEPARATOR . 
					'assets' . 
					DIRECTORY_SEPARATOR . 
					'uploads' . 
					DIRECTORY_SEPARATOR;

		$file_name = basename($image['name']);
		$dest_name = date("YmdHi").'_'.$file_name;

		if (!file_exists($dest_path))
			mkdir($dest_path, 0777, true);

		$check = getimagesize($image["tmp_name"]);

		if($check === false)
			return response(['error'=>'Invalid Image'],500);

		if (move_uploaded_file($image["tmp_name"], $dest_path.$dest_name))
			return response(['msg'=>'Image Uploaded!','image'=>'/assets/uploads/'.$dest_name]);
			
		else return response(['error'=>'Something went wrong'],500);
	}
}