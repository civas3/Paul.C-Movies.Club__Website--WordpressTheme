<?php get_header(); ?>

<main>     
    <div class="container">

        <!-- virus movies -->
        <article id="most-recent" class="most-recent">
            <!-- image of lab -->
            <div class="flex box most-recent_mob_col">
                <div href="#" class="flex-box__item col-lg-6 width-50 background">
                <section class="background__info">
                    <p>
                    Best movies about viruses and pandemics. See how people change
                    their lives after a global disease outbreak. Can what is shown
                    in movies turn into reality?
                    </p>
                </section>
                </div>
                <!-- Movies list -->
                <?php echo do_shortcode('[virus_movies]'); ?>
            </div>
        </article>

        <!-- latest movies -->
        <article id="movies">
            <!-- movies list header -->
            <div class="flex-box">
                <div class="col-lg-8">
                    <section class="flex header">  
                        <h2>Latest Movies</h2>
                        <!-- total movies uploaded today -->
                        <a href="#" class="btn btn__green disabled-link">
                            <span class="two-chars">
                                <!-- Today uploaded movies posts count-->
                                <?php echo do_shortcode('[count_posts_today]'); ?> 
                            </span> 
                        </a>    
                        <a href="<?php echo site_url('/category/') . 'all-movies'; ?>" class="btn btn__blue disabled-link">
                            <span>
                                <?php echo do_shortcode('[count_posts_total]');?>                                  
                            </span> 
                        </a>
                    </section>
                </div>

                <div class="col-lg-4">
                    <section class="flex header">
                        <a href="<?php echo site_url('/category/') . 'movies'; ?>" class="btn btn__transparent"><span>All</span></a>
                    </section>
                </div>
            </div>

            <!-- Movies list -->
            <?php echo do_shortcode('[latest_movies]'); ?>

        </article>

        <!-- latest series -->
        <article id="series">
            <!-- movies list header -->
            <div class="flex-box">
                <div class="col-lg-8">
                    <section class="flex header"> 
                        <h2>Latest Series</h2>
                        <!-- Today uploaded movies posts count-->
                        <a href="#" class="btn btn__green disabled-link">
                            <span class="two-chars">
                            <?php echo do_shortcode('[count_posts_today post_type="series"]'); ?> 
                            </span> 
                        </a>    
                        <a href="<?php echo site_url('/category/') . 'series'; ?>" class="btn btn__blue disabled-link">
                            <span>
                                <?php echo do_shortcode('[count_posts_total post_type="series"]');?>
                            </span> 
                        </a>                    
                    </section>
                </div>

                <div class="col-lg-4">
                    <section class="flex header">
                    <a href="<?php echo site_url('/category/') . 'series'; ?>" class="btn btn__transparent"><span>All</span></a>
                    </section>
                </div>
            </div>

            <!-- Movies list -->
            <?php echo do_shortcode('[latest_series]'); ?>
        </article>

        <article id="most-recent-two" class="most-recent">
            <div class="flex-box">

            <!--latest documentieres section-->
            <section class="flex-box__item col-lg-6 f-dir-col">
                <!--section header  -->
                <div class="flex header">            
                    <h2>Latest Documentaries</h2>
                    <a href="#" class="btn btn__green disabled-link">
                        <span class="two-chars">
                            <?php echo do_shortcode('[count_posts_today post_type="documentries"]'); ?> 
                        </span> 
                    </a>    

                </div>
                <!-- Documentries list -->
                <?php echo do_shortcode('[latest_documentries]'); ?>
            </section>

            <!--latest shows section-->
            <section class="flex-box__item col-lg-6 f-dir-col">
                <!--section header  -->
                <section class="flex header">
                    <h2>Latest TV-Shows</h2>
                    <a href="#" class="btn btn__green disabled-link">
                        <span class="two-chars">
                            <?php echo do_shortcode('[count_posts_today post_type="tv-shows"]'); ?> 
                        </span> 
                    </a>    

                </section>
                <!-- tv-show list -->
                <?php echo do_shortcode('[latest_tvshows]'); ?>
            </section>
            </div>
       </article>

        <!-- latest animation -->
        <article id="animation">
            <!-- movies list header -->
            <div class="flex-box">
                <div class="col-lg-8">
                    <section class="flex header">
                        <h2>Latest Animation</h2>
                        <a href="#" class="btn btn__green disabled-link">
                            <span class="two-chars">
                              <?php echo do_shortcode('[count_posts_today post_type="animation"]'); ?> 
                            </span> 
                        </a>    
                        <a href="<?php echo site_url('/category/') . 'animation'; ?>" class="btn btn__blue disabled-link">
                            <span>
                              <?php echo do_shortcode('[count_posts_total post_type="animation"]');?>                                
                            </span> 
                        </a>                    
                    </section>
                </div>

                <div class="col-lg-4">
                    <section class="flex header">
                    <a href="<?php echo site_url('/category/') . 'animation'; ?>" class="btn btn__transparent"><span>All</span></a>
                    </section>
                </div>
            </div>

            <!-- Movies list -->
            <?php echo do_shortcode('[latest_animation]'); ?>
        </article>
        
        <!-- latest animation -->
        <article id="anime">
            <!-- movies list header -->
            <div class="flex-box">
                <div class="col-lg-8">
                    <section class="flex header">
                        <h2>Latest Anime</h2>
                        <!-- total movies uploaded today -->
                        <a href="#" class="btn btn__green disabled-link">
                            <span class="two-chars">
                                 <?php echo do_shortcode('[count_posts_today post_type="anime"]'); ?> 
                            </span> 
                        </a>    
                        <a href="<?php echo site_url('/category/') . 'anime'; ?>" class="btn btn__blue disabled-link">
                            <span>
                                <?php echo do_shortcode('[count_posts_total post_type="anime"]');?>
                            </span> 
                        </a>
                    </section>
                </div>

                <div class="col-lg-4">
                    <section class="flex header">
                    <a href="<?php echo site_url('/category/') . 'anime'; ?>" class="btn btn__transparent"><span>All</span></a>
                    </section>
                </div>
            </div>

            <!-- Movies list -->
            <?php echo do_shortcode('[latest_anime]'); ?>
        </article>

        <!-- The Modal -->
        <div id="myModal1" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <div class="modal-header">
                <span class="close">&times;</span>
                <h2>You need to be logged in to watch this movie.</h2>
            </div>

            <div class="modal-body flex">
                <img src="<?php echo home_url('/wp-content/uploads/2024/08/stop.png'); ?>" alt="stop sign">
                      <!-- HTML FORM  -->
             <?php dynamic_sidebar( 'sign_in_html_form' ) ?>
            </div>
        </div>

    </div>
</main>


<?php get_footer(); ?>

</div>
<!--wrap ends here-->