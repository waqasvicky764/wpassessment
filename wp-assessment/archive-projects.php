<?php get_header(); ?>



<div class="container mt-5">
    <div class="row">
        <!-- Search Filter Column -->
        <div class="col-md-4">
            <h2>Filter Projects</h2>
            <form id="project-filter" method="GET" action="<?php echo esc_url(home_url('/projects')); ?>">
                <div class="mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo isset($_GET['start_date']) ? esc_attr($_GET['start_date']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo isset($_GET['end_date']) ? esc_attr($_GET['end_date']) : ''; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <!-- Projects Column -->
        <div class="col-md-8">
            <h2>Projects</h2>
            <div class="row">
                <?php
                // Query arguments
                $args = array(
                    'post_type'      => 'projects',
                    'posts_per_page' => -1, // Show all projects
                );

                // Add date filters if set
                if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
                    $args['meta_query'][] = array(
                        'key'     => 'project_start_date',
                        'value'   => $_GET['start_date'],
                        'compare' => '>=',
                        'type'    => 'DATE',
                    );
                }
                if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
                    $args['meta_query'][] = array(
                        'key'     => 'project_end_date',
                        'value'   => $_GET['end_date'],
                        'compare' => '<=',
                        'type'    => 'DATE',
                    );
                }

                $projects = new WP_Query($args);

                if ($projects->have_posts()) :
                    while ($projects->have_posts()) : $projects->the_post(); ?>
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?php the_title(); ?></h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endwhile;
                else : ?>
                    <p>No projects found.</p>
                <?php endif;

                // Reset Post Data
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
