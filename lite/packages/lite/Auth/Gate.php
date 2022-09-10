<?php

namespace Lite\Auth;

use App\Models\User;
use InvalidArgumentException;
use Lite\Support\Str;

final class Gate
{
	/**
	 * @var User
	 */
	private static $user;

	/**
	 * @var string
	 */
	const ACCESS_TOKEN = 'access_token';
	/**
	 * @var string`
	 */
	const TOKEN_VALID = 'token_valid';

	/**
	 * Get current user
	 * @return null|User
	 */
	public static function auth(){
		//If user is already fetched
		if(self::$user)
			return self::$user;
		//if token is set
		if(!isset($_SESSION[self::ACCESS_TOKEN])
			&& !isset($_COOKIE[self::ACCESS_TOKEN]))
			return null;

		//Get token
		$token = $_SESSION[self::ACCESS_TOKEN]
			?? $_COOKIE[self::ACCESS_TOKEN];

		//Find User
		$user = User::find(['token'=>$token]);
		if($user)
			self::$user = $user;
		return $user;
	}

	/**
	 * Login user with email and password
	 * @param $credentials
	 * @return User User Data
	 * @throws InvalidArgumentException
	 */
	public static function attempt($credentials){
		//Get the credentials
		$username = $credentials[0];
		$password = $credentials[1];
		//Get username field from User
		$attemptBy = User::username() ?? 'email';
		//Find user
		$user = User::find([$attemptBy=>$username]);
		//Throw error if user if null
		if(!$user)
			throw new InvalidArgumentException("invalid_credentials");
		//Verify password
		$verify = password_verify($password,$user->password);
		//Throw error if password not verified
		if(!$verify)
			throw new InvalidArgumentException("invalid_password");
		//Login User
		return self::login($user);
	}
	/**
	 * Login
	 * @param User $user
	 * @return User
	 */
	private static function login($user){
		//CreateToken
		if(!isset($_COOKIE[self::TOKEN_VALID])){
			$user->token = Str::uuid();
			$user->save(true);
		}
		//Create Session
		$_SESSION[self::ACCESS_TOKEN] = $user->token;
		//Create Cookies
		setcookie(self::TOKEN_VALID,true,time() + (86400 * 1), "/");
		setcookie(self::ACCESS_TOKEN, $user->token, time() + (86400 * 30), "/");

		//Setting user
		self::$user = $user;

		return $user;
	}

	/**
	 * Login with Token
	 * @param string $token
	 * @return User
	 * @throws InvalidArgumentException
	 */
	public static function token_attempt($token){
		//if Token is not available in cookie
		if(!isset($_COOKIE[self::ACCESS_TOKEN]))
			throw new InvalidArgumentException('token_not_set');
		//If cookie token not matches the request token
		if($_COOKIE[self::ACCESS_TOKEN] !== $token)
			throw new InvalidArgumentException("invalid_token!");
		//Get User
		$user = User::find(['token'=>$token]);
		//If User not found
		if(!$user)
			throw new InvalidArgumentException('something_went_wrong');
		//Login User
		return self::login($user);
	}

	/**
	 * Clear Session and logout
	 * @throws InvalidArgumentException
	 */
	public static function logout(){
		//Get User
		$user = self::auth();
		//Remove Token
		if($user){
			$user->token = 'null';
			$user->save(true);
		}
		//Remove Session
		if(isset($_SESSION[self::ACCESS_TOKEN]))
			unset($_SESSION[self::ACCESS_TOKEN]);
		$past = time() - 3600;
		//Remove Cookies
		setcookie(self::ACCESS_TOKEN, '', $past, '/' );
		setcookie(self::TOKEN_VALID,false,$past,'/');
		//Reset user
		self::$user = null;
		//return true;
		return true;
	}
}