<?php
declare( strict_types=1 );

use \Tribe\Project\Templates\Components\comments\comments_section\Comments_Section_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = Comments_Section_Controller::factory();
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments" data-js="comment-form">

	<?php if ( have_comments() ) { ?>

		<h6 class="comments__title h4">
			<?php echo $c->get_title(); ?>
		</h6>

		<ol class="comments__list">
			<?php echo $c->get_comments(); ?>
		</ol>

		<?php get_template_part(
			'components/pagination/comments/comments',
			null,
			$c->get_pagination_args()
		); ?>

		<?php if ( ! comments_open() && ! pings_open() ) { ?>
			<p class="comments__none">
				<?php esc_html_e( 'Comments are closed.', 'tribe' ); ?>
			</p>
		<?php } ?>

	<?php } ?>

	<?php echo $c->get_comment_form(); ?>

</div>


