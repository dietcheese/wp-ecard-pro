<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://flap.tv
 * @since      1.0.0
 *
 * @package    Wp_Ecard_Pro
 * @subpackage Wp_Ecard_Pro/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
        <?php settings_errors(); ?>
        <?php
                $active_tab = "options-display";
                if(isset($_GET["tab"]))
                {
                    if($_GET["tab"] == "options-email")
                    {
                        $active_tab = "options-email";
                    }
                    else
                    {
                        $active_tab = "options-display";
                    }
                }
            ?>
        <h2 class="nav-tab-wrapper">
                <!-- when tab buttons are clicked we jump back to the same page but with a new parameter that represents the clicked tab. accordingly we make it active -->
                <a href="?post_type=wp-ecard-pro&page=wp-ecard-pro-settings&tab=options-display" class="nav-tab <?php if($active_tab == 'options-display'){echo 'nav-tab-active';} ?> "><?php _e('Display Options', 'sandbox'); ?></a>
                <a href="?post_type=wp-ecard-pro&page=wp-ecard-pro-settings&tab=options-email" class="nav-tab <?php if($active_tab == 'options-email'){echo 'nav-tab-active';} ?>"><?php _e('Email Options', 'sandbox'); ?></a>
            </h2>
	    <form action="options.php" method="post">
	        <?php
	            settings_fields( $this->plugin_name );
	            do_settings_sections( $this->plugin_name );
	            submit_button();
	        ?>
	    </form>
	</div>