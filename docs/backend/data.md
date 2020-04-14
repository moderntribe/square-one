# Data to Front End

The front end uses Twig as a template engine.

Read more about Twig here: [https://twig.symfony.com/](https://twig.symfony.com/)

The back end passes data to the Twig templates using controllers in the `core/src/Templates` directory.  You will see familiar template and template related classes in there:

- Page.php
- Single.php
- Pagination/Loop.php
- Loop/Item.php

... and so on.

These classes all extend the `Twig_Template` class.  They all contain a `get_data()` method which is used by the Twig templates to access data needed in the front end.

The example below shows how you might get data needed for a post within the Loop and pass it to the front end.

```php
public function get_data(): array {
    $data[ 'post' ] = [
        'post_type'      => get_post_type(),
        'title'          => get_the_title(),
        'content'        => apply_filters( 'the_content', get_the_content() ),
        'excerpt'        => apply_filters( 'the_excerpt', get_the_excerpt() ),
        'permalink'      => get_the_permalink(),
        'featured_image' => $this->get_featured_image(),
        'time'           => $this->get_time(),
        'author'         => $this->get_author(),
        'primary_cat'    => $this->get_primary_category(),
        'target'         => $this->get_permalink_target(),
    ];

    return $data;
}

protected function get_featured_image(): string {
    $options = [
        Image::ATTACHMENT => \Tribe\Project\Templates\Models\Image::factory( get_post_thumbnail_id() ),
        'as_bg'         => true,
        'echo'          => false,
        'wrapper_class' => 'item__image',
        'shim'          => trailingslashit( get_template_directory_uri() ) . 'img/shims/16x9.png',
        'src_size'      => Image_Sizes::ITEM_SMALL,
        'srcset_sizes'  => [
            Image_Sizes::ITEM_SMALL,
            Image_Sizes::ITEM_MEDIUM,
            Image_Sizes::ITEM_LARGE,
            Image_Sizes::ITEM_XLARGE,
        ]
    ];

    return $this->factory->get( Image::class, $options )->render();
}
```

As you see in this example with the `Image_Sizes` class, the Template classes interact with the core plugin classes as needed to generate the data requested by the front end.

In the front end files you will see the data being used like the below example.  In `themes/core/content/loop/item.twig` we use the data prepared by the above php example:

```
{% block category %}
    {% if post.primary_cat %}
        <h3 class="item__category h5">
            <a href="{{ post.primary_cat.url|esc_url }}">
                {{ post.primary_cat.name|esc_html }}
            </a>
        </h3>
    {% endif %}
{%  endblock %}

{% block image %}
    <figure class="item__figure">
        <a href="{{ post.permalink|esc_url }}"{{ post.target }}>
            {{ post.featured_image }}
        </a>
    </figure>
{%  endblock %}
```

In the data array being passed from `get_data()` we have `$data['post']['permalink']` which is accessed in Twig as post.permalink.
