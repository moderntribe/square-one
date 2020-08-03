<?php
declare( strict_types=1 );

$controller = \Tribe\Project\Templates\Components\routes\unsupported_browser\Controller::factory();

get_template_part( 'components/document/header/header' );
?>
<main id="main-content">

<div class="site-header">
    <div class="l-container">
        <h1 class="site-brand">
            <img src="<?php echo $controller->legacy_logo_header ?>"
                 class="site-logo site-logo--header"
                 alt="<?php echo $controller->name?> <?php echo esc_attr( __('logo', 'tribe') ) ?>"/>
        </h1>
    </div>
</div>

<div class="site-content">
    <div class="l-container">

        <div class="site-content__content">
            <h2><?php echo $controller->legacy_browser_title ?></h2>
            <p><?php echo $controller->legacy_browser_content ?></p>
        </div>

        <ul class="browser-list">
            <li class="browser-list__item">
                <a href="http://www.google.com/chrome/"
                   class="browser-list__item-anchor"
                   rel="external noopener"
                   target="_blank">
	                           <span class="browser-list__item-image">
	                               <img src="<?php echo $controller->legacy_browser_icon_chrome ?>>"
                                        alt="<?php echo esc_attr( __('Chrome browser logo', 'tribe') ) ?>"/>
	                           </span>
	                <?php echo esc_html( __('Chrome', 'tribe') ) ?>
                </a>
            </li>
            <li class="browser-list__item">
                <a href="https://www.mozilla.org/firefox/new/"
                   class="browser-list__item-anchor"
                   rel="external noopener"
                   target="_blank">
	                           <span class="browser-list__item-image">
	                               <img src="<?php echo esc_url( $controller->legacy_browser_icon_firefox ) ?>>"
                                        alt="<?php echo esc_attr( __('Firefox browser logo') ) ?>"/>
	                           </span>
	                <?php echo esc_html( __('Firefox') ) ?>
                </a>
            </li>
            <li class="browser-list__item">
                <a href="https://support.apple.com/downloads/#safari"
                   class="browser-list__item-anchor"
                   rel="external noopener"
                   target="_blank">
	                           <span class="browser-list__item-image">
	                               <img src="{{ legacy_browser_icon_safari|esc_url }}"
                                        alt="{{ __('Safari browser logo') }}"/>
	                           </span>
                    {{ __('Safari') }}
                </a>
            </li>
            <li class="browser-list__item">
                <a href="http://windows.microsoft.com/internet-explorer/download-ie"
                   class="browser-list__item-anchor"
                   rel="external noopener"
                   target="_blank">
	                           <span class="browser-list__item-image">
	                               <img src="{{ legacy_browser_icon_ie|esc_url }}"
                                        alt="{{ __('Internet Explorer browser logo') }}"/>
	                           </span>
                    {{ __('Internet Explorer') }}
                </a>
            </li>
        </ul>

    </div>
</div>

<div class="site-footer">
    <div class="l-container">

        <img src="{{ legacy_logo_footer|esc_url }}"
             class="site-logo site-logo--footer"
             alt="{{ name|esc_attr }} {{ __('logo') }}"/>

        <p class="site-footer__copy">{{ copyright }} {{ name }}.</p>

    </div>
</div>

</body>
</html>
