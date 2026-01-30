<?php
define('KADENCE_CHILD_VERSION', time());
add_action('wp_enqueue_scripts', 'kadence_child_enqueue_scripts');
function kadence_child_enqueue_scripts()
{

    wp_enqueue_style('kadence-child-style', get_stylesheet_uri(), array(), KADENCE_CHILD_VERSION);
}

require_once get_stylesheet_directory() . '/inc/vc-addons/index.php';
add_action('ppw_sitewide_above_password_form_container', 'add_sitewide_custom_content');

function add_sitewide_custom_content()
{
    echo '<h2>We\'re performing scheduled maintenance to improve your experience.</h2>';
    echo '<p>The site will be back online shortly — thank you for your patience.</p>';
}
