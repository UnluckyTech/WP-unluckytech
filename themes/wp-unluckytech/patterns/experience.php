<?php
/**
 * Title: Experience
 * Slug: unluckytech/experience
 * Categories: text, featured
 * Description: This page is used for listing IT experience.
 */
?>

<div class="main-container">

    <!-- Experience Banner -->
    <div class="main-banner" style="background-image: url('/wp-content/themes/wp-unluckytech/assets/images/bg1.png');">
        <div class="main-banner-overlay">
            <h1 class="main-title">Experience</h1>
        </div>
    </div>

    <!-- Experience Content Section -->
    <div class="main-content">

        <div class="intro-section">
            <p>With years of experience in the IT field, I have worked on various projects that have honed my skills and expertise. Here, I highlight key experiences that have contributed to my professional growth.</p>
        </div>

        <!-- Experience Entries Section -->
        <div class="experience-entries">
            <?php
            // Query posts from the 'experience' category
            $experience_query = new WP_Query(array(
                'category_name' => 'experience', // Category slug
                'posts_per_page' => -1,          // Show all posts
                'order' => 'DESC'                // Sort by most recent
            ));

            // Start the loop
            if ($experience_query->have_posts()) :
                while ($experience_query->have_posts()) : $experience_query->the_post();
            ?>
                    <!-- Experience Entry -->
                    <div class="experience-entry">
                        <h3>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h3>
                        <p>
                            <?php
                            // Get the first line of post content and remove block tags
                            $content = get_the_content();
                            $content = strip_tags($content); // Remove HTML tags
                            $content = preg_replace('/<!--(.|\s)*?-->/', '', $content); // Remove block comments
                            $content_lines = explode("\n", trim($content));

                            // Output the first line as the time spent
                            if (!empty($content_lines[0])) {
                                echo esc_html($content_lines[0]);
                            }
                            ?>
                        </p>
                        <p><?php the_excerpt(); // Display the excerpt ?></p>
                    </div>

            <?php
                endwhile;
            else :
                echo '<p>No experiences found in the "experience" category.</p>';
            endif;

            // Reset the query
            wp_reset_postdata();
            ?>
        </div>

    </div>
</div>

<!-- Add any additional necessary scripts -->
<script>
    // You can include any additional JavaScript here if needed
</script>
