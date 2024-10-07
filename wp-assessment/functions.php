<?php
function wp_assessment_setup() {
    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus.
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'wp-assessment' ),
    ) );
}
add_action( 'after_setup_theme', 'wp_assessment_setup' );

function wp_assessment_scripts() {
    // Enqueue main stylesheet.
    wp_enqueue_style( 'wp-assessment-style', get_stylesheet_uri() );
    wp_enqueue_style( 'wp-assessment-bootstrap', get_template_directory_uri() . '/assets/bootstrap/css/bootstrap.min.css' );
    wp_enqueue_style( 'wp-assessment-fontawesome', get_template_directory_uri() . '/assets/fontawesome/fontawesome.css' );
    wp_enqueue_style( 'wp-assessment-customstyle', get_template_directory_uri() . '/assets/css/custom-style.css');

    // Enqueue custom scripts if needed.
    wp_enqueue_script( 'wp-assessment-popperscript', get_template_directory_uri() . '/assets/bootstrap/js/popper.min.js', array( 'jquery' ), true );
    wp_enqueue_script( 'wp-assessment-bootstrapscript', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap.min.js', array( 'jquery' ), true );
    wp_enqueue_script( 'wp-assessment-fontawesomescript', get_template_directory_uri() . '/assets/fontawesome/fontawesome.js', array( 'jquery' ), true );
    wp_enqueue_script( 'wp-assessment-customscript', get_template_directory_uri() . '/assets/js/custom-js.js', array( 'jquery' ), true );
}
add_action( 'wp_enqueue_scripts', 'wp_assessment_scripts' );

// Register custom Bootstrap Navwalker
require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

// Register theme menu
function wp_assessment_register_menus() {
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'wp-assessment' ),
    ) );
}
add_action( 'init', 'wp_assessment_register_menus' );
add_theme_support( 'post-thumbnails', array( 'projects' ) );


function wp_assessment_register_projects_post_type() {
    $labels = array(
        'name'               => 'Projects',
        'singular_name'      => 'Project',
        'menu_name'          => 'Projects',
        'name_admin_bar'     => 'Project',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Project',
        'new_item'           => 'New Project',
        'edit_item'          => 'Edit Project',
        'view_item'          => 'View Project',
        'all_items'          => 'All Projects',
        'search_items'       => 'Search Projects',
        'parent_item_colon'  => 'Parent Projects:',
        'not_found'          => 'No projects found.',
        'not_found_in_trash' => 'No projects found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'projects'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'thumbnail'), // Add 'editor' if you want a content editor
    );

    register_post_type('projects', $args);
}
add_action('init', 'wp_assessment_register_projects_post_type');


function add_project_meta_boxes() {
    add_meta_box(
        'project_details',
        'Project Details',
        'render_project_meta_box',
        'projects', // Custom post type
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'add_project_meta_boxes');

function render_project_meta_box($post) {
    // Use nonce for verification
    wp_nonce_field('project_meta_box_nonce', 'meta_box_nonce');

    // Retrieve existing values from the database
    $project_url = get_post_meta($post->ID, 'project_url', true);
    $project_description = get_post_meta($post->ID, 'project_description', true);
    $project_start_date = get_post_meta($post->ID, 'project_start_date', true);
    $project_end_date = get_post_meta($post->ID, 'project_end_date', true);
    ?>
    <p>
        <label for="project_url">Project URL:</label>
        <input type="text" id="project_url" name="project_url" value="<?php echo esc_attr($project_url); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="project_description">Project Description:</label>
        <input type="text" id="project_description" name="project_description" value="<?php echo esc_attr($project_description); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="project_start_date">Project Start Date:</label>
        <input type="date" id="project_start_date" name="project_start_date" value="<?php echo esc_attr($project_start_date); ?>" style="width:100%;" />
    </p>
    <p>
        <label for="project_end_date">Project End Date:</label>
        <input type="date" id="project_end_date" name="project_end_date" value="<?php echo esc_attr($project_end_date); ?>" style="width:100%;" />
    </p>
    <?php
}

function save_project_meta_box($post_id) {
    // Check nonce
    if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'project_meta_box_nonce')) {
        return;
    }

    // Save the custom fields
    if (isset($_POST['project_url'])) {
        update_post_meta($post_id, 'project_url', sanitize_text_field($_POST['project_url']));
    }
    if (isset($_POST['project_description'])) {
        update_post_meta($post_id, 'project_description', sanitize_text_field($_POST['project_description']));
    }
    if (isset($_POST['project_start_date'])) {
        update_post_meta($post_id, 'project_start_date', sanitize_text_field($_POST['project_start_date']));
    }
    if (isset($_POST['project_end_date'])) {
        update_post_meta($post_id, 'project_end_date', sanitize_text_field($_POST['project_end_date']));
    }
}
add_action('save_post', 'save_project_meta_box');



// Register a custom API endpoint for projects
function wp_assessment_register_projects_api() {
    register_rest_route('wp-assessment/v1', '/projects', array(
        'methods' => 'GET',
        'callback' => 'wp_assessment_get_projects',
        'permission_callback' => '__return_true', // Allow public access
    ));
}

// The callback function that returns project data
function wp_assessment_get_projects() {
    $args = array(
        'post_type'      => 'projects', // Your custom post type
        'posts_per_page' => -1, // Get all projects
    );

    $projects = get_posts($args);
    $data = array();

    foreach ($projects as $project) {
        $project_url = get_post_meta($project->ID, 'project_url', true);
        $project_start_date = get_post_meta($project->ID, 'project_start_date', true);
        $project_end_date = get_post_meta($project->ID, 'project_end_date', true);

        $data[] = array(
            'title'            => $project->post_title,
            'url'              => esc_url($project_url),
            'start_date'       => $project_start_date,
            'end_date'         => $project_end_date,
        );
    }

    return new WP_REST_Response($data, 200);
}

// Hook into the REST API initialization
add_action('rest_api_init', 'wp_assessment_register_projects_api');




