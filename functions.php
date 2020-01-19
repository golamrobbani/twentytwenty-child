<?php

/*add-parent-and-child-style-css*/
add_action( 'wp_enqueue_scripts', 'twentytwenty_child_enqueue_styles' );
function twentytwenty_child_enqueue_styles() {
    $parent_style = 'twentytwenty-style'; 
    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'twentytwenty-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}


/* Add custom thumbnail sizes */
// if ( function_exists( 'add_image_size' ) ) {
//     add_image_size( '590x390', 590, 390, true );
// }


/*add extra file*/
require_once(get_stylesheet_directory() . '/inc/extras.php');


/*add prepare-rest-api file*/
require_once(get_stylesheet_directory() . '/inc/prepare-rest-api.php');

