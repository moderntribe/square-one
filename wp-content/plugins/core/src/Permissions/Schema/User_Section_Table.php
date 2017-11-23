<?php


namespace Tribe\Project\Permissions\Schema;


use Tribe\Project\Database\Table_Maker;

class User_Section_Table extends Table_Maker {
	const NAME = 'user_section';

	protected $schema_version = 0.1;

	protected $tables = [ self::NAME ];

	protected function get_table_definition( $table ) {

		global $wpdb;
		$table_name       = $wpdb->$table;
		$charset_collate  = $wpdb->get_charset_collate();
		switch ( $table ) {
			case self::NAME:
				return "CREATE TABLE {$table_name} (
				        section_id bigint(20) unsigned NOT NULL,
				        user_id bigint(20) unsigned NOT NULL,
				        role varchar(50) NOT NULL,
				        KEY section_id (section_id),
				        KEY user_id (user_id),
				        KEY role (role(50))
				        ) $charset_collate";
		}
	}
}