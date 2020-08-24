<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\comments\comment\Comment_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Comment_Controller::factory( $args );
?>

<li <?php echo $c->get_classes(); ?><?php echo $c->get_attrs(); ?>>

	<header class="comment__header">

		<?php echo $c->get_gravatar(); ?>

		<h5 class="comment__title" rel="author">
			<cite><?php echo esc_html( get_comment_author() ); ?></cite>
		</h5>

		<time class="comment__time" datetime="<?php echo esc_attr( $c->get_time( 'c' ) ); ?>">
			<?php echo esc_html( $c->get_time( 'g:i A - M j, Y' ) ); ?>
		</time>

	</header><!-- .comment-header -->

	<div class="comment__text">
		<?php echo $c->get_edit_link(); ?>
		<?php echo wp_kses_post( get_comment_text() ); ?>
	</div><!-- .comment-text -->

	<?php get_template_part(
		'components/text/text',
		null,
		$c->get_moderation_message_args()
	); ?>

	<?php echo $c->get_reply_link() ; ?>

</li>
