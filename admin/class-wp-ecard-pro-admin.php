<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://flap.tv
 * @since      1.0.0
 *
 * @package    Wp_Ecard_Pro
 * @subpackage Wp_Ecard_Pro/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Ecard_Pro
 * @subpackage Wp_Ecard_Pro/admin
 * @author     Chad Lieberman <chad@flap.tvb>
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;

class Wp_Ecard_Pro_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        /* Load CARBON FIELDS */
        require_once plugin_dir_path( __FILE__ ) . '../lib/carbon-fields/vendor/autoload.php'; // modify depending on your actual setup
        \Carbon_Fields\Carbon_Fields::boot();
        add_action( 'carbon_fields_register_fields', array($this,'create_admin_fields' ));
        add_action( 'carbon_fields_register_fields', array($this,'create_cpt_fields'));

    }
    public function create_admin_fields()
    {
        Container::make( 'theme_options', $this->plugin_name, __( 'Settings', $this->plugin_name . '-settings' ))
            ->set_page_parent( 'edit.php?post_type='. $this->plugin_name )
            ->add_tab( __('Dashboard'), array(
                Field::make( 'text', 'crb_first_name', 'First Name' ),
                Field::make( 'text', 'crb_last_name', 'Last Name' ),
                Field::make( 'text', 'crb_position', 'Position' ),
            ) )
            ->add_tab( __('General Settings'), array(
                Field::make( 'text', 'crb_email', 'Notification Email' ),
                Field::make( 'text', 'crb_phone', 'Phone Number' ),
            ) )
            ->add_tab( __('Appearance'), array(
                Field::make( 'text', 'foo', 'FOO' )
            ) )
            ->add_tab( __('E-mail'), array(
                Field::make( 'text', 'foo2', 'FOO' )
            ) )
            ->add_tab( __('Fields'), array(
                Field::make( 'text', 'foo3', 'FOO' )
            ) )
            ->add_tab( __('Testing'), array(
                Field::make( 'text', 'foo4', 'FOO' )
            )
        );
    }

    public function create_cpt_fields()
    {
        Container::make( 'post_meta', 'E-Card Images')
        ->set_context( 'carbon_fields_after_title' )
        ->add_fields( array(
                Field::make( 'media_gallery', $this->plugin_name . '_media_gallery', '' )
                    ->set_type( array( 'image', 'video' ) )->set_help_text( 'Add your e-card images here.  Click and drag to change the order in which they display' )
            )
        );
        
    }

    /* Remove text editor from custom post type */
    
    function init_remove_text_editor(){
        remove_post_type_support($this->plugin_name, 'editor');
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    /* Checks plugin version against version required for displaying
    * notices.
    */
    public function admin_notices_init()
    {
        $current_version = '1.0.0';
        if ($this->version !== $current_version) {
            // Do whatever upgrades needed here.
            update_option('my_plugin_version', $current_version);
            $this->add_notice();
        }
    }

    /**
     * Displays admin notices
     *
     * @return 	string 			Admin notices
     */
    public function display_admin_notices()
    {
        $notices = get_option('wep_deferred_admin_notices');
        
        if (empty($notices)) {
            return;
        }
        
        foreach ($notices as $notice) {
            echo '<div class="' . esc_attr($notice['class']) . '"><p>' . $notice['notice'] . '</p></div>';
        }
        
        delete_option('wep_deferred_admin_notices');
    }
    
    /* Adds notices for the admin to display.
     * Saves them in a temporary plugin option.
     * This method is called on plugin activation, so its needs to be static.
     */
    public static function add_admin_notices()
    {
        $notices 	= get_option('wep_deferred_admin_notices', array());
        //$notices[] 	= array( 'class' => 'updated', 'notice' => esc_html__('Custom Activation Message', Wp_Ecard_Pro::$cpt_slug) );
        //$notices[] 	= array( 'class' => 'error', 'notice' => esc_html__('Problem Activation Message', Wp_Ecard_Pro::$cpt_slug) );
        apply_filters('wep_admin_notices', $notices);
        update_option('wep_deferred_admin_notices', $notices);
    }


    public function add_plugin_admin_menu()
    {

        /*
        * Add a settings page for this plugin to the Settings menu.
        *
        * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
        *
        *        Administration Menus: http://codex.wordpress.org/Administration_Menus
        *
        */
        //add_options_page( 'WP E-Card Pro Setup', 'WP E-Card Pro', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
    }
    
	
    public function remove_menu_items()
    {
        // remove the default 'add new' page because it just takes up space //
        $page = remove_submenu_page('edit.php?post_type='.$this->plugin_name, 'post-new.php?post_type='.$this->plugin_name);
    }

    public static function new_cpt_wp_ecard_pro()
    {
        $cap_type 	= 'page';
        $plural 	= Wp_Ecard_Pro::$cpt_plural;
        $single 	= Wp_Ecard_Pro::$cpt_single;
        $cpt_name 	= Wp_Ecard_Pro::$cpt_slug;

        $opts['can_export']								= true;
        $opts['capability_type']						= $cap_type;
        $opts['description']							= '';
        $opts['exclude_from_search']					= false;
        $opts['has_archive']							= false;
        $opts['hierarchical']							= true;
        $opts['map_meta_cap']							= true;
        $opts['menu_icon']								= 'dashicons-images-alt2';
        $opts['menu_position']							= 25;
        $opts['public']									= true;
        $opts['publicly_querable']						= true;
        $opts['query_var']								= true;
        $opts['register_meta_box_cb']					= '';
        $opts['rewrite']								= false;
        $opts['show_in_admin_bar']						= true;
        $opts['show_in_menu']							= true;
        $opts['show_in_nav_menu']						= true;
        $opts['show_ui']								= true;
        $opts['supports']								= array( 'title', 'editor', 'thumbnail' );
        $opts['taxonomies']								= array();
        $opts['capabilities']['delete_others_posts']	= "delete_others_{$cap_type}s";
        $opts['capabilities']['delete_post']			= "delete_{$cap_type}";
        $opts['capabilities']['delete_posts']			= "delete_{$cap_type}s";
        $opts['capabilities']['delete_private_posts']	= "delete_private_{$cap_type}s";
        $opts['capabilities']['delete_published_posts']	= "delete_published_{$cap_type}s";
        $opts['capabilities']['edit_others_posts']		= "edit_others_{$cap_type}s";
        $opts['capabilities']['edit_post']				= "edit_{$cap_type}";
        $opts['capabilities']['edit_posts']				= "edit_{$cap_type}s";
        $opts['capabilities']['edit_private_posts']		= "edit_private_{$cap_type}s";
        $opts['capabilities']['edit_published_posts']	= "edit_published_{$cap_type}s";
        $opts['capabilities']['publish_posts']			= "publish_{$cap_type}s";
        $opts['capabilities']['read_post']				= "read_{$cap_type}";
        $opts['capabilities']['read_private_posts']		= "read_private_{$cap_type}s";
        $opts['labels']['add_new']						= esc_html__("Add New {$single}", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['add_new_item']					= esc_html__("Add New {$single}", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['all_items']					= esc_html__($plural, Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['edit_item']					= esc_html__("Edit {$single}", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['menu_name']					= esc_html__($plural, Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['name']							= esc_html__($plural, Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['name_admin_bar']				= esc_html__($single, Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['new_item']						= esc_html__("New {$single}", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['not_found']					= esc_html__("No {$plural} Found", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['not_found_in_trash']			= esc_html__("No {$plural} Found in Trash", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['parent_item_colon']			= esc_html__("Parent {$plural} :", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['search_items']					= esc_html__("Search {$plural}", Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['singular_name']				= esc_html__($single, Wp_Ecard_Pro::$cpt_slug);
        $opts['labels']['view_item']					= esc_html__("View {$single}", Wp_Ecard_Pro::$cpt_slug);
        $opts['rewrite']['ep_mask']						= EP_PERMALINK;
        $opts['rewrite']['feeds']						= false;
        $opts['rewrite']['pages']						= true;
        $opts['rewrite']['slug']						= esc_html__(strtolower($plural), Wp_Ecard_Pro::$cpt_slug);
        $opts['rewrite']['with_front']					= false;
        $opts = apply_filters('wp-ecard-pro-cpt-options', $opts);
        register_post_type(strtolower($cpt_name), $opts);
    } // new_cpt_job()

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links($links)
    {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
        '<a href="' . admin_url('options-general.php?page=' . $this->plugin_name) . '">' . __('Settings', $this->plugin_name) . '</a>',
    );
        return array_merge($settings_link, $links);
    }



    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Ecard_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Ecard_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-ecard-pro-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Wp_Ecard_Pro_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Wp_Ecard_Pro_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wp-ecard-pro-admin.js', array( 'jquery' ), $this->version, false);
    }
}
