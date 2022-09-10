<?php


namespace App\Controllers\Auth;

use App\Resources\UserResource;
use Exception;
use Lite\Auth\Gate;

class AuthController
{
	/**
	 * Login Method
	 */
	public function login(){

		if(isset($_POST['token'])){
			return $this->login_with_token($_POST['token']);
		}

		$credentials[] = $_POST['email'];
		$credentials[] = $_POST['password'];

		try{
			$user = Gate::attempt($credentials);
			return response(['user'=>new UserResource($user)]);
		}catch(Exception $e){
			return response(['error'=>$e->getMessage()],500);
		}
	}

	/**
	 * Login With Token
	 * @param string $token
	 */
	public function login_with_token($token){
		try{
			$user = Gate::token_attempt($token);
			return response(['user'=>new UserResource($user)]);
		}catch(Exception $e){
			return response(['error'=>$e->getMessage()],500);
		}
	}

	/**
	 * Edit User
	 * @throws Exception
	 */
	public function edit(){
		//Get user
		$user = Gate::auth();
		//Check
		if(!$user || !$user->admin)
			return response(['error'=>'Permission Denied'],500);
		//Get data
		$email = $_POST['email'] ?? null;
		$password = $_POST['password'] ?? null;
		//Try now
		try {
			if ($email)
				$user->email = $email;
			if ($password)
				$user->password = password_hash($password,PASSWORD_DEFAULT);
			$user->save();
			//Return data
			return response(['user'=>new UserResource($user)]);
		}catch(Exception $e){
			return response(['error'=>$e->getMessage()],500);
		}

	}

	/**
	 * Logout
	 * @throws Exception
	 */
	public function logout(){
		Gate::logout();
	}


}