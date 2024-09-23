<?php
/*
Plugin Name: OMDb Movie Info
Description: A plugin to fetch movie information from the OMDb API.
Version: 1.0
Author: Your Name
*/


/******__________ OMDb API __________******/
define('OMDB_API_KEY', 'fdd11ab');

function get_movie_info($movie_title) {
    $url = 'http://www.omdbapi.com/?apikey=' . OMDB_API_KEY . '&t=' . urlencode($movie_title);
    $response = wp_remote_get($url);
    
    if (is_wp_error($response)) {
        return []; // Return an empty array on API error
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Check if the response is valid and contains the movie data
    if (isset($data['Response']) && $data['Response'] === 'True') {
        return $data; // Return the full data
    } else {
        return []; // Return an empty array if the movie is not found or an error occurred
    }
}


/******___________ WP DASHBOARD SETTINGS __________******/
function movies_settings_menu() {
    add_options_page('Movies Settings', 'Movies Settings', 'manage_options', 'movies-settings', 'movies_settings_page');
}
add_action('admin_menu', 'movies_settings_menu');

/******___________ WP DASHBOARD DISPLAY SETTING PAGE __________******/
function movies_settings_page() {
    ?>
    <div class="wrap">
        <h1>Movies Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('movies_settings_group');
            do_settings_sections('movies-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}


/******___________ REGISTER THE SETTING FOR EACH CATEGORY __________******/
function movies_settings_init() {
//virus movies titles
register_setting('movies_settings_group', 'virus_movies_titles');
add_settings_section('virus_movies_section', 'Viruses and Pandemics Movies', null, 'movies-settings');
add_settings_field('virus_movies_titles', 'Enter Titles', 'virus_movies_titles_callback', 'movies-settings', 'virus_movies_section');

//latest movies
register_setting('movies_settings_group', 'latest_movies');
add_settings_section('latest_movies_section', 'Latest Movies', null, 'movies-settings');
add_settings_field('latest_movies', 'Enter Titles', 'latest_movies_callback', 'movies-settings', 'latest_movies_section');


//latest series
register_setting('movies_settings_group', 'latest_series');
add_settings_section('latest_series_section', 'Latest Series', null, 'movies-settings');
add_settings_field('latest_series', 'Enter Titles', 'latest_series_callback', 'movies-settings', 'latest_series_section');


//latest documentries
register_setting('movies_settings_group', 'latest_documentries');
add_settings_section('latest_documentries_section', 'Latest documentries', null, 'movies-settings');
add_settings_field('latest_documentries', 'Enter Titles', 'latest_documentries_callback', 'movies-settings', 'latest_documentries_section');



//latest tv-shows
register_setting('movies_settings_group', 'latest_tvshows');
add_settings_section('latest_tvshows_section', 'Latest TV-shows', null, 'movies-settings');
add_settings_field('latest_tvshows', 'Enter Titles', 'latest_tvshows_callback', 'movies-settings', 'latest_tvshows_section');



//latest animation
register_setting('movies_settings_group', 'latest_animation');
add_settings_section('latest_animation_section', 'Latest Animation', null, 'movies-settings');
add_settings_field('latest_animation', 'Enter Titles', 'latest_animation_callback', 'movies-settings', 'latest_animation_section');
//latest anime
register_setting('movies_settings_group', 'latest_anime');
add_settings_section('latest_anime_section', 'Latest Anime', null, 'movies-settings');
add_settings_field('latest_anime', 'Enter Titles', 'latest_anime_callback', 'movies-settings', 'latest_anime_section');

}
add_action('admin_init', 'movies_settings_init');



/******___________  DISPLAY INPUT FIELDS FOR EACH CATEGORY __________******/
// virus movies
function virus_movies_titles_callback() {
    $virus_movies_titles = get_option('virus_movies_titles');
    ?>
    <textarea name="virus_movies_titles" rows="3" cols="50" class="large-text code"><?php echo esc_textarea($virus_movies_titles); ?></textarea>
    <p>Enter virus-related movie titles separated by commas.</p>
    <?php
}

// latest movies
function latest_movies_callback() {
    $latest_movies = get_option('latest_movies');
    ?>
    <textarea name="latest_movies" rows="3" cols="50" class="large-text code"><?php echo esc_textarea($latest_movies); ?></textarea>
    <p>Enter the latest movie titles separated by commas.</p>
    <?php
}

// latest series
function latest_series_callback() {
    $latest_series = get_option('latest_series');
    ?>
    <textarea name="latest_series" rows="3" cols="50" class="large-text code"><?php echo esc_textarea($latest_series); ?></textarea>
    <p>Enter the latest series titles separated by commas.</p>
    <?php
}

// latest documentries
function latest_documentries_callback() {
    $latest_documentries = get_option('latest_documentries');
    ?>
    <textarea name="latest_documentries" rows="3" cols="50" class="large-text code"><?php echo esc_textarea($latest_documentries); ?></textarea>
    <p>Enter the latest documentries titles separated by commas.</p>
    <?php
}

// latest tv-shows
function latest_tvshows_callback() {
    $latest_tvshows = get_option('latest_tvshows');
    ?>
    <textarea name="latest_tvshows" rows="3" cols="50" class="large-text code"><?php echo esc_textarea($latest_tvshows); ?></textarea>
    <p>Enter the latest tv-shows titles separated by commas.</p>
    <?php
}

// latest animation
function latest_animation_callback() {
    $latest_animation = get_option('latest_animation');
    ?>
    <textarea name="latest_animation" rows="3" cols="50" class="large-text code"><?php echo esc_textarea($latest_animation); ?></textarea>
    <p>Enter the latest animation titles separated by commas.</p>
    <?php
}

// latest anime
function latest_anime_callback() {
    $latest_anime = get_option('latest_anime');
    ?>
    <textarea name="latest_anime" rows="3" cols="50" class="large-text code"><?php echo esc_textarea($latest_anime); ?></textarea>
    <p>Enter the latest anime titles separated by commas.</p>
    <?php
}


/******__________ FETCH FUNCTIONS __________******/

//virus movies
function fetch_virus_movies($limit = 1) {
    $movies = [];
    $virus_movie_titles = get_option('virus_movies_titles'); // Get the predefined list of virus-related movie titles

    if ($virus_movie_titles) {
        $titles = explode(',', $virus_movie_titles); // Convert the comma-separated string to an array
        shuffle($titles); // Shuffle the titles to simulate randomness

        foreach ($titles as $title) {
            $movie_info = get_movie_info(trim($title));

            // Add the movie info directly without any conditions
            $movies[] = $movie_info;
            if (count($movies) >= $limit) {
                break;
            }
        }
    }

    return $movies;
}

//latest movies
// Fetch latest movies using OMDb API
function fetch_latest_movies($limit = 1) {
    $movies = [];
    $latest_movie_titles = get_option('latest_movies'); // Get the predefined list of latest movie titles

    if ($latest_movie_titles) {
        $titles = explode(',', $latest_movie_titles); // Convert the comma-separated string to an array
        shuffle($titles); // Shuffle the titles to simulate randomness

        foreach ($titles as $title) {
            $movie_info = get_movie_info(trim($title));

            // Check if we received valid movie information
            if (is_array($movie_info) && isset($movie_info['Title'], $movie_info['Poster'], $movie_info['Year'], $movie_info['imdbRating'])) {
                $movies[] = $movie_info;
                if (count($movies) >= $limit) {
                    break;
                }
            }
        }
    }

    return $movies;
}
//latest series
function fetch_latest_series($limit = 1) {
    $movies = [];
    $latest_series_titles = get_option('latest_series'); // Get the predefined list of latest series-related titles

    if ($latest_series_titles) {
        $titles = explode(',', $latest_series_titles); // Convert the comma-separated string to an array
        shuffle($titles); // Shuffle the titles to simulate randomness

        foreach ($titles as $title) {
            $movie_info = get_movie_info(trim($title));

            // Add the movie info directly without any conditions
            $movies[] = $movie_info;
            if (count($movies) >= $limit) {
                break;
            }
        }
    }

    return $movies;
}
//latest documentries
function fetch_latest_documentries($limit = 1) {
    $movies = [];
    $latest_documentries_titles = get_option('latest_documentries'); // Get the predefined list of latest documentries-related titles

    if ($latest_documentries_titles) {
        $titles = explode(',', $latest_documentries_titles); // Convert the comma-separated string to an array
        shuffle($titles); // Shuffle the titles to simulate randomness

        foreach ($titles as $title) {
            $movie_info = get_movie_info(trim($title));

                // Check if we received valid movie information
                if (is_array($movie_info) && isset($movie_info['Title'], $movie_info['Poster'], $movie_info['Year'], $movie_info['imdbRating'])) {
                    $movies[] = $movie_info;
                    if (count($movies) >= $limit) {
                        break;
                    }
                }
        }
    }

    return $movies;
}
//latest tv-shows
function fetch_latest_tvshows($limit = 1) {
    $movies = [];
    $latest_tvshows_titles = get_option('latest_tvshows'); // Get the predefined list of latest tvshows-related titles

    if ($latest_tvshows_titles) {
        $titles = explode(',', $latest_tvshows_titles); // Convert the comma-separated string to an array
        shuffle($titles); // Shuffle the titles to simulate randomness

        foreach ($titles as $title) {
            $movie_info = get_movie_info(trim($title));

            // Check if we received valid movie information
            if (is_array($movie_info) && isset($movie_info['Title'], $movie_info['Poster'], $movie_info['Year'], $movie_info['imdbRating'])) {
                $movies[] = $movie_info;
                if (count($movies) >= $limit) {
                    break;
                }
            }
        }
    }

    return $movies;
}

//latest animation
function fetch_latest_animation($limit = 1) {
    $movies = [];
    $latest_animation_titles = get_option('latest_animation'); // Get the predefined list of latest animation-related titles

    if ($latest_animation_titles) {
        $titles = explode(',', $latest_animation_titles); // Convert the comma-separated string to an array
        shuffle($titles); // Shuffle the titles to simulate randomness

        foreach ($titles as $title) {
            $movie_info = get_movie_info(trim($title));

            // Add the movie info directly without any conditions
            $movies[] = $movie_info;
            if (count($movies) >= $limit) {
                break;
            }
        }
    }

    return $movies;
}
//latest anime
function fetch_latest_anime($limit = 1  ) {
    $movies = [];
    $latest_anime_titles = get_option('latest_anime'); // Get the predefined list of latest anime-related titles

    if ($latest_anime_titles) {
        $titles = explode(',', $latest_anime_titles); // Convert the comma-separated string to an array
        shuffle($titles); // Shuffle the titles to simulate randomness

        foreach ($titles as $title) {
            $movie_info = get_movie_info(trim($title));

            // Add the movie info directly without any conditions
            $movies[] = $movie_info;
            if (count($movies) >= $limit) {
                break;
            }
        }
    }

    return $movies;
}


/*
// archive page
function theme_fetch_all_movies($limit = 48, $page = 1) {
    $movies = [];
    $titles = get_option('movies_titles'); // Assuming this holds the movie titles

    if (!$titles) {
        return [
            'movies' => [],
            'total_movies' => 0,
            'total_pages' => 1,
            'current_page' => $page
        ];
    } else {
        $titles = explode(',', $titles); // Convert the comma-separated string to an array
    }

    // Reverse the titles array to get latest movies first
    // $titles = array_reverse($titles);

    // Calculate offset for pagination
    $offset = ($page - 1) * $limit;

    // Ensure the limit is within bounds
    $limit = min($limit, count($titles) - $offset);

    // Fetch movies with pagination
    foreach (array_slice($titles, $offset, $limit) as $title) {
        $movie_info = get_movie_info(trim($title));
        if (
            is_array($movie_info) &&
            !empty($movie_info['Poster']) &&
            $movie_info['Poster'] != 'N/A' &&
            !empty($movie_info['imdbRating']) &&
            $movie_info['imdbRating'] != 'N/A' &&
            in_array($movie_info['Language'], ['English', 'Spanish', 'French'])
        ) {
            // Only add movie if valid poster URL, IMDb rating, and language are present
            $movies[] = $movie_info;
        }
    }

    // Return movies and pagination data
    return [
        'movies' => $movies,
        'total_movies' => count($titles),
        'total_pages' => ceil(count($titles) / $limit),
        'current_page' => $page
    ];
}

*/
// archive page - function.php code
/*
    function movies_archive_shortcode($atts) {
        // Extract attributes
        $atts = shortcode_atts([
            'page' => 1
        ], $atts, 'movies_archive');
    
        $page = (int) $atts['page'];
        $limit = 48;
    
        $data = theme_fetch_all_movies($limit, $page); // Updated function name
        $movies = $data['movies'];
        $total_pages = $data['total_pages'];
        $current_page = $data['current_page'];
    
        ob_start();
        ?>
   
   <!-- 6 latest movies -->
    <article class="movies movies-archive recent-added-movies">
        <div class="flex-box">
            <?php foreach (array_slice($movies, 0, 6) as $movie): ?>
                <a href="<?php echo site_url('/movie-detail/?movie=' . urlencode($movie['Title'])); ?>">
                    <div class="image-size" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></div>
                </a>
            <?php endforeach; ?>
        </div>
    </article>

    <!-- archives -->
    <article>
        <div class="grid list">
            <?php
            // Initialize counter
            $counter = 0;

            foreach ($movies as $index => $movie):
                // Skip the first 6 movies (recently added) in the grid
                if ($index < 6) {
                    continue;
                }

                // Increment the counter
                $counter++;

                // Check if it's the 7th movie in the grid
                if ($counter == 7): ?>
                    <!-- Display the 7th movie differently -->
                    <div class="grid__item special flex">
                        <a href="<?php echo site_url('/movie-detail/?movie=' . urlencode($movie['Title'])); ?>">
                            <section class="grid__item--poster">
                                <div class="image-size" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></div>
                            </section>
                        </a>
                        <section class="flex grid__item--info f-dir-col">
                            <a href="<?php echo site_url('/movie-detail/?movie=' . urlencode($movie['Title'])); ?>">
                                <h3><?php echo strtoupper(esc_html($movie['Title'])); ?></h3>
                            </a>
                            <ul>
                                <li><a href="#"><?php echo esc_html($movie['Year']); ?></a></li>
                                <li class="imdb"><a href="#"><span>IMDb&nbsp;</span> <?php echo esc_html($movie['imdbRating']); ?></a></li>
                                <li>Likes: <?php echo esc_html(rand(10, 100)); ?></li>
                                <li><?php echo esc_html($movie['Language']); ?><span class="country-flag <?php echo strtolower(substr($movie['Language'], 0, 2)); ?>"> </span></li>
                            </ul>
                            <span><button class="myBtn">Watch Now</button></span>
                        </section>
                    </div>
                <?php else: ?>
                    <!-- Display the other movies normally -->
                    <div class="grid__item flex">
                        <a href="<?php echo site_url('/movie-detail/?movie=' . urlencode($movie['Title'])); ?>">
                            <section class="grid__item--poster">
                                <div class="image-size" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></div>
                            </section>
                        </a>
                        <section class="flex grid__item--info f-dir-col">
                            <a href="<?php echo site_url('/movie-detail/?movie=' . urlencode($movie['Title'])); ?>">
                                <h3><?php echo strtoupper(esc_html($movie['Title'])); ?></h3>
                            </a>
                            <ul>
                                <li><a href="#"><?php echo esc_html($movie['Year']); ?></a></li>
                                <li class="imdb"><a href="#"><span>IMDb&nbsp;</span> <?php echo esc_html($movie['imdbRating']); ?></a></li>
                                <li>Likes: <?php echo esc_html(rand(10, 100)); ?></li>
                                <li><?php echo esc_html($movie['Language']); ?><span class="country-flag <?php echo strtolower(substr($movie['Language'], 0, 2)); ?>"> </span></li>
                            </ul>
                            <span><button class="myBtn">Watch Now</button></span>
                        </section>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </article>

    <!-- Pagination -->
    <?php if ($total_pages > 1): ?>
        <div class="page-number flex pagination">
            <?php if ($current_page > 1): ?>
                <a href="<?php echo esc_url(add_query_arg('page', $current_page - 1)); ?>">&larr;</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="<?php echo esc_url(add_query_arg('page', $i)); ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <?php if ($current_page < $total_pages): ?>
                <a href="<?php echo esc_url(add_query_arg('page', $current_page + 1)); ?>">&rarr;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php
    return ob_get_clean();
}

add_shortcode('movies_archive', 'movies_archive_shortcode');
*/
    
