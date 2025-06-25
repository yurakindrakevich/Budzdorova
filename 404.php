<?php
/**
 * The template for displaying 404 pages (Not Found)
 */
get_header();
?>

<div class="normal-page inner-page">
    <div class="page-hero">
        <div class="page-info row">
            <h1>Сторінка не знайдена</h1>
        </div>
    </div>
    <div class="page-main">
        <div class="container">
            <div class="post-content">
                <div class="not-found-text">
                    <p><strong>Стривай, не йди!</strong></p>
                    <p>Хоч цієї сторінки і немає на нашому сайті, але ж у нас ще багато цікавого!</p>
                    <p>Ти можеш продовжити свій шлях, перейшовши на <a href="/">Головну сторінку</a>, або повернутися до <a href="javascript:history.go(-1)" mce_href="javascript:history.go(-1)">попередньої сторінки.</a></p>
                </div>
                <?php
                $args = [
                    'numberposts' => 4,
                    'post_type' => 'post',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'post_status' => 'publish',
                    'has_password' => FALSE,
                ];
                $useful_posts = get_posts($args);
                if (count($useful_posts)) {
                    print '<div class="useful-posts">';
                    print '<div class="border-title row sb"><h2>Читайте також:</h2></div>';
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
                ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
