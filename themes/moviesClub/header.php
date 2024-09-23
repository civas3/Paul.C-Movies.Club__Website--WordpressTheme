<!doctype html>
<html <?php language_attributes();?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php bloginfo('name'); ?> &raquo; <?php is_front_page() ? bloginfo('description') : wp_title(''); ?></title>

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>">
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php wp_head(); ?>


</head>

<body <?php body_class(); ?>>

    <header>
        <div class="container">
            <!-- logo and sign in button -->
            <div class="top-header">

                <div class="top-header__logo">
                    <?php the_custom_logo(); ?>
                    <div class="logo_title">
                        <p>FREE MOVIES ONLINE</p>
                    </div>
                </div>

                <div class="top-header__sign-in">
                    <a class="btn-login" href="<?php echo site_url('/register/')?>"><span>SIGN IN</span></a>
                </div>

            </div>

            <!--Desktop navigation menu-->
            <nav id="top-nav" class="top-nav">
                <?php
                wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'menu_class' => 'nav-list',
                'container' => false, // Remove the default container
                ));
                ?>
            </nav>

            <!--Mobile navigation menu-->
            <div class="mobile-menu">
                <div class="burger-menu" onclick="openNav()">☰</div>

                <div id="mobileNav" class="nav-container">
                    <span class="close-btn" onclick="closeNav()">&times;</span>
                    <?php
                    wp_nav_menu(array(
                    'theme_location' => 'mobile-menu',
                    'menu_class' => 'mobile-nav-list',
                    'container' => false, // Remove the default container
                    ));
                    ?>

                    <div class="top-header__sign-in top-header__sign-in--mobile">
                        <a class="btn-login" href=""><span>SIGN IN</span></a>
                    </div>
                </div>
            </div>

             <!-- search bar -->
            <nav id="filter" class="search-bar filter">
                <!--Movies filter
                    <div class="filter__menu">
                        <input type="button" onclick="growDiv()" value="FILTER" id="more-button">
                    </div>

                    <div id='grow'>
                        <div class='measuringWrapper'>
                    </div>
                -->
                <!--seach bar -->
                <div class="filter__search">
                    <form class="nav-search-form" role="search" method="get" action="<?php echo home_url( '/' ); ?>">
                        <div class="filter__search">

                            <input type="text" class="form-control"
                                placeholder="<?php echo esc_attr_x( 'Search for…', 'placeholder' ) ?>"
                                value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>
                            ">

                            <button class="filter__search--button " type="subsmit" id="button-addon2">
                                <svg style="" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                    <path
                                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                </svg>
                            </button>

                        </div>
                    </form>
                </div>
            </nav>         
        </div> 
    </header>