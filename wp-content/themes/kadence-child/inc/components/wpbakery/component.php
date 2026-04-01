<?php
if (!defined('ABSPATH')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

if (!function_exists('is_plugin_active')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
if (!is_plugin_active('js_composer/js_composer.php')) {
    return;
}

/**
 * Compatibility: Masterstudy/STM used a custom VC param type "number_field".
 * Register a fallback renderer so migrated vc_map configs keep working.
 */
if (!function_exists('kadence_child_register_vc_number_field_param')) {
    function kadence_child_register_vc_number_field_param()
    {
        if (!function_exists('vc_add_shortcode_param')) {
            return;
        }

        vc_add_shortcode_param('number_field', 'kadence_child_vc_number_field_param_html');
    }
}

if (!function_exists('kadence_child_vc_number_field_param_html')) {
    /**
     * Render WPBakery "number_field" param as numeric input.
     *
     * @param array  $settings VC param settings.
     * @param string $value    Current field value.
     * @return string
     */
    function kadence_child_vc_number_field_param_html($settings, $value)
    {
        $param_name = isset($settings['param_name']) ? (string) $settings['param_name'] : '';
        $min        = isset($settings['min']) ? (string) $settings['min'] : '';
        $max        = isset($settings['max']) ? (string) $settings['max'] : '';
        $step       = isset($settings['step']) ? (string) $settings['step'] : '1';

        return sprintf(
            '<input type="number" class="wpb_vc_param_value wpb-textinput %1$s %2$s_field" name="%2$s" value="%3$s" min="%4$s" max="%5$s" step="%6$s" />',
            esc_attr($param_name),
            esc_attr($param_name),
            esc_attr((string) $value),
            esc_attr($min),
            esc_attr($max),
            esc_attr($step)
        );
    }
}

add_action('vc_before_init', 'kadence_child_register_vc_number_field_param', 5);


$vc_addons_dir = get_stylesheet_directory() . '/inc/components/wpbakery';
if (!is_dir($vc_addons_dir)) {
    return;
}

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($vc_addons_dir, RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $basename = $file->getFilename();
        if (strpos($basename, 'vc-addon-') === 0) {
            require_once $file->getPathname();
        }
    }
}