<?php

if ( ! function_exists('listTemplates')) {
    function listTemplates() {

        $all_templates = wp_get_theme()->get_page_templates();


        $templates_array = array();
        $templates = scandir(dirname(get_theme_root()) . '/acf-ffc-config');

        foreach ($all_templates as $template_file => $template_name) {

            if (in_array($template_file, $templates)) {

                $templates_array[$template_file] = $template_name;
            }
        }

        return $templates_array;
    }
}
