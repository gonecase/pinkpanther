<?php
/**
* Create Logo Setting and Upload Control
*/
function pink_panther_new_customizer_settings($wp_customize) {
  // add a setting for the site logo
  $wp_customize->add_setting('pink_panther_logo');
  // Add a control to upload the logo
  $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'pink_panther_logo',
    array(
    'label' => 'Upload Logo',
    'section' => 'title_tagline',
    'settings' => 'pink_panther_logo',
    ))
  );
}
add_action('customize_register', 'pink_panther_new_customizer_settings');