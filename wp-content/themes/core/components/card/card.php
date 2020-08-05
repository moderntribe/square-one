<?php
declare( strict_types=1 );

/**
 * @var array $args Arguments passed to the template
 */
// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
$controller = \Tribe\Project\Templates\Components\card\Controller::factory( $args );

/*

<div {{ card_classes|stringify }}>

    {% if before_card %}
        {{ before_card }}
    {% endif %}

    {% if image %}
        <header {{ card_header_classes|stringify }}>
            {{ component( 'image/Image.php', image ) }}
        </header>
    {% endif %}

    <div {{ card_content_classes|stringify }}>

        {% if pre_title %}
            {{ pre_title }}
        {% endif %}

        {% if title %}
            {{ component( 'text/Text.php', title ) }}
        {% endif %}

        {% if post_title %}
            {{ post_title }}
        {% endif %}

        {% if text %}
            {{ component( 'text/Text.php', text ) }}
        {% endif %}

        {% if button %}
            {{ component( 'button/Button.php', button ) }}
        {% endif %}

    </div>

    {% if after_card %}
        {{ after_card }}
    {% endif %}

</div>

*/
?>
