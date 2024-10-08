<?php
get_header(); // Include the header

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        $project_url = get_post_meta(get_the_ID(), 'project_url', true);
        $project_description = get_post_meta(get_the_ID(), 'project_description', true);
        $project_start_date = get_post_meta(get_the_ID(), 'project_start_date', true);
        $project_end_date = get_post_meta(get_the_ID(), 'project_end_date', true);
        ?>
        
        <div class="container mt-5 debug-container">
            <div class="row align-items-center projectrow">
                <div class="col-lg-8 col-md-6">
                    <div class="wcardtext mb-4">
                        <div class="wcard-body">
                            <h1 class="card-title"><?php the_title(); ?></h1>
                            <p class="card-text"><strong>Project URL:</strong> <a href="<?php echo esc_url($project_url); ?>" target="_blank"><?php echo esc_html($project_url); ?></a></p>
                            <p class="card-text"><strong>Start Date:</strong> <?php echo esc_html($project_start_date); ?></p>
                            <p class="card-text"><strong>End Date:</strong> <?php echo esc_html($project_end_date); ?></p>
                            <div class="card-text">
                                <h5>Description</h5>
                                <?php echo esc_html($project_description); ?>
                            </div>
                            <a href="<?php echo esc_url(home_url('/projects')); ?>" class="btn btn-primary mt-3">Back to Projects</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="wcardimg mb-4">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <img src="<?php echo get_the_post_thumbnail_url(); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
    endwhile;
else :
    echo '<p>No project found.</p>';
endif;

get_footer(); // Include the footer
