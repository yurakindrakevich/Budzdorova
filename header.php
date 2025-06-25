<?php
/**
 * The header.
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
wp_body_open();
$options = get_option('wporg_options');
if (isset($options['subscribe-on']) && $options['subscribe-on'] == 1) {
?>
    <?php echo do_shortcode('[subscribe_form_message]'); ?>
    <div class="subscribe-popup">
        <div class="popup-head">
            <div class="title"><?php print $options['popup-name']; ?></div>
            <div class="close"></div>
        </div>
        <div class="popup-content">
            <div class="form">
                <?php echo do_shortcode('[subscribe_form]'); ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php
$class = '';
if (isset($options['subscribe-on']) && $options['subscribe-on'] == 1) {
    $class = ' has-subscribe';
}
?>
<section class="header<?php print $class; ?>">
    <div class="mobile-header">
        <div class="container">
            <div class="row sb aic">
                <div class="subscribe">
                    <?php
                    if (isset($options['subscribe-on']) && $options['subscribe-on'] == 1) {
                        ?>
                        <a href="#" class="st-btn subscribe-open"><?php print $options['popup-btn-name']; ?></a>
                    <?php } ?>
                </div>
                <div class="logotype">
                    <?php if (is_front_page()) { ?>
                        <span></span>
                    <?php } else { ?>
                        <a href="/" aria-label="Головна сторінка"></a>
                    <?php } ?>
                </div>
                <div class="menu"></div>
            </div>
        </div>
    </div>
    <div class="header-top">
        <div class="container">
            <div class="row sb aic">
                <div class="logotype">
                    <?php if (is_front_page()) { ?>
                        <span><img src="/wp-content/themes/budzdorova/images/logotype.svg" width="280" height="64" alt="Будь здорова!" /></span>
                    <?php } else { ?>
                        <a href="/" aria-label="Головна сторінка"><img src="/wp-content/themes/budzdorova/images/logotype.svg" width="280" height="64" alt="Будь здорова!" /></a>
                    <?php } ?>
                </div>
                <div class="header-right row aic">
                    <div class="header-live-search">
                        <?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
                    </div>
                    <a href="#" class="open-search" aria-label="Пошук"></a>
                    <?php
                    if (isset($options['subscribe-on']) && $options['subscribe-on'] == 1) {
                    ?>
                    <a href="#" class="st-btn subscribe-open"><?php print $options['popup-btn-name']; ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="header-live-search">
            <?php echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
        </div>
        <div class="container">
            <?php
            $args = array('menu_class' => 'row sb', 'theme_location' => 'primary');
            wp_nav_menu($args);
            ?>
        </div>
    </div>
</section>
