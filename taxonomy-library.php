<?php
get_header();
$term = get_queried_object();
$category_title = get_the_archive_title();
$term_id = $category_image_style = '';
if (!empty($term)) {
    $term_id = $term->term_id;
    $category_image = get_field('subcategory_image', $term->taxonomy . '_' . $term_id);
    $category_image_bg = get_field('subcategory_image_bg', $term->taxonomy . '_' . $term_id);
    $category_font_color = get_field('font_color', $term->taxonomy . '_' . $term_id);
    if (!empty($category_image['ID'])) {
        $hero_color = $font_color = '';
        if ($category_image_bg) {
            $hero_color = 'background-color: '.$category_image_bg.';';
        }
        if($category_font_color) {
            $font_color = 'color: '.$category_font_color;
        }
        $category_image_style = ' style="background-image: url(' . wp_get_attachment_image_url($category_image['ID'], '', '', '') . ');'.$hero_color.$font_color.'"';
    }
    $category_custom_title = get_field('category_title', $term->taxonomy . '_' . $term_id);
    if (!empty($category_custom_title)) {
        $category_title = $category_custom_title;
    }
}
$breadcrumb_medium = '';
$main_title = $breadcrumb_last = 'Захворювання на літеру "' . $category_title. '"';
if (isset($term->parent) && $term->parent > 0) {
    $parent_term = get_term_by('id', $term->parent, 'library');
    $breadcrumb_medium = '<span><a href="/library/'.$parent_term->slug.'/">Захворювання на літеру "'.$parent_term->name.'"</a></span> &gt;';
    $main_title = $breadcrumb_last = 'Статті про захворювання "' . $category_title. '"';
}
?>
<div class="category-page inner-page">
    <div class="container">
        <div class="breadcrumbs">
            <span>
                <span><a href="/">Головна</a></span> &gt;
                <span><a href="/library/">Від А до Я</a></span> &gt;
                <?php print $breadcrumb_medium; ?>
                <span class="breadcrumb_last" aria-current="page"><?php print $breadcrumb_last; ?></span>
            </span>
        </div>
    </div>
    <div class="page-hero"<?php print $category_image_style; ?>>
        <div class="page-info row">
            <h1><?php print $main_title; ?></h1>
            <?php if (isset($term->description)) { ?>
                <div class="page-description"><?php print $term->description; ?></div>
            <?php } ?>
        </div>
    </div>
    <div class="library-letters">
        <div class="container">
            <?php
            library_taxonomy_terms('library', $parent = 0, $hide_empty = false)
            ?>
        </div>
    </div>
    <?php $parent = '';
    if (!empty($term_id)) { ?>
        <div class="library-sub-categories">
            <div class="container">
                <div class="row">
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'library',
                        'hide_empty' => false,
                        'parent' => $term_id
                    ));
                    if (count($categories)) {
                        foreach ($categories as $category) {
                            print '<a href="' . get_category_link($category->term_id) . '">' . $category->name . '</a>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php
    if (isset($term->parent) && $term->parent > 0) {
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
                                    <p><?php print $trim_content; ?></p>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        ?>
                    </div>
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
                    'taxonomy' => 'library',
                    'hide_empty' => false,
                    'parent' => $term_id
                ));
                foreach ($categories as $category) {
                    print '<div class="border-title row sb"><h2>' . $category->name . '</h2><div class="read-more"><a href="' . get_category_link($category->term_id) . '">Більше</a></div></div>';
                    $posts = get_posts(array(
                        'numberposts' => 3,
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'library',
                                'field' => 'term_id',
                                'terms' => $category->term_id,
                            )
                        ),
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
                                                    href="<?php print get_category_link($categories[0]->term_id); ?>"><?php print esc_html($categories[0]->name); ?></a>
                                        </div>
                                    <?php } ?>
                                    <h3><a href="<?php print get_the_permalink(); ?>"><?php print get_the_title(); ?></a>
                                    </h3>
                                    <p><?php print $trim_content; ?></p>
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