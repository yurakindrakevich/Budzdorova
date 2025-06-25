<?php
/**
 * Template Name: Home page template
 */
?>
<?php
get_header();
?>
    <section class="main-content">
        <div class="container">
            <div class="hero-slides row sb">
                <?php
                $args = [
                    'numberposts' => 1,
                    'post_type' => 'post',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish',
                    'has_password' => FALSE,
                    'meta_query' => [
                        [
                            'key' => 'see_options',
                            'value' => sprintf('"%s"', '1'),
                            'compare' => 'LIKE',
                        ],
                    ],
                ];
                $main_posts = get_posts($args);
                if (count($main_posts)) {
                    print '<div class="main-post">';
                    foreach ($main_posts as $main_post) {
                        setup_postdata($main_post);
                        $id = $main_post->ID;
                        $image = get_the_post_thumbnail($id, 'big-post');
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
                        ?>
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
                                <h2><a href="<?php print get_the_permalink($id); ?>"><?php print $title; ?></a></h2>
                                <p><a href="<?php print get_the_permalink($id); ?>"><?php print $trim_content; ?></a></p>
                            </div>
                        </div>
                        <?php
                    }
                    print '</div>';
                }
                wp_reset_postdata();
                $args = [
                    'numberposts' => 10,
                    'post_type' => 'post',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish',
                    'has_password' => FALSE,
                    'meta_query' => [
                        [
                            'key' => 'see_options',
                            'value' => sprintf('"%s"', '2'),
                            'compare' => 'LIKE',
                        ],
                    ],
                ];
                $main_posts = get_posts($args);
                if (count($main_posts)) {
                    print '<div class="hero-slider"><div class="slides-posts"><div class="swiper-wrapper">';
                    foreach ($main_posts as $main_post) {
                        setup_postdata($main_post);
                        $id = $main_post->ID;
                        $image = get_the_post_thumbnail($id, 'slide-post');
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
                        ?>
                        <div class="swiper-slide">
                            <div class="image">
                                <a href="<?php print get_the_permalink($id); ?>">
                                    <?php print $image; ?>
                                </a>
                            </div>
                            <div class="post-content">
                                <?php if (!empty($categories)) { ?>
                                    <div class="category"><a
                                                href="<?php print get_category_link($categories[0]->term_id); ?>"><?php print esc_html($categories[0]->name); ?></a>
                                    </div>
                                <?php } ?>
                                <h2><a href="<?php print get_the_permalink($id); ?>"><?php print $title; ?></a></h2>
                                <p><a href="<?php print get_the_permalink($id); ?>"><?php print $trim_content; ?></a></p>
                            </div>
                        </div>
                        <?php
                    }
                    print '</div><div class="button-next"></div><div class="button-prev"></div></div></div>';
                }
                wp_reset_postdata();
                ?>
            </div>
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'category',
                'hide_empty' => false,
                'meta_query' => array(
                    array(
                        'key' => 'front_page_photo',
                        'value' => array(''),
                        'compare' => 'NOT IN'
                    )
                )
            ));
            if (count($categories)) {
                print '<div class="border-title row sb"><h2>Дослідити тему</h2></div>';
                print '<div class="category-slider">';
                foreach ($categories as $category) {
                    $image_id = get_field('front_page_photo', $category->taxonomy . '_' . $category->term_id);
                    if (!empty($image_id['ID'])) {
                        print '<a href="' . get_category_link($category->term_id) . '" class="swiper-slide">';
                        $image = wp_get_attachment_image($image_id['ID'], 'small');
                        print '<div class="image">' . $image . '</div>';
                        print '<div class="title">' . $category->name . '</div>';
                        print '</a>';
                    }
                }
                print '</div>';
            }
            $args = [
                'numberposts' => 4,
                'post_type' => 'post',
                'orderby' => 'date',
                'order' => 'DESC',
                'post_status' => 'publish',
                'has_password' => FALSE,
                'meta_query' => [
                    [
                        'key' => 'see_options',
                        'value' => sprintf('"%s"', '3'),
                        'compare' => 'LIKE',
                    ],
                ],
            ];
            $theme_posts = get_posts($args);
            if (count($theme_posts)) {
                print '<div class="theme-posts">';
                print '<div class="border-title row sb"><h2>Теми місяця</h2></div>';
                print '<div class="grid two-grid">';
                foreach ($theme_posts as $theme_post) {
                    setup_postdata($theme_post);
                    $id = $theme_post->ID;
                    $image = get_the_post_thumbnail($id, 'small');
                    $title = get_the_title($id);
                    $teaser_text = get_post_field('teaser_text', $id);
                    if (empty($teaser_text)) {
                        $content = strip_tags(get_post_field('post_content', $id));
                        $content = strip_shortcodes($content);
                        $trim_content = mb_strimwidth($content, 0, 60, '...');
                    } else {
                        $trim_content = $teaser_text;
                    }
                    $trim_title = mb_strimwidth($title, 0, 45, '...');
                    $categories = get_the_category($id);
                    ?>
                    <div class="post row row-post">
                        <div class="image">
                            <a href="<?php print get_the_permalink($id); ?>">
                                <?php print $image; ?>
                            </a>
                        </div>
                        <div class="post-content">
                            <?php if (!empty($categories)) { ?>
                                <div class="category"><a
                                            href="<?php print get_category_link($categories[0]->term_id); ?>"><?php print esc_html($categories[0]->name); ?></a>
                                </div>
                            <?php } ?>
                            <h3><a href="<?php print get_the_permalink($id); ?>"><?php print $trim_title; ?></a></h3>
                            <p><a href="<?php print get_the_permalink($id); ?>"><?php print $trim_content; ?></a></p>
                        </div>
                    </div>
                    <?php
                }
                print '</div></div>';
            }
            wp_reset_postdata();
            $args = [
                'numberposts' => 4,
                'post_type' => 'post',
                'orderby' => 'date',
                'order' => 'DESC',
                'post_status' => 'publish',
                'has_password' => FALSE,
                'meta_query' => [
                    [
                        'key' => 'see_options',
                        'value' => sprintf('"%s"', '4'),
                        'compare' => 'LIKE',
                    ],
                ],
            ];
            $useful_posts = get_posts($args);
            if (count($useful_posts)) {
                print '<div class="useful-posts">';
                print '<div class="border-title row sb"><h2>Цим цікавляться найбільше</h2></div>';
                print '<div class="grid four-grid">';
                foreach ($useful_posts as $useful_post) {
                    setup_postdata($useful_post);
                    $id = $useful_post->ID;
                    $image = get_the_post_thumbnail($id, 'small-post');
                    $title = get_the_title($id);
                    $categories = get_the_category($id);
                    ?>
                    <div class="post row column">
                        <div class="image">
                            <a href="<?php print get_the_permalink($id); ?>">
                                <?php print $image; ?>
                            </a>
                        </div>
                        <div class="post-content">
                            <?php if (!empty($categories)) { ?>
                                <div class="category"><a
                                            href="<?php print get_category_link($categories[0]->term_id); ?>"><?php print esc_html($categories[0]->name); ?></a>
                                </div>
                            <?php } ?>
                            <h4><a href="<?php print get_the_permalink($id); ?>"><?php print $title; ?></a></h4>
                        </div>
                    </div>
                    <?php
                }
                print '</div></div>';
            }
            wp_reset_postdata();

            print '<div class="video-podcast grid">';
            $args = [
                'numberposts' => 1,
                'post_type' => 'video',
                'orderby' => 'date',
                'order' => 'DESC',
                'post_status' => 'publish',
                'has_password' => FALSE,
            ];
            $videos = get_posts($args);
            if (count($videos)) {
                print '<div class="video-post">';
                foreach ($videos as $video) {
                    setup_postdata($video);
                    $id = $video->ID;
                    $title = get_the_title($id);
                    $bg_image = get_post_field('video_background_image', $id);
                    $bg_image_style = $logotype = $open_icon = '';
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
                        $video_file_url = '';
                        $video_file = get_post_field('video_file', $id);
                        if (!empty($video_file)) {
                            $video_file_url = wp_get_attachment_url($video_file);
                        }
                        $bg_image_style = 'background-image: url(' . wp_get_attachment_image_url($bg_image, '', '', '') . ');background-repeat: no-repeat;background-size: cover;';
                        $read_icon_color = get_post_field('read_icon_color', $id);
                        if ($read_icon_color == 1) {
                            $open_icon = '<img src="/wp-content/themes/budzdorova/images/circle-arrow-dark.svg" alt="Відкрити подкаст">';
                        } elseif ($read_icon_color == 2) {
                            $open_icon = '<img src="/wp-content/themes/budzdorova/images/circle-arrow-white.svg" alt="Відкрити подкаст">';
                        }
                        ?>
                        <div class="video-block" style="<?php print $bg_image_style; ?>" data-video="<?php print $video_file_url; ?>">
                            <div class="video-block-inner row sb column">
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
                print '<div class="all-link"><a href="/video/">Всі відео</a></div>';
                print '</div>';
            }
            wp_reset_postdata();
            $args = [
                'numberposts' => 1,
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
                    $bg_image_style = $logotype = $open_icon = '';
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
                print '<div class="all-link"><a href="/podcasts/">Всі подкасти</a></div>';
                print '</div>';
            }
            wp_reset_postdata();
            print '</div>';

            $categories = get_terms(array(
                'taxonomy' => 'library',
                'hide_empty' => false
            ));
            if (count($categories)) {
                print '<div class="disease"><div class="border-title row sb"><h2>Здоров’я та медицина</h2><div class="read-more"><a href="/library/">Більше</a></div></div>';
                print '<div class="disease-buttons row aic">';
                foreach ($categories as $category) {
                    $show = get_field('disease_front', 'term_' . $category->term_id, false);
                    if ($show) {
                        print '<a href="' . get_category_link($category->term_id) . '" class="st-btn yellow-style">' . $category->name . '</a>';
                    }
                }
                print '</div></div>';
            }
            ?>

        </div>
    </section>
<?php get_footer(); ?>