<?php


namespace Lite\Contracts\Database;


use Lite\Database\Eloquent;

interface CastsInboundAttributes
{
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
