<?php

class MetaBox {
	/**
	 * The single instance of the class.
	 *
	 * @var themeplate
	 * @since 1.0.0
	 */

	protected static $instance = null;

	/**
	 * MetaBox constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_service_post_meta' ) );
		
	}


	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function register_meta_boxes() {
		add_meta_box( 'cd_service', 'Service Icon', array(
			$this,
			'render_item_meta_metabox'
		), 'post', 'normal', 'high' );
	}


	public function render_item_meta_metabox( $post ) {
		$cd_service_metavalues = get_post_meta( get_the_ID(), 'service_icon_key', true );
		//var_dump($cd_service_metavalues)
		// if ( ! $cd_service_metavalues ) {
		// 	$cd_service_metavalues['service_icon'] = '';
		// }
		?>
		<div class="form-group">
			<label for="service_icon"><?php _e( 'Service Icon', 'rsp' ); ?></label>
			<input value="<?php echo esc_attr( $cd_service_metavalues ); ?>" id="service_icon"
			type="text" class="form-field" name="service_icon"/>
		</div>
		<?php
	}


	public function save_service_post_meta( $post_id ) {
		if ( get_post_type( $post_id ) != 'post' ) {
			return;
		}
		if (isset($_POST['service_icon'] )) {
			$cd_service_metavalues = sanitize_text_field( $_POST['service_icon'] );
			update_post_meta( $post_id, 'service_icon_key', $cd_service_metavalues );
		}
	}

	
}

function cd_custom_metabox() {
	return MetaBox::instance();
}

cd_custom_metabox();
