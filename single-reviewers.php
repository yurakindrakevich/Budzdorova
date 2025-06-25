<?php
/**
 * The template for displaying reviewer single
 */
get_header();
$object = get_queried_object();
?>
<div class="category-page inner-page">
    <div class="container">
        <?php
        if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div class="breadcrumbs">', '</div>');
        }
        ?>
    </div>
    <div class="post-head">
        <div class="container">
            <h1><?php print 'Фаховий рецензент: ' . get_the_title(); ?></h1>
        </div>
    </div>
    <?php
    $id = get_the_ID();
    $image = get_the_post_thumbnail($id, 'user');
    $user_description = get_post_field('post_content', $id);
    print '<div class="author-page-line reviewer container"><div class="author-block">';
    if (!empty($image)) {
        print '<div class="avatar">' . $image . '</div>';
    }
    print '<div class="body">';
    print '<div class="name">' . get_the_title() . '</div>';
    print '<div class="description">' . $user_description . '</div>';
    print '</div>';
    print '</div></div>';
    $args = [
        'post_type' => 'post',
        'meta_query' => [
            [
                'key' => 'reviewer',
                'value' => $id,
                'compare' => 'LIKE',
            ],
        ],
    ];
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        ?>
        <div class="sub-categories-posts">
            <div class="container">
                <div class="row-posts row">
                    <?php
                    while ($query->have_posts()) {
                        $query->the_post();
                        $id = get_the_ID();
                        $image = get_the_post_thumbnail($id, 'post');
                        $content = strip_tags(get_post_field('post_content', $id));
                        $content = strip_shortcodes($content);
                        $trim_content = mb_strimwidth($content, 0, 60, '...');
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
                    ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="container">
            <div class="empty-category">Немає дописів</div>
        </div>
    <?php } ?>
</div>
<?php get_footer(); ?>
