<?php
/**
 * The template for displaying author page
 */
get_header();
$object = get_queried_object();
$author_id = $object->ID;
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
            <h1><?php print 'Архіви автора: '. get_the_archive_title(); ?></h1>
        </div>
    </div>
    <?php if (!empty($author_id)) {
        print '<div class="author-page-line container"><div class="author-block">';
        $profile_position = get_field('user_position', 'user_' . $author_id);
        $profile_avatar = get_field('user_avatar', 'user_' . $author_id);
        $user_description = get_field('description', 'user_' . $author_id);
        if (!empty($profile_avatar)) {
            print '<div class="avatar">' . wp_get_attachment_image($profile_avatar['ID'], 'user') . '</div>';
        }
        print '<div class="body">';
        print '<div class="name">' . $object->display_name . '</div>';
        if (!empty($profile_position)) {
            print '<div class="position">' . $profile_position . '</div>';
        }
        print '<div class="description">' . $user_description . '</div>';
        print '</div>';
        print '</div></div>';
    } ?>
    <?php if (have_posts()) : ?>
        <div class="sub-categories-posts">
            <div class="container">
                <div class="row-posts row">
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
    <?php endif; ?>
</div>
<?php get_footer(); ?>
