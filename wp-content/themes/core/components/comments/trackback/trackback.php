<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c = \Tribe\Project\Templates\Components\comments\trackback\Controller::factory( $args );
?>

<li <?php echo $c->classes(); ?><?php echo $c->attributes(); ?>>
	<p>
		<strong><?php echo esc_html( $c->label ); ?></strong>

		<?php echo get_comment_author_link( $c->comment_id ); ?>

		{% if edit_link %}
		<span class="comment__action-edit">
				{{ edit_link }}
			</span>
		{% endif %}
	</p>
</li>
