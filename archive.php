<?php
/**
 * The template for displaying all archive pages
 */
get_header();
$term = get_queried_object();
global $page, $paged;
$paged_title = '';
if ($paged >= 2 || $page >= 2) {
    $paged_title = ' - Сторінка ' . $paged;
}
$pre_title = '';
if (is_tag()) {
    $pre_title = 'Статті на тему ';
}
$category_title = get_the_archive_title();
$term_id = $category_style = $left_image = $right_image = '';
$term_depth = '';
if (!empty($term)) {
    $term_id = $term->term_id;
    $category_image_left = get_field('subcategory_image', $term->taxonomy . '_' . $term_id);
    $category_image_right = get_field('subcategory_image_right', $term->taxonomy . '_' . $term_id);
    $category_image_bg = get_field('subcategory_image_bg', $term->taxonomy . '_' . $term_id);
    $category_font_color = get_field('font_color', $term->taxonomy . '_' . $term_id);
    if (!empty($category_image_left['ID'])) {
        $hero_color = $font_color = '';
        if ($category_image_bg) {
            $hero_color = 'background-color: '.$category_image_bg.';';
        }
        if($category_font_color) {
            $font_color = 'color: '.$category_font_color;
        }
        $category_style = ' style="'.$hero_color.$font_color.'"';
        $left_image = '<div class="left-image">'.wp_get_attachment_image($category_image_left['ID'], '', '', '').'</div>';
    }
    if (!empty($category_image_right['ID'])) {
        $right_image = '<div class="right-image">'.wp_get_attachment_image($category_image_right['ID'], '', '', '').'</div>';
    }
    $category_custom_title = get_field('category_title', $term->taxonomy . '_' . $term_id);
    if (!empty($category_custom_title)) {
        $category_title = $category_custom_title;
    }
    $term_depth = get_term_depth($term_id, 'category');
}
?>
<div class="category-page inner-page">
    <div class="container">
        <?php
        if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div class="breadcrumbs">', '</div>');
        }
        ?>
    </div>
    <div class="page-hero"<?php print $category_style; ?>>
        <?php print $left_image; ?>
        <div class="page-info row">
            <h1><?php print $pre_title . $category_title . $paged_title; ?></h1>
            <?php if (isset($term->description)) { ?>
                <div class="page-description"><?php print $term->description; ?></div>
            <?php } ?>
        </div>
        <?php print $right_image; ?>
    </div>
    <?php $parent = '';
    if (!empty($term_id)) { ?>
        <div class="sub-categories">
            <div class="container">
                <div class="row">
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'category',
                        'hide_empty' => false,
                        'parent' => $term_id
                    ));
                    if (count($categories)) {
                        foreach ($categories as $category) {
                            print '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                        }
                    } else {
                        $parent = get_category($term->category_parent);
                        if (!empty($parent->term_id)) {
                            $categories = get_terms(array(
                                'taxonomy' => 'category',
                                'hide_empty' => false,
                                'parent' => $parent->term_id
                            ));
                            if (count($categories)) {
                                foreach ($categories as $category) {
                                    $cat_class = '';
                                    if ($term_id === $category->term_id) {
                                        $cat_class = 'active';
                                    }
                                    print '<a href="' . get_category_link($category->term_id) . '" class="'.$cat_class.'">' . $category->name . '</a>';
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php }
    if ($term_depth === 0) {
    $args = [
        'numberposts' => 4,
        'post_type' => 'post',
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish',
        'has_password' => FALSE,
        'tax_query' => [
            [
                'taxonomy' => 'category',
                'terms' => array($term->term_id),
                'field' => 'term_id',
                'operator' => 'IN'
            ]
        ],
        'meta_query' => [
            [
                'key' => 'see_options',
                'value' => sprintf('"%s"', '5'),
                'compare' => 'LIKE',
            ],
        ],
    ];
    $main_posts = get_posts($args);
    if (count($main_posts)) {
        print '<div class="hero-posts"><div class="container"><div class="grid">';
        $i=1;
        $post_count = count($main_posts);
        foreach ($main_posts as $main_post) {
            setup_postdata($main_post);
            $id = $main_post->ID;
            $image = get_the_post_thumbnail($id, 'big-cat-post');
            $title = get_the_title($id);
            $teaser_text = get_post_field('teaser_text', $id);
            if (empty($teaser_text)) {
                $content = strip_tags(get_post_field('post_content', $id));
                $content = strip_shortcodes($content);
                $trim_content = mb_strimwidth($content, 0, 60, '...');
            } else {
                $trim_content = $teaser_text;
            }
            $categories = get_the_category($id);
            $post_class = ' big-post';
            $col_div_start = $col_div_end = '';
            if ($i > 1) {
                $post_class = ' small-post';
                $image = get_the_post_thumbnail($id, 'medium-post');
            }
            if ($i === 2) {
                $col_div_start = '<div class="right-small-posts">';
            }
            if ($i === $post_count) {
                $col_div_end = '</div>';
            }
            ?>
                <?php print $col_div_start; ?>
                <div class="post<?php print $post_class; ?>">
                    <div class="image">
                        <a href="<?php print get_the_permalink($id); ?>">
                            <?php print $image; ?>
                        </a>
                    </div>
                    <div class="post-content">
                        <div class="post-content-label">
                            <?php if (!empty($categories)) { ?>
                                <div class="category"><a
                                            href="<?php print get_category_link($categories[0]->term_id); ?>"><?php print esc_html($categories[0]->name); ?></a>
                                </div>
                            <?php } ?>
                            <h3><a href="<?php print get_the_permalink($id); ?>"><?php print $title; ?></a></h3>
                            <p><a href="<?php print get_the_permalink($id); ?>"><?php print $trim_content; ?></a></p>
                        </div>
                    </div>
                </div>
            <?php print $col_div_end; ?>
            <?php
            $i++;
        }
        print '</div></div></div>';
    }
    wp_reset_postdata();
    }
    if (!empty($parent)) {
    if (have_posts()) : ?>
        <div class="sub-categories-posts">
            <div class="container">
                <div class="row-posts grid tree-grid">
                    <?php while (have_posts()) : the_post();
                        $id = get_the_ID();
                        $image = get_the_post_thumbnail($id, 'post');
                        $teaser_text = get_post_field('teaser_text', $id);
                        if (empty($teaser_text)) {
                            $content = strip_tags(get_post_field('post_content', $id));
                            $content = strip_shortcodes($content);
                            $trim_content = mb_strimwidth($content, 0, 60, '...');
                        } else {
                            $trim_content = $teaser_text;
                        }
                        $categories = get_the_category();
                        ?>
                        <div class="post row column">
                            <div class="image">
                                <a href="<?php print get_the_permalink(); ?>">
                                    <?php print $image; ?>
                                </a>
                            </div>
                            <div class="post-content">
                                <?php if (!empty($categories)) { ?>
                                    <div class="category"><a
                                                href="<?php print get_category_link($categories[0]->term_id); ?>"><?php print esc_html($categories[0]->name); ?></a>
                                    </div>
                                <?php } ?>
                                <h3><a href="<?php print get_the_permalink(); ?>"><?php print get_the_title(); ?></a>
                                </h3>
                                <p><a href="<?php print get_the_permalink(); ?>"><?php print $trim_content; ?></a></p>
                            </div>
                        </div>
                    <?php
                    endwhile;
                    ?>
                </div>
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('«', 'textdomain'),
                    'next_text' => __('»', 'textdomain'),
                    'screen_reader_text' => ''
                ));
                ?>
            </div>
        </div>
    <?php else : ?>
    <div class="container">
        <div class="empty-category">В цій категорії немає дописів</div>
    </div>
    <?php endif; } ?>

    <?php if (!empty($term_id)) { ?>
        <div class="sub-categories-posts">
            <div class="container">
                <?php
                $categories = get_terms(array(
                    'taxonomy' => 'category',
                    'hide_empty' => false,
                    'parent' => $term_id
                ));
                foreach ($categories as $category) {
                    print '<div class="border-title row sb"><h2>' . $category->name . '</h2><div class="read-more"><a href="' . get_category_link($category->term_id) . '">Більше</a></div></div>';
                    $posts = get_posts(array(
                        'numberposts' => 3,
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'category' => $category->term_id,
                        'suppress_filters' => true,
                        'has_password' => FALSE,
                    ));
                    if (count($posts)) {
                        print '<div class="row-posts grid tree-grid">';
                        foreach ($posts as $post) {
                            setup_postdata($post);
                            $id = get_the_ID();
                            $image = get_the_post_thumbnail($id, 'post');
                            $teaser_text = get_post_field('teaser_text', $id);
                            if (empty($teaser_text)) {
                                $content = strip_tags(get_post_field('post_content', $id));
                                $content = strip_shortcodes($content);
                                $trim_content = mb_strimwidth($content, 0, 60, '...');
                            } else {
                                $trim_content = $teaser_text;
                            }
                            $categories = get_the_category();
                            ?>
                            <div class="post row column">
                                <div class="image">
                                    <a href="<?php print get_the_permalink(); ?>">
                                        <?php print $image; ?>
                                    </a>
                                </div>
                                <div class="post-content">
                                    <?php if (!empty($categories)) { ?>
                                        <div class="category"><a
                                                    href="<?php print get_category_link($category->term_id); ?>"><?php print $category->name; ?></a>
                                        </div>
                                    <?php } ?>
                                    <h3><a href="<?php print get_the_permalink(); ?>"><?php print get_the_title(); ?></a>
                                    </h3>
                                    <p><a href="<?php print get_the_permalink(); ?>"><?php print $trim_content; ?></a></p>
                                </div>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                        print '</div>';
                    }
                }
                ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php get_footer(); ?>
