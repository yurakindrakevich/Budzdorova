<?php
/**
 * Template Name: Podcasts page
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
                <?php
                print '<div class="video-podcast grid">';
                $args = [
                    'numberposts' => -1,
                    'post_type' => 'podcasts',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish',
                    'has_password' => FALSE,
                ];
                $videos = get_posts($args);
                if (count($videos)) {
                    print '<div class="podcast-post">';
                    foreach ($videos as $video) {
                        setup_postdata($video);
                        $id = $video->ID;
                        $title = get_the_title($id);
                        $bg_image = get_post_field('video_background_image', $id);
                        $bg_image_style = $logotype = '';
                        if (!empty($bg_image)) {
                            $call_to_action_text = get_post_field('call_to_action_text', $id);
                            $video_short_body = get_post_field('video_short_body', $id);
                            $text_color = get_post_field('video_text_color', $id);
                            $logotype_color = get_post_field('logotype_color', $id);
                            if ($logotype_color == 1) {
                                $logotype = '<img src="/wp-content/themes/budzdorova/images/small-logotype-dark.svg" alt="Логотип сайту Будь здорова">';
                            } elseif ($logotype_color == 2) {
                                $logotype = '<img src="/wp-content/themes/budzdorova/images/small-logotype-white.svg" alt="Логотип сайту Будь здорова">';
                            }
                            $audio_file_url = '';
                            $audio_file = get_post_field('audio_file', $id);
                            if (!empty($audio_file)) {
                                $audio_file_url = wp_get_attachment_url($audio_file);
                            }
                            $bg_image_style = 'background-image: url(' . wp_get_attachment_image_url($bg_image, '', '', '') . ');background-repeat: no-repeat;background-size: cover;';
                            $padding_left_style = '';
                            $padding_left = get_post_field('padding_left', $id);
                            if (!empty($padding_left)) {
                                $padding_left_style = ' style="padding-left: '.$padding_left.'px"';
                            }
                            $open_icon = '';
                            $read_icon_color = get_post_field('read_icon_color', $id);
                            if ($read_icon_color == 1) {
                                $open_icon = '<img src="/wp-content/themes/budzdorova/images/circle-arrow-dark.svg" alt="Відкрити подкаст">';
                            } elseif ($read_icon_color == 2) {
                                $open_icon = '<img src="/wp-content/themes/budzdorova/images/circle-arrow-white.svg" alt="Відкрити подкаст">';
                            }
                            ?>
                            <div class="podcast-block" style="<?php print $bg_image_style; ?>" data-audio="<?php print $audio_file_url; ?>">
                                <div class="podcast-block-inner row sb column"<?php print $padding_left_style; ?>>
                                    <div class="logotype"><?php print $logotype; ?></div>
                                    <div class="body" style="color: <?php print $text_color; ?>">
                                        <?php if (!empty($call_to_action_text)) { ?>
                                            <div class="call-to-action"><?php print $call_to_action_text; ?></div>
                                        <?php } ?>
                                        <div class="title"><?php print $title; ?></div>
                                        <?php if (!empty($video_short_body)) { ?>
                                            <div class="description"><?php print $video_short_body; ?></div>
                                        <?php } ?>
                                        <div class="open-icon"><?php print $open_icon; ?></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    print '</div>';
                }
                wp_reset_postdata();
                print '</div>';
                ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
