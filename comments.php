<div class="comments clearfix box">
	<?php $comments = get_comments('post_id='.$post->ID.'&status=approve');?>
	<h5 id="comments-title"><span><?php echo count($comments); ?> 条评论</span></h5>
	<div class="commentshow">
		<ol class="comments-list">
			<?php wp_list_comments('type=comment&callback=comment&max_depth=1000&style=ol'); ?>
		</ol>
		<nav class="commentnav" data-postid="<?php echo $post->ID?>"><?php paginate_comments_links('prev_text=«&next_text=»');?></nav>
	</div>

	<div id="respond" class="comment-respond">
		<h5 id="replytitle" class="comment-reply-title">发表一条评论 <small><a rel="nofollow" id="cancel-comment-reply-link" href="#respond" style="display:none;">取消回复</a></small></h5>
		<form action="#" method="post" id="commentform" class="clearfix">
			<?php if ( $user_ID ) { ?>
			<p style="margin-bottom:10px"><i class="fa fa-user"></i> 已登录为 <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>
			&nbsp;|&nbsp;
			<i class="fa fa-mail-reply"></i> <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="退出登录">登出 &raquo;</a></p>
			<?php } else { ?>
			<p class="input-row replay_author"><input type="text" name="author" class="text_input" id="author" size="22" tabindex="1" placeholder="署名 *"/>
			</p>

			<p class="input-row replay_email"><input type="text" name="email" class="text_input" id="email" size="22" tabindex="2" placeholder="<?php _e('E-MAIL', 'quench');?> *" value="<?php echo rand().'@random.mail' ?>"/>
			</p>

			<p class="input-row replay_url"><input type="text" name="url" class="text_input" id="url" size="22" tabindex="3"  placeholder="<?php _e('WEBSITE', 'quench');?>"/>
			</p>

			<?php }?>

			<?php comment_id_fields(); do_action('comment_form', $post->ID); ?>



			<p class="input-row message-row"><textarea class="text_area" rows="3" cols="80" name="comment" id="comment" tabindex="4"  placeholder="欢迎在这里畅所欲言..."></textarea></p>
			<input type="submit" name="submit" class="button" id="submit" tabindex="5" value="提交评论"/>

		</form>
	</div>

</div>
