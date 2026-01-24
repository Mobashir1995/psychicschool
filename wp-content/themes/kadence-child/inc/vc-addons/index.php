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

$vc_addons_dir = get_stylesheet_directory() . '/inc/vc-addons';
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