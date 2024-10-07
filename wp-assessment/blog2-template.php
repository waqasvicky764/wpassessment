<?php
/**
 * Template Name: Blog 2
 * Description: A custom template for displaying specific content.
 */
?>

<?php get_header(); ?>

<div class="container">
    
    <div class="custom-content">
        <!-- Your custom content goes here -->
        <h1><?php the_title(); ?></h1>
        <?php
         echo 'here we can show or add custom section and functionality by using this Blog 2 template';
        ?>
    </div>
</div>

<?php get_footer(); ?>
