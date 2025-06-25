<?php
/**
 * The template for displaying all archive pages
 */
get_header();
global $page, $paged;
$paged_title = '';
if ($paged >= 2 || $page >= 2) {
    $paged_title = ' - ' . pll__('Сторінка') .' '. $paged;
}
$pre_title = '';
if (is_tag()) {
    $pre_title = pll__('Статті на тему').'&nbsp;';
}
$tag_id = get_queried_object()->slug;
?>
<div class="full-width front-full inner-page">
    <h1 class="playfair"><?php print $pre_title . get_the_archive_title() . $paged_title; ?></h1>
    <?php the_breadcrumb(); ?>
    <?php if (have_posts()) : ?>
        <div class="posts-grid row pad">
            <?php while (have_posts()) : the_post();
                $id = get_the_ID();
                $permalink = get_the_permalink($id);
                $title = get_the_title($id);
                $date = get_the_date('F d, Y', $id);
                $image = get_the_post_thumbnail($id, 'post');
                $read_time = get_post_meta( $id, 'read_time', true );
                $categories = get_the_category();
                ?>
                <div class="post">
                    <div class="image">
                        <a href="<?php print $permalink; ?>">
                            <?php print $image; ?>
                        </a>
                    </div>
                    <div class="post-info">
                        <?php if ( ! empty( $categories ) ) { ?>
                            <div class="category"><?php print esc_html( $categories[0]->name ); ?></div>
                        <?php } ?>
                        <div class="title playfair">
                            <a href="<?php print $permalink; ?>">
                                <?php print $title; ?>
                            </a>
                        </div>
                        <div class="post-meta row aic">
                            <?php if (!empty($read_time)) { ?>
                                <div class="read-time"><?php print $read_time; ?> <?php pll_e('хв. читання'); ?></div>
                                <span></span>
                            <?php } ?>
                            <div class="date"><?php print $date; ?></div>
                        </div>
                    </div>
                </div>
            <?php
            endwhile;
            ?>
        </div>
    <?php else : ?>
        <div class="empty-category"><?php pll_e('В цій категорії немає дописів'); ?></div>
    <?php endif; print do_shortcode( '[ajax_load_more id="alm_6067552323" loading_style="infinite fading-circles" post_type="post" posts_per_page="8" offset="8" tag="'.$tag_id.'" scroll_distance="-600"]' ); ?>
</div>
<?php get_footer(); ?>
