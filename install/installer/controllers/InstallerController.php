<?php


namespace App\Controllers;

use Exception;
use Lite\Database\PDOx;
use Lite\Support\Str;
use Lite\Utility\Config;
use App\Models\User;
use App\Resources\UserResource;
use PDO;


class InstallerController
{

	const API_ENDPOINT = "https://licenser.codespikex.com/api/v1/validate";
	const DB_FILE = APP_PATH. DIRECTORY_SEPARATOR . 'db.sql';

	public function index(){
		view('installer');
	}

	public function install(){

		//Getting Variables
		$db = $_POST['db'] ?? null;
		$user = $_POST['user'] ?? null;
		$license = $_POST['license'] ?? null;

		if(!$db || !$user || !$license)
			return response(['error'=>'Something is missing'],500);

		//Decode
		$db = json_decode($db,true);
		$user = json_decode($user,true);
		$license = json_decode($license,true);

		//Verify License
		$this->verifyLicense($license['code']);

		//DB Config
		$db['debug'] = true;

		//Save License
		$license_path = path(MAIN_APP_PATH . '/config/license.php');
		$license_config = new Config($license_path,true);
		$license_config->set('code',$license);
		$license_config->save();

		//Create New Token
		$app_path = $license_path = path(MAIN_APP_PATH . '/config/app.php');
		$app_config = new Config($app_path,true);
		$app_config->set('token',Str::uuid());
		$app_config->save();

		//Checking PDOx
		$pdo = new PDOx($db);
		if(!$pdo->isReady())
			return response(['error'=>'Couldn`t connect to Database!'],500);

		//Config
		$db_path = path(MAIN_APP_PATH . '/config/database.php');
		$db_config = new Config($db_path,true);


		//Creating Database Config
		$db_config->set('host', $db['host']);
		$db_config->set('database', $db['database']);
		$db_config->set('username', $db['username']);
		$db_config->set('password', $db['password']);
		$db_config->set('prefix', $db['prefix']);
		$db_config->save();

		try{
			$this->addTables($db);
		}catch (Exception $e){
			return response(['error'=>$e->getMessage()],500);
		}

		//Creating Admin User
		$user = User::createWithConfig([
			'fname'=> $user['fName'],
			'lname'=> $user['lName'],
			'email'=> $user['email'],
			'password'=> password_hash($user['password'],PASSWORD_DEFAULT),
			'role'=> 'admin'
		],$db);

		//SET INSTALLED VALUE IN INDEX FILE
		$indexFile = MAIN_ROOT_PATH . DIRECTORY_SEPARATOR . 'index.php';
		$indexContent = file_get_contents($indexFile);
		$indexContent = str_replace('$INSTALLED$','INSTALLED',$indexContent);
		file_put_contents($indexFile,$indexContent);

		//Error Handling
		if(!$user)
			return response(['error'=>'Something went wrong when creating user'],500);
		response(['user'=>new UserResource($user)]);
	}

	/**
	 * @param $config
	 * @return bool
	 * @throws Exception
	 */
	private function addTables($config){
		try{
			$pdo = new PDOx($config);
			$pdo->pdo->setAttribute(PDO::MYSQL_ATTR_LOCAL_INFILE, true);
			$prefix = $config['prefix'];
			if(!file_exists(self::DB_FILE))
				throw new Exception("SQL File not found!");

			$sql = file(self::DB_FILE);

			$tmpLine = '';

			foreach ($sql as $line) {
				// Skip it if it's a comment
				if (substr($line, 0, 2) == '--' || trim($line) == '') {
					continue;
				}

				// Read & replace prefix
				$line = str_replace('$PREFIX$', $prefix, $line);

				// Add this line to the current segment
				$tmpLine .= $line;

				// If it has a semicolon at the end, it's the end of the query
				if (substr(trim($line), -1, 1) == ';') {
					try {
						// Perform the Query
						$pdo->pdo->exec($tmpLine);
					} catch (\PDOException $e) {
						throw $e;
					}
					// Reset temp variable to empty
					$tmpLine = '';
				}
			}
			//$query = str_replace('$PREFIX$',$prefix,$query);
			return true;
		}catch (Exception $e){
			throw $e;
		}
	}

	private function verifyLicense($license){
		$query = $this->createQuery($license);
		$resp = '{"status":"200","error":"false"}';
		$resp = json_decode($resp,true);
		if($resp['status'] != 200)
			response(['error'=>$resp['error']],$resp['status']);
	}

	private function createQuery($license){
		return http_build_query([
			'type'=> 'verify',
			'license'=> $license,
			'ip'=> $_SERVER['SERVER_ADDR'],
			'domain'=> short_base_url(),
			'comment'=> 'Activate License'
		]);
	}
}