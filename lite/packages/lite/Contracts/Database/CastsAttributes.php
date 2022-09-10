<?php


namespace Lite\Contracts\Database;


use Lite\Database\Eloquent;

interface CastsAttributes
{
	/**
	 * Transform the attribute from the underlying model values.
	 *
	 * @param  Eloquent  $model
	 * @param  string  $key
	 * @param  mixed  $value
	 * @param  array  $attributes
	 * @return mixed
	 */
	public function get($model, string $key, $value, array $attributes);

	/**
	 * Transform the attribute to its underlying model values.
	 *
	 * @param  Eloquent  $model
	 * @param  string  $key
	 * @param  mixed  $value
	 * @param  array  $attributes
	 * @return array
	 */
	public function set($model, string $key, $value, array $attributes);
}