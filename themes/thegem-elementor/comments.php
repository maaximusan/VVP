<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 */

if ( post_password_required() ) {
	return;
}

global $thegem_comments_params;
$params = $thegem_comments_params;

?>

<div id="comments" class="comments-area <?php if (!empty($params)): ?>post-comments<?php endif; ?>">
	
	<?php if ( have_comments() ) :?>
		
		<?php if (!empty($params)): ?>
			<?php if ( !empty($params['title_list'])): ?>
                <h2 class="post-comments__title">
	            <span class="<?= $params['title_styled'] ?>">
                    <?= get_comments_number(); ?> <?= esc_html_e($params['title_list_label']); ?>
                </span>
                </h2>
			<?php endif; ?>
		<?php else: ?>
            <h2 class="comments-title"><?php esc_html_e('Comments', 'thegem'); ?> <span class="light">(<?php echo get_comments_number(); ?>)</span></h2>
		<?php endif; ?>

        <div class="<?php if (!empty($params)): ?>post-comments__list<?php else: ?>comment-list<?php endif; ?>">
			<?php
			if (!empty($params)) {
				wp_list_comments( array(
					'avatar' => $params['avatar'],
					'avatar_size' => !empty($params['avatar_size']['size']) ? $params['avatar_size']['size'] : 70,
					'name' => $params['name'],
					'name_styled' => $params['name_styled'],
					'name_link' => $params['name_link'],
					'reply' => $params['reply'],
					'reply_label' => $params['reply_label'],
					'reply_styled' => $params['reply_styled'],
					'date' => $params['date'],
					'date_link' => $params['date_link'],
					'date_styled' => $params['date_styled'],
					'desc' => $params['desc'],
					'desc_styled' => $params['desc_styled'],
					'callback' => 'thegem_shortcode_comment'
				) );
			} else {
				wp_list_comments( array(
					'style'      => 'div',
					'short_ping' => true,
					'avatar_size'=> 70,
					'callback' => 'thegem_comment'
				) );
			}
			?>
        </div><!-- .comment-list -->
		
		<?php if ( ! comments_open() ) : ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'thegem' ); ?></p>
		<?php endif; ?>
	
	<?php endif; // have_comments() ?>
	
	<?php
	if (!empty($params)) {
		$comments_form_args = array(
			'fields' => array(
				'author' => '<div class="comment-form__line col-md-4 col-xs-12"><label>'.esc_attr__('Name', 'thegem').($req ? ' *' : '').'</label><input type="text" name="author" id="comment-author" value="'.esc_attr($comment_author).'" size="22" tabindex="1"'.($req ? ' aria-required="true"' : '').'/></div>',
				'email' => '<div class="comment-form__line col-md-4 col-xs-12"><label>'.esc_attr__('Mail', 'thegem').($req ? ' *' : '').'</label><input type="text" name="email" id="comment-email" value="'.esc_attr($comment_author_email).'" size="22" tabindex="2"'.($req ? ' aria-required="true"' : '').'/></div>',
				'url' => '<div class="comment-form__line col-md-4 col-xs-12"><label>'.esc_attr__('Website', 'thegem').'</label><input type="text" name="url" id="comment-url" value="'.esc_attr($comment_author_url).'" size="22" tabindex="3" /></div>'
			),
			'comment_notes_after' => '',
			'comment_notes_before' => '',
			'comment_field' => '<div class="row"><div class="col-xs-12"><label>'.esc_attr__('Message *', 'thegem').'</label><textarea name="comment" id="comment" rows="7" tabindex="4"></textarea></div></div>',
			'must_log_in' => '<div class="comment-form-message">'.sprintf(wp_kses(__('You must be <a href="%s">logged in</a> to post a comment.', 'thegem'), array('a' => array('href' => array()))), esc_url(wp_login_url( get_permalink() ))).'</div>',
			'logged_in_as' => '<div class="comment-form-message">'.sprintf(wp_kses(__('Logged in as <a href="%1$s">%2$s</a>.', 'thegem'), array('a' => array('href' => array()))), esc_url(get_edit_user_link()), $user_identity).' <a href="'.esc_url(wp_logout_url(get_permalink())).'" title="'.esc_attr__('Log out of this account', 'thegem').'">'.esc_html__('Log out &raquo;', 'thegem').'</a></div>',
			'submit_field' => '<div class="form-submit gem-button-position-inline">%1$s</div><p>%2$s</p>',
			'label_submit' => esc_html__('Send Comment', 'thegem'),
			'class_submit' => 'gem-button gem-button-style-outline gem-button-size-'.$params['send_btn_size'].' submit',
			'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />%4$s</button>',
			'title_reply' => wp_kses(__('<span class="'.$params['title_styled'].'">'.$params['title_form_label'].'</span>', 'thegem'), array('span' => array('class' => array()))),
			'title_reply_to' => wp_kses(__('<span class="'.$params['title_styled'].'">Comment to %s</span>', 'thegem'), array('span' => array('class' => array()))),
			'must_log_in' => sprintf(wp_kses(__('You must be <a href="%s">logged in</a> to post a comment.', 'thegem'), array('a' => array('href' => array()))), esc_url(wp_login_url( get_permalink() ))),
		);
	} else {
		$comments_form_args = array(
			'fields' => array(
				'author' => '<div class="col-md-4 col-xs-12 comment-author-input"><input type="text" name="author" id="comment-author" value="'.esc_attr($comment_author).'" size="22" tabindex="1"'.($req ? ' aria-required="true"' : '').' placeholder="'.esc_attr__('Name', 'thegem').($req ? ' *' : '').'" /></div>',
				'email' => '<div class="col-md-4 col-xs-12 comment-email-input"><input type="text" name="email" id="comment-email" value="'.esc_attr($comment_author_email).'" size="22" tabindex="2"'.($req ? ' aria-required="true"' : '').' placeholder="'.esc_attr__('Mail', 'thegem').($req ? ' *' : '').'" /></div>',
				'url' => '<div class="col-md-4 col-xs-12 comment-url-input"><input type="text" name="url" id="comment-url" value="'.esc_attr($comment_author_url).'" size="22" tabindex="3" placeholder="'.esc_attr__('Website', 'thegem').'" /></div>'
			),
			'comment_notes_after' => '',
			'comment_notes_before' => '',
			'comment_field' => '<div class="row"><div class="col-xs-12"><textarea name="comment" id="comment" cols="58" rows="10" tabindex="4" placeholder="'.esc_attr__('Message *', 'thegem').'"></textarea></div></div>',
			'must_log_in' => '<div class="comment-form-message">'.sprintf(wp_kses(__('You must be <a href="%s">logged in</a> to post a comment.', 'thegem'), array('a' => array('href' => array()))), esc_url(wp_login_url( get_permalink() ))).'</div>',
			'logged_in_as' => '<div class="comment-form-message">'.sprintf(wp_kses(__('Logged in as <a href="%1$s">%2$s</a>.', 'thegem'), array('a' => array('href' => array()))), esc_url(get_edit_user_link()), $user_identity).' <a href="'.esc_url(wp_logout_url(get_permalink())).'" title="'.esc_attr__('Log out of this account', 'thegem').'">'.esc_html__('Log out &raquo;', 'thegem').'</a></div>',
			'submit_field' => '<div class="form-submit gem-button-position-inline">%1$s</div><p>%2$s</p>',
			'label_submit' => esc_html__('Send Comment', 'thegem'),
			'class_submit' => 'gem-button gem-button-size-medium submit',
			'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" value="%4$s" />%4$s</button>',
			'title_reply' => wp_kses(__('Leave <span class="light">a comment</span>', 'thegem'), array('span' => array('class' => array()))),
			'title_reply_to' => esc_html__('Comment to %s', 'thegem'),
			'must_log_in' => sprintf(wp_kses(__('You must be <a href="%s">logged in</a> to post a comment.', 'thegem'), array('a' => array('href' => array()))), esc_url(wp_login_url( get_permalink() ))),
		);
	}
	
	if (has_action( 'set_comment_cookies', 'wp_set_comment_cookies') && get_option('show_comments_cookies_opt_in')) {
		$consent = empty($commenter['comment_author_email'] ) ? '' : ' checked="checked"';
		$fields['cookies'] = '<p class="col-md-12 col-xs-12 comment-form-cookies-consent"><input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" class="gem-checkbox" type="checkbox" value="yes"' . $consent . ' />' .
			'<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.' ) . '</label></p>';
		if ( isset( $comments_form_args['fields'] ) && ! isset( $comments_form_args['fields']['cookies'] ) ) {
			$comments_form_args['fields']['cookies'] = $fields['cookies'];
		}
	}
	comment_form($comments_form_args);
	?>
	
	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
        <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
            <div class="nav-previous"><?php previous_comments_link( esc_html__( 'Prev', 'thegem' ) ); ?></div>
            <div class="nav-next"><?php next_comments_link( esc_html__( 'Next', 'thegem' ) ); ?></div>
        </nav><!-- #comment-nav-below -->
	<?php endif; // Check for comment navigation. ?>

</div><!-- #comments -->
