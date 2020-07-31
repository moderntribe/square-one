<?php

$controller = \Tribe\Project\Templates\Components\content\search_item\Controller::factory();

?>
<article class="item-loop item-loop--search item-loop--{{ post.post_type|esc_attr }}">

    <header class="item-loop__header">

        <h3 class="item-loop__title">
            <a href="<?php the_permalink() ?>>" rel="bookmark">
               <?php echo esc_html( get_the_title() ) ?>
            </a>
        </h3>

    </header>

    <?php the_post_thumbnail() // TODO: Do we need to replace this with our image handling? ?>

    <?php the_excerpt() ?>

</article>
