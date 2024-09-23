<?php
get_header();
?>

<main>
    <div class="container">

        <article class="movies">
            <div class="flex-box category-latest-6">               
                <!-- Show 6 posts, offset 0 (due to "custom_archive_query function" I had to use WP_query for this section)  -->       
                <?php
                    $args1 = array_merge(
                        $wp_query->query_vars, // Use the same query vars from the main query
                        array(
                            'posts_per_page' => 6, // Show 6 posts
                            'paged' => get_query_var('paged') ? get_query_var('paged') : 1, // Handle pagination
                            'offset' => 0 // No offset
                        )
                    );
                    $query1 = new WP_Query($args1);
                ?>

                <?php if ($query1->have_posts()) : ?>
                <?php while ($query1->have_posts()) : $query1->the_post(); ?>

                    <!--link and poster-->
                    <a href="<?php echo esc_url(generate_clean_movie_url(get_the_title())); ?>">
                        <div class="image-size"
                            style="background-image: url('<?php echo wp_kses_post( get_field('poster_image') );?> ');">
                        </div>
                    </a>

                    <?php endwhile; 
                        wp_reset_postdata();
                    else : ?>
                    <p>
                        <?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?>
                    </p>
                <?php endif; ?>
            </div>
        </article>
     

        <!-- movies list header -->    
        <section class="flex header">
            <h1 class="main-header">
                <?php echo do_shortcode('[custom_archive_title]'); ?>              
            </h1>

            <!-- Total posts uploaded today across all post types -->
            <a href="#" class="btn btn__green disabled-link">
                <span class="two-chars">
                    <?php 
                        // Get today's date in 'Y-m-d' format
                        $todays_date = date('Y-m-d');

                        // Define the post types you want to check
                        $post_types = array('movies', 'series', 'documentaries', 'tv-shows', 'animation', 'anime');

                        $total_posts_today = 0;

                        // Loop through each post type and count posts uploaded today
                        foreach ($post_types as $post_type) {
                            $args = array(
                                'post_type'      => $post_type,          // Specify the post type
                                'posts_per_page' => -1,                  // Retrieve all posts
                                'fields'         => 'ids',               // We only need the IDs to count
                                'date_query'     => array(               // Filter by today's date
                                    array(
                                        'after'     => $todays_date . ' 00:00:00',
                                        'before'    => $todays_date . ' 23:59:59',
                                        'inclusive' => true,
                                    ),
                                ),
                            );

                            $query = new WP_Query($args);
                            $total_posts_today += $query->found_posts; // Accumulate the total count
                        }

                        // Display the total number of posts uploaded today across all specified post types
                        echo '+' . $total_posts_today . ' Today'; 
                    ?> 
                </span> 
            </a>

            <!-- Total posts across all post types, categories, and tags -->
            <a href="#" class="btn btn__blue disabled-link">
                <span>
                    <?php 
                        // Define the post types to check
                        $post_types = array('movies', 'series', 'documentaries', 'tv-shows', 'animation', 'anime');

                        // Initialize total post count
                        $total_posts = 0;

                        // Get the current queried category and tag (if any)
                        $category_slug = is_category() ? get_queried_object()->slug : '';
                        $tag_slug = is_tag() ? get_queried_object()->slug : '';

                        // Loop through each post type and accumulate the total post count
                        foreach ($post_types as $post_type) {
                            // Build the tax query
                            $tax_query = array('relation' => 'AND');

                            // If a category is present, add it to the tax query
                            if (!empty($category_slug)) {
                                $tax_query[] = array(
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => $category_slug,
                                    'operator' => 'IN',
                                );
                            }

                            // If a tag is present, add it to the tax query
                            if (!empty($tag_slug)) {
                                $tax_query[] = array(
                                    'taxonomy' => 'post_tag',
                                    'field'    => 'slug',
                                    'terms'    => $tag_slug,
                                    'operator' => 'IN',
                                );
                            }

                            // Build the query arguments
                            $args = array(
                                'post_type'      => $post_type,    // Specify the post type
                                'posts_per_page' => -1,            // Retrieve all posts
                                'fields'         => 'ids',         // We only need the IDs to count
                                'tax_query'      => count($tax_query) > 1 ? $tax_query : '', // Only add tax query if category/tag exists
                            );

                            // Debug: Display the query arguments (you can comment this out later)
                            // echo '<pre>'; print_r($args); echo '</pre>';

                            // Query the posts
                            $post_query = new WP_Query($args);

                            // Accumulate the total count
                            $total_posts += $post_query->found_posts;
                        }

                        // Display the total number of posts
                        echo 'Total: ' . $total_posts;
                    ?>
                </span>
            </a>  
        </section>


        <article id="movies">
            <div class="grid list">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>

                        <div class="grid__item flex">
                            <!--link and poster-->
                            <a href="<?php echo esc_url(generate_clean_movie_url(get_the_title())); ?>">
                                <section class="grid__item--poster">
                                    <div class="image-size"
                                        style="background-image: url('<?php echo esc_url(get_field('poster_image')); ?>');">
                                    </div>
                                </section>
                            </a>

                            <section class="flex grid__item--info f-dir-col">
                                <!--title-->
                                <a href="<?php echo esc_url(generate_clean_movie_url(get_the_title())); ?>">
                                    <h3><?php echo esc_html(get_field('titlle')); ?></h3>
                                </a>

                                <ul>
                                    <!--release year + link to archives-->
                                    <li>
                                        <a class="disabled-link" href="<?php echo esc_url(site_url('/category/' . get_field('realease_year'))); ?>">
                                            <?php echo esc_html(get_field('realease_year')); ?>
                                        </a>
                                    </li>
                                    <!--IMBd score-->
                                    <li class="imdb"><span>IMDb&nbsp;<?php echo esc_html(get_field('imdb')); ?></span></li>
                                    <!--total likes-->
                             
                                    <li>Watched: <?php echo esc_html(get_field('total_likes')); ?></li>
                                    <!--available languages-->
                                    <li>
                                        <?php 
                                            $languages = get_field('languages');

                                            if ($languages) {
                                                foreach ($languages as $language) {
                                                    $language = strtolower(trim($language)); // Normalize language string
                                                    $lang_code = substr($language, 0, 2); // First two letters for matching
                                        ?>
                                        <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                        <?php
                                                }
                                            } else {
                                                echo 'N/A';
                                            }
                                        ?>
                                    </li>
                                </ul>
                                <!--modal button-->
                                <span class="watch-button"><button class="myBtn" data-modal="myModal1">Watch Now</button></span>
                                
                            </section>
                        </div>

                    <?php endwhile; 
                    else : 
                    ?>
                    <p>
                        <?php esc_html_e('Sorry, no posts matched your criteria.'); ?>
                    </p>
                <?php endif; ?>
                
            </div>
            <!-- Pagination with custom class -->
            <div class="post-paginaition">
                    <?php
                        the_posts_pagination(array(
                            'mid_size'  => 2,
                            'prev_text' => __('« Previous', 'textdomain'),
                            'next_text' => __('Next »', 'textdomain'),
                            'screen_reader_text' => ' ',
                        ));
                    ?>
                </div>

            <!-- The Modal -->
            <div id="myModal1" class="modal">
                <!-- Modal content -->
                <div class="modal-content">
                    <div class="modal-header">
                        <span class="close">&times;</span>
                        <h2>You need to be logged in to watch this movie..</h2>
                    </div>

                    <div class="modal-body flex">
                        <img src="<?php echo home_url('/wp-content/uploads/2024/08/stop.png'); ?>" alt="stop sign">
                        <!-- HTML FORM  -->
                        <?php dynamic_sidebar( 'sign_in_html_form' ) ?>
                    </div>
                </div>

            </div>
        </article>    
</main>

<?php
// Include WordPress footer
get_footer();
?>