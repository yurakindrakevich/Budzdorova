<?php
/**
 * Footer template
 */
?>
<section class="footer">
    <div class="footer-top">
        <div class="container row sb">
            <div class="footer-left">
                <div class="logotype">
                    <?php if (is_front_page()) { ?>
                        <span><img src="/wp-content/themes/budzdorova/images/logotype.svg" width="280" height="64" alt="Будь здорова!"/></span>
                    <?php } else { ?>
                        <a href="/"><img src="/wp-content/themes/budzdorova/images/logotype.svg" width="280" height="64"
                                         alt="Будь здорова!"/></a>
                    <?php } ?>
                </div>
                <?php if (isset($options['subscribe-on']) && $options['subscribe-on'] == 1) { ?>
                <div class="subscribe">
                    <a href="#" class="st-btn subscribe-open">Підписатися</a>
                </div>
                <?php } ?>
            </div>
            <div class="footer-right">
                <?php
                $args = array('menu_class' => 'grid', 'theme_location' => 'bottom');
                wp_nav_menu($args);
                ?>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <p>Контент «Будь здорова!» призначений лише для інформаційних та освітніх цілей. Наш веб-сайт не призначений для
                заміни професійної медичної консультації, діагностики чи лікування.</p>
            <div class="partner-link">
                <a href="https://synevo.ua" target="_blank">www.synevo.ua</a>
            </div>
        </div>
    </div>
</section>
<div class="video-container"><div class="video-logotype"></div><div class="video-close"></div><div class="video-container-inner"></div></div>
<?php wp_footer(); ?>
</body>
</html>