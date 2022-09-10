<?php


namespace Lite\Contracts\Http;


use Lite\Contracts\Support\Arrayable;

interface Resource extends Arrayable
{
	/**
	 * @return mixed
	 */
	public function toArray();
}