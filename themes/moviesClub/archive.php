<?php
get_header();
?>

<main>
    <div class="container">     

        <!-- movies list header -->    
        <section class="flex header">
            <h1 class="main-header">
                <?php echo do_shortcode('[custom_archive_title]'); ?>              
            </h1> 
        </section>


        <article id="movies">
            <div class="grid list">
                <?php
                    // Ensure we're using the main query
                    if (have_posts()) :
                        while (have_posts()) : the_post();
                ?>

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
                                    // Get the 'languages' ACF field, which is a checkbox field and returns an array
                                    $languages = get_field('languages');

                                    if ($languages) {
                                        // Loop through the array and output each selected value with the corresponding flag
                                        foreach ($languages as $language) {
                                            $language = strtolower(trim($language)); // Normalize language string

                                            // Determine the language code for the flag (assuming your flags are styled based on a 2-letter language code)
                                            $lang_code = substr($language, 0, 2); // First two letters, for matching

                                            // Display the flag for each language
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

                <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                ?>
                <p><?php esc_html_e('Sorry, no posts matched your criteria.'); ?></p>
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
            </div>
        </article>   
</main>

<?php
// Include WordPress footer
get_footer();
?>