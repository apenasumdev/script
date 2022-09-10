<?php

namespace Lite\Database;

use Lite\Contracts\Database\EloquentInterface;
use Lite\Exceptions\JsonEncodingException;
use Lite\Support\Str;
use Lite\Database\Concerns\{HasAttributes, HasTimestamps, HidesAttributes};
use Lite\Contracts\Support\{Arrayable, Jsonable};
use ArrayAccess, JsonSerializable;

abstract class  Eloquent implements EloquentInterface, Arrayable, ArrayAccess, Jsonable, JsonSerializable{

	use HasAttributes, HasTimestamps, HidesAttributes;

	/**
	 * @var PDOx
	 */
	protected static $pdo = null;

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'id';

	/**
	 * The "type" of the primary key ID.
	 *
	 * @var string
	 */
	protected $keyType = 'int';

	protected static $jsonCast = [];

	/**
	 * Indicates if the IDs are auto-incrementing.
	 *
	 * @var bool
	 */
	public $incrementing = true;

	/**
	 * Indicates if the model exists.
	 *
	 * @var bool
	 */
	public $exists = false;

	/**
	 * The name of the "created at" column.
	 * Disabled due to some errors
	 *
	 * @var string
	 */
	const CREATED_AT = '';

	/**
	 * The name of the "updated at" column.
	 * Disabled due to some errors
	 *
	 * @var string
	 */
	const UPDATED_AT = '';


	protected static $config = null;


	/**
	 * Eloquent constructor.
	 * @param array $attrs
	 */
	public function __construct($attrs)
	{
		$this->fill($attrs);
	}


	/**
	 * @return PDOx
	 */
	public static function db(){
		self::$pdo = new PDOx(self::$config);
		self::$pdo
				->table(static::getTableName())
				->on(static::eloquent());
		return self::$pdo;
	}

	protected static function eloquent(){
		return static::class;
	}
	/**
	 * Create record
	 * @param array $collection
	 * @param array|null $db
	 * @return static
	 */
    public static function create($collection){
    	foreach ($collection as $key => $value){
    		if(in_array($key,static::$jsonCast))
    			$collection[$key] = json_encode($value);
	    }
		$record =  static::db()->insert($collection);
		if($record)
			return static::find($record);
		else return null;
    }

    public static function createWithConfig($collection, $config = null){
    	if($config)
    		self::$config = $config;
    	return static::create($collection);
    }

	/**
	 * Find a record by primary|unique|index key
	 * @param string|array|mixed $args
	 * @return static
	 */
    public static function find($args){
    	if(!is_array($args))
    		$where = ['id'=>$args];
    	else $where = $args;

    	return static::db()
		    ->select('*')
		    ->where($where)
		    ->get();
    }

	/**
	 * Find all matching records
	 * @param $where
	 * @param string $op
	 * @param mixed $value
	 * @param string $dicBy Dictionary Keyed by
	 * @return Collection
	 */
    public static function findAll($where, $op=null, $value=null, $dicBy = 'id'){
	    if(!is_array($where) && func_num_args() < 2)
		    $where = ['id'=>$where];

	    return static::db()
		        ->select('*')
		        ->where($where,$op,$value)
		        ->dicBy($dicBy)
		        ->getAll();
    }

	/**
	 * Fetch all records
	 * @param string $dicBy Dictionary Keyed by
	 * @return Collection
	 */
	public static function all($dicBy = 'id'){
    	return static::db()
		        ->select('*')
		        ->dicBy($dicBy)
		        ->getAll();
	}

	/**
	 * Save Record
	 * @param bool $silently
	 * @return void
	 */
	public function save($silently = false){
		//return is null or empty


		if(empty($this->updated)
			|| is_null($this->updated))
			return;

		$columns = [];


		//Get new values
		foreach ($this->updated as $key) {
			if(in_array($key, self::$jsonCast))
				$columns[$key] = json_encode($this->attributes[$key]);
			else
				$columns[$key] = $this->attributes[$key];
		}

		if($silently) {
			//Add Silent Column`s default values
			foreach ($this->silentColumns as $key)
				$columns[$key] = $this->original[$key];
		}

		//Update Column
		static::db()
			->where($this->getKeyName(),'=',$this->getKey())
			->update($columns);


		$this->updated = [];
		//$this->fill($this->attributes);
	}

	/**
	 * Delete a record from table
	 */
	public function delete(){
		static::db()
			->where($this->getKeyName(),'=',$this->getKey())
			->delete();
	}

	/**
	 * Delete records associated with these keys
	 * @param $keys
	 * @param string $key
	 * @return bool
	 */
	public static function deleteAll($keys, $key = 'id'){
		static::db()
			->whereIn($key,$keys)
			->delete();
		return true;
	}

	public static function paginate($limit = 20, $offset = 1){
		return static::db()
					->pagination($limit,$offset)
					->getAll();
	}

	/**
	 * Fill the model
	 * @param array $attrs
	 */
	public function fill($attrs){
		$this->attributes = (array)$attrs;
		$this->original = (array)$attrs;
	}


	/**
	 * Get current table name
	 * @return string
	 */
    protected static function getTableName(){
		return
			defined('static::TABLE') ?
			static::TABLE :
			(string)str(static::class)
					->afterLast('\\')
					->plural()->lower();
    }

