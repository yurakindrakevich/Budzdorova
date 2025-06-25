<?php
/**
 * budzdorova functions and definitions
 */

add_action('init', 'admin_bar');

function admin_bar()
{
    if (is_user_logged_in()) {
        add_filter('show_admin_bar', '__return_true', 1000);
    }
}

add_filter('wp_default_scripts', 'change_default_jquery');
function change_default_jquery(&$scripts)
{
    if (!is_admin()) {
        $scripts->remove('jquery');
    }
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('admin_print_styles', 'print_emoji_styles');
remove_filter('the_content_feed', 'wp_staticize_emoji');
remove_filter('comment_text_rss', 'wp_staticize_emoji');
remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
add_filter('tiny_mce_plugins', 'disable_wp_emojis_in_tinymce');
remove_action('wp_head', 'wp_generator');
function budzdorova_remove_block_library_css()
{
    wp_dequeue_style('wp-block-library');
}

add_action('wp_enqueue_scripts', 'budzdorova_remove_block_library_css');

function disable_wp_emojis_in_tinymce($plugins)
{
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}

function budzdorova_css()
{
    wp_enqueue_style('swipe-style', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css');
    wp_enqueue_style('main-style', get_stylesheet_directory_uri() . '/css/style.css');
}

add_action('wp_enqueue_scripts', 'budzdorova_css');

function budzdorova_scripts()
{
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), false, true);
    wp_enqueue_script('main-js', get_stylesheet_directory_uri() . '/js/main.js', array(), false, true);
}
add_action('wp_enqueue_scripts', 'budzdorova_scripts');

add_filter('img_caption_shortcode_width', '__return_false');

// Navigation
if (function_exists('register_nav_menus')) {
    register_nav_menus(
        array(
            'primary' => 'Меню в шапці сайту',
            'bottom' => 'Меню в підвалі сайту',
        )
    );
}

add_action( 'after_setup_theme', 'budzdorova_theme_setup' );
function budzdorova_theme_setup() {
    add_theme_support('post-thumbnails');
    add_image_size( 'user', 140, 140, true );
    add_image_size( 'small', 200, 150, true );
    add_image_size( 'medium-post', 210, 180, true );
    add_image_size( 'small-post', 260, 190, true );
    add_image_size( 'post', 350, 230, true );
    add_image_size( 'journal', 255, 373, true );
    add_image_size( 'slide-post', 520, 310, true );
    add_image_size( 'big-cat-post', 555, 420, true );
    add_image_size( 'big-post', 640, 550, true );
}

add_action('before_delete_post', 'delete_attachments_with_post', 20);
function delete_attachments_with_post($postid)
{
    $post = get_post($postid);
    if (in_array($post->post_type, ['post', 'recipe'])) {
        $attachments = get_children(array('post_type' => 'attachment', 'post_parent' => $postid));
        if ($attachments) {
            foreach ($attachments as $attachment) wp_delete_attachment($attachment->ID);
        }
    }
}

function budzdorova_archive_title($title)
{
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }

    return $title;
}

add_filter('get_the_archive_title', 'budzdorova_archive_title');

function budzdorova_tags($post_id)
{
    $content = '';
    $posttags = get_the_terms($post_id, 'post_tag');
    if ($posttags) {
        $array = [];
        foreach ($posttags as $tag) {
            $array[] = '<a href="/tag/' . $tag->slug . '/">' . $tag->name . '</a>';
        }
        $content .= '<div class="post-tags row column"><div class="title playfair">' . pll__('Повʼязані теми:') . '</div><div class="tags-links row">' . implode('', $array) . '</div></div>';
    }

    return $content;
}

function budzdorova_search_form($form)
{
    $form = '<form role="search" method="get" id="search-form" class="search-form flex aic jcc" action="' . home_url('/') . '" >
	<div class="search-form-content flex aic jcc">
	<div class="close-search"></div>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="'.pll__('Що ви шукаєте?').'" class="form-text" />
	<input type="submit" id="search-submit" value="Search" />
	</div>
	</form>';
    return $form;
}

add_filter('get_search_form', 'budzdorova_search_form');

function budzdorova_filter_wpseo_canonical_pagination($canonical)
{
    $canonical = urldecode($canonical);
    if ($canonical) {
        if (is_paged()) {
            $canonical = preg_replace('/\d+/', '', $canonical);
            $canonical = str_replace('/page/', "", $canonical);
        }
    }
    return $canonical;
}

add_filter('wpseo_canonical', 'budzdorova_filter_wpseo_canonical_pagination', 999);

