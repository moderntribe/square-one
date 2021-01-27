<?php
declare( strict_types=1 );

use Tribe\Project\Templates\Components\dialog\Dialog_Controller;

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$c         = Dialog_Controller::factory( $args );
$content   = $c->get_content();

if ( empty( $content ) ) {
	return;
}
?>
<<?php echo $c->get_tag(); ?>
<?php echo $c->get_classes(); ?>
<?php echo $c->get_attrs(); ?>
>
<script data-js="dialog-content-<?php echo $c->get_dialog_id(); ?>" type="text/template">
    <div class="c-dialog">
        <div class="c-dialog__overlay">
            <div class="c-dialog__header">
                <div class="c-dialog__title"><?php echo $c->get_dialog_title(); ?></div>
            </div>
            <div class="c-dialog__overlay-inner">
                <div class="c-dialog__content-wrapper">
                    <div class="c-dialog__content-inner">
                        <?php echo $content; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

</<?php echo $c->get_tag(); ?>>
