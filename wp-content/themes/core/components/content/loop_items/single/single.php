<?php
declare( strict_types=1 );
$controller = \Tribe\Project\Templates\Components\content\loop_items\single\Controller::factory();
?>
<article class="item-single">

	<?php // TODO: featured image ?>

    <div class="item-single__content s-sink t-sink">
        <?php the_content() ?>
    </div>

    <footer class="item-single__footer">

        <ul class="item-single__meta">

            <li class="item-single__meta-date">
                <time datetime="">
                    <?php the_date() ?>
                </time>
            </li>

            <li class="item-single__meta-author">
                <?php echo esc_html( __('by') ) ?>
                <a href="<?php the_author_link(); ?>" rel="author">
                    <?php the_author() ?>
                </a>
            </li>

        </ul>

        <?php //TODO: Share Component ?>

    </footer>

</article>
