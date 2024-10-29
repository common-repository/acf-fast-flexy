<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       mediavuk.com
 * @since      1.0.0
 *
 * @package    Acf_ffc
 * @subpackage Acf_ffc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Acf_ffc
 * @subpackage Acf_ffc/admin
 * @author     Marko & Ivan <md@mediavuk.com>
 */
class Acf_ffc_Admin {
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of this plugin.
     * @param      string $version The version of this plugin.
     */

    // php partials
    private $header;
    private $paragraph = '';
    private $list = '';
    private $one_image = '';
    private $pdf = '';
    private $text_image = '';
    private $two_images = '';
    private $three_images = '';
    private $footer;
    // json partials
    private $header_json;
    private $paragraph_json = '';
    private $list_json = '';
    private $one_image_json = '';
    private $pdf_json = '';
    private $text_image_json = '';
    private $two_images_json = '';
    private $three_images_json = '';
    private $footer_json;
    // Helpers
    private $template_name = 'Flexy';
    private $template_name_slug = '';
    private $acf_key;
    private $acf_key_two;
    private $acf_json;
    private $acf_php;
    private $template;

    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->acf_ffc_retrieveTemplates();

        add_action('wp_ajax_create_new_group', array(&$this, 'acf_ffc_callCreateNewGroup'));
        add_action('wp_ajax_create_new_delete', array(&$this, 'acf_ffc_callCreateNewDelete'));
        add_action('wp_ajax_list_templates', array(&$this, 'acf_ffc_listTemplates'));
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Acf_ffc_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Acf_ffc_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/acf_ffc-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/main.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Acf_ffc_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Acf_ffc_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/acf_ffc-admin.js', array('jquery'), $this->version, true);
        // AJAX
        wp_localize_script($this->plugin_name, 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /**
     * Add an options page under the Settings submenu
     *
     * @since  1.0.0
     */
    public function add_options_page() {

        $this->plugin_screen_hook_suffix = add_options_page(
            __('ACF Fast Flexy Settings', 'acf-ffc'),
            __('ACF Fast Flexy', 'acf-ffc'),
            'manage_options',
            $this->plugin_name,
            array($this, 'display_options_page')
        );
    }

    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     */
    public function display_options_page() {
        include_once 'partials/acf_ffc-admin-display.php';
    }

    public function register_setting() {

        // Add a General section
        add_settings_section(
            'acf_ffc_general',
            __('General', 'acf-ffc'),
            array($this, 'acf_ffc_general_cb'),
            $this->plugin_name
        );

        add_settings_field(
            'acf_ffc_check_all',
            __('Check All', 'acf-ffc'),
            array($this, 'acf_ffc_check_all_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_check_all')
        );

        add_settings_field(
            'acf_ffc_paragraph',
            __('Paragraph', 'acf-ffc'),
            array($this, 'acf_ffc_paragraph_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_paragraph', 'class' => 'acf_paragraph')
        );

        add_settings_field(
            'acf_ffc_text_image',
            __('Text - Image', 'acf-ffc'),
            array($this, 'acf_ffc_text_image_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_text_image', 'class' => 'acf_text_image')
        );

        add_settings_field(
            'acf_ffc_list',
            __('List', 'acf-ffc'),
            array($this, 'acf_ffc_list_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_list', 'class' => 'acf_list')
        );

        add_settings_field(
            'acf_ffc_pdf',
            __('Pdf', 'acf-ffc'),
            array($this, 'acf_ffc_pdf_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_pdf', 'class' => 'acf_pdf')
        );

        add_settings_field(
            'acf_ffc_one_image',
            __('One Image', 'acf-ffc'),
            array($this, 'acf_ffc_one_image_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_one_image', 'class' => 'acf_one_image')
        );

        add_settings_field(
            'acf_ffc_two_images',
            __('Two Images', 'acf-ffc'),
            array($this, 'acf_ffc_two_images_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_two_images', 'class' => 'acf_two_images')
        );

        add_settings_field(
            'acf_ffc_three_images',
            __('Three Images', 'acf-ffc'),
            array($this, 'acf_ffc_three_images_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_three_images', 'class' => 'acf_three_images')
        );

        add_settings_field(
            'acf_ffc_template_name',
            __('Template name', 'acf-ffc'),
            array($this, 'acf_ffc_template_name_cb'),
            $this->plugin_name,
            'acf_ffc_general',
            array('label_for' => 'acf_ffc_template_name', 'class' => 'acf_template_name')
        );

        register_setting($this->plugin_name, 'acf_ffc_paragraph', 'intval');
        register_setting($this->plugin_name, 'acf_ffc_text_image', 'intval');
        register_setting($this->plugin_name, 'acf_ffc_list', 'intval');
        register_setting($this->plugin_name, 'acf_ffc_pdf', 'intval');
        register_setting($this->plugin_name, 'acf_ffc_one_image', 'intval');
        register_setting($this->plugin_name, 'acf_ffc_two_images', 'intval');
        register_setting($this->plugin_name, 'acf_ffc_three_images', 'intval');
        register_setting($this->plugin_name, 'acf_ffc_template_name', 'intval');
    }

    /**
     * Render the text for the general section
     *
     * @since  1.0.0
     */
    public function acf_ffc_general_cb() {
        echo '<p>' . esc_html('Please, check out desired fields.') . '</p>';
    }

    /**
     * Render the treshold paragraph input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_paragraph_cb() {
        echo '<input class="js_checkbox" type="checkbox" name="acf_ffc_paragraph" id="acf_ffc_paragraph" value="1">';
    }

    /**
     * Render the treshold text - image input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_text_image_cb() {
        echo '<input class="js_checkbox" type="checkbox" name="acf_ffc_text_image" id="acf_ffc_text_image" value="1">';
    }

    /**
     * Render the treshold list input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_list_cb() {
        echo '<input class="js_checkbox" type="checkbox" name="acf_ffc_list" id="acf_ffc_list" value="1">';
    }

    /**
     * Render the treshold pdf input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_pdf_cb() {
        echo '<input class="js_checkbox" type="checkbox" name="acf_ffc_pdf" id="acf_ffc_pdf" value="1">';
    }

    /**
     * Render the treshold one image input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_one_image_cb() {
        echo '<input class="js_checkbox" type="checkbox" name="acf_ffc_one_image" id="acf_ffc_one_image" value="1">';
    }

    /**
     * Render the treshold two images input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_two_images_cb() {
        echo '<input class="js_checkbox" type="checkbox" name="acf_ffc_two_images" id="acf_ffc_two_images" value="1">';
    }

    /**
     * Render the treshold two images input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_three_images_cb() {
        echo '<input class="js_checkbox" type="checkbox" name="acf_ffc_three_images" id="acf_ffc_three_images" value="1">';
    }

    /**
     * Render the treshold two images input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_check_all_cb() {
        echo '<input class="js_check_all" type="checkbox" name="acf_ffc_check_all" id="acf_ffc_check_all" value="1">';
    }

    /**
     * Render the treshold two images input for this plugin
     *
     * @since  1.0.0
     */
    public function acf_ffc_template_name_cb() {
        echo '<input type="text" data-filename name="acf_ffc_template_name" id="acf_ffc_template_name" value="Flexy">';
    }

    public function acf_ffc_listTemplates() {

        $all_templates = wp_get_theme()->get_page_templates();

        $templates_array = array();
        $temp_folder = __DIR__;
        $templates = scandir(dirname(get_theme_root()) . '/acf-ffc-config');

        foreach ($all_templates as $template_file => $template_name) {

            if (in_array($template_file, $templates)) {

                $templates_array[$template_file] = $template_name;
            }
        }
        echo json_encode($templates_array);
        die();
    }

    public function acf_ffc_retrieveTemplates() {

        if ( ! file_exists(get_template_directory() . '/acf-json/index.php')) {

            $this->acf_ffc_setAcfDir();
            $this->acf_ffc_setConfigDir();
            $this->acf_ffc_copyIndexToJson();

            $templates = scandir(dirname(get_theme_root()) . '/acf-ffc-config');
            $json_array = scandir(dirname(get_theme_root()) . '/acf-ffc-config/acf-json');
            $templates_array = array();

            foreach ($templates as $template) {

                if (pathinfo($template, PATHINFO_EXTENSION) == 'php')

                    array_push($templates_array, $template);
            }

            foreach ($json_array as $json_item) {

                if ( ! file_exists(get_template_directory() . '/acf-json/' . $json_item))

                    copy(dirname(get_theme_root()) . '/acf-ffc-config/acf-json/' . $json_item, get_template_directory() . '/acf-json/' . $json_item);
            }

            foreach ($templates_array as $template_item) {

                if ( ! file_exists(get_template_directory() . '/' . $template_item))

                    copy(dirname(get_theme_root()) . '/acf-ffc-config/' . $template_item, get_template_directory() . '/' . $template_item);
            }
        }
    }

    /**
     * AJAX
     */
    public function acf_ffc_callCreateNewGroup() {

        $data = sanitize_text_field($_POST['data']);
        $this->acf_ffc_createNewGroup($data);
        wp_die();
    }

    /**
     * @param $data
     */
    function acf_ffc_createNewGroup($data) {

        parse_str($data, $data);

        // Set names, slugs, keys, dirs
        $this->acf_ffc_setBasics($data);

        // Generating files
        $this->acf_ffc_generateJson($data);
        $this->acf_ffc_generatePhpBody($data);
        $this->acf_ffc_setTemplate($this->acf_ffc_getAcfPhp());

        // Move Files
        $this->acf_ffc_moveTemplate($this->acf_ffc_getTemplate());
        $this->acf_ffc_moveJson($this->acf_ffc_getAcfJson());
        $this->acf_ffc_copyIndexToJson();
    }

    /**
     * AJAX DELETE
     */
    public function acf_ffc_callCreateNewDelete() {

        $data = $_POST['data'];

        if (is_array($data)) {

            $this->acf_ffc_createNewDelete($data);
        }
        wp_die();
    }

    public function acf_ffc_createNewDelete($data) {

        parse_str($data, $data);
        $filename = sanitize_text_field($data['filename']);

        if (file_exists(get_template_directory() . '/' . $filename)) {

            unlink(get_template_directory() . '/' . $filename);
        }

        if (file_exists(__DIR__ . '/temp/' . $filename)) {

            unlink(__DIR__ . '/temp/' . $filename);
        }
    }

    /**
     * @param $data
     */
    public function acf_ffc_setBasics($data) {

        // Set names and slugs
        $this->acf_ffc_checkTemplateNameExists($data);
        $this->acf_ffc_setTemplateNameSlug($this->acf_ffc_generateSlug());
        $this->acf_ffc_setTemplateName($this->acf_ffc_generateUniqueName($this->acf_ffc_getTemplateName()));
        $this->acf_ffc_setTemplateNameSlug($this->acf_ffc_generateUniqueSlug($this->template_name_slug));
        $this->acf_ffc_setAcfKey('group_' . uniqid());
        $this->acf_ffc_setAcfKeyTwo('field_' . uniqid());
        // Set required directories
        $this->acf_ffc_setAcfDir();
        $this->acf_ffc_setConfigDir();
    }

    /**
     * @param $template_json
     */
    public function acf_ffc_moveJson($template_json) {

        file_put_contents(get_template_directory() . '/acf-json/' . $this->acf_ffc_getAcfKeyTwo() . '.json', $template_json);

        file_put_contents(dirname(get_theme_root()) . '/acf-ffc-config/acf-json/' . $this->acf_ffc_getAcfKeyTwo() . '.json', $template_json);
    }

    /**
     * @param $template
     */
    public function acf_ffc_moveTemplate($template) {

        file_put_contents(get_template_directory() . '/' . $this->acf_ffc_getTemplateNameSlug() . '.php', $template);

        file_put_contents(dirname(get_theme_root()) . '/acf-ffc-config/' . $this->acf_ffc_getTemplateNameSlug() . '.php', $template);
    }

    /**
     * Create index file in acf-json Folder
     */
    public function acf_ffc_copyIndexToJson() {

        if ( ! file_exists(get_template_directory() . '/acf-json/index.php')) {

            file_put_contents(get_template_directory() . '/acf-json/index.php', '<?php // Silence is golden');
        }
    }

    /**
     * @param $data
     */
    public function acf_ffc_checkTemplateNameExists($data) {

        if ($data) {
            if (key_exists('acf_ffc_template_name', $data) && ! empty($data['acf_ffc_template_name'])) {

                $name = filter_var($data['acf_ffc_template_name'], FILTER_SANITIZE_STRING);
                $this->acf_ffc_setTemplateName($name);
            }
        }
    }

    /**
     * Create acf_json folder if doesn't exists
     */
    public function acf_ffc_setTempDir() {

        if ( ! is_dir(__DIR__ . '/temp/acf-json')) {
            mkdir(__DIR__ . '/temp/acf-json', 0777, true);
        }
    }

    public function dd() {
        echo '<pre>';
        $vars = func_get_args();
        call_user_func_array('var_dump', $vars);
        echo '</pre>';
        die;
    }

    public function acf_ffc_setAcfDir() {

        if ( ! is_dir(get_template_directory() . '/acf-json/')) {
            mkdir(get_template_directory() . '/acf-json/');
        }
    }

    /**
     * @param $data
     */
    public function acf_ffc_generatePhpBody($data) {

        $this->acf_ffc_checkFormPhp($data);
        $this->acf_ffc_setHeader($this->acf_ffc_renderHeader());
        $this->acf_ffc_setFooter($this->acf_ffc_renderFooter());

        // acf.php header
        $acf_php = $this->acf_ffc_getHeader();

        // acf.php partials
        $acf_php .= $this->acf_ffc_getParagraph();
        $acf_php .= $this->acf_ffc_getList();
        $acf_php .= $this->acf_ffc_getOneImage();
        $acf_php .= $this->acf_ffc_getPdf();
        $acf_php .= $this->acf_ffc_getTextImage();
        $acf_php .= $this->acf_ffc_getTwoImages();
        $acf_php .= $this->acf_ffc_getThreeImages();

        // acf.php footer
        $acf_php .= $this->acf_ffc_getFooter();

        $this->acf_ffc_setAcfPhp($acf_php);
    }

    /**
     * @param $data
     */
    public function acf_ffc_checkFormPhp($data) {

        $this->acf_ffc_inputCheck($data, 'acf_ffc_paragraph', 'acf_ffc_setParagraph', 'acf_ffc_renderParagraph');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_list', 'acf_ffc_setList', 'acf_ffc_renderList');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_one_image', 'acf_ffc_setOneImage', 'acf_ffc_renderOneImage');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_pdf', 'acf_ffc_setPdf', 'acf_ffc_renderPdf');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_text_image', 'acf_ffc_setTextImage', 'acf_ffc_renderTextImage');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_two_images', 'acf_ffc_setTwoImages', 'acf_ffc_renderTwoImages');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_three_images', 'acf_ffc_setThreeImages', 'acf_ffc_renderThreeImages');
    }

    /**
     * @param $data
     */
    public function acf_ffc_generateJson($data) {

        $this->acf_ffc_checkFormJson($data);
        $this->acf_ffc_setHeaderJson($this->acf_ffc_renderHeaderJson());
        $this->acf_ffc_setFooterJson($this->acf_ffc_renderFooterJson());

        $acf_json = $this->acf_ffc_getHeaderJson();

        // acf.json partials
        $acf_json .= $this->acf_ffc_getParagraphJson();
        $acf_json .= $this->acf_ffc_getListJson();
        $acf_json .= $this->acf_ffc_getOneImageJson();
        $acf_json .= $this->acf_ffc_getPdfJson();
        $acf_json .= $this->acf_ffc_getTextImageJson();
        $acf_json .= $this->acf_ffc_getTwoImagesJson();
        $acf_json .= $this->acf_ffc_getThreeImagesJson();

        // Now trimming the whole json file
        $acf_json = $this->acf_ffc_trimJson($acf_json);

        $acf_json .= $this->acf_ffc_getFooterJson();

        $this->acf_ffc_setAcfJson($acf_json);
    }

    /**
     * @param $data
     */
    public function acf_ffc_checkFormJson($data) {

        $this->acf_ffc_inputCheck($data, 'acf_ffc_paragraph', 'acf_ffc_setParagraphJson', 'acf_ffc_renderParagraphJson');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_list', 'acf_ffc_setListJson', 'acf_ffc_renderListJson');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_one_image', 'acf_ffc_setOneImageJson', 'acf_ffc_renderOneImageJson');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_pdf', 'acf_ffc_setPdfJson', 'acf_ffc_renderPdfJson');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_text_image', 'acf_ffc_setTextImageJson', 'acf_ffc_renderTextImageJson');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_two_images', 'acf_ffc_setTwoImagesJson', 'acf_ffc_renderTwoImagesJson');
        $this->acf_ffc_inputCheck($data, 'acf_ffc_three_images', 'acf_ffc_setThreeImagesJson', 'acf_ffc_renderThreeImagesJson');
    }

    public function acf_ffc_inputCheck($data, $parameterName, $setter, $renderer) {

        if (key_exists($parameterName, $data) && $data[$parameterName] == 1) {

            $this->$setter($this->$renderer());
        }
    }

    /**
     * @param $json
     * @return string
     */
    public function acf_ffc_trimJson($json) {

        $r = rtrim(rtrim($json), ',');

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderHeaderJson() {

        $r = '{' . PHP_EOL;
        $r .= "\t" . '"key": "';
        $r .= $this->acf_ffc_getAcfKey();
        $r .= '",' . PHP_EOL;
        $r .= "\t" . '"title": "Flexible content",' . PHP_EOL;
        $r .= "\t" . '"fields": [' . PHP_EOL;
        $r .= "\t\t" . '{' . PHP_EOL;
        $r .= "\t\t\t" . '"key": "';
        $r .= $this->acf_ffc_getAcfKeyTwo();
        $r .= '",' . PHP_EOL;
        $r .= "\t\t\t" . '"label": "Flexible Content",' . PHP_EOL;
        $r .= "\t\t\t" . '"name": "flexible_content",' . PHP_EOL;
        $r .= "\t\t\t" . '"type": "flexible_content",' . PHP_EOL;
        $r .= "\t\t\t" . '"instructions": "",' . PHP_EOL;
        $r .= "\t\t\t" . '"required": 0,' . PHP_EOL;
        $r .= "\t\t\t" . '"conditional_logic": 0,' . PHP_EOL;
        $r .= "\t\t\t" . '"wrapper": {' . PHP_EOL;
        $r .= "\t\t\t" . '"width": "",' . PHP_EOL;
        $r .= "\t\t\t" . '"class": "",' . PHP_EOL;
        $r .= "\t\t\t" . '"id": ""' . PHP_EOL;
        $r .= "\t\t\t" . '},' . PHP_EOL;
        $r .= "\t\t\t" . '"button_label": "Add New Content",' . PHP_EOL;
        $r .= "\t\t\t" . '"min": "",' . PHP_EOL;
        $r .= "\t\t\t" . '"max": "",' . PHP_EOL;
        $r .= "\t\t\t" . '"layouts": [' . PHP_EOL;

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderFooterJson() {

        $r = "\n\r";
        $r .= "\t\t\t" . ']' . PHP_EOL;
        $r .= "\t\t" . '}' . PHP_EOL;
        $r .= "\t" . '],' . PHP_EOL;
        $r .= "\t" . '"location": [' . PHP_EOL;
        $r .= "\t\t\t" . '[' . PHP_EOL;
        $r .= "\t\t" . '{' . PHP_EOL;
        $r .= "\t" . '"param": "page_template",' . PHP_EOL;
        $r .= "\t" . '"operator": "==",' . PHP_EOL;
        $r .= "\t" . '"value": "';
        $r .= $this->acf_ffc_getTemplateNameSlug() . '.php';
        $r .= '"' . PHP_EOL;
        $r .= "\t\t\t" . '}' . PHP_EOL;
        $r .= "\t\t" . ']' . PHP_EOL;
        $r .= "\t" . '],' . PHP_EOL;
        $r .= "\t" . '"menu_order": 0,' . PHP_EOL;
        $r .= "\t" . '"position": "normal",' . PHP_EOL;
        $r .= "\t" . '"style": "default",' . PHP_EOL;
        $r .= "\t" . '"label_placement": "top",' . PHP_EOL;
        $r .= "\t" . '"instruction_placement": "label",' . PHP_EOL;
        $r .= "\t" . '"hide_on_screen": "",' . PHP_EOL;
        $r .= "\t" . '"active": 1,' . PHP_EOL;
        $r .= "\t" . '"description": ""' . PHP_EOL;
        $r .= '}';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderTwoImagesJson() {

        $r = '           {' . PHP_EOL;
        $r .= '            "key": "' . uniqid() . '",' . PHP_EOL;
        $r .= '            "name": "two_images",
            "label": "Two Images",
            "display": "table",
            "sub_fields": [
              {
                "return_format": "array",
                "preview_size": "thumbnail",
                "library": "all",
                "min_width": "",
                "min_height": "",
                "min_size": "",
                "max_width": "",
                "max_height": "",
                "max_size": "",
                "mime_types": "",' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "Image 1",
                "name": "photo_one",
                "type": "image",
                "instructions": "",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              },
              {
                "return_format": "array",
                "preview_size": "thumbnail",
                "library": "all",
                "min_width": "",
                "min_height": "",
                "min_size": "",
                "max_width": "",
                "max_height": "",
                "max_size": "",
                "mime_types": "",' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "Image 2",
                "name": "photo_two",
                "type": "image",
                "instructions": "",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              }
            ],
            "min": "",
            "max": ""
          },';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderThreeImagesJson() {

        $r = '           {' . PHP_EOL;
        $r .= '          "key": "' . uniqid() . '",' . PHP_EOL;
        $r .= '          "name": "three_images",
          "label": "Three Images",
          "display": "table",
          "sub_fields": [
            {
              "return_format": "array",
              "preview_size": "thumbnail",
              "library": "all",
              "min_width": "",
              "min_height": "",
              "min_size": "",
              "max_width": "",
              "max_height": "",
              "max_size": "",
              "mime_types": "",' . PHP_EOL;
        $r .= '              "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '              "label": "Image",
              "name": "photo_one",
              "type": "image",
              "instructions": "",
              "required": 0,
              "conditional_logic": 0,
              "wrapper": {
                "width": "",
                "class": "",
                "id": ""
              }
            },
            {
              "return_format": "array",
              "preview_size": "thumbnail",
              "library": "all",
              "min_width": "",
              "min_height": "",
              "min_size": "",
              "max_width": "",
              "max_height": "",
              "max_size": "",
              "mime_types": "",' . PHP_EOL;
        $r .= '              "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '              "label": "Image 2",
              "name": "photo_two",
              "type": "image",
              "instructions": "",
              "required": 0,
              "conditional_logic": 0,
              "wrapper": {
                "width": "",
                "class": "",
                "id": ""
              }
            },
            {
              "return_format": "array",
              "preview_size": "thumbnail",
              "library": "all",
              "min_width": "",
              "min_height": "",
              "min_size": "",
              "max_width": "",
              "max_height": "",
              "max_size": "",
              "mime_types": "",' . PHP_EOL;
        $r .= '              "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '              "label": "Image 3",
              "name": "photo_three",
              "type": "image",
              "instructions": "",
              "required": 0,
              "conditional_logic": 0,
              "wrapper": {
                "width": "",
                "class": "",
                "id": ""
              }
            }
          ],
          "min": "",
          "max": ""
        },';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderTextImageJson() {

        $r = '           {' . PHP_EOL;
        $r .= '            "key": "' . uniqid() . '",' . PHP_EOL;
        $r .= '            "name": "text_image",
            "label": "Text with image aside",
            "display": "table",
            "sub_fields": [
              {
                "tabs": "all",
                "toolbar": "full",
                "media_upload": 1,
                "default_value": "",
                "delay": 0,' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "Paragraph",
                "name": "paragraph",
                "type": "wysiwyg",
                "instructions": "",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              },
              {
                "return_format": "array",
                "preview_size": "thumbnail",
                "library": "all",
                "min_width": "",
                "min_height": "",
                "min_size": "",
                "max_width": "",
                "max_height": "",
                "max_size": "",
                "mime_types": "",' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "Image",
                "name": "image",
                "type": "image",
                "instructions": "",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              },
              {
                "multiple": 0,
                "allow_null": 0,
                "choices": {
                  "left": "Align image left",
                  "right": "Align image right"
                },
                "default_value": [],
                "ui": 0,
                "ajax": 0,
                "placeholder": "",
                "return_format": "value",' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "Align Image",
                "name": "align_image",
                "type": "select",
                "instructions": "left : Align image left\r\nright : Align image right",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              }
            ],
            "min": "",
            "max": ""
          },';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderParagraphJson() {

        $r = '           {' . PHP_EOL;
        $r .= '            "key": "' . uniqid() . '",' . PHP_EOL;
        $r .= '            "name": "paragraph",
            "label": "Paragraph",
            "display": "block",
            "sub_fields": [
              {
                "tabs": "all",
                "toolbar": "full",
                "media_upload": 1,
                "default_value": "",
                "delay": 0,' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "Paragraph",
                "name": "paragraph",
                "type": "wysiwyg",
                "instructions": "",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              }
            ],
            "min": "",
            "max": ""
          },
