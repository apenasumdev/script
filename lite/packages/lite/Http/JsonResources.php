<?php


namespace Lite\Http;


use Lite\Contracts\Http\Resource;
use Lite\Database\Collection;
use Lite\Database\Eloquent;

abstract class JsonResources implements Resource
{
	/**
	 * @var Collection|array
	 */
	private $collection = null;

	/**
	 * JsonResponse constructor.
	 * @param $collection $model
	 */
	public function __construct($collection)
	{
		$this->collection = $collection;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		if($this->collection instanceof \Lite\Support\Collection)
			return $this->collection->toArray();
		return $this->collection;
	}
}