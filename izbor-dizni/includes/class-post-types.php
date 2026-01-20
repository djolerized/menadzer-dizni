<?php
/**
 * Register Custom Post Types
 */

if (!defined('ABSPATH')) {
    exit;
}

class Izbor_Dizni_Post_Types {

    /**
     * Initialize
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'register_post_types'));
    }

    /**
     * Register post types
     */
    public static function register_post_types() {
        // Register Dizna CPT
        $labels = array(
            'name'                  => _x('Dizne', 'Post Type General Name', 'izbor-dizni'),
            'singular_name'         => _x('Dizna', 'Post Type Singular Name', 'izbor-dizni'),
            'menu_name'             => __('Dizne', 'izbor-dizni'),
            'name_admin_bar'        => __('Dizna', 'izbor-dizni'),
            'archives'              => __('Arhiva dizni', 'izbor-dizni'),
            'attributes'            => __('Atributi dizne', 'izbor-dizni'),
            'parent_item_colon'     => __('Roditeljska dizna:', 'izbor-dizni'),
            'all_items'             => __('Sve dizne', 'izbor-dizni'),
            'add_new_item'          => __('Dodaj novu diznu', 'izbor-dizni'),
            'add_new'               => __('Dodaj novu', 'izbor-dizni'),
            'new_item'              => __('Nova dizna', 'izbor-dizni'),
            'edit_item'             => __('Izmeni diznu', 'izbor-dizni'),
            'update_item'           => __('Ažuriraj diznu', 'izbor-dizni'),
            'view_item'             => __('Pogledaj diznu', 'izbor-dizni'),
            'view_items'            => __('Pogledaj dizne', 'izbor-dizni'),
            'search_items'          => __('Pretraži dizne', 'izbor-dizni'),
            'not_found'             => __('Nije pronađeno', 'izbor-dizni'),
            'not_found_in_trash'    => __('Nije pronađeno u korpi', 'izbor-dizni'),
            'featured_image'        => __('Slika dizne', 'izbor-dizni'),
            'set_featured_image'    => __('Postavi sliku dizne', 'izbor-dizni'),
            'remove_featured_image' => __('Ukloni sliku dizne', 'izbor-dizni'),
            'use_featured_image'    => __('Koristi kao sliku dizne', 'izbor-dizni'),
            'insert_into_item'      => __('Ubaci u diznu', 'izbor-dizni'),
            'uploaded_to_this_item' => __('Otpremljeno u ovu diznu', 'izbor-dizni'),
            'items_list'            => __('Lista dizni', 'izbor-dizni'),
            'items_list_navigation' => __('Navigacija liste dizni', 'izbor-dizni'),
            'filter_items_list'     => __('Filtriraj listu dizni', 'izbor-dizni'),
        );

        $args = array(
            'label'               => __('Dizna', 'izbor-dizni'),
            'description'         => __('Dizne za prskanje', 'izbor-dizni'),
            'labels'              => $labels,
            'supports'            => array('title', 'thumbnail'),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-admin-tools',
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
        );

        register_post_type('dizna', $args);
    }
}
