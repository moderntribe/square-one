<?php
declare( strict_types=1 );

namespace Tribe\Project\Templates\Components\Page;

use Tribe\Project\Templates\Components\Component;
use Tribe\Project\Templates\Controllers\Traits\Copyright;

class Page_Unsupported_Browser extends Component {

	use Copyright;

	public const COPYRIGHT    = 'copyright';
	public const HOME_URL     = 'home_url';
	public const BLOG_NAME    = 'name';
	public const STYLES       = 'styles';
	public const FAVICON      = 'favicon';
	public const TITLE        = 'legacy_browser_title';
	public const CONTENT      = 'legacy_browser_content';
	public const LOGO_HEADER  = 'legacy_logo_header';
	public const LOGO_FOOTER  = 'legacy_logo_footer';
	public const ICON_CHROME  = 'legacy_browser_icon_chrome';
	public const ICON_FIREFOX = 'legacy_browser_icon_firefox';
	public const ICON_SAFARI  = 'legacy_browser_icon_safari';
	public const ICON_IE      = 'legacy_browser_icon_ie';

	public function init() {
		$this->data[ self::COPYRIGHT ]    = $this->get_copyright();
		$this->data[ self::HOME_URL ]     = home_url( '/' );
		$this->data[ self::BLOG_NAME ]    = get_bloginfo( 'name' );
		$this->data[ self::STYLES ]       = $this->get_styles();
		$this->data[ self::FAVICON ]      = $this->get_favicon();
		$this->data[ self::TITLE ]        = $this->get_legacy_page_title();
		$this->data[ self::CONTENT ]      = $this->get_legacy_page_content();
		$this->data[ self::LOGO_HEADER ]  = $this->get_legacy_image_url( 'logo-header.png' );
		$this->data[ self::LOGO_FOOTER ]  = $this->get_legacy_image_url( 'logo-footer.png' );
		$this->data[ self::ICON_CHROME ]  = $this->get_legacy_image_url( 'chrome.png' );
		$this->data[ self::ICON_FIREFOX ] = $this->get_legacy_image_url( 'firefox.png' );
		$this->data[ self::ICON_SAFARI ]  = $this->get_legacy_image_url( 'safari.png' );
		$this->data[ self::ICON_IE ]      = $this->get_legacy_image_url( 'ie.png' );
	}

	protected function get_favicon(): string {
		return trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme/branding-assets/favicon.ico';
	}

	protected function get_styles(): string {
		$legacy_css = $this->build_parser->get_legacy_style_handles();
		ob_start();
		$GLOBALS['wp_styles']->do_items( $legacy_css );

		return ob_get_clean();
	}

	protected function get_legacy_page_title(): string {
		return sprintf( '%s %s', __( 'Welcome to', 'tribe' ), get_bloginfo( 'name' ) );
	}

	protected function get_legacy_page_content(): string {
		return sprintf( '%s <a href="http://browsehappy.com/" rel="external">%s</a>.', __( 'You are viewing this site in a browser that is no longer supported or secure. For the best possible experience, we recommend that you', 'tribe' ), __( 'update or use a modern browser', 'tribe' ) );
	}

	protected function get_legacy_image_url( $filename ): string {
		if ( empty( $filename ) ) {
			return '';
		}

		return esc_url( trailingslashit( get_stylesheet_directory_uri() ) . 'assets/img/theme/legacy-browser/' . $filename );
	}

	public function render(): void {
		?>
        <!DOCTYPE html>
        <html {{ language_attributes }}>
        <head>

            <title>{{ __('Unsupported Browser') }} | {{ name }}</title>

            {# // MISC Meta #}
            <meta charset="utf-8">
            <meta name="author" content="{{ name|esc_attr }}">
            <meta http-equiv="cleartype" content="on">
            <meta name="robots" content="noindex, nofollow">

            {{ styles }}

            <link rel="shortcut icon" href="{{ favicon|esc_url }}">

            {{ do_action( 'tribe/unsupported_browser/head') }}

        </head>
        <body>

        <div class="site-header">
            <div class="l-container">
                <h1 class="site-brand">
                    <img src="{{ legacy_logo_header|esc_url }}"
                         class="site-logo site-logo--header"
                         alt="{{ name|esc_attr }} {{ __('logo') }}"/>
                </h1>
            </div>
        </div>

        <div class="site-content">
            <div class="l-container">

                <div class="site-content__content">
                    <h2>{{ legacy_browser_title }}</h2>
                    <p>{{ legacy_browser_content }}</p>
                </div>

                <ul class="browser-list">
                    <li class="browser-list__item">
                        <a href="http://www.google.com/chrome/"
                           class="browser-list__item-anchor"
                           rel="external noopener"
                           target="_blank">
		                           <span class="browser-list__item-image">
		                               <img src="{{ legacy_browser_icon_chrome|esc_url }}"
                                            alt="{{ __('Chrome browser logo') }}"/>
		                           </span>
                            {{ __('Chrome') }}
                        </a>
                    </li>
                    <li class="browser-list__item">
                        <a href="https://www.mozilla.org/firefox/new/"
                           class="browser-list__item-anchor"
                           rel="external noopener"
                           target="_blank">
		                           <span class="browser-list__item-image">
		                               <img src="{{ legacy_browser_icon_firefox|esc_url }}"
                                            alt="{{ __('Firefox browser logo') }}"/>
		                           </span>
                            {{ __('Firefox') }}
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
		<?php
	}

}
