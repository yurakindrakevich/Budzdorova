<?php
/**
 * Template Name: Search Page
 */
get_header();
global $page, $paged;
$paged_title = '';
if ($paged >= 2 || $page >= 2) {
    $paged_title = ' - Сторінка '. $paged;
}
?>
<div class="normal-page inner-page">
    <div class="container">
        <?php
        if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div class="breadcrumbs">', '</div>');
        }
        ?>
    </div>
    <div class="page-main">
        <div class="container">
            <h1>Ви шукали: <?php echo get_search_query() . $paged_title; ?></h1>
            <div class="post-content">
                <?php
                $s = get_search_query();
                $args = array(
                    's' => $s,
                );
                $the_query = new WP_Query($args);
                if ($the_query->have_posts()) {
                print '<div class="row-posts grid tree-grid">';
                while ($the_query->have_posts()) {
                    $the_query->the_post();
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
                <?php } } ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
