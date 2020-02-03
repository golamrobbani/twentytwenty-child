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
