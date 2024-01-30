<?php

// Theme Option
add_theme_support('menus');

// Menu
register_nav_menus(

    array(

        'header-menu' => 'Header Menu Location',
        'mobile-menu' => 'Mobile Menu Location',
    )
);

function enqueue_tailwind_css()
{
    wp_enqueue_style('tailwind', 'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_tailwind_css');