	/**
	 * Get current Table
	 * @return string
	 */
    private function getTable(){
    	return static::getTableName();
    }

	/**
	 * Qualify the given column name by the model's table.
	 *
	 * @param  string  $column
	 * @return string
	 */
	public function qualifyColumn($column)
	{
		if (Str::contains($column, '.')) {
			return $column;
		}

		return $this->getTable().'.'.$column;
	}

	/**
	 * Remove the table name from a given key.
	 *
	 * @param  string  $key
	 * @return string
	 */
	protected function removeTableFromKey($key)
	{
		return Str::contains($key, '.') ? last(explode('.', $key)) : $key;
	}

	/**
	 * Create a new Eloquent Collection instance.
	 *
	 * @param  array  $models
	 * @return Collection
	 */
	public function newCollection($models = [])
	{

		return new Collection((array)$models);
	}

	/**
	 * Get a fresh record
	 */
	public function fresh(){
		//Get fresh record
		$raw = static::db()
			->select('*')
			->where($this->getKeyName(),'=',$this->getKey())
			->get();
		//Fill the eloquent
		$this->fill($raw);
	}

	/**
	 * Determine if two models have the same ID and belong to the same table.
	 *
	 * @param Eloquent|null  $model
	 * @return bool
	 */
	public function is($model)
	{
		return ! is_null($model) &&
			$this->getKey() === $model->getKey() &&
			$this->getTable() === $model->getTable();
	}

	/**
	 * Determine if two models are not the same.
	 *
	 * @param Eloquent|null  $model
	 * @return bool
	 */
	public function isNot($model)
	{
		return ! $this->is($model);
	}


	/**
	 * Get the primary key for the model.
	 *
	 * @return string
	 */
	public function getKeyName()
	{
		return $this->primaryKey;
	}

	/**
	 * Set the primary key for the model.
	 *
	 * @param  string  $key
	 * @return $this
	 */
	public function setKeyName($key)
	{
		$this->primaryKey = $key;

		return $this;
	}

	/**
	 * Get the table qualified key name.
	 *
	 * @return string
	 */
	public function getQualifiedKeyName()
	{
		return $this->qualifyColumn($this->getKeyName());
	}

	/**
	 * Get the auto-incrementing key type.
	 *
	 * @return string
	 */
	public function getKeyType()
	{
		return $this->keyType;
	}

	/**
	 * Set the data type for the primary key.
	 *
	 * @param  string  $type
	 * @return $this
	 */
	public function setKeyType($type)
	{
		$this->keyType = $type;

		return $this;
	}

	/**
	 * Get the value indicating whether the IDs are incrementing.
	 *
	 * @return bool
	 */
	public function getIncrementing()
	{
		return $this->incrementing;
	}

	/**
	 * Set whether IDs are incrementing.
	 *
	 * @param  bool  $value
	 * @return $this
	 */
	public function setIncrementing($value)
	{
		$this->incrementing = $value;

		return $this;
	}

	/**
	 * Get the default foreign key name for the model.
	 *
	 * @return string
	 */
	public function getForeignKey()
	{
		return Str::snake(class_basename($this)).'_'.$this->getKeyName();
	}

	/**
	 * Get the value of the model's primary key.
	 *
	 * @return mixed
	 */
	public function getKey()
	{
		return $this->getAttribute($this->getKeyName());
	}

	/**
	 * Dynamically retrieve attributes on the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->getAttribute($key);
	}

	/**
	 * Dynamically set attributes on the model.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 * @throws JsonEncodingException
	 */
	public function __set($key, $value)
	{
		$this->setAttribute($key, $value);
	}

	/**
	 * Determine if the given attribute exists.
	 *
	 * @param  mixed  $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return ! is_null($this->getAttribute($offset));
	}

	/**
	 * Get the value for a given offset.
	 *
	 * @param  mixed  $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->getAttribute($offset);
	}

	/**
	 * Set the value for a given offset.
	 *
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 * @throws JsonEncodingException
	 */
	public function offsetSet($offset, $value)
	{
		$this->setAttribute($offset, $value);
	}

	/**
	 * Unset the value for a given offset.
	 *
	 * @param  mixed  $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->attributes[$offset]);
	}

	/**
	 * Determine if an attribute or relation exists on the model.
	 *
	 * @param  string  $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return $this->offsetExists($key);
	}

	/**
	 * Unset an attribute on the model.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function __unset($key)
	{
		$this->offsetUnset($key);
	}

	/**
	 * Convert the model instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		$array = array_merge($this->attributesToArray());
		foreach ($array as $key => $value){
			if(in_array($key,self::$jsonCast) && is_string($value))
				$array[$key] = json_decode($value,true);
		}
		return $array;
	}

	/**
	 * Convert the model instance to JSON.
	 *
	 * @param  int  $options
	 * @return string
	 *
	 * @throws JsonEncodingException
	 */
	public function toJson($options = 0)
	{
		$json = json_encode($this->jsonSerialize(), $options);

		if (JSON_ERROR_NONE !== json_last_error()) {
			throw new JsonEncodingException(json_last_error_msg());
		}

		return $json;
	}

	/**
	 * Convert the object into something JSON serializable.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

	/**
	 * Convert the model to its string representation.
	 *
	 * @return string
	 * @throws JsonEncodingException
	 */
	public function __toString()
	{
		return $this->toJson();
	}
}