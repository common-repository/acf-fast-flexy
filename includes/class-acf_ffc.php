<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       mediavuk.com
 * @since      1.0.0
 *
 * @package    Acf_ffc
 * @subpackage Acf_ffc/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Acf_ffc
 * @subpackage Acf_ffc/includes
 * @author     Marko & Ivan <md@mediavuk.com>
 */
class Acf_ffc
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Acf_ffc_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        $this->plugin_name = 'acf_ffc';
        $this->version = '1.0.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Acf_ffc_Loader. Orchestrates the hooks of the plugin.
     * - Acf_ffc_i18n. Defines internationalization functionality.
     * - Acf_ffc_Admin. Defines all hooks for the admin area.
     * - Acf_ffc_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-acf_ffc-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-acf_ffc-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-acf_ffc-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-acf_ffc-public.php';

        $this->loader = new Acf_ffc_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Acf_ffc_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new Acf_ffc_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Acf_ffc_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        // This activated ACF flexy Content tab in settings
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_options_page');
        // Added to hook settings
        $this->loader->add_action('admin_init', $plugin_admin, 'register_setting');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new Acf_ffc_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Acf_ffc_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

    public function slugExists($filename) {

        $file_exists = file_exists(get_template_directory() . '/' . $filename . '.php');

        if ($file_exists) {

            return true;
        }

        return false;
    }

    public function generateUniqueSlug($input_slug) {

        $i = 1;
        $baseSlug = $input_slug;

        while ($this->slugExists($input_slug)) {
            $input_slug = $baseSlug . "-" . $i++;
        }

        return $input_slug;
    }

    public function nameExists($filename) {

        $names_array = [];

        $templates = wp_get_theme()->get_page_templates();

        foreach ($templates as $template => $template_name) {

            array_push($names_array, $template_name);
        }

        if (in_array($filename, $names_array)) {

            return true;
        }

        return false;
    }

    public function generateUniqueName($input_slug) {

        $i = 1;
        $baseSlug = $input_slug;

        while ($this->nameExists($input_slug)) {
            $input_slug = $baseSlug . " " . $i++;
        }

        return $input_slug;
    }
}