function budzdorova_filter_plugin_updates($value)
{
    if (isset($value->response['advanced-custom-fields-pro/acf.php'])) {
        unset($value->response['advanced-custom-fields-pro/acf.php']);
    }
    if (isset($value->response['yoast-test-helper/yoast-test-helper.php'])) {
        unset($value->response['yoast-test-helper/yoast-test-helper.php']);
    }
    if (isset($value->response['wordpress-seo/wp-seo.php'])) {
        unset($value->response['wordpress-seo/wp-seo.php']);
    }
    return $value;
}

add_filter('site_transient_update_plugins', 'budzdorova_filter_plugin_updates');

function the_breadcrumb()
{
    $sep = '<span class="separator">/</span>';
    if (!is_front_page()) {
        echo '<div class="breadcrumb row">';
        echo '<a href="';
        echo get_option('home');
        echo '">';
        echo 'Головна</a>' . $sep;
        if (is_category() || is_single()) {
            the_category('title_li=');
        } elseif (is_archive() || is_single()) {
            if (is_day()) {
                printf(__('%s', 'text_domain'), get_the_date());
            } elseif (is_month()) {
                printf(__('%s', 'text_domain'), get_the_date(_x('F Y', 'monthly archives date format', 'text_domain')));
            } elseif (is_year()) {
                printf(__('%s', 'text_domain'), get_the_date(_x('Y', 'yearly archives date format', 'text_domain')));
            } else {
                _e('Архіви', 'text_domain');
            }
        }
        if (is_single()) {
            echo $sep;
            echo '<span>';
            the_title();
            echo '</span>';
        }
        if (is_page()) {
            echo '<span>' . the_title() . '</span>';
        }
        echo '</div>';
    }
}
function budzdorova_tiny_mce_before_init( $mce_init ) {

    $mce_init['cache_suffix'] = 'v=26';

    return $mce_init;
}
add_filter( 'tiny_mce_before_init', 'budzdorova_tiny_mce_before_init' );


//Comment Field Order
add_filter( 'comment_form_fields', 'budzdorova_comment_fields_custom_order' );
function budzdorova_comment_fields_custom_order( $fields ) {
    $comment_field = $fields['comment'];
    $author_field = $fields['author'];
    $email_field = $fields['email'];
    $cookies_field = $fields['cookies'];
    unset( $fields['comment'] );
    unset( $fields['author'] );
    unset( $fields['email'] );
    unset( $fields['url'] );
    unset( $fields['cookies'] );
    // the order of fields is the order below, change it as needed:
    $fields['author'] = $author_field;
    $fields['email'] = $email_field;
    $fields['comment'] = $comment_field;
    $fields['cookies'] = $cookies_field;
    // done ordering, now return the fields:
    return $fields;
}

function budzdorova_get_comment_depth( $my_comment_id ) {
    $depth_level = 0;
    while( $my_comment_id > 0  ) { // if you have ideas how we can do it without a loop, please, share it with us in comments
        $my_comment = get_comment( $my_comment_id );
        $my_comment_id = $my_comment->comment_parent;
        $depth_level++;
    }
    return $depth_level;
}

function get_taxonomy_parents($term_id, $taxonomy) {
    $ancestors = get_ancestors($term_id, $taxonomy, 'taxonomy');
    $ancestors = array_reverse($ancestors);
    $parent_terms = [];
    foreach ($ancestors as $ancestor_id) {
        $parent_terms[] = get_term($ancestor_id, $taxonomy);
    }
    return $parent_terms;
}

function library_taxonomy_terms($taxonomy, $parent = 0, $hide_empty = false) {
    $terms = get_terms([
        'taxonomy' => $taxonomy,
        'parent' => $parent,
        'hide_empty' => $hide_empty,
    ]);
    if (!empty($terms) && !is_wp_error($terms)) {
        print '<div class="library-alphabet"><div class="row-title">Знайти назву за першою літерою:</div><div class="library-letters row aic sb">';
        foreach ($terms as $term) {
            $class = ( is_tax($taxonomy, $term->slug) ) ? 'active' : '';
            print '<a href="/library/' . $term->slug . '" class="' . $class . '">' . $term->name . '</a>';
        }
        print '</div></div>';
    }
}

function get_term_depth($term_id, $taxonomy) {
    $depth = 0;
    $term = get_term($term_id, $taxonomy);

    // Traverse up the parent hierarchy
    while ($term && $term->parent != 0) {
        $depth++;
        $term = get_term($term->parent, $taxonomy);
    }
    return $depth;
}
