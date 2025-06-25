<?php
/**
 * Template Name: Library page
 */
get_header();
$id = get_the_ID();
$page_permalink = get_the_permalink($id);
$page_title = get_the_title($id);
$category_image_style = '';
$page_image_bg = get_post_field('page_image_bg', $id);
$page_font_color = get_post_field('page_font_color', $id);
$page_title = get_post_field('page_title', $id);
$hero_description = get_post_field('hero_description', $id);
if (!empty($page_image_bg)) {
    $category_image_style = 'background-color: ' . $page_image_bg . ';';
}
if (!empty($page_font_color)) {
    $category_image_style .= 'color: ' . $page_font_color . ';';
}
$left_image = $right_image = '';
$page_image_left = get_post_field('page_image', $id);
$page_image_right = get_post_field('page_image_right', $id);
if (!empty($page_image_left)) {
    $left_image = '<div class="left-image">'.wp_get_attachment_image($page_image_left, '', '', '').'</div>';
}
if (!empty($page_image_right)) {
    $right_image = '<div class="right-image">'.wp_get_attachment_image($page_image_right, '', '', '').'</div>';
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
    <div class="page-hero" style="<?php print $category_image_style; ?>">
        <?php print $left_image; ?>
        <div class="page-info row">
            <h1><?php print $page_title; ?></h1>
            <?php if (!empty($hero_description)) { ?>
                <div class="page-description"><?php print $hero_description; ?></div>
            <?php } ?>
        </div>
        <?php print $right_image; ?>
    </div>
    <div class="page-main">
        <div class="container">
            <div class="post-content">
                <div class="live-search">
                    <div class="row-title low-case">Захворювання від А до Я</div>
                    <div class="live-search-box">
                        <?php echo do_shortcode('[library_search]'); ?>
                    </div>
                </div>
                <div class="popular">
                    <div class="row-title">Популярні у пошуку:</div>
                    <div class="library-sub-categories">
                        <div class="row">
                            <?php
                            $childrens = get_terms([
                                'taxonomy'   => 'library',
                                'hide_empty' => false,
                                'parent'     => 0,
                            ]);
                            foreach ($childrens as $child) {
                                $sub_childrens = get_terms([
                                    'taxonomy'   => 'library',
                                    'hide_empty' => false,
                                    'parent'     => $child->term_id,
                                ]);
                                foreach ($sub_childrens as $sub_children) {
                                    print '<a href="' . get_category_link($sub_children->term_id) . '">' . $sub_children->name . '</a>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
                library_taxonomy_terms('library', $parent = 0, $hide_empty = false)
                ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
