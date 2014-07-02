<?php
/**
 * This file implements a class derived of the generic Skin class in order to provide custom code for
 * the skin in this folder.
 *
 * This file is part of the b2evolution project - {@link http://b2evolution.net/}
 *
 * @package skins
 * @subpackage photoblog
 *
 * @version $Id: _skin.class.php 4246 2013-07-16 17:34:37Z yura $
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

/**
 * Specific code for this skin.
 *
 * ATTENTION: if you make a new skin you have to change the class name below accordingly
 */
class photoblog_Skin extends Skin
{
  	
 	/**
	 * Get default name for the skin.
	 * Note: the admin can customize it.
	 */
	function get_default_name()
	{
		return 'Photoblog';
	}


  /**
	 * Get default type for the skin.
	 */
	function get_default_type()
	{
		return 'normal';
	}


	/**
	 * Get definitions for editable params
	 *
	 * @see Plugin::GetDefaultSettings()
	 * @param local params like 'for_editing' => true
	 */
	function get_param_definitions( $params )
	{
		// Load to use function get_available_thumb_sizes()
		load_funcs( 'files/model/_image.funcs.php' );

		$r = array_merge( array(
				'menu_bg_color' => array(
					'label' => T_('Menu background color'),
					'note' => T_('E-g: #0000ff for blue'),
					'defaultvalue' => '#333333',
					'type' => 'color',
				),
				'menu_text_color' => array(
					'label' => T_('Menu text color'),
					'note' => T_('E-g: #ff6600 for orange'),
					'defaultvalue' => '#AAAAAA',
					'type' => 'color',
				),
				'page_bg_color' => array(
					'label' => T_('Page background color'),
					'note' => T_('E-g: #ff0000 for red'),
					'defaultvalue' => '#666666',
					'type' => 'color',
				),
				'page_text_color' => array(
					'label' => T_('Page text color'),
					'note' => T_('E-g: #00ff00 for green'),
					'defaultvalue' => '#AAAAAA',
					'type' => 'color',
				),
				'post_bg_color' => array(
					'label' => T_('Post info background color'),
					'note' => T_('E-g: #0000ff for blue'),
					'defaultvalue' => '#555555',
					'type' => 'color',
				),
				'post_text_color' => array(
					'label' => T_('Post info text color'),
					'note' => T_('E-g: #ff6600 for orange'),
					'defaultvalue' => '#AAAAAA',
					'type' => 'color',
				),
				'colorbox' => array(
					'label' => T_('Colorbox Image Zoom'),
					'note' => T_('Check to enable javascript zooming on images (using the colorbox script)'),
					'defaultvalue' => 1,
					'type' => 'checkbox',
				),
				'gender_colored' => array(
					'label' => T_('Display gender'),
					'note' => T_('Use colored usernames to differentiate men & women.'),
					'defaultvalue' => 0,
					'type' => 'checkbox',
				),
				'bubbletip' => array(
					'label' => T_('Username bubble tips'),
					'note' => T_('Check to enable bubble tips on usernames'),
					'defaultvalue' => 0,
					'type' => 'checkbox',
				),
				'comments_display' => array(
					'label' => T_('Comments display'),
					'note' => '',
					'defaultvalue' => 'popup',
					'type' => 'radio',
					'options' => array(
						array( 'popup', T_('In a popup window') ),
						array( 'under_post', T_('Under each post') ) ),
					'field_lines' => true,
				),
				'mediaidx_thumb_size' => array(
					'label' => T_('Thumbnail size for media index'),
					'note' => '',
					'defaultvalue' => 'fit-80x80',
					'options' => get_available_thumb_sizes(),
					'type' => 'select',
				),
			), parent::get_param_definitions( $params )	);

		return $r;
	}
	

	/**
	 * Get ready for displaying the skin.
	 *
	 * This may register some CSS or JS...
	 */
	function display_init()
	{
		// call parent:
		parent::display_init();

		// Add CSS:
		require_css( 'basic_styles.css', 'blog' ); // the REAL basic styles
		require_css( 'basic.css', 'blog' ); // Basic styles
		require_css( 'blog_base.css', 'blog' ); // Default styles for the blog navigation
		require_css( 'item_base.css', 'blog' ); // Default styles for the post CONTENT

		// Colorbox (a lightweight Lightbox alternative) allows to zoom on images and do slideshows with groups of images:
		if ($this->get_setting("colorbox")) 
		{
			require_js_helper( 'colorbox', 'blog' );
		}

		// Make sure standard CSS is called ahead of custom CSS generated below:
		require_css( 'style.css', 'relative' );

		// Add custom CSS:
		$custom_css = '';

		// Custom menu styles:
		$custom_styles = array();
		if( $bg_color = $this->get_setting( 'menu_bg_color' ) )
		{ // Background color:
			$custom_styles[] = 'background-color: '.$bg_color;
		}
		if( $text_color = $this->get_setting( 'menu_text_color' ) )
		{ // Text color:
			$custom_styles[] = 'color: '.$text_color;
		}
		if( ! empty( $custom_styles ) )
		{
			$custom_css .= '	div.pageHeader { '.implode( ';', $custom_styles )." }\n";
		}

		// Custom page styles:
		$custom_styles = array();
		if( $bg_color = $this->get_setting( 'page_bg_color' ) )
		{ // Background color:
			$custom_styles[] = 'background-color: '.$bg_color;
		}
		if( $text_color = $this->get_setting( 'page_text_color' ) )
		{ // Text color:
			$custom_styles[] = 'color: '.$text_color;
		}
		if( ! empty( $custom_styles ) )
		{
			$custom_css .= '	body { '.implode( ';', $custom_styles )." }\n";
		}

		// Custom post area styles:
		$custom_styles = array();
		if( $bg_color = $this->get_setting( 'post_bg_color' ) )
		{ // Background color:
			$custom_styles[] = 'background-color: '.$bg_color;
		}
		if( $text_color = $this->get_setting( 'post_text_color' ) )
		{ // Text color:
			$custom_styles[] = 'color: '.$text_color;
		}
		if( ! empty( $custom_styles ) )
		{
			$custom_css .= '	div.bDetails { '.implode( ';', $custom_styles )." }\n";
		}

		if( !empty( $custom_css ) )
		{
			$custom_css = '<style type="text/css">
	<!--
'.$custom_css.'	-->
	</style>';
			add_headline( $custom_css );
		}
	}

}

?>