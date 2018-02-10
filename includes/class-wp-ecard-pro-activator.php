<?php

/**
 * Fired during plugin activation
 *
 * @link       http://flap.tv
 * @since      1.0.0
 *
 * @package    Wp_Ecard_Pro
 * @subpackage Wp_Ecard_Pro/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Ecard_Pro
 * @subpackage Wp_Ecard_Pro/includes
 * @author     Chad Lieberman <chad@flap.tvb>
 */
class Wp_Ecard_Pro_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		Wp_Ecard_Pro_Admin::new_cpt_wp_ecard_pro();
		Wp_Ecard_Pro_Admin::add_admin_notices();
		//flush_rewrite_rules();

	}

}
