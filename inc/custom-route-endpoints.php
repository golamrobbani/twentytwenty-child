<?php
 
class Gr_Custom_Route extends WP_REST_Controller {


protected $parent_type = null;


  public function __construct() {
    add_action( 'rest_api_init', array( $this, 'register_routes' ) );
    
  }

 
  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes() {
    $version = '1';
    $namespace = 'gr/v' . $version;
    $base = 'cmeta';
    register_rest_route( $namespace, '/' . $base, array(
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_items' ),
        //'permission_callback' => array( $this, 'get_items_permissions_check' ),
        'args'                => array(
 
        ),
      ),
      array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => array( $this, 'create_item' ),
        //'permission_callback' => array( $this, 'create_item_permissions_check' ),
        'args'                => $this->get_endpoint_args_for_item_schema( true ),
      ),
    ) );
    register_rest_route( $namespace, '/' . $base .'/(?P<parent_id>[\d]+)'. '/(?P<id>[\d]+)', array(
      array(
        'methods'             => WP_REST_Server::READABLE,
        'callback'            => array( $this, 'get_item' ),
        //'permission_callback' => array( $this, 'get_item_permissions_check' ),
        'args'                => array(
          'context' => array(
            'default' => 'view',
          ),
        ),
      ),
      array(
        'methods'             => WP_REST_Server::EDITABLE,
        'callback'            => array( $this, 'update_item' ),
        'permission_callback' => array( $this, 'update_item_permissions_check' ),
        'args'                => $this->get_endpoint_args_for_item_schema( false ),
      ),
      array(
        'methods'             => WP_REST_Server::DELETABLE,
        'callback'            => array( $this, 'delete_item' ),
        //'permission_callback' => array( $this, 'delete_item_permissions_check' ),
        'args'                => array(
          'force' => array(
            'default' => false,
          ),
        ),
      ),
    ) );
    register_rest_route( $namespace, '/' . $base . '/schema', array(
      'methods'  => WP_REST_Server::READABLE,
      'callback' => array( $this, 'get_public_item_schema' ),
    ) );
  }


  /**
   * Get the meta ID column for the relevant table.
   *
   * @return string
   */
  protected function get_id_column() {
    return ( 'user' === 'post' ) ? 'umeta_id' : 'meta_id';
  }
