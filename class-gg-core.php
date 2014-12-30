<?php
/**
 * Gambit Gallery core settings & rendering
 */

class GGCore {
	
	function __construct() {
		add_filter( 'gg_settings_attrib_defaults', array( $this, 'attributeDefaults' ), 1 );
		add_action( 'gg_settings_create', array( $this, 'createSettings' ), 1 );
		add_filter( 'gg_gallery_render', array( $this, 'renderGallery' ), 10, 2 );
	}
	
	
	/**
	 * Get the attachments, this block of code comes from media.php gallery_shortcode()
	 * AS OF VERSION 4.1
	 *
	 * @access	protected
	 * @param	$attr array The attributes of the gallery shortcode
	 * @see		wp-includes/media.php gallery_shortcode()
	 * @return	empty if no attachments are available,
	 *			string if feed results
	 *			array of attachment objects
	 * @since	0.1-alpha
	 */
	protected function getAttachments( &$atts ) {
		$post = get_post();

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$atts['orderby'] = 'post__in';
			}
			$atts['include'] = $atts['ids'];
		}
		
		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			// 'itemtag'    => $html5 ? 'figure'     : 'dl',
			// 'icontag'    => $html5 ? 'div'        : 'dt',
			// 'captiontag' => $html5 ? 'figcaption' : 'dd',
			'columns'    => 3,
			'size'       => 'thumbnail',
			'include'    => '',
			'exclude'    => '',
			'link'       => ''
		), $atts, 'gallery' );

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		}
		
		if ( empty( $attachments ) ) {
			return '';
		}

		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
			}
			return $output;
		}
		
		return $attachments;
	}
	
	public function attributeDefaults( $atts ) {
		// FIXME These are tests only
		return array_merge( $atts, array(
			'image_gap' => '10',
		) );
	}
	
	public function createSettings() {
		// FIXME These are tests only
		?>
		<label class="setting <?php echo GG_SLUG ?>">
			<span><?php _e('Image Gap Size', GG_I18NDOMAIN ) ?></span>
			<input type="number" min="0" max="1000" step="1" value="10" data-setting="image_gap"/>
			px
		</label>
		<?php
	}
	
	public function renderGallery( $output, $atts ) {
		
		/**
		 * Get the attachments that we will display
		 */
		$attachments = $this->getAttachments( $atts );
		
		if ( is_string( $attachments ) || empty( $attachments ) ) {
			return $attachments;
		}
		
		// Instance from media.php
		static $instance = 0;
		$instance++;
		
		
		// print_r($attachments);
		// FIXME These are tests only
		return $output;
	}
}

new GGCore();