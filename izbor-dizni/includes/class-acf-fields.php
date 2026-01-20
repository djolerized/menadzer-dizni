<?php
/**
 * Register ACF Fields
 */

if (!defined('ABSPATH')) {
    exit;
}

class Izbor_Dizni_ACF_Fields {

    /**
     * Initialize
     */
    public static function init() {
        add_action('acf/init', array(__CLASS__, 'register_fields'));
    }

    /**
     * Register ACF fields
     */
    public static function register_fields() {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        // Register fields for Dizna CPT
        self::register_dizna_fields();

        // Register fields for Kultura taxonomy
        self::register_kultura_fields();
    }

    /**
     * Register fields for Dizna CPT
     */
    private static function register_dizna_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_dizna_osnovni_podaci',
            'title' => 'Osnovni podaci',
            'fields' => array(
                array(
                    'key' => 'field_dizna_primena',
                    'label' => 'Primena',
                    'name' => 'primena',
                    'type' => 'text',
                    'instructions' => 'Primer: Tretman herbicidima',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_dizna_vreme_primene',
                    'label' => 'Vreme primene',
                    'name' => 'vreme_primene',
                    'type' => 'text',
                    'instructions' => 'Primer: na crno; od 2 lista do cvetanja',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_dizna_materijal',
                    'label' => 'Materijal',
                    'name' => 'materijal',
                    'type' => 'text',
                    'instructions' => 'Primer: Delrin',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_dizna_sema_mlaza',
                    'label' => 'Šema mlaza',
                    'name' => 'sema_mlaza',
                    'type' => 'image',
                    'instructions' => 'Slika šeme mlaza',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'medium',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'dizna',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
        ));

        acf_add_local_field_group(array(
            'key' => 'group_dizna_radni_parametri',
            'title' => 'Preporučeni radni parametri',
            'fields' => array(
                array(
                    'key' => 'field_dizna_radni_pritisak',
                    'label' => 'Radni pritisak',
                    'name' => 'radni_pritisak',
                    'type' => 'text',
                    'instructions' => 'Primer: 2–5 bar',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_dizna_brzina_hoda',
                    'label' => 'Brzina hoda',
                    'name' => 'brzina_hoda',
                    'type' => 'text',
                    'instructions' => 'Primer: 8–12 km/h',
                    'required' => 0,
                ),
                array(
                    'key' => 'field_dizna_protok',
                    'label' => 'Protok',
                    'name' => 'protok',
                    'type' => 'text',
                    'instructions' => 'Primer: 140–200 l/ha',
                    'required' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'dizna',
                    ),
                ),
            ),
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
        ));
    }

    /**
     * Register fields for Kultura taxonomy
     */
    private static function register_kultura_fields() {
        acf_add_local_field_group(array(
            'key' => 'group_kultura_fields',
            'title' => 'Podešavanja kulture',
            'fields' => array(
                array(
                    'key' => 'field_kultura_slika_kulture_faze',
                    'label' => 'Slika kulture - faze rasta',
                    'name' => 'slika_kulture_faze',
                    'type' => 'image',
                    'instructions' => 'Jedna velika slika sa svim fazama rasta kulture',
                    'required' => 0,
                    'return_format' => 'array',
                    'preview_size' => 'large',
                ),
                array(
                    'key' => 'field_kultura_pozicionirane_dizne',
                    'label' => 'Pozicionirane dizne',
                    'name' => 'pozicionirane_dizne',
                    'type' => 'repeater',
                    'instructions' => 'Koristite drag & drop interfejs ispod za pozicioniranje dizni',
                    'required' => 0,
                    'layout' => 'table',
                    'button_label' => 'Dodaj diznu',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_kultura_dizna',
                            'label' => 'Dizna',
                            'name' => 'dizna',
                            'type' => 'post_object',
                            'post_type' => array('dizna'),
                            'return_format' => 'id',
                            'allow_null' => 0,
                            'multiple' => 0,
                        ),
                        array(
                            'key' => 'field_kultura_pozicija_x',
                            'label' => 'Pozicija X (%)',
                            'name' => 'pozicija_x',
                            'type' => 'number',
                            'instructions' => 'Automatski se popunjava kroz drag & drop',
                            'default_value' => 50,
                            'min' => 0,
                            'max' => 100,
                            'step' => 0.1,
                        ),
                        array(
                            'key' => 'field_kultura_pozicija_y',
                            'label' => 'Pozicija Y (%)',
                            'name' => 'pozicija_y',
                            'type' => 'number',
                            'instructions' => 'Automatski se popunjava kroz drag & drop',
                            'default_value' => 50,
                            'min' => 0,
                            'max' => 100,
                            'step' => 0.1,
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'taxonomy',
                        'operator' => '==',
                        'value' => 'kultura',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
        ));
    }
}
