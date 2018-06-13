<?php


function tribe_cache_set( $key, $value, $group = 'tribe', $expire = 0 ) {
	$cache = new \Tribe\Libs\Cache\Cache();
	return $cache->set( $key, $value, $group, $expire );
}

function tribe_cache_get( $key, $group = 'tribe' ) {
	$cache = new \Tribe\Libs\Cache\Cache();
	return $cache->get( $key, $group );
}

function tribe_cache_delete( $key, $group = 'tribe' ) {
	$cache = new \Tribe\Libs\Cache\Cache();
	return $cache->delete( $key, $group );
}