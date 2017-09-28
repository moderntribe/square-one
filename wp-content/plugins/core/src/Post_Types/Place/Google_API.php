<?php

namespace Tribe\Project\Post_Types\Place;

use Tribe\Project\Object_Meta;
use Tribe\Project\Settings\Places_Settings;
use \Tribe\Project\Post_Types\Place\Place;

class Google_API implements API_Interface {

	private $api_key = '';
	private $search_string = '';
	private $post_id = 0;
	private $place_object = null;

	public function __construct( $container ) {
		$this->setup_api_key( $container );
	}

	private function setup_api_key( $container ) {
		$api_key_setting = $container[ 'settings.google_api' ];
		$this->api_key = $api_key_setting->get_setting( Places_Settings::API_KEY );
	}

	/**
	 * Get the google places ID, but only if it needs to be updated.
	 *
	 * @param integer $post_id the Post ID in question.
	 */
	public function get_place( $post_id ) {
		$this->post_id = $post_id;

		if ( ! $this->should_update() ) {
			return;
		}

		$google_place_id = $this->search();
		$this->update_fields( $google_place_id );
	}

	/**
	 * Does this post need to be updated?
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	private function should_update() : bool {
		// Check the post type.
		if ( ! $this->is_place_cpt( $this->post_id ) ) {
			return false;
		}

		// Setup the place object we'll be using.
		$this->place_object = Place::factory( $this->post_id );

		// Setup the string we'll be using.
		$this->search_string = $this->get_search_string();

		// Are the old and new values equal?
		return ! $this->values_are_equal();
	}

	/**
	 * Are the old and new values equal?
	 *
	 * @param int $post_id
	 *
	 * @return bool
	 */
	private function values_are_equal() {
		return $this->place_object->get_meta( Object_Meta\Place::HASHED_NAME_AND_ID ) === md5( $this->place_object->get_meta( Object_Meta\Place::PLACE_ID ) . $this->search_string );
	}

	private function is_place_cpt() {
		return Place::NAME === get_post_type( $this->post_id );
	}

	private function get_search_string() {
		return $this->place_object->get_meta( Object_Meta\Place::PLACE );
	}

	private function update_fields( $google_place_id ) {
		update_field( Object_Meta\Place::PLACE_ID, $google_place_id, $this->post_id );
		update_field( Object_Meta\Place::HASHED_NAME_AND_ID, md5( $google_place_id . $this->search_string ) , $this->post_id );
	}

	private function search() {
		$location = rawurlencode( trim( preg_replace( '/[^0-9a-zA-Z -]/', '', $this->search_string ) ) );
		$response = wp_remote_get( esc_url_raw( "https://maps.googleapis.com/maps/api/place/autocomplete/json?input={$location}&key={$this->api_key}" ) );
		return $this->parse_json( wp_remote_retrieve_body( $response ) );
	}

	private function parse_json( $string ) {
		$response = json_decode( $string, true );

		// This is silly and we'd prob have some logic and validation
		// in real life...but here we are.
		return $response['predictions'][0]['place_id'];
	}
}