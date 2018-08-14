<?php

namespace Tribe\Project\Theme\Resources;

use Tribe\Project\Object_Meta\General_Settings;

class Third_Party_Tags {

    /**
   	 *  Google Tag Manager (head tag)
   	 */
   	public function inject_google_tag_manager_head_tag() {

   		$id = General_Settings::instance()->get_setting( General_Settings::ID_GTM );

   		if ( empty( $id ) ) {
   			return;
   		}

   		?>

           <!-- Google Tag Manager -->
           <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
           new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
           j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
           'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
           })(window,document,'script','dataLayer','<?php echo $id; ?>');</script>
           <!-- End Google Tag Manager -->

   		<?php
   	}

	/**
	 *  Google Tag Manager (body tag)
	 */
	public function inject_google_tag_manager_body_tag() {

		$id = General_Settings::instance()->get_setting( General_Settings::ID_GTM );

		if ( empty( $id ) ) {
			return;
		}

		?>

        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $id; ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->

		<?php
	}
}

new Third_Party_Tags();
