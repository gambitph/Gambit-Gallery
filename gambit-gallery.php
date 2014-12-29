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
defined( 'GG' ) or define( 'GG', 'gambit-gallery' );
// Used for general naming
defined( 'GG_NAME' ) or define( 'GG_NAME', 'Gambit Gallery' );
// Used for file includes
defined( 'GG_PATH' ) or define( 'GG_PATH', trailingslashit( dirname( __FILE__ ) ) );


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
	 * @param	array $plugin_meta The current array of links
	 * @param	string $plugin_file The plugin file
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
}


new GambitGalleryPlugin();