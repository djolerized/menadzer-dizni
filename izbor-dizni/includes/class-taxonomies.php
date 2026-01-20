<?php
/**
 * Register Taxonomies
 */

if (!defined('ABSPATH')) {
    exit;
}

class Izbor_Dizni_Taxonomies {

    /**
     * Register taxonomies
     */
    public static function register_taxonomies() {
        // Register Kultura taxonomy
        $labels = array(
            'name'                       => _x('Kulture', 'Taxonomy General Name', 'izbor-dizni'),
            'singular_name'              => _x('Kultura', 'Taxonomy Singular Name', 'izbor-dizni'),
            'menu_name'                  => __('Kulture', 'izbor-dizni'),
            'all_items'                  => __('Sve kulture', 'izbor-dizni'),
            'parent_item'                => __('Roditeljska kultura', 'izbor-dizni'),
            'parent_item_colon'          => __('Roditeljska kultura:', 'izbor-dizni'),
            'new_item_name'              => __('Naziv nove kulture', 'izbor-dizni'),
            'add_new_item'               => __('Dodaj novu kulturu', 'izbor-dizni'),
            'edit_item'                  => __('Izmeni kulturu', 'izbor-dizni'),
            'update_item'                => __('Ažuriraj kulturu', 'izbor-dizni'),
            'view_item'                  => __('Pogledaj kulturu', 'izbor-dizni'),
            'separate_items_with_commas' => __('Razdvoji kulture zapetama', 'izbor-dizni'),
            'add_or_remove_items'        => __('Dodaj ili ukloni kulture', 'izbor-dizni'),
            'choose_from_most_used'      => __('Izaberi od najkorišćenijih', 'izbor-dizni'),
            'popular_items'              => __('Popularne kulture', 'izbor-dizni'),
            'search_items'               => __('Pretraži kulture', 'izbor-dizni'),
            'not_found'                  => __('Nije pronađeno', 'izbor-dizni'),
            'no_terms'                   => __('Nema kultura', 'izbor-dizni'),
            'items_list'                 => __('Lista kultura', 'izbor-dizni'),
            'items_list_navigation'      => __('Navigacija liste kultura', 'izbor-dizni'),
        );

        $args = array(
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => false,
            'show_in_rest'      => true,
        );

        register_taxonomy('kultura', array('dizna'), $args);
    }
}
