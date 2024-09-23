<?php
// This function enqueues the Normalize.css for use. The first parameter is a name for the stylesheet, the second is the URL. Here we
// use an online version of the css file.
function add_normalize_CSS() {
 wp_enqueue_style( 'normalize-styles',
"https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css");
}
add_action('wp_enqueue_scripts', 'add_normalize_CSS');



/******___________ REGISTER __________ ******/
//Theme Files
function theme_files() {
    // Enqueue main stylesheet
    wp_enqueue_style('main-style', get_stylesheet_uri());

    // Enqueue jQuery from a CDN
    wp_enqueue_script('jquery-cdn', 'https://code.jquery.com/jquery-3.4.1.min.js', array(), '3.4.1', true);

    // Enqueue custom scripts
    wp_enqueue_script('menu-items-graphics', get_template_directory_uri() . '/js/menu-items-graphics.js', array('jquery-cdn'), '1.0', true);
    wp_enqueue_script('nav-filter-toggle', get_template_directory_uri() . '/js/nav-filter-toggle.js', array('jquery-cdn'), '1.0', true);
    wp_enqueue_script('mobile-menu-toggle', get_template_directory_uri() . '/js/mobile-menu-toggle.js', array('jquery-cdn'), '1.0', true);
    // Enqueue the JavaScript for the modal, making sure jQuery is a dependency and the script is loaded in the footer
    wp_enqueue_script('modal-popup', get_template_directory_uri() . '/js/modal-popup.js', array('jquery'), '1.0', true);
    // wp_enqueue_script('font-awesome', 'https://kit.fontawesome.com/a076d05399.js', array(), null, false);
    wp_enqueue_script('likesDislikes-recorder', get_template_directory_uri() . '/js/likesDislikes-recorder.js', array('jquery'), '1.0', true);
}
// Hook the theme_files function to the wp_enqueue_scripts action
add_action('wp_enqueue_scripts', 'theme_files');



//Logo
add_theme_support( 'custom-logo', array(
	'height'      => 100,
	'width'       => 400,
	'flex-height' => true,
	'flex-width'  => true,
	'header-text' => array( 'site-title', 'site-description' ),
) );

//Menu
function register_my_menus() {
    register_nav_menu('main-menu', __('Main Menu'));
    register_nav_menu('mobile-menu', __('Mobile Menu'));
}
add_action('init', 'register_my_menus');


// Hook the widget initiation and run our function
add_action( 'widgets_init', 'custom_widgets' );   
// Register a new banner simply named 'home_banner_slider'
function custom_widgets() {
    register_sidebar( array(
        'name' => 'Sign-In HTML Form',
        'id' => 'sign_in_html_form',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
        ) );
    
    register_sidebar( array(
        'name' => 'Registration HTML Form',
        'id' => 'registration_html_form',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
        ) );

    register_sidebar( array(
        'name' => 'Forgot Password HTML Form',
        'id' => 'forgotpassword_html_form',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => '',
        ) );
 
}
     

/******___________ SETTINGS __________ ******/
// API permalinks rewrite rule
//before:  Movies_club/movie-detail/?movie=John+Wick%3A+Chapter+4
//after:   Movies_club/Dune-Part-Two


// Register a custom rewrite rule for movies only, not for pages
function add_custom_rewrite_rules() {
    // Exclude existing page slugs from the rewrite rule for movies
    $pages = get_pages();
    $page_slugs = array();

    foreach ( $pages as $page ) {
        $page_slugs[] = $page->post_name;
    }

    $page_slugs_regex = implode('|', $page_slugs);

  //Only add the rewrite rule if the slug does not match any page slug
    add_rewrite_rule(
        '^((?!(' . $page_slugs_regex . '))[A-Za-z0-9-]+)/?$',
        'index.php?movie=$matches[1]&post_type=movies',
        'top'
    );
    
}
add_action('init', 'add_custom_rewrite_rules');


function add_query_vars_filter($vars) {
    $vars[] = 'post_type';
    return $vars;
}
add_filter('query_vars', 'add_query_vars_filter');


