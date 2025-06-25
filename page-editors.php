<?php
/**
 * Template Name: Editors page
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
                $rows = get_field('tabs');
                if($rows) {
                    foreach ($rows as $row) {
                        $tabs = $row['tab'];
                        if (count($tabs)) {
                            print '<div class="tabs">';
                            $i=1;
                            print '<div class="tabs-links row aic jcc">';
                            foreach ($tabs as $tab) {
                                $class = '';
                                if ($i == 1) {
                                    $class = ' active';
                                }
                                $tab_name = $tab['tab_name'];
                                print '<div class="tab'.$class.'" id="tab-'.$i.'">' . $tab_name . '</div>';
                                $i++;
                            }
                            print '</div>';
                            print '<div class="tabs-contents">';
                            $i=1;
                            foreach ($tabs as $tab) {
                                $class = '';
                                if ($i == 1) {
                                    $class = ' active';
                                }
                                $tab_text = $tab['tab_text'];
                                $select_authors = $tab['select_authors'];
                                $select_partners = $tab['select_partners'];
                                print '<div class="tab-content'.$class.'" id="tab-content-'.$i.'">';
                                if (!empty($tab_text)) {
                                    print '<div class="text-field">' . $tab_text . '</div>';
                                }
                                if (!empty($select_authors)) {
                                    foreach ($select_authors as $select_author) {
                                        $user_url = get_author_posts_url($select_author['ID']);
                                        $profile_position = get_field('user_position', 'user_' . $select_author['ID']);
                                        $profile_avatar = get_field('user_avatar', 'user_' . $select_author['ID']);
                                        print '<a href="'.$user_url.'" class="author-block">';
                                        if (!empty($profile_avatar)) {
                                            print '<div class="avatar">'.wp_get_attachment_image( $profile_avatar['ID'], 'user' ).'</div>';
                                        } else {
                                            if (!empty($select_author['user_avatar'])) {
                                                print '<div class="avatar">' . $select_author['user_avatar'] . '</div>';
                                            }
                                        }
                                        print '<div class="body">';
                                        print '<div class="name">'.$select_author['display_name'].'</div>';
                                        if (!empty($profile_position)) {
                                            print '<div class="position">'.$profile_position.'</div>';
                                        }
                                        print '<div class="description">'.$select_author['user_description'].'</div>';
                                        print '</div>';
                                        print '</a>';
                                    }
                                }
                                if (!empty($select_partners)) {
                                    foreach ($select_partners as $select_partner) {
                                        //print_r($select_partner);
                                        $id = $select_partner->ID;
                                        $partner_link = get_post_meta($id, 'partner_link', true);
                                        $title = get_the_title($id);
                                        $body = get_post_field('post_content', $id);
                                        $image = get_the_post_thumbnail($id, 'user');
                                        if (!empty($partner_link)) {
                                            print '<div class="partner-block-with-link">';
                                            print '<a href="'.$partner_link.'" target="_blank" rel="nofollow">';
                                        } else {
                                            print '<div class="partner-block">';
                                        }
                                        print '<div class="image">'.$image.'</div>';
                                        print '<div class="body">';
                                        print '<div class="title">'.$title.'</div>';
                                        if (!empty($body)) {
                                            print '<div class="content">'.apply_filters('the_content', $body).'</div>';
                                        }
                                        print '</div>';
                                        if (!empty($partner_link)) {
                                            print '</a>';
                                        }
                                        print '</div>';
                                    }
                                }
                                print '</div>';
                                $i++;
                            }
                            print '</div>';
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
