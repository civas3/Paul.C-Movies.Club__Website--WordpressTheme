<?php
/* Template Name: Movie Detail */
get_header();
?>

<main> <!--this?-->
<div class="container">



 <?php
// Ensure this is a single movie page
if (get_query_var('movie')) {
    $movie_title = sanitize_text_field(get_query_var('movie'));
    $movie_title = str_replace('-', ' ', $movie_title); // Convert slug back to original title
    $movie_info = get_movie_info($movie_title);

    
    //fetching movie background with Google Custom Search API
    $background_image_url = fetch_background_image_url($movie_title); 

    if ($movie_info && $movie_info['Response'] === 'True') {
        ?>
        <article class="the-movie" id="the-movie">
            <div class="flex-box" id="movie-bg" style="<?php if ($background_image_url): ?>background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo esc_url($background_image_url); ?>');<?php endif; ?>">
                <div class="flex-box__item col-lg-6">
                    <div class="flex-box__item--vote">  
                        
                    
                    
                        <?php 
                            $imdb_id = $movie_info['imdbID'];  // Fetch the imdbID from the OMDB API response
                        ?>
                        <button id="thumbsUpBtn_<?php echo $imdb_id; ?>" onclick="likeMovie('<?php echo $imdb_id; ?>')">
                            <img src="<?php echo home_url('/wp-content/uploads/2024/08/thumbs-up.png'); ?>" alt="Thumbs Up">
                            <?php
                                $movie_likes = get_option('movie_likes_' . $imdb_id, 0);
                                echo '<span id="thumbsUp_' . $imdb_id . '">' . esc_html($movie_likes) . '</span>';
                            ?>
                        </button>




                        <button id="thumbsDownBtn_<?php echo $imdb_id; ?>" onclick="dislikeMovie('<?php echo $imdb_id; ?>')">
                            <img src="<?php echo home_url('/wp-content/uploads/2024/08/thumbs-down.png'); ?>" alt="Thumbs Down">
                            <?php
                                $movie_dislikes = get_option('movie_dislikes_' . $imdb_id, 0);
                                echo '<span id="thumbsDown_' . $imdb_id . '">' . esc_html($movie_dislikes) . '</span>';
                            ?>
                        </button>
                    </div>
                    <h1 class="single-page-movie-title"><?php echo esc_html($movie_info['Title']); ?></h1>
                    <div class="flex-box__item--info">
                        <p><?php echo esc_html($movie_info['Year']); ?></p>
                        <p><?php echo esc_html($movie_info['Runtime']); ?></p>
                        <span>HD</span>
                        <span><?php echo esc_html($movie_info['imdbRating']); ?></span>
                    </div>
                    <div class="flex-box__item--description">
                        <p><?php echo esc_html($movie_info['Plot']); ?></p>
                    </div>
                    <div class="flex-box__item--cast">
                        <ul>
                            <li>Cast: <?php echo esc_html($movie_info['Actors']); ?></li>
                            <li>Director: <?php echo esc_html($movie_info['Director']); ?></li>
                            <li><?php echo esc_html($movie_info['Genre']); ?></li>
                        </ul>
                    </div>

                    <div class="flex-box__item--functions flex">
                        <!-- WATCH NOW Button to open the modal -->
                            <button class="myBtn" data-modal="myModal1">WATCH NOW</button>
                              <!-- WATCH TRAILER Button to open the modal -->
                              <button class="myBtn" data-modal="myModal2" >WATCH TRAILER</button>
                    </div>
                  
                                     <!--WATCH NOW The Modal -->
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
                          
                            <!-- WATCH TRAILER The Modal -->    
                            <div id="myModal2" class="modal">
                                <!-- Modal content -->
                                <div class="modal-content ">
                                    <span class="close">&times;</span>
                                    <?php
                                    // Fetching movie trailer with YOUTUBE DATA API V3
                                    $trailer_id = fetch_youtube_trailer($movie_info['Title'], $movie_info['Year']);
                                    if ($trailer_id != 'No trailer found' && $trailer_id != 'Error fetching trailer') {
                                        echo '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . esc_attr($trailer_id) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                    } else {
                                        echo '<p>' . esc_html($trailer_id) . '</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                                 
                 

                </div>
            </div>
        </article>
        <?php
    } else {
        echo '<p>Movie not found.</p>';
        
        
    }
} else {
    echo '<p>No movie specified.</p>';
    
}
?>

</div>

<?php get_footer(); ?>






</main>

  