/**
   * Get the object (parent) ID column for the relevant table.
   *
   * @return string
   */
  protected function get_parent_column() {
    return ( 'user' === 'post' ) ? 'user_id' : 'post_id';
  }

  /**
   * Check if the data provided is valid data.
   *
   * Excludes serialized data from being sent via the API.
   *
   * @see https://github.com/WP-API/WP-API/pull/68
   * @param mixed $data Data to be checked
   * @return boolean Whether the data is valid or not
   */
  protected function is_valid_meta_data( $data ) {
    if ( is_array( $data ) || is_object( $data ) || is_serialized( $data ) ) {
      return false;
    }

    return true;
  }
 

  /**
   * Get a collection of items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_items( $request ) {
    $items = array(); //do a query, call another class, etc
    $data = array();
    foreach( $items as $item ) {
      $itemdata = $this->prepare_item_for_response( $item, $request );
      $data[] = $this->prepare_response_for_collection( $itemdata );
    }
 
    return new WP_REST_Response( $data, 200 );
  }



 
  /**
   * Get one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_item( $request ) {
    //get parameters from request
    $params = $request->get_params();
    $item = array();//do a query, call another class, etc
    $data = $this->prepare_item_for_response( $item, $request );
 
    //return a response or error based on some conditional
    if ( 1 == 1 ) {
      return new WP_REST_Response( $data, 200 );
    } else {
      return new WP_Error( 'code', __( 'message', 'text-domain' ) );
    }
  }



 
  /**
   * Create one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function create_item( $request ) {
    $item = $this->prepare_item_for_database( $request );
 
    if ( function_exists( 'Gr_some_function_to_create_item' ) ) {
      $data = Gr_some_function_to_create_item( $item );
      if ( is_array( $data ) ) {
        return new WP_REST_Response( $data, 200 );
      }
    }
 
    return new WP_Error( 'cant-create', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
  }



 
  /**
   * Update one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function update_item( $request ) {
    $item = $this->prepare_item_for_database( $request );
 
    if ( function_exists( 'Gr_some_function_to_update_item' ) ) {
      $data = Gr_some_function_to_update_item( $item );
      if ( is_array( $data ) ) {
        return new WP_REST_Response( $data, 200 );
      }
    }
 
    return new WP_Error( 'cant-update', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
  }



 
  /**
   * Delete one item from the collection
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function delete_item( $request ) {
    $item = $this->prepare_item_for_database( $request );
 
    if ( function_exists( 'Gr_some_function_to_delete_item' ) ) {
      $deleted = Gr_some_function_to_delete_item( $item );
      if ( $deleted ) {
        return new WP_REST_Response( true, 200 );
      }
    }
 
    return new WP_Error( 'cant-delete', __( 'message', 'text-domain' ), array( 'status' => 500 ) );
  }


 
  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_items_permissions_check( $request ) {
    //return true; <--use to make readable by all
    return current_user_can( 'edit_something' );
  }
 
  /**
   * Check if a given request has access to get a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_item_permissions_check( $request ) {
    return $this->get_items_permissions_check( $request );
  }
 
  /**
   * Check if a given request has access to create items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function create_item_permissions_check( $request ) {
    return current_user_can( 'edit_something' );
  }
 
  /**
   * Check if a given request has access to update a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function update_item_permissions_check( $request ) {
    return $this->create_item_permissions_check( $request );
  }
 
  /**
   * Check if a given request has access to delete a specific item
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function delete_item_permissions_check( $request ) {
    return $this->create_item_permissions_check( $request );
  }



 
  /**
   * Prepare the item for create or update operation
   *
   * @param WP_REST_Request $request Request object
   * @return WP_Error|object $prepared_item
   */
  protected function prepare_item_for_database( $request ) {

   //var_dump($request);

    $parent_id = (int) $request['parent_id'];
    $mid = (int) $request['id'];
    
    $force = isset( $request['force'] ) ? (bool) $request['force'] : false;

    // We don't support trashing for this type, error out
    if ( ! $force ) {
      return new WP_Error( 'rest_trash_not_supported', __( 'Meta does not support trashing.' ), array( 'status' => 501 ) );
    }

    $parent_column = $this->get_parent_column();
    $current = get_metadata_by_mid( 'post', $mid );

    //var_dump($current);

    if ( empty( $current ) ) {
      return new WP_Error( 'rest_meta_invalid_id', __( 'Invalid meta id.' ), array( 'status' => 404 ) );
    }

    if ( absint( $current->$parent_column ) !== (int) $parent_id ) {
      return new WP_Error( 'rest_meta_' . 'post' . '_mismatch', __( 'Meta does not belong to this object' ), array( 'status' => 400 ) );
    }

    // for now let's not allow updating of arrays, objects or serialized values.
    if ( ! $this->is_valid_meta_data( $current->meta_value ) ) {
      $code = ( 'post' === 'post' ) ? 'rest_post_invalid_action' : 'rest_meta_invalid_action';
      return new WP_Error( $code, __( 'Invalid existing meta data for action.' ), array( 'status' => 400 ) );
    }

    if ( is_protected_meta( $current->meta_key ) ) {
      return new WP_Error( 'rest_meta_protected', sprintf( __( '%s is marked as a protected field.' ), $current->meta_key ), array( 'status' => 403 ) );
    }

    if ( ! delete_metadata_by_mid( 'post', $mid ) ) {
      return new WP_Error( 'rest_meta_could_not_delete', __( 'Could not delete meta.' ), array( 'status' => 500 ) );
    }

    /**
     * Fires after a meta value is deleted via the REST API.
     *
     * @param WP_REST_Request $request The request sent to the API.
     */
    do_action( 'rest_delete_meta', $request );

    return rest_ensure_response( array( 'message' => __( 'Deleted meta' ) ) );
  

    //return array();
  }
 
  /**
   * Prepare the item for the REST response
   *
   * @param mixed $item WordPress representation of the item.
   * @param WP_REST_Request $request Request object.
   * @return mixed
   */
  public function prepare_item_for_response( $item, $request ) {

   global $wpdb;
   $pid=$request['parent_id'];
   $querystr = "
   SELECT *
   FROM $wpdb->postmeta 
   WHERE post_id=$pid and meta_key LIKE 'service_icon_key'
   ORDER BY meta_id ASC
   ";
   $specific_post = $wpdb->get_results( $querystr, OBJECT );
   return $specific_post;


    //return array();
 }
 
  /**
   * Get the query params for collections
   *
   * @return array
   */
  public function get_collection_params() {
    return array(
      'page'     => array(
        'description'       => 'Current page of the collection.',
        'type'              => 'integer',
        'default'           => 1,
        'sanitize_callback' => 'absint',
      ),
      'per_page' => array(
        'description'       => 'Maximum number of items to be returned in result set.',
        'type'              => 'integer',
        'default'           => 10,
        'sanitize_callback' => 'absint',
      ),
      'search'   => array(
        'description'       => 'Limit results to those matching a string.',
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
      ),
    );
  }
}