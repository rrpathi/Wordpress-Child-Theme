<?php

function orbisius_ct_storefront_child_theme_child_theme_enqueue_styles() {
    $parent_style = 'orbisius_ct_storefront_child_theme_parent_style';
    $parent_base_dir = 'storefront';

    wp_enqueue_style( $parent_style,
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme( $parent_base_dir ) ? wp_get_theme( $parent_base_dir )->get('Version') : ''
    );

    wp_enqueue_style( $parent_style . '_child_style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

add_action( 'wp_enqueue_scripts', 'orbisius_ct_storefront_child_theme_child_theme_enqueue_styles' );

add_action( 'woocommerce_new_order', 'wc_activation_code');
    function wc_activation_code($order_id) {
        do_action('activation_key_table');
        $order = new WC_Order( $order_id );
        $email =  $order->get_billing_email();
        generate_activation_key($email,$order_id);
}



function generate_activation_key($email,$order_id){
    global $wpdb;
    $table_name = $wpdb->prefix.'activation_key';
    $activation_key = sha1(uniqid().date('Y-m-d'));
    $column_values = array('email' =>$email,'activation_key'=>$activation_key,'order_id'=>$order_id);
    $activation = $wpdb->insert($table_name,$column_values);
    if($activation){
        $subject = 'Welcome to Revmax Technologies Your Activation Code';
        $message   = 'Thanks Purchase Order Your Activation Code: <b style="color:green;">'.$activation_key.'</b>';
        wp_mail( $email, $subject, $message);
    }

    
}


add_action('activation_key_table','create_table');
    function create_table(){
    global $wpdb;
    $table_name = $wpdb->prefix.'activation_key';
    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
        `order_id` varchar(100) NOT NULL,
        `activation_key` varchar(100) NOT NULL,
        `activation_key_status` int(11) DEFAULT '0',
        `plugin_communication_key` varchar(100) DEFAULT NULL,
        `site_url` varchar(200) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}