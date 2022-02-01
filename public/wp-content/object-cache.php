<?php declare(strict_types=1);

if ( ! filter_var( getenv( 'DISABLE_OBJECT_CACHE', true ), FILTER_VALIDATE_BOOLEAN ) ) {
	require_once  __DIR__ . '/memcached-object-cache.php';
}
