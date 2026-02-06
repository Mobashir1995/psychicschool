<?php
/**
 * Password Protection Component
 * 
 * This component adds a custom content to the password protection form
 */
function add_sitewide_custom_content()
{
    echo '<h2>We\'re performing scheduled maintenance to improve your experience.</h2>';
    echo '<p>The site will be back online shortly — thank you for your patience.</p>';
}
add_action('ppw_sitewide_above_password_form_container', 'add_sitewide_custom_content');