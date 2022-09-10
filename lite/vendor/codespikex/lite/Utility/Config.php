<?php

namespace Lite\Utility;


final class Config
{
	/**
	 * @var array
	 */
	private $attrs;
	/**
	 * @var array
	 */
	private $originals;
	/**
	 * @var string
	 */
	private $path = null;

	private $install = false;

	/**
	 * Configuration constructor.
	 * @param string $path
	 * @param bool $install
	 */
	public function __construct($path = null, $install =false)
	{
		$this->install = $install;
		if($path)
			$this->loadFromPath($path);
	}

	public function getAttributes(){
		return $this->attrs;
	}

	/**
	 * Load configuration from path
	 * @param string $path
	 */
	public function loadFromPath($path){
		$this->attrs = $this->loadFromFile($path);
		$this->originals = $this->attrs;
		$this->path = $path;
	}

	public function clear($field = null){
		if(!$field)
			$this->attrs = [];
		else if(isset($this->attrs[$field]))
			$this->attrs[$field] = null;
	}

	public function restore(){
		$this->attrs = $this->originals;
		$this->save();
	}


	/**
	 * @param $path
	 * @return array|mixed|object|null
	 */
	function loadFromFile($path){
		//Creating path
		if($this->install)
			$file = path($path);
		else $file = app_path("config/{$path}.php");
		//removing path element
		//return null if file is invalid
		if(!file_exists($file))
			return [];

		//getting content
		$config = require $file;
		if($config)
			return $config;
		else return [];
	}

	/**
	 * Create new file
	 * @param string $fileName
	 */
	public function newFile($fileName){
		$this->path = $fileName;

		$this->attrs = [];
	}
	/**
	 * SETTER
	 * @param string $property
	 * @param mixed $value
	 */
	public function set($property, $value){
		//Getting property
		$properties = explode('.',$property);
		//Setting target
		$_target = &$this->attrs;
		//setting property
		foreach($properties as $_property)
			$_target = &$_target[$_property];
		$_target = $value;
	}
	/**
	 * Getters
	 * @param string $property
	 * @return mixed
	 */
	public function __get($property){
		return $this->attrs[$property] ?? null;
	}

    /**
     * Replace with new Array
     * @param $array
     * @return $this
     */
	public function replaceWith($array){
	    $this->attrs = $array;
	    return $this;
    }

	/**
	 * Save Config
	 * @return bool
	 */
	public function save(){
		//Generating PHP FILE
		$content = "<?php\r\n return [\r\n";
		foreach ($this->attrs as $key=>$value)
			$content .= $this->create_field($key,$value);
		$content .= "];";
		//Path
		$config_path = app_path('config').DIRECTORY_SEPARATOR;

		if($this->install)
			$file_path = $this->path;
		else $file_path = $config_path . str_replace('.',DIRECTORY_SEPARATOR,$this->path) . '.php';
		//Putting Content
		file_put_contents($file_path,
			$content);
		return true;
	}

	/**
	 * @param $property
	 * @param $value
	 * @return string
	 */
	private function create_field($property, $value){
		if(is_numeric($property))
			$str = "\t{$property}=>";
		else $str = "\t'{$property}'=>";
		if(!is_array($value) && !is_object($value)){
			if(is_bool($value)){
				if($value === true)
					$str .= "true,\r\n";
				else $str .= "false,\r\n";
			}
			elseif(is_string($value)){
				$value = str_replace("\'","'",$value);
				$value = str_replace("'","\'",$value);
				$str .= "'{$value}',\r\n";
			}
			else
				$str .= "{$value},\r\n";
			return $str;
		}
		$str .= "[\r\n";
		foreach($value as $key=>$val)
			$str .= "\t".$this->create_field($key,$val);
		$str .= "\t],\r\n";
		return $str;
	}
}
