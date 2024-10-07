<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
    <div class="container">
        <!-- Bootstrap Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <!-- Logo -->
                <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                    <?php bloginfo('name'); ?>
                </a>

                <!-- Toggler/collapsible Button for Mobile -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'wp-assessment'); ?>">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Links and Dropdown -->
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container' => false, // Remove the default <div> wrapper
                        'menu_class' => 'navbar-nav ml-auto', // Bootstrap classes for styling (use ml-auto for right alignment)
                        'fallback_cb' => '__return_false', // Prevent fallback to default menu
                        'depth' => 2, // Allows for dropdowns
                        'walker' => new WP_Bootstrap_Navwalker(), // Ensure you have a walker for dropdowns
                    ));
                    ?>
                </div>
            </div>
        </nav>
    </div>
</header>