';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderPdfJson() {

        $r = '           {' . PHP_EOL;
        $r .= '            "key": "' . uniqid() . '",' . PHP_EOL;
        $r .= '            "name": "pdf",
            "label": "PDF",
            "display": "block",
            "sub_fields": [
              {
                "sub_fields": [
                  {
                    "return_format": "url",
                    "library": "all",
                    "min_size": "",
                    "max_size": "",
                    "mime_types": "",' . PHP_EOL;
        $r .= '                    "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                    "label": "File",
                    "name": "file",
                    "type": "file",
                    "instructions": "",
                    "required": 0,
                    "conditional_logic": 0,
                    "wrapper": {
                      "width": "",
                      "class": "",
                      "id": ""
                    }
                  },
                  {
                    "default_value": "",
                    "maxlength": "",
                    "placeholder": "",
                    "prepend": "",
                    "append": "",' . PHP_EOL;
        $r .= '                    "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                    "label": "Document Name",
                    "name": "documentName",
                    "type": "text",
                    "instructions": "",
                    "required": 0,
                    "conditional_logic": 0,
                    "wrapper": {
                      "width": "",
                      "class": "",
                      "id": ""
                    }
                  }
                ],
                "min": 0,
                "max": 0,
                "layout": "table",
                "button_label": "",
                "collapsed": "",' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "File",
                "name": "file",
                "type": "repeater",
                "instructions": "",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              }
            ],
            "min": "",
            "max": ""
          },';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderListJson() {

        $r = '           {' . PHP_EOL;
        $r .= '            "key": "' . uniqid() . '",' . PHP_EOL;
        $r .= '            "name": "list",
            "label": "List",
            "display": "block",
            "sub_fields": [
              {
                "sub_fields": [
                  {
                    "default_value": "",
                    "maxlength": "",
                    "placeholder": "",
                    "prepend": "",
                    "append": "",' . PHP_EOL;
        $r .= '                    "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                    "label": "Title",
                    "name": "title",
                    "type": "text",
                    "instructions": "",
                    "required": 0,
                    "conditional_logic": 0,
                    "wrapper": {
                      "width": "",
                      "class": "",
                      "id": ""
                    }
                  },
                  {
                    "tabs": "all",
                    "toolbar": "basic",
                    "media_upload": 1,
                    "default_value": "",
                    "delay": 0,' . PHP_EOL;
        $r .= '                    "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                    "label": "List Content",
                    "name": "list_content",
                    "type": "wysiwyg",
                    "instructions": "",
                    "required": 0,
                    "conditional_logic": 0,
                    "wrapper": {
                      "width": "",
                      "class": "",
                      "id": ""
                    }
                  }
                ],
                "min": 0,
                "max": 0,
                "layout": "table",
                "button_label": "",
                "collapsed": "",' . PHP_EOL;
        $r .= '                "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '                "label": "Repeater",
                "name": "list_repeater",
                "type": "repeater",
                "instructions": "",
                "required": 0,
                "conditional_logic": 0,
                "wrapper": {
                  "width": "",
                  "class": "",
                  "id": ""
                }
              }
            ],
            "min": "",
            "max": ""
          },';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderOneImageJson() {

        $r = '           {' . PHP_EOL;
        $r .= '          "key": "' . uniqid() . '",' . PHP_EOL;
        $r .= '          "name": "one_image",
          "label": "One image",
          "display": "block",
          "sub_fields": [
            {
              "return_format": "array",
              "preview_size": "thumbnail",
              "library": "all",
              "min_width": "",
              "min_height": "",
              "min_size": "",
              "max_width": "",
              "max_height": "",
              "max_size": "",
              "mime_types": "",' . PHP_EOL;
        $r .= '              "key": "';
        $r .= 'field_' . uniqid() . '",' . PHP_EOL;
        $r .= '              "label": "Photo",
              "name": "photo",
              "type": "image",
              "instructions": "",
              "required": 0,
              "conditional_logic": 0,
              "wrapper": {
                "width": "",
                "class": "",
                "id": ""
              }
            }
          ],
          "min": "",
          "max": ""
        },
