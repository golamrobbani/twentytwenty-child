<?php

function prepare_rest($data, $post, $request){
    $_data = $data->data;

    // Thumbnails
    $thumbnail_id = get_post_thumbnail_id( $post->ID );
    //$thumbnail300x180 = wp_get_attachment_image_src( $thumbnail_id, '590x390' );
    $thumbnailMedium = wp_get_attachment_image_src( $thumbnail_id, 'medium' );
    $full = wp_get_attachment_image_src( $thumbnail_id, 'full' );

    //Categories
    $cats = get_the_category($post->ID);

    //comment number
    $t_coments_num=get_comments_number($post->ID);

    var_dump($t_coments_num);

    //next/prev
    /*$nextPost = get_adjacent_post(false, '', true );
    $nextPost = $nextPost->ID;
    $prevPost = get_adjacent_post(false, '', false );
    $prevPost = $prevPost->ID;*/

    //$_data['fi_300x180'] = $thumbnail300x180[0];
    $_data['f_image_medium'] = $thumbnailMedium[0];
    $_data['f_image_full'] = $full[0];
    $_data['cats'] = $cats;
    $_data['t_coments_num']=$t_coments_num;

    /*$_data['next_post'] = $nextPost;
    $_data['previous_post'] = $prevPost;*/


    $data->data = $_data;

    return $data;
}
add_filter('rest_prepare_post', 'prepare_rest', 10, 3);