<?php
/*
Plugin Name: Gambit Gallery
Plugin URI: http://www.gambitgallery.com/
Description: Gambit Gallery extends WordPress' image gallery feature with new options that allow you to create beautiful image galleries.
Author: Benjamin Intal, Gambit
Version: 0.1-alpha
Author URI: http://gambit.ph
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Used for tracking the version used
defined( 'GG_VERSION' ) or define( 'GG_VERSION', '0.1-alpha' );
// Used for text domains
defined( 'GG_I18NDOMAIN' ) or define( 'GG_I18NDOMAIN', 'gambit-gallery' );
// Used for general naming, e.g. nonces
defined( 'GG_SLUG' ) or define( 'GG_SLUG', 'gambit-gallery' );
// Used for general naming
defined( 'GG_NAME' ) or define( 'GG_NAME', 'Gambit Gallery' );
// Used for file includes
defined( 'GG_PATH' ) or define( 'GG_PATH', trailingslashit( dirname( __FILE__ ) ) );

require_once( GG_PATH . 'class-gg-core.php' );

/**
 * Gambit Gallery Plugin Class
 *
 * @since 0.1-alpha
 */
class GambitGalleryPlugin {


	/**
	 * Constructor, add hooks
	 *
	 * @since	0.1-alpha
	 */
	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
		add_filter( 'plugin_row_meta', array( $this, 'pluginLinks' ), 10, 2 );
		
		// Override default gallery shortcode output
		add_filter( 'post_gallery', array( $this, 'renderGalleryOutput'), 10, 2 );
		
		// Adds our own gallery settings
		add_action( 'print_media_templates', array( $this, 'renderGallerySettings' ) );
		
		// Style our gallery settings
		add_action( 'admin_enqueue_scripts', array( $this, "loadAdminScripts" ) );

		// Enable / disable Gambit Gallery setting
		add_action( 'gg_settings_create', array( $this, 'createSettings' ), -100 );
		add_filter( 'gg_settings_attrib_defaults', array( $this, 'attributeDefaults' ), -100 );
	}


	/**
	 * Load plugin translations
	 *
	 * @access	public
	 * @return	void
	 * @since	0.1-alpha
	 */
	public function loadTextDomain() {
		load_plugin_textdomain( GG_I18NDOMAIN, false, basename( dirname( __FILE__ ) ) . '/languages/' );
	}


	/**
	 * Adds links to the docs and GitHub
	 *
	 * @access	public
	 * @param	$plugin_meta array The current array of links
	 * @param	$plugin_file array The plugin file
	 * @return	array The current array of links together with our additions
	 * @since	0.1-alpha
	 **/
	public function pluginLinks( $plugin_meta, $plugin_file ) {
		if ( $plugin_file == plugin_basename( __FILE__ ) ) {
			$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
				"http://www.gambitgallery.com/docs",
				__( "Documentation", GG_I18NDOMAIN )
			);
			$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
				"https://github.com/gambitph/Gambit-Gallery",
				__( "GitHub Repo", GG_I18NDOMAIN )
			);
			$plugin_meta[] = sprintf( "<a href='%s' target='_blank'>%s</a>",
				"https://github.com/gambitph/Gambit-Gallery/issues",
				__( "Issue Tracker", GG_I18NDOMAIN )
			);
		}
		return $plugin_meta;
	}
	
	
	/**
	 * Loads the admin styles for our settings
	 *
	 * @access	public
	 * @return	void
	 * @since	0.1-alpha
	 */
	public function loadAdminScripts() {
		wp_enqueue_style( 
			GG_SLUG . '-admin', 
			plugins_url( 'css/admin.css', __FILE__ ), 
			array(), 
			GG_VERSION
		);
	}
	
	
	/**
	 * Renders the Gambit Gallery settings after the normal gallery settings
	 *
	 * @access	public
	 * @return	void
	 * @since	0.1-alpha
	 */
	public function renderGallerySettings() {
		
		// Associative array of attributes and their default values
		$attributeDefaults = apply_filters( 'gg_settings_attrib_defaults', array() );
		
	    // define your backbone template;
	    // the "tmpl-" prefix is required,
	    // and your input field should have a data-setting attribute
	    // matching the shortcode name
	    ?>
		
		<script type="text/html" id="tmpl-<?php echo GG_SLUG ?>-settings">
			<h3 class="gg_heading">
				<?php _e( 'Gambit Gallery Settings', GG_I18NDOMAIN ) ?>
			</h3>
			<?php do_action( 'gg_settings_create' ) ?>
		</script>

	    <script>

	      jQuery(document).ready(function(){

	        // add your shortcode attribute and its default value to the
	        // gallery settings list; $.extend should work as well...
	        _.extend(wp.media.gallery.defaults, 
				<?php echo json_encode( $attributeDefaults ) ?>
			);

	        // merge default gallery settings template with yours
	        wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
	          template: function(view){
	            return wp.media.template('gallery-settings')(view)
	                 + wp.media.template('<?php echo GG_SLUG ?>-settings')(view);
	          }
	        });

	      });

	    </script>
	    <?php
	}
	
	
	/**
	 * Overrides the default gallery shortcode output
	 *
	 * @access	public
	 * @param	$output string The current rendered shortcode (empty string)
	 * @param	$atts array The list of gallery attributes
	 * @return	string The new gallery shortcode output
	 * @since	0.1-alpha
	 */
	public function renderGalleryOutput( $output, $atts ) {
		
		// Check whether to apply gambit gallery stuff
		if ( empty( $atts['gambit_gallery'] ) ) {
			return $output;
		}
		if ( strtolower( $atts['gambit_gallery'] ) !== 'enabled' ) {
			return $output;
		}
		
		$atts = apply_filters( 'gg_gallery_attributes', $atts );
		
		$output = apply_filters( 'gg_gallery_render', $output, $atts );
		
		return apply_filters( 'gg_gallery_output', $output );
	}

	
	/**
	 * Overrides the default gallery shortcode output
	 *
	 * @access	public
	 * @param	$atts array The list of gallery attributes
	 * @return	string The default values of the attributes
	 * @since	0.1-alpha
	 */
	public function attributeDefaults( $atts ) {
		return array_merge( $atts, array(
			'gambit_gallery' => 'disabled',
		) );
	}

	
	/**
	 * Create the disable / enable Gambit Gallery setting
	 *
	 * @access	public
	 * @return	void
	 * @since	0.1-alpha
	 */
	public function createSettings() {
		?>
		<label class="setting <?php echo GG_SLUG ?>">
			<span><?php _e( 'Gambit Gallery', GG_I18NDOMAIN ); ?></span>
			<select data-setting="gambit_gallery">
				<option value='disabled'><?php _e( 'Disabled', GG_I18NDOMAIN ) ?></option>
				<option value='enabled'><?php _e( 'Enabled', GG_I18NDOMAIN ) ?></option>
			</select>
		</label>
		<?php
	}
}


new GambitGalleryPlugin();