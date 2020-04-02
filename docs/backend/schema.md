#Schema

## Overview
The Schema class is a base class for handling one-time updates. 
It is useful for circumstances where you need to make a change once and only once. Often these are database schema changes.

## Using Schema
To use, extend and implement the Schema class.
The `get_updates()` method is required and should return an array `[ 1 => [ $this, 'callable' ] ]`. The key is used by Schema to determine if an update has already been run.

A short example class:
```php
<?php

use Tribe\Libs\Schema\Schema;

class Update extends Schema {
	
	public function get_updates() {
        return [
            1 => [ $this, 'update_one' ],
        ];
	}
	
	// Update post author in a bad hard-coded sort of way.
	public function update_one() {
		global $wpdb;
		
		$wpdb->query( 'UPDATE wp_posts SET post_author=1 WHERE post_author=99' );
	}
	
}
?>
```

Then in your subscriber you can use the following logic:
```php
add_action( 'admin_init', function() use ( $container ) {
    $schema = $container->get( Update::class );
	if ( $schema->update_required() ) {
		$schema->do_updates();
	}
}, 10, 0 );
```

