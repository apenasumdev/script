<?php

/**
 * @var array $pages
 * @var array $videos
 */

echo '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . PHP_EOL;


//Pages
foreach ($pages as $page) {
	echo '<url>' . PHP_EOL;
	echo '<loc>' . base_url() . '/page/' . $page['slug'] . '</loc>' . PHP_EOL;
	echo '<changefreq>daily</changefreq>' . PHP_EOL;
	echo '</url>' . PHP_EOL;
}

//Videos
foreach ($videos as $video){
	$date = $video['updated_at'] ?? $video['created_at'];
	$date = explode(' ',$date)[0];
	echo '<url>' . PHP_EOL;
	echo '<loc>' . $video['share_url'] . '</loc>' . PHP_EOL;
	echo '<lastmod>'.$date.'</lastmod>' . PHP_EOL;
	echo '<changefreq>monthly</changefreq>' . PHP_EOL;
	echo '</url>' . PHP_EOL;
}

echo '</urlset>' . PHP_EOL;

?>
