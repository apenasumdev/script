<?php


namespace App\Controllers;


use App\Models\Page;
use App\Models\Video;

class SitemapController
{
	protected $collection = [];

	public function index(){
		$this->populateCollection();

		header("Content-Type: application/xml; charset=utf-8");

		return view('sitemap',$this->collection);
	}

	protected function populateCollection(){

		$this->collection['videos'] = Video::all()->map(function($video){return $video[0];})->toArray();
		$this->collection['pages'] = Page::all()->map(function($page){return $page[0];})->toArray();

	}
}