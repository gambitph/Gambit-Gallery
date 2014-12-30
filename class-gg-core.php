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
	
	public function attributeDefaults( $attributes ) {
		// FIXME These are tests only
		return array_merge( $attributes, array(
			'my_custom_attr' => 'default_val',
			'my_custom_attr2' => 'default_val'
		) );
	}
	
	public function createSettings() {
		// FIXME These are tests only
		?>
      <label class="setting">
        <span><?php _e('My setting'); ?></span>
        <select data-setting="my_custom_attr">
          <option value="foo"> Foo </option>
          <option value="bar"> Bar </option>
          <option value="default_val"> Default Value </option>
        </select>
      </label>
	  <?php
	}
	
	public function renderGallery( $output, $atts ) {
		// FIXME These are tests only
		return $output;
	}
}

new GGCore();
?>