<?php
// Disable Gutenberg Editor

/*if (version_compare($GLOBALS['wp_version'], '5.0-beta', '>')) {
    add_filter('use_block_editor_for_post_type', '__return_false', 100);
} else {
    add_filter('gutenberg_can_edit_post_type', '__return_false');
}*/

/*

//practice for gutenbarg blog
add_action(
	'rest_api_init',
	function () {

		if ( ! function_exists( 'use_block_editor_for_post_type' ) ) {
			require ABSPATH . 'wp-admin/includes/post.php';
		}

		// Surface all Gutenberg blocks in the WordPress REST API
		$post_types = get_post_types_by_support( [ 'editor' ] );
		foreach ( $post_types as $post_type ) {
			if ( use_block_editor_for_post_type( $post_type ) ) {
				register_rest_field(
					$post_type,
					'blocks',
					[
						'get_callback' => function ( array $post ) {
							return parse_blocks( $post['content']['raw'] );
						},
					]
				);
			}
		}
	}
);*/



/**
 * This is our callback function that embeds our phrase in a WP_REST_Response
 */
function gr_get_endpoint_phrase() {
    // rest_ensure_response() wraps the data we want to return into a WP_REST_Response, and ensures it will be properly returned.
    return rest_ensure_response( 'Hello World, this is the WordPress REST API' );
}
 
/**
 * This function is where we register our routes for our example endpoint.
 */
function gr_register_example_routes() {
    // register_rest_route() handles more arguments but we are going to stick to the basics for now.
    register_rest_route( 'gr/v1', '/grsearch', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        // Here we register our callback. The callback is fired when this endpoint is matched by the WP_REST_Server class.
        'callback' => 'gr_get_endpoint_phrase',
    ) );
}
 
add_action( 'rest_api_init', 'gr_register_example_routes' );