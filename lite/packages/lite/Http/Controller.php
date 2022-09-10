<?php


namespace Lite\Http;


use Lite\Auth\Gate;

class Controller
{
	/**
	 * Check permissions
	 * @return void|null
	 */
	protected function checkPermissions(){

		if(!Gate::auth() || !Gate::auth()->admin)
            return response(['error' => 'Permission Denied!'], 500);

		return null;
	}
}