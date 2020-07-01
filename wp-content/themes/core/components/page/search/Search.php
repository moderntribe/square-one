<?php

namespace Tribe\Project\Templates\Components\Page;

class Search extends Index {

	public function render(): void {
		?>
		{% if breadcrumbs %}
        {{ component( 'breadcrumbs/Breadcrumbs.php', breadcrumbs ) }}
        {% endif %}

        {{ component( 'header/subheader/Subheader.php', subheader ) }}

        <div class="l-container">

            {% if posts|length > 0 %}

            {% for post in posts %}
                {{ component( 'content/search-item/Search_Item.php', { 'post': post } ) }}
            {% endfor %}

            {{ component( 'pagination/Pagination.php', pagination ) }}

            {% else %}

            {{ component( 'content/no-results/No_Results.php' ) }}

            {% endif %}

        </div>
		<?php
	}

}