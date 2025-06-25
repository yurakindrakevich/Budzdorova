<?php
/**
 * The template for displaying all single posts
 */
get_header();
$id = get_the_ID();
$auth = get_post_field('post_author', $id);
$reviewer = get_post_field('reviewer', $id);
$page_permalink = get_the_permalink($id);
$page_title = get_the_title($id);
if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
}

?>
    <div class="single-post inner-page">
        <div class="container">
            <?php
            if (function_exists('yoast_breadcrumb')) {
                yoast_breadcrumb('<div class="breadcrumbs">', '</div>');
            }
            ?>
            <div class="inner-container">
                <div class="with-sidebar row sb">
                    <div class="main-content">
                        <div class="post-head">
                            <h1><?php print $page_title; ?></h1>
                            <div class="post-info row aic">
                                <?php if (!empty($auth)) { $user_info = get_userdata($auth); $user_url = get_author_posts_url($auth); ?>
                                    <div class="author"><a href="<?php print $user_url; ?>" target="_blank"><?php print $user_info->display_name; ?></a></div>
                                <?php } ?>
                                <div class="pub-date">Опубліковано: <span><?php print get_the_date('F d, Y', $id); ?></span></div>
                                <?php if (!empty($reviewer[0])) { ?>
                                    <div class="reviewer">Фаховий рецензент: <a href="<?php print get_the_permalink($reviewer[0]); ?>"><?php print get_the_title($reviewer[0]); ?></a></div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="post-content">
                            <?php the_content(); ?>
                        </div>
                        <div class="post-footer">
                            <div class="post-footer-top row aic">
                                <div class="collect-tooltips">
                                    <div class="open-collect-tooltips">Джерела</div>
                                    <div class="all-tooltip-box">
                                        <div class="close-all-tooltip">x</div>
                                        <div class="all-tooltip"></div>
                                    </div>
                                </div>
                                <div class="post-share row aic">
                                    <div class="label">Поділитись:</div>
                                    <div class="share-links row aic a2a_kit a2a_kit_size_32 a2a_default_style"
                                         data-a2a-url="<?php print $page_permalink; ?>"
                                         data-a2a-title="<?php print $page_title; ?>">
                                        <a class="a2a_button_facebook facebook"></a>
                                        <a class="a2a_button_telegram telegram"></a>
                                        <a class="a2a_button_x xxx"></a>
                                        <a class="a2a_button_email"></a>
                                        <a class="a2a_button_print"></a>
                                    </div>
                                </div>
                            </div>
                            <?php /*
                            <div class="post-comments">
                                <div class="add-comment"><a href="#" class="st-btn yellow-style">Додати коментар</a></div>
                                <?php
                                $comments_args = array(
                                    'label_submit' => __( 'Відправити', 'textdomain' ),
                                    'title_reply' => __( 'Додати коментар', 'textdomain' ),
                                    'comment_notes_after' => '',
                                    'comment_field' => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" aria-required="true"></textarea></p>',
                                );
                                comment_form( $comments_args );
                                $paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1;
                                $args = array(
                                    'post_id' => $id,
                                );
                                $comments = get_comments($args);
                                if (count($comments)) {
                                    print '<div class="comments-list"><div class="border-title">Коментарі ('.count($comments).')</div>';
                                        foreach ($comments as $comment) :
                                            $avatar = get_avatar( get_the_author_meta( 'ID' ), 50 );
                                        $comment_depth = budzdorova_get_comment_depth( $comment->comment_ID );
                                        ?>
                                           <div class="comment depth-<?php print $comment_depth; ?>" id="comment-<?php print $comment->comment_ID; ?>">
                                            <?php
                                            print '<div class="comment-author row aic">';
                                            if (!empty($avatar)) {
                                                print '<div class="avatar">'.$avatar.'</div>';
                                            } else {
                                                print '<div class="avatar"></div>';
                                            }
                                            print '<div class="info"><div class="author-name">'.$comment->comment_author.'</div><div class="comment-date">'.$comment->comment_date.'</div></div>';
                                            print '</div>';
                                            print '<div class="comment-body">'.$comment->comment_content.'</div>';
                                            print '<div class="comment-footer row sb aic">';
                                            print '<div class="like"><a href="#"><span>0</span></a></div>';
                                            print '<div class="reply">';
                                            $reply_link = get_comment_reply_link( array(
                                                'reply_text' => 'Відповісти',
                                                'depth'      => $comment_depth,
                                                'max_depth'  => get_option('thread_comments_depth'),
                                            ), $comment->comment_ID, $id );
                                            if ( $reply_link ) {
                                                print $reply_link;
                                            }
                                            print '</div>';

                                            print '</div>';
                                            print '</div>';
                                        endforeach;
                                    print '</div>';
                                } else {
                                    print '<div class="comments-list"><div class="border-title">Коментарі (0)</div><p class="no-content">Коментарів ще немає.</p></div>';
                                }
                                ?>
                            </div>
                                */ ?>
                        </div>
                    </div>
                    <div class="sidebar">
                        <?php /*
                        <div class="sidebar-box">
                            <div class="test-box-1">300x250</div>
                        </div>
                        <div class="sidebar-box">
                            <div class="test-box-2">300x600</div>
                        </div>
 */?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script async src="https://static.addtoany.com/menu/page.js"></script>
<?php get_footer(); ?>