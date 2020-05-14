# DB Module

This package creates and manages custom DB tables and their associated objects. Each table stores objects of a particular type, found in `Models`. To add a new table, simply create a new Model with the requisite methods, as required by the `Storable` interface. You then need to add it to the switch in DB_Query::get_storable_instance().

## Hey Goober, where's the service provider?

The services are instantiated through `DB_Query`, so I didn't find that I needed one.

## Usage

The `Query\DB_Query` object works something like `WP_Query`:

```php
        $args = [
			DB_Query::TYPE => Example::NAME,
			DB_Query::ID   => $params[ Example::ID ],
		];
		$q    = new DB_Query( $args );

		/**
		 * @var Example A single Example record, if one corresponds to the ID supplied.
		 */
		$profile  = $q->get_storable();
```

`DB_Query` could use some sprucing up to make interacting with it more similar to WP_Query.

## Needs

 * Currently, I have only created Column models for the data types I've needed. See the `Models\Columns` package to understand what I'm talking about.
 * More Repositories could be helpful, including ElasticSearch or something non-databasey.
