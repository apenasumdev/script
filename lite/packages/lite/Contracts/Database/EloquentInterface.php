<?php

namespace Lite\Contracts\Database;

interface EloquentInterface {

    /**
     * Create record
     * @param array $collection
     * @return static
     */
    public static function create($collection);

	/**
	 * Find a record by primary|unique|index key
	 * @param string|array|mixed $args
	 * @return static
	 */
    public static function find($args);

	/**
	 * Find all matching records
	 * @param string|array|mixed $args
	 * @param string $groupBy
	 * @return static
	 */
    public static function findAll($args,$groupBy = 'id');

	/**
	 * Fetch all records
	 * @param string $groupBy
	 * @return static
	 */
    public static function all($groupBy = 'id');

	/**
	 * Save Record
	 * @return boolean
	 */
    public function save();

	/**
	 * Delete a record from table
	 */
    public function delete();

	/**
	 * Delete records associated with these keys
	 * @param $keys
	 * @param string $key
	 * @return bool
	 */
    public static function deleteAll($keys,$key);
}