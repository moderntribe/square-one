<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\comments\comment\Controller::factory( $args ); ?>
<li <?php echo $c->classes(); ?><?php echo $c->attributes(); ?>>

	<header class="comment__header">

		<?php echo $c->get_gravatar(); ?>

		<h5 class="comment__title" rel="author">
			<cite><?php echo esc_html(  get_comment_author() ); ?></cite>
		</h5>

		<time class="comment__time" datetime="<?php echo esc_attr( $c->time( 'c' ) ); ?> ">
			<?php echo esc_html( $c->time( 'g:i A - M j, Y' ) ); ?>
		</time>

	</header><!-- .comment-header -->

	<div class="comment__text">
		<?php echo $c->edit_link(); ?>

		<?php echo wp_kses_post( get_comment_text() ); ?>

	</div><!-- .comment-text -->

	<?php echo $c->get_moderation_message() ; ?>

	<?php echo $c->reply_link() ; ?>

</li>
