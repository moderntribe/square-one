/**
 * @module
 * @exports init
 * @description Initializes all components found in the components directory of the theme
 */

import accordion from 'components/accordion';
import card from 'components/card';
import comments from 'components/comments';
import navigation from 'components/navigation';
import sectionNav from 'components/section_nav';
import share from 'components/share';
import slider from 'components/slider';
import tabs from 'components/tabs';
import video from 'components/video';
import dialog from 'components/dialog';
import search from 'routes/search';
import blockGallerySlider from 'components/blocks/gallery_slider';

const init = () => {
	accordion();
	card();
	comments();
	navigation();
	sectionNav();
	share();
	slider();
	tabs();
	video();
	dialog();
	search();
	blockGallerySlider();

	console.info( 'SquareOne Theme: Initialized all components.' );
};

export default init;
