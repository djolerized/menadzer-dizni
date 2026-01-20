<?php
/**
 * Plugin Name: Izbor Dizni
 * Plugin URI: https://example.com/izbor-dizni
 * Description: Interaktivna prezentacija izbora dizni po kulturama sa drag & drop interfejsom
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: izbor-dizni
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('IZBOR_DIZNI_VERSION', '1.0.0');
define('IZBOR_DIZNI_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('IZBOR_DIZNI_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class Izbor_Dizni {

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->load_dependencies();
        $this->init_hooks();
    }

    /**
     * Load required files
     */
    private function load_dependencies() {
        require_once IZBOR_DIZNI_PLUGIN_DIR . 'includes/class-post-types.php';
        require_once IZBOR_DIZNI_PLUGIN_DIR . 'includes/class-taxonomies.php';
        require_once IZBOR_DIZNI_PLUGIN_DIR . 'includes/class-acf-fields.php';
        require_once IZBOR_DIZNI_PLUGIN_DIR . 'includes/class-admin.php';
        require_once IZBOR_DIZNI_PLUGIN_DIR . 'includes/class-frontend.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'init'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'));
    }

    /**
     * Initialize plugin
     */
    public function init() {
        // Initialize post types
        Izbor_Dizni_Post_Types::init();

        // Initialize taxonomies
        Izbor_Dizni_Taxonomies::init();

        // Initialize ACF fields
        Izbor_Dizni_ACF_Fields::init();

        // Initialize admin
        Izbor_Dizni_Admin::init();

        // Initialize frontend
        Izbor_Dizni_Frontend::init();
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_enqueue_scripts($hook) {
        // Only load on kultura edit page
        if ('term.php' !== $hook && 'edit-tags.php' !== $hook) {
            return;
        }

        $screen = get_current_screen();
        if ($screen && $screen->taxonomy === 'kultura') {
            wp_enqueue_style(
                'izbor-dizni-admin',
                IZBOR_DIZNI_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                IZBOR_DIZNI_VERSION
            );

            wp_enqueue_script(
                'izbor-dizni-admin',
                IZBOR_DIZNI_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-draggable'),
                IZBOR_DIZNI_VERSION,
                true
            );

            wp_localize_script('izbor-dizni-admin', 'izborDizniAdmin', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('izbor_dizni_nonce')
            ));
        }
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function frontend_enqueue_scripts() {
        wp_enqueue_style(
            'izbor-dizni-frontend',
            IZBOR_DIZNI_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            IZBOR_DIZNI_VERSION
        );

        wp_enqueue_script(
            'izbor-dizni-frontend',
            IZBOR_DIZNI_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            IZBOR_DIZNI_VERSION,
            true
        );

        wp_localize_script('izbor-dizni-frontend', 'izborDizniFrontend', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
}

/**
 * Initialize plugin
 */
function izbor_dizni_init() {
    return Izbor_Dizni::get_instance();
}

// Start the plugin
izbor_dizni_init();
