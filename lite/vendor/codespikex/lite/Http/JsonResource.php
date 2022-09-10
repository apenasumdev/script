<?php

namespace Lite\Http;

use Lite\Contracts\Http\Resource;
use Lite\Database\Eloquent;

abstract class JsonResource implements Resource
{
	/**
	 * @var Eloquent
	 */
	private $model = null;

	/**
	 * JsonResponse constructor.
	 * @param Eloquent $model
	 */
	public function __construct($model)
	{
		$this->model = $model;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		//Return model attributes to array
		return $this->model->attributesToArray();
	}
}