';

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderHeader() {

        $r = '<?php' . PHP_EOL;
        $r .= "\n\r";
        $r .= '/* Template Name:';
        $r .= $this->acf_ffc_getTemplateName();
        $r .= '*/' . PHP_EOL;
        $r .= "\n\r";
        $r .= '?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= '<?php get_header() ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= '<?php if (have_posts()) : while (have_posts()) :' . PHP_EOL;
        $r .= 'the_post();' . PHP_EOL;
        $r .= "\n\r";
        $r .= 'the_content(); ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "<?php if (have_rows('flexible_content')): ?>" . PHP_EOL;
        $r .= "\n\r";
        $r .= "<div class=\"container acfFfcFlexy\">";
        $r .= "\n\r";
        $r .= "\t<?php while (have_rows('flexible_content')) : the_row(); ?>" . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderFooter() {

        $r = "\n\r";
        $r .= "<?php endwhile; // End general while flexy content has rows ?>" . PHP_EOL;
        $r .= "\n\r";
        $r .= "</div>";
        $r .= "\n\r";
        $r .= "<?php endif; // End general if flexy content has rows ?>" . PHP_EOL;
        $r .= "\n\r";
        $r .= "<?php endwhile; ?>" . PHP_EOL;
        $r .= "<?php endif; ?>" . PHP_EOL;
        $r .= "\n\r";
        $r .= "<?php" . PHP_EOL;
        $r .= "get_footer();" . PHP_EOL;

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderParagraph() {

        $r = "<?php if (get_row_layout() == 'paragraph') { ?>" . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<div class="paragraph row">' . PHP_EOL;
        $r .= "\t\t" . '<div class="col-lg-12">' . PHP_EOL;
        $r .= "\t\t\t<?php the_sub_field('paragraph'); ?>" . PHP_EOL;
        $r .= "\t\t" . "</div>" . PHP_EOL;
        $r .= "\t" . "</div>" . PHP_EOL;
        $r .= "\n\r";
        $r .= "<?php } ?>" . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderList() {

        $r = '<?php if (get_row_layout() == "list") { ?>' . PHP_EOL;
        $r .= "\t" . '<?php if (have_rows("list_repeater")): ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<ul class=" list_standard">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<?php while (have_rows("list_repeater")):' . PHP_EOL;
        $r .= "\t\t\t\tthe_row(); ?>" . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t" . "<li>" . PHP_EOL;
        $r .= "\t\t\t\t\t" . '<h4><strong><?php the_sub_field("title"); ?></strong></h4>' . PHP_EOL;
        $r .= "\t\t\t\t\t" . '<span class="content_list"><?php the_sub_field("list_content"); ?></span>' . PHP_EOL;
        $r .= "\t\t\t\t" . '</li>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<?php endwhile; ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</ul>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<?php endif; ?>' . PHP_EOL;
        $r .= '<?php } ?>' . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderOneImage() {

        $r = '<?php if (get_row_layout() == "one_image") { ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<div class="image_box row">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<?php' . PHP_EOL;
        $r .= "\t\t" . '$photo = get_sub_field("photo");' . PHP_EOL;
        $r .= "\t\t" . '$url = $photo["url"];' . PHP_EOL;
        $r .= "\t\t" . '$title = $photo["title"];' . PHP_EOL;
        $r .= "\t\t" . '$alt = $photo["alt"];' . PHP_EOL;
        $r .= "\t\t" . '?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-12">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<img alt="<?php echo $alt; ?>" title="<?php echo $title; ?>" src="<?php echo $url; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= '<?php } ?>' . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderPdf() {

        $r = '<?php if (get_row_layout() == "pdf") { ?>' . PHP_EOL;
        $r .= "\t" . '<?php if (have_rows("file")): ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="documentList row">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<div class="col-lg-12">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t" . '<ul>';
        $r .= "\n\r";
        $r .= "\t\t\t\t\t" . '<?php while (have_rows("file")): the_row(); ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t\t\t" . '<li class="pdf">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t\t\t\t" . '<a target="_blank" href="<?php the_sub_field(\'file\'); ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t\t\t\t\t" . '<i class="fa fa-file-pdf-o" aria-hidden="true"></i><?php the_sub_field("documentName"); ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t\t\t\t" . '</a>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t\t\t" . '</li>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t\t" . '<?php endwhile ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t\t" . '</ul>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= '<?php endif; ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= '<?php } ?>' . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderTextImage() {

        $r = '<?php if (get_row_layout() == "text_image") { ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<div class="text_image row">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-6 col-xs-12 <?php echo get_sub_field(\'align_image\') ?> aligned_image">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<?php' . PHP_EOL;
        $r .= "\t\t\t" . '$photo = get_sub_field("image");' . PHP_EOL;
        $r .= "\t\t\t" . '$url = $photo["url"];' . PHP_EOL;
        $r .= "\t\t\t" . '$title = $photo["title"];' . PHP_EOL;
        $r .= "\t\t\t" . '$alt = $photo["alt"];' . PHP_EOL;
        $r .= "\t\t\t" . '?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<img alt="<?php echo $alt; ?>" title="<?php echo $title; ?>" src="<?php echo $url; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<?php' . PHP_EOL;
        $r .= "\t\t" . '$float_class = "right";' . PHP_EOL;
        $r .= "\t\t" . 'if (get_sub_field(\'align_image\') == "right") {' . PHP_EOL;
        $r .= "\t\t\t" . '$float_class = "left";' . PHP_EOL;
        $r .= "\t\t" . '} ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-6 col-xs-12 aligned_text <?php echo $float_class; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<?php the_sub_field(\'paragraph\'); ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\t" . '</div><!--End of text_image -->' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<?php } ?>' . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderTwoImages() {

        $r = '<?php if (get_row_layout() == "two_images") { ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<div class="row">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<?php' . PHP_EOL;
        $r .= "\t\t" . '// The first image data' . PHP_EOL;
        $r .= "\t\t" . '$photo_one = get_sub_field(\'photo_one\');' . PHP_EOL;
        $r .= "\t\t" . '$url_one = $photo_one[\'url\'];' . PHP_EOL;
        $r .= "\t\t" . '$title_one = $photo_one[\'title\'];' . PHP_EOL;
        $r .= "\t\t" . '$alt_one = $photo_one[\'alt\'];' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '// The second image data' . PHP_EOL;
        $r .= "\t\t" . '$photo_two = get_sub_field(\'photo_two\');' . PHP_EOL;
        $r .= "\t\t" . '$url_two = $photo_two[\'url\'];' . PHP_EOL;
        $r .= "\t\t" . '$title_two = $photo_two[\'title\'];' . PHP_EOL;
        $r .= "\t\t" . '$alt_two = $photo_two[\'alt\'];' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-6 col-xs-12 pb20">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<img alt="<?php echo $alt_one; ?>" title="<?php echo $title_one; ?>" src="<?php echo $url_one; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-6 col-xs-12 pb20">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<img alt="<?php echo $alt_two; ?>" title="<?php echo $title_two; ?>" src="<?php echo $url_two; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<?php } ?>' . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_renderThreeImages() {

        $r = '<?php if (get_row_layout() == "three_images") { ?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<div class="row">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<?php' . PHP_EOL;
        $r .= "\t\t" . '// The first image data' . PHP_EOL;
        $r .= "\t\t" . '$photo_one = get_sub_field(\'photo_one\');' . PHP_EOL;
        $r .= "\t\t" . '$url_one = $photo_one[\'url\'];' . PHP_EOL;
        $r .= "\t\t" . '$title_one = $photo_one[\'title\'];' . PHP_EOL;
        $r .= "\t\t" . '$alt_one = $photo_one[\'alt\'];' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '// The second image data' . PHP_EOL;
        $r .= "\t\t" . '$photo_two = get_sub_field(\'photo_two\');' . PHP_EOL;
        $r .= "\t\t" . '$url_two = $photo_two[\'url\'];' . PHP_EOL;
        $r .= "\t\t" . '$title_two = $photo_two[\'title\'];' . PHP_EOL;
        $r .= "\t\t" . '$alt_two = $photo_two[\'alt\'];' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '// The third image data' . PHP_EOL;
        $r .= "\t\t" . '$photo_three = get_sub_field(\'photo_three\');' . PHP_EOL;
        $r .= "\t\t" . '$url_three = $photo_three[\'url\'];' . PHP_EOL;
        $r .= "\t\t" . '$title_three = $photo_three[\'title\'];' . PHP_EOL;
        $r .= "\t\t" . '$alt_three = $photo_three[\'alt\'];' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '?>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-4 col-xs-12 pb20">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<img alt="<?php echo $alt_one; ?>" title="<?php echo $title_one; ?>" src="<?php echo $url_one; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-4 col-xs-12 pb20">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<img alt="<?php echo $alt_two; ?>" title="<?php echo $title_two; ?>" src="<?php echo $url_two; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '<div class="col-lg-4 col-xs-12 pb20">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t\t" . '<img alt="<?php echo $alt_three; ?>" title="<?php echo $title_three; ?>" src="<?php echo $url_three; ?>">' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '</div>' . PHP_EOL;
        $r .= "\n\r";
        $r .= "\t" . '<?php } ?>' . PHP_EOL;
        $r .= "\n\r";

        return $r;
    }

    /**
     * @return string
     */
    public function acf_ffc_generateSlug() {

        return strtolower(str_replace(' ', '-', $this->acf_ffc_getTemplateName()));
    }

    /**
     * @param $filename
     * @return bool
     */
    public function acf_ffc_slugExists($filename) {

        $file_exists = file_exists(get_template_directory() . '/' . $filename . '.php');

        if ($file_exists) {

            return true;
        }

        return false;
    }

    /**
     * @param $input_slug
     * @return string
     */
    public function acf_ffc_generateUniqueSlug($input_slug) {

        $i = 1;
        $baseSlug = $input_slug;

        while ($this->acf_ffc_slugExists($input_slug)) {
            $input_slug = $baseSlug . "-" . $i++;
        }

        return $input_slug;
    }

    /**
     * @param $filename
     * @return bool
     */
    public function acf_ffc_nameExists($filename) {

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

    /**
     * @param $input_slug
     * @return string
     */
    public function acf_ffc_generateUniqueName($input_slug) {

        $i = 1;
        $baseSlug = $input_slug;

        while ($this->acf_ffc_nameExists($input_slug)) {
            $input_slug = $baseSlug . " " . $i++;
        }

        return $input_slug;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getParagraph() {
        return $this->paragraph;
    }

    /**
     * @param mixed $paragraph
     */
    public function acf_ffc_setParagraph($paragraph) {
        $this->paragraph = $paragraph;
    }

    /**
     * @return string
     */
    public function acf_ffc_getThreeImagesJson() {
        return $this->three_images_json;
    }

    /**
     * @param string $three_images_json
     */
    public function acf_ffc_setThreeImagesJson($three_images_json) {
        $this->three_images_json = $three_images_json;
    }

    /**
     * @return string
     */
    public function acf_ffc_getList() {
        return $this->list;
    }

    /**
     * @param string $list
     */
    public function acf_ffc_setList($list) {
        $this->list = $list;
    }

    /**
     * @return string
     */
    public function acf_ffc_getOneImage() {
        return $this->one_image;
    }

    /**
     * @param string $one_image
     */
    public function acf_ffc_setOneImage($one_image) {
        $this->one_image = $one_image;
    }

    /**
     * @return string
     */
    public function acf_ffc_getPdf() {
        return $this->pdf;
    }

    /**
     * @param string $pdf
     */
    public function acf_ffc_setPdf($pdf) {
        $this->pdf = $pdf;
    }

    /**
     * @return string
     */
    public function acf_ffc_getTextImage() {
        return $this->text_image;
    }

    /**
     * @param string $text_image
     */
    public function acf_ffc_setTextImage($text_image) {
        $this->text_image = $text_image;
    }

    /**
     * @return string
     */
    public function acf_ffc_getTwoImages() {
        return $this->two_images;
    }

    /**
     * @param string $two_images
     */
    public function acf_ffc_setTwoImages($two_images) {
        $this->two_images = $two_images;
    }

    /**
     * @return string
     */
    public function acf_ffc_getThreeImages() {
        return $this->three_images;
    }

    /**
     * @param string $three_images
     */
    public function acf_ffc_setThreeImages($three_images) {
        $this->three_images = $three_images;
    }

    /**
     * @return string
     */
    public function acf_ffc_getParagraphJson() {
        return $this->paragraph_json;
    }

    /**
     * @param string $paragraph_json
     */
    public function acf_ffc_setParagraphJson($paragraph_json) {
        $this->paragraph_json = $paragraph_json;
    }

    /**
     * @return string
     */
    public function acf_ffc_getListJson() {
        return $this->list_json;
    }

    /**
     * @param string $list_json
     */
    public function acf_ffc_setListJson($list_json) {
        $this->list_json = $list_json;
    }

    /**
     * @return string
     */
    public function acf_ffc_getOneImageJson() {
        return $this->one_image_json;
    }

    /**
     * @param string $one_image_json
     */
    public function acf_ffc_setOneImageJson($one_image_json) {
        $this->one_image_json = $one_image_json;
    }

    /**
     * @return string
     */
    public function acf_ffc_getPdfJson() {
        return $this->pdf_json;
    }

    /**
     * @param string $pdf_json
     */
    public function acf_ffc_setPdfJson($pdf_json) {
        $this->pdf_json = $pdf_json;
    }

    /**
     * @return string
     */
    public function acf_ffc_getTextImageJson() {
        return $this->text_image_json;
    }

    /**
     * @param string $text_image_json
     */
    public function acf_ffc_setTextImageJson($text_image_json) {
        $this->text_image_json = $text_image_json;
    }

    /**
     * @return string
     */
    public function acf_ffc_getTwoImagesJson() {
        return $this->two_images_json;
    }

    /**
     * @param string $two_images_json
     */
    public function acf_ffc_setTwoImagesJson($two_images_json) {
        $this->two_images_json = $two_images_json;
    }

    /**
     * @return string
     */
    public function acf_ffc_getTemplateName() {
        return $this->template_name;
    }

    /**
     * @param string $template_name
     */
    public function acf_ffc_setTemplateName($template_name) {
        $this->template_name = $template_name;
    }

    /**
     * @return string
     */
    public function acf_ffc_getTemplateNameSlug() {
        return $this->template_name_slug;
    }

    /**
     * @param string $template_name_slug
     */
    public function acf_ffc_setTemplateNameSlug($template_name_slug) {
        $this->template_name_slug = $template_name_slug;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getAcfKey() {
        return $this->acf_key;
    }

    /**
     * @param mixed $acf_key
     */
    public function acf_ffc_setAcfKey($acf_key) {
        $this->acf_key = $acf_key;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getAcfKeyTwo() {
        return $this->acf_key_two;
    }

    /**
     * @param mixed $acf_key_two
     */
    public function acf_ffc_setAcfKeyTwo($acf_key_two) {
        $this->acf_key_two = $acf_key_two;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getAcfJson() {
        return $this->acf_json;
    }

    /**
     * @param mixed $acf_json
     */
    public function acf_ffc_setAcfJson($acf_json) {
        $this->acf_json = $acf_json;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getAcfPhp() {
        return $this->acf_php;
    }

    /**
     * @param mixed $acf_php
     */
    public function acf_ffc_setAcfPhp($acf_php) {
        $this->acf_php = $acf_php;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getTemplate() {
        return $this->template;
    }

    /**
     * @param $template
     * @internal param $body
     */
    public function acf_ffc_setTemplate($template) {

        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getHeader() {
        return $this->header;
    }

    /**
     * @param mixed $header
     */
    public function acf_ffc_setHeader($header) {
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getFooter() {
        return $this->footer;
    }

    /**
     * @param mixed $footer
     */
    public function acf_ffc_setFooter($footer) {
        $this->footer = $footer;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getHeaderJson() {
        return $this->header_json;
    }

    /**
     * @param mixed $header_json
     */
    public function acf_ffc_setHeaderJson($header_json) {
        $this->header_json = $header_json;
    }

    /**
     * @return mixed
     */
    public function acf_ffc_getFooterJson() {
        return $this->footer_json;
    }

    /**
     * @param mixed $footer_json
     */
    public function acf_ffc_setFooterJson($footer_json) {
        $this->footer_json = $footer_json;
    }

    private function acf_ffc_setConfigDir() {

        if ( ! is_dir(dirname(get_theme_root()) . '/acf-ffc-config/')) {

            mkdir(dirname(get_theme_root()) . '/acf-ffc-config/');
        }

        if ( ! is_dir(dirname(get_theme_root()) . '/acf-ffc-config/acf-json/')) {

            mkdir(dirname(get_theme_root()) . '/acf-ffc-config/acf-json');
        }
    }
}