// Handle the 'movie' query variable
function add_custom_query_vars($vars) {
    $vars[] = 'movie';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');

// Load a custom template for movie posts based on the 'movie' query variable.
function load_custom_template($template) {
    if (get_query_var('movie')) {
        // Ensure it's the movie post type, not a page
        $new_template = locate_template('single-movie.php');
        if ($new_template) {
            return $new_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_custom_template');

// Modify the main query for custom post types (exclude pages)
function custom_archive_query($query) {
    // Ensure we're modifying the main query, not in admin, and exclude pages
    if ($query->is_main_query() && !is_admin() && !is_page()) {
        // Get the current page number for pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Define the post types to include (excluding 'page')
        $post_types = ['post', 'movies', 'series', 'documentries', 'tv-shows', 'animation', 'anime'];

        // Handle custom post type archives
        if ($query->is_post_type_archive()) {
            $query->set('post_type', get_query_var('post_type', $post_types));
            $query->set('posts_per_page', 16);
            $query->set('paged', $paged); // Ensure pagination works

        // Handle category archives
        } elseif ($query->is_category()) {
            $posts_per_page = 8;
            $offset = ($paged - 1) * $posts_per_page + 6; // Apply an additional offset of 6 posts
            $query->set('post_type', $post_types);
            $query->set('posts_per_page', $posts_per_page);
            $query->set('offset', $offset); // Set offset for pagination
            $query->set('paged', $paged); // Ensure pagination works

        // Handle tag archives
        } elseif ($query->is_tag()) {
            $posts_per_page = 12;
            $query->set('post_type', $post_types);
            $query->set('posts_per_page', $posts_per_page);
            $query->set('paged', $paged); // Ensure pagination works

        // Handle date archives
        } elseif ($query->is_date()) {
            $query->set('post_type', $post_types);
            $query->set('posts_per_page', 16);
            $query->set('paged', $paged); // Ensure pagination works

        // Handle combined category and tag archives for custom post type
        } elseif ($query->is_post_type_archive('movies') && $query->get('category_name') && $query->get('tag')) {
            $query->set('post_type', 'movies');
            $query->set('posts_per_page', 16);
            $query->set('category_name', $query->get('category_name'));
            $query->set('tag', $query->get('tag'));
            $query->set('paged', $paged); // Ensure pagination works

        // Handle combined category archives for custom post type
        } elseif ($query->is_post_type_archive('movies') && $query->get('category_name')) {
            $query->set('post_type', 'movies');
            $query->set('posts_per_page', 12);
            $query->set('category_name', $query->get('category_name'));
            $query->set('paged', $paged); // Ensure pagination works

        // Handle combined tag archives for custom post type
        } elseif ($query->is_post_type_archive('movies') && $query->get('tag')) {
            $query->set('post_type', 'movies');
            $query->set('posts_per_page', 12);
            $query->set('tag', $query->get('tag'));
            $query->set('paged', $paged); // Ensure pagination works

        // Handle other cases (like combined category and tag filters)
        } elseif (isset($query->query_vars['category_name']) && isset($query->query_vars['tag'])) {
            $category = $query->query_vars['category_name'];
            $tag = $query->query_vars['tag'];
            $query->set('post_type', $post_types);
            $query->set('category_name', $category);
            $query->set('tag', $tag);
            $query->set('posts_per_page', 16);
            $query->set('paged', $paged); // Ensure pagination works
        }
    }
}
add_action('pre_get_posts', 'custom_archive_query');

/* ********************* */



// Generate a clean URL slug for a movie title by replacing special characters with hyphens.
function generate_clean_movie_url($title) {
    // Replace special characters with hyphens and remove multiple hyphens
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $title); // Replace non-alphanumeric characters with hyphens
    $slug = trim($slug, '-'); // Trim any leading or trailing hyphens
    return home_url('/' . $slug); // Create the full URL
}

//Adds custom rewrite rules for specific post types with category, tag, and pagination support.
function custom_rewrite_rules() {
    $post_types = ['movies', 'series', 'tv-shows', 'documentries', 'animation', 'anime'];

    foreach ($post_types as $post_type) {
        // Custom post type archive with category
        add_rewrite_rule(
            "^{$post_type}/category/([^/]+)/?$",
            "index.php?post_type={$post_type}&category_name=\$matches[1]",
            'top'
        );

        // Custom post type archive with category and pagination
        add_rewrite_rule(
            "^{$post_type}/category/([^/]+)/page/([0-9]{1,})/?$",
            "index.php?post_type={$post_type}&category_name=\$matches[1]&paged=\$matches[2]",
            'top'
        );

        // Custom post type archive with category and tag
        add_rewrite_rule(
            "^{$post_type}/category/([^/]+)/tag/([^/]+)/?$",
            "index.php?post_type={$post_type}&category_name=\$matches[1]&tag=\$matches[2]",
            'top'
        );

        // Custom post type archive with category, tag, and pagination
        add_rewrite_rule(
            "^{$post_type}/category/([^/]+)/tag/([^/]+)/page/([0-9]{1,})/?$",
            "index.php?post_type={$post_type}&category_name=\$matches[1]&tag=\$matches[2]&paged=\$matches[3]",
            'top'
        );

        // Custom post type archive with tag only
        add_rewrite_rule(
            "^{$post_type}/tag/([^/]+)/?$",
            "index.php?post_type={$post_type}&tag=\$matches[1]",
            'top'
        );

        // Custom post type archive with tag and pagination
        add_rewrite_rule(
            "^{$post_type}/tag/([^/]+)/page/([0-9]{1,})/?$",
            "index.php?post_type={$post_type}&tag=\$matches[1]&paged=\$matches[2]",
            'top'
        );
    }
}
add_action('init', 'custom_rewrite_rules');


// Generate a dynamic archive title SHORTCODE based on the current query context (post type, category, tag, author, date).
function custom_dynamic_archive_title_shortcode() {
    ob_start(); // Start output buffering to capture the output
    
    if (is_post_type_archive()) {
        $post_type = get_post_type_object(get_post_type());

        if (is_category() && get_query_var('tag')) {
            $category = get_queried_object();
            $tag_slug = get_query_var('tag');
            $tag_obj = get_term_by('slug', $tag_slug, 'post_tag');

            if ($category && $tag_obj) {
                echo esc_html($post_type->labels->singular_name) . ' ' . esc_html($category->name) . ' ' . esc_html($tag_obj->name);
            } else {
                echo esc_html($post_type->labels->singular_name);
            }
        } elseif (is_category()) {
            $category = get_queried_object();
            echo esc_html($post_type->labels->singular_name) . ' ' . esc_html($category->name);
        } elseif (is_tag()) {
            $tag_obj = get_queried_object();
            echo esc_html($post_type->labels->singular_name) . ' ' . esc_html($tag_obj->name);
        } else {
            echo esc_html($post_type->labels->singular_name);
        }
    } elseif (is_category()) {
        echo single_cat_title('', false) . ' Category';
    } elseif (is_tag()) {
        echo single_tag_title('', false) . ' Tag';
    } elseif (is_author()) {
        the_post();
        echo 'Author: ' . get_the_author();
        rewind_posts();
    } elseif (is_day()) {
        echo 'Daily Archives: ' . get_the_date();
    } elseif (is_month()) {
        echo 'Monthly Archives: ' . get_the_date('F Y');
    } elseif (is_year()) {
        echo 'Yearly Archives: ' . get_the_date('Y');
    } elseif (is_tax()) {
        single_term_title();
    } else {
        echo 'Archives';
    }

    return ob_get_clean(); // Return the buffered output
}
add_shortcode('custom_archive_title', 'custom_dynamic_archive_title_shortcode');


// Generate a count posts published today SHORTCODE, with optional post type parameter
function count_posts_today_shortcode($atts) {
    // Extract shortcode attributes, with 'movies' as the default post type
    $atts = shortcode_atts(
        array(
            'post_type' => 'movies', // Default post type is 'movies'
        ),
        $atts
    );

    // Get today's date in 'Y-m-d' format
    $todays_date = date('Y-m-d');

    // Set up the query arguments to count posts published today
    $args = array(
        'post_type'      => $atts['post_type'],   // Use the specified or default post type
        'posts_per_page' => -1,                   // Retrieve all posts
        'fields'         => 'ids',                // We only need the IDs to count
        'date_query'     => array(                // Filter by today's date
            array(
                'after'     => $todays_date . ' 00:00:00',
                'before'    => $todays_date . ' 23:59:59',
                'inclusive' => true,
            ),
        ),
    );

    // Query for posts of the specified post type published today
    $query = new WP_Query($args);

    // Get the total number of posts published today
    $total_posts_today = $query->found_posts;

    // Return the total number of posts (this will be displayed where the shortcode is used)
    return '+' . $total_posts_today . ' Today';
}
// Register the shortcode
add_shortcode('count_posts_today', 'count_posts_today_shortcode');


// Generate a count posts totalfor a specified post type SHORTCODE, with optional post type parameter
function count_posts_total_shortcode($atts) {
    // Extract shortcode attributes, with 'movies' as the default post type
    $atts = shortcode_atts(
        array(
            'post_type' => 'movies', // Default post type is 'movies'
        ),
        $atts
    );

    // Ensure the post type exists in WordPress before querying
    if (!post_type_exists($atts['post_type'])) {
        return 'Invalid post type specified: ' . esc_html($atts['post_type']);
    }

    // Get the current queried category and tag (if any)
    $category_slug = is_category() ? get_queried_object()->slug : '';
    $tag_slug = is_tag() ? get_queried_object()->slug : '';

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
        'post_type'      => $atts['post_type'], // Use the specified or default post type
        'posts_per_page' => -1,                // Retrieve all posts
        'fields'         => 'ids',             // We only need the IDs to count
        'tax_query'      => count($tax_query) > 1 ? $tax_query : '', // Only add tax query if category/tag exists
    );

    // Query the posts
    $post_query = new WP_Query($args);

    // Get the total number of posts
    $total_posts = $post_query->found_posts;

    // Return the total number of posts (this will be displayed where the shortcode is used)
    return 'Total: ' . $total_posts;
}
// Register the shortcode
add_shortcode('count_posts_total', 'count_posts_total_shortcode');



/******___________ MOVIES OMDb API PLUGIN - Fetch and display __________ ******/
// virus-related movies   
function display_virus_movies() {
    $movies = fetch_virus_movies(); // Fetch the virus-related movies
    ob_start();
    ?>
    <div class="flex-box__item col-lg-6 flex__wrap">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <!--link-->
                <a href="<?php echo site_url('/movie-detail/?movie=' . urlencode($movie['Title'])); ?>" class="flex item width-50">
                    <!--poster-->
                    <section class="item__poster" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></section>
                    <section class="flex a-center item__info">
                        <!--title-->
                        <h3><?php echo esc_html($movie['Title']); ?></h3>
                        <p>
                             <!--release year-->
                            <?php echo esc_html($movie['Year']); ?>  
                            <!--available languages-->  
                            <?php   // Display multiple flags
                                    $languages = explode(',', $movie['Language']);
                                    foreach ($languages as $language):
                                        $language = strtolower(trim($language)); // Normalize language string
                                        
                                        // Determine the language code for the flag
                                        $lang_code = substr($language, 0, 2); // First two letters, for matching
                                        
                                        // Display flags only for English, Spanish, and French
                                        if (in_array($lang_code, ['en','spa','sp','fra','fr'])):
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                        <?php
                                        else:
                                            // Display a placeholder span with display:none for other languages
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>" style="display: none;"></span>
                                        <?php
                                        endif;
                                    endforeach;
                                ?>
                            </p>
                    </section>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No virus-related movies found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('virus_movies', 'display_virus_movies');

// latest movies 
function display_latest_movies() {
    $movies = fetch_latest_movies(); // Fetch the latest movies

    // Debugging: Check the structure of $movies
    if (!is_array($movies)) {
        $movies = []; // Ensure $movies is an array
    }

    ob_start();
    ?>
    <div class="grid list">
        <?php if (!empty($movies) && is_array($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <?php if (is_array($movie) && isset($movie['Title'], $movie['Poster'], $movie['Year'], $movie['imdbRating'], $movie['Language'])): ?>
                    <div class="grid__item flex">
                        <!--link and poster-->
                        <a href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">               
                            <section class="grid__item--poster">
                                <div class="image-size" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></div>
                            </section>
                        </a>
                        <section class="flex grid__item--info f-dir-col">
                            <!--title-->
                            <a class="item-title" href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">
                                <h3><?php echo esc_html($movie['Title']); ?></h3>          
                            </a>
                            <ul>
                                <!--release year + link to archives-->
                                <li><a class="disabled-link" href="<?php echo site_url('/category/' . esc_attr($movie['Year'])); ?>"><?php echo esc_html($movie['Year']); ?></a></li>
                                <!--IMBd score-->
                                <li class="imdb"><span>IMDb</span><?php echo esc_html($movie['imdbRating']); ?></li>
                                <!--total likes-->
                                <li>Watched:<?php echo esc_html(rand(10, 100)); ?></li>
                                <!--available languages-->
                                <li>  
                                    <?php 
                                        // Display multiple flags
                                        $languages = explode(',', $movie['Language']);
                                        foreach ($languages as $language):
                                            $language = strtolower(trim($language)); // Normalize language string
                                            
                                            // Determine the language code for the flag
                                            $lang_code = substr($language, 0, 2); // First two letters, for matching
                                            
                                            // Display flags only for English, Spanish, and French
                                            if (in_array($lang_code, ['en', 'spa', 'sp', 'fra', 'fr'])):
                                                ?>
                                                <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                            <?php
                                            else:
                                                ?>
                                                <span class="country-flag <?php echo esc_attr($lang_code); ?>" style="display: none;"></span>
                                                <?php
                                            endif;
                                        endforeach;
                                    ?> 
                                </li>
                            </ul>
                            <!--modal button-->
                            <span class="watch-button"><button class="myBtn" data-modal="myModal1">Watch Now</button></span>
                        </section>
                    </div>
                <?php else: ?>
                    <p>Oops! The "Free" API fetch didn’t go through as expected. Please refresh the page.</p>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No latest movies found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('latest_movies', 'display_latest_movies');

// latest series 
function display_latest_series() {
    $movies = fetch_latest_series(); // Fetch the latest movies
    ob_start();
    ?>
    <div class="grid list">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>

                <div class="grid__item flex">
                    <!--link and poster-->
                    <a href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">
                        <section class="grid__item--poster">
                            <div class="image-size" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></div>
                        </section>
                    </a>
                    <section class="flex grid__item--info f-dir-col">
                        <!--title-->
                        <a class="item-title" href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">
                            <h3><?php echo esc_html($movie['Title']); ?></h3>          
                        </a>
                        <ul>
                            <!--release year + link to archives-->
                            <li><a class="disabled-link" href=" <?php echo site_url('/category/' . $movie['Year']); ?>"><?php echo esc_html($movie['Year']); ?></a></li>
                            <!--IMBd score-->
                            <li class="imdb"><span>IMDb</span><?php echo esc_html($movie['imdbRating']); ?></li>
                            <!--total likes-->
                            <li>Watched: <?php echo esc_html(rand(10, 100)); ?></li>
                            <!--available languages-->
                            <li>  
                                <?php 
                                    // Display multiple flags
                                    $languages = explode(',', $movie['Language']);
                                    foreach ($languages as $language):
                                        $language = strtolower(trim($language)); // Normalize language string
                                        
                                        // Determine the language code for the flag
                                        $lang_code = substr($language, 0, 2); // First two letters, for matching
                                        
                                        // Display flags only for English, Spanish, and French
                                        if (in_array($lang_code, ['en','spa','sp','fra','fr'])):
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                        <?php
                                        else:
                                            // Display a placeholder span with display:none for other languages
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>" style="display: none;"></span>
                                        <?php
                                        endif;
                                    endforeach;
                                ?> 
                            </li>
                        </ul>
                        <span class="watch-button"><button class="myBtn" data-modal="myModal1">Watch Now</button></span>
                    </section>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No latest movies found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('latest_series', 'display_latest_series');

// latest documentries
function display_latest_documentries() {
    $movies = fetch_latest_documentries(); // Fetch the latest documentaries
    ob_start();
    ?>
    <div class="most-recent-list">
        <?php if (!empty($movies) && is_array($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <?php if (isset($movie['Title'])): // Ensure $movie['Title'] exists ?>
                    <a href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>" class="flex item">
                        <section class="flex item__info">
                             <!--available languages--> 
                            <h3> 
                                <?php 
                                    // Display multiple flags
                                    if (isset($movie['Language'])) {
                                        $languages = explode(',', $movie['Language']);
                                        foreach ($languages as $language):
                                            $language = strtolower(trim($language)); // Normalize language string
                                            
                                            // Determine the language code for the flag
                                            $lang_code = substr($language, 0, 2); // First two letters, for matching
                                            
                                            // Display flags only for English, Spanish, and French
                                            if (in_array($lang_code, ['en','sp','fr'])): // Adjusted language codes
                                                ?>
                                                <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                            <?php
                                            endif;
                                        endforeach;
                                    }
                                ?>
                                <?php echo esc_html($movie['Title']); ?>
                            </h3>
                            <!--genre + release year-->
                            <p><?php echo esc_html($movie['Genre'] ?? 'Unknown Genre'); ?>, <?php echo esc_html($movie['Year'] ?? 'Unknown Year'); ?></p>
                        </section>
                        <!--IMBd score-->
                        <section class="flex item__date">iMDb <?php echo esc_html($movie['imdbRating'] ?? 'N/A'); ?></section>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No latest documentaries found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('latest_documentries', 'display_latest_documentries');

// latest tv-shows
function display_latest_tvshows() {
    $movies = fetch_latest_tvshows(); // Fetch the latest TV shows
    ob_start();
    ?>
    <div class="most-recent-list">
        <?php if (!empty($movies) && is_array($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <?php if (isset($movie['Title'])): // Ensure $movie['Title'] exists ?>
                    <a href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>" class="flex item">
                        <section class="flex item__info">
                             <!--available languages--> 
                            <h3> 
                                <?php 
                                    // Display multiple flags
                                    if (isset($movie['Language'])) {
                                        $languages = explode(',', $movie['Language']);
                                        foreach ($languages as $language):
                                            $language = strtolower(trim($language)); // Normalize language string
                                            
                                            // Determine the language code for the flag
                                            $lang_code = substr($language, 0, 2); // First two letters, for matching
                                            
                                            // Display flags only for English, Spanish, and French
                                            if (in_array($lang_code, ['en','sp','fr'])): // Adjusted language codes
                                                ?>
                                                <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                            <?php
                                            endif;
                                        endforeach;
                                    }
                                ?>
                                <?php echo esc_html($movie['Title']); ?>
                            </h3>
                            <!--genre + release year-->
                            <p><?php echo esc_html($movie['Genre'] ?? 'Unknown Genre'); ?>, <?php echo esc_html($movie['Year'] ?? 'Unknown Year'); ?></p>
                        </section>
                        <!--IMBd score-->
                        <section class="flex item__date">iMDb <?php echo esc_html($movie['imdbRating'] ?? 'N/A'); ?></section>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No latest TV shows found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('latest_tvshows', 'display_latest_tvshows');

//latest animation
function display_latest_animation() {
    $movies = fetch_latest_animation(); // Fetch the latest movies
    ob_start();
    ?>
    <div class="grid list">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="grid__item flex">
                    <!--link and poster-->
                    <a href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">
                        <section class="grid__item--poster">
                            <div class="image-size" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></div>
                        </section>
                    </a>
                    <section class="flex grid__item--info f-dir-col">
                         <!--title-->
                        <a class="item-title" href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">
                            <h3><?php echo esc_html($movie['Title']); ?></h3>          
                        </a>
                        <ul>
                            <!--release year + link to archives-->
                            <li><a class="disabled-link" href=" <?php echo site_url('/category/' . $movie['Year']); ?>"><?php echo esc_html($movie['Year']); ?></a></li>
                            <!--IMBd score-->
                            <li class="imdb"><span>IMDb</span><?php echo esc_html($movie['imdbRating']); ?></li>
                            <!--total likes-->
                            <li>Watched: <?php echo esc_html(rand(10, 100)); ?></li>
                             <!--available languages-->
                            <li>  
                                <?php 
                                    // Display multiple flags
                                    $languages = explode(',', $movie['Language']);
                                    foreach ($languages as $language):
                                        $language = strtolower(trim($language)); // Normalize language string
                                        
                                        // Determine the language code for the flag
                                        $lang_code = substr($language, 0, 2); // First two letters, for matching
                                        
                                        // Display flags only for English, Spanish, and French
                                        if (in_array($lang_code, ['en','spa','sp','fra','fr'])):
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                        <?php
                                        else:
                                            // Display a placeholder span with display:none for other languages
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>" style="display: none;"></span>
                                        <?php
                                        endif;
                                    endforeach;
                                ?> 
                            </li>
                        </ul>
                        <!--modal button-->
                        <span class="watch-button"><button class="myBtn" data-modal="myModal1">Watch Now</button></span>
                    </section>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No latest movies found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('latest_animation', 'display_latest_animation');

//latest anime
function display_latest_anime() {
    $movies = fetch_latest_anime(); // Fetch the latest movies
    ob_start();
    ?>
    <div class="grid list">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <div class="grid__item flex">
                     <!--link and poster-->
                    <a href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">
                    
                        <section class="grid__item--poster">
                            <div class="image-size" style="background-image: url('<?php echo esc_url($movie['Poster']); ?>');"></div>
                        </section>
                    </a>
                    <section class="flex grid__item--info f-dir-col">
                         <!--title-->
                        <a class="item-title" href="<?php echo esc_url(generate_clean_movie_url($movie['Title'])); ?>">
                            <h3><?php echo esc_html($movie['Title']); ?></h3>          
                        </a>
                        <ul>
                            <!--release year + link to archives-->
                            <li><a class="disabled-link"  href=" <?php echo site_url('/category/' . $movie['Year']); ?>"><?php echo esc_html($movie['Year']); ?></a></li>
                             <!--IMBd score-->
                            <li class="imdb"><span>IMDb</span><?php echo esc_html($movie['imdbRating']); ?></li>
                             <!--total likes-->
                            <li>Watched: <?php echo esc_html(rand(10, 100)); ?></li>
                            <!--available languages-->
                            <li>  
                                <?php 
                                    // Display multiple flags
                                    $languages = explode(',', $movie['Language']);
                                    foreach ($languages as $language):
                                        $language = strtolower(trim($language)); // Normalize language string
                                        
                                        // Determine the language code for the flag
                                        $lang_code = substr($language, 0, 2); // First two letters, for matching
                                        
                                        // Display flags only for English, Spanish, and French
                                        if (in_array($lang_code, ['en','spa','sp','fra','fr'])):
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>"></span>
                                        <?php
                                        else:
                                            // Display a placeholder span with display:none for other languages
                                            ?>
                                            <span class="country-flag <?php echo esc_attr($lang_code); ?>" style="display: none;"></span>
                                        <?php
                                        endif;
                                    endforeach;
                                ?> 
                            </li>
                        </ul>
                        <!--modal button-->
                        <span class="watch-button"><button class="myBtn" data-modal="myModal1">Watch Now</button></span>
                    </section>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No latest movies found.</p>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('latest_anime', 'display_latest_anime');


/******___________ YOUTUBE DATA API V3 - Fetch & display Youtube trailers __________ ******/

function fetch_youtube_trailer($movie_title, $movie_year) {
    $api_key = 'AIzaSyBnIjdP79jQ9mHxgORLbfFcqs1yqEGOMnU';
    $search_query = urlencode($movie_title . ' ' . $movie_year . ' trailer');
    $api_url = "https://www.googleapis.com/youtube/v3/search?part=snippet&type=video&maxResults=1&q={$search_query}&key={$api_key}";

    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) {
        return 'Error fetching trailer';
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['items'][0])) {
        $video_id = $data['items'][0]['id']['videoId'];
        return $video_id;
    }

    return 'No trailer found';
}

/******___________ GOOGLE CUSTOM SEACH API - Fetch images related to the title __________ ******/
// Define Google Custom Search API credentials
define('GOOGLE_API_KEY', 'AIzaSyCyAsxLhnT2YE__UJ1ZbsB-lR1SWH72JWE');
define('GOOGLE_CSE_ID', 'a1727a30c0025463a'); 

function fetch_background_image_url($movie_title) {
    $api_key = GOOGLE_API_KEY;
    $cse_id = GOOGLE_CSE_ID;
    $query = urlencode($movie_title . ' movie');
    $url = "https://www.googleapis.com/customsearch/v1?q=$query&key=$api_key&cx=$cse_id&searchType=image&imgType=photo&num=10";

    // Fetch the result from the API
    $response = wp_remote_get($url);
    
    // Check for errors in the request
    if (is_wp_error($response)) {
        error_log('Google API request failed: ' . $response->get_error_message()); // Log the error
        return null; // Return early if there's an error
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    // Check if the API returned valid data
    if (isset($data['items']) && is_array($data['items'])) {
        // Try to find the best image
        foreach ($data['items'] as $item) {
            $image_width = isset($item['image']['width']) ? $item['image']['width'] : 0;
            $image_height = isset($item['image']['height']) ? $item['image']['height'] : 0;

            // Check if the image is landscape and meets the width requirement
            if ($image_width >= 1920 && $image_width > $image_height) {
                return $item['link'];
            }
        }

        // If no image met the strict constraints, return the first suitable image found
        return isset($data['items'][0]['link']) ? $data['items'][0]['link'] : null;
    } else {
        // Log the API error response for debugging
        error_log('Google API returned no valid items. Response: ' . print_r($data, true));
    }

    return null;
}
 


/******___________ RECORDS LIKES & DISLIKES __________ ******/
// Handle the like action
function like_movie() {
    if (!isset($_POST['imdb_id'])) {
        wp_send_json_error('Missing IMDb ID.');
        return;
    }

    $imdb_id = sanitize_text_field($_POST['imdb_id']);
    $likes = get_option('movie_likes_' . $imdb_id, 0);
    $likes++;

    update_option('movie_likes_' . $imdb_id, $likes);

    wp_send_json_success(['likes' => $likes]);
}
// Handle the dislikes action
function dislike_movie() {
    if (!isset($_POST['imdb_id'])) {
        wp_send_json_error('Missing IMDb ID.');
        return;
    }

    $imdb_id = sanitize_text_field($_POST['imdb_id']);
    $dislikes = get_option('movie_dislikes_' . $imdb_id, 0);
    $dislikes++;

    update_option('movie_dislikes_' . $imdb_id, $dislikes);

    wp_send_json_success(['dislikes' => $dislikes]);
}

add_action('wp_ajax_like_movie', 'like_movie');
add_action('wp_ajax_nopriv_like_movie', 'like_movie');
add_action('wp_ajax_dislike_movie', 'dislike_movie');
add_action('wp_ajax_nopriv_dislike_movie', 'dislike_movie');


function movie_club_scripts() {
    wp_enqueue_script('movie-club-vote', get_template_directory_uri() . '/js/likesDislikes-recorder.js', array('jquery'), null, true);
    
    // Localize the script with new data
    wp_localize_script('movie-club-vote', 'movieClubAjax', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'movie_club_scripts');





// function create_custom_post_type() {
//     register_post_type('movies',
//         array(
//             'labels'      => array(
//                 'name'          => __('Movies'),
//                 'singular_name' => __('Movie'),
//             ),
//             'public'      => true,
//             'has_archive' => true,
//             'supports'    => array('title', 'editor', 'thumbnail'),
//             'taxonomies'  => array('category', 'post_tag'), // Ensure tags and categories are supported
//         )
//     );
// }
// add_action('init', 'create_custom_post_type');




    
    