/**
 * @desc Embed a youtube or vimeo video and make it fill its parent container on init and resize.
 * Vimeo support depends on their froogaloop mini lib.
 */

import _ from 'lodash';

export const getVideoId = (url = '') => {
	let videoId = null;
	if (url.indexOf('vimeo') !== -1) {
		const regex = /https?:\/\/(?:www\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/;
		const match = url.match(regex);
		videoId = match ? { type: 'vimeo', id: match[3] } : null;
	} else {
		const regex = /^.*(youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/; // eslint-disable-line
		const match = url.match(regex);
		videoId = (match && match[2].length === 11) ? { type: 'youtube', id: match[2] } : null;
	}
	return videoId;
};

export const isVideoUrl = (url = '') => url.indexOf('vimeo') !== -1 || url.indexOf('youtube') !== -1 || url.indexOf('youtu.be') !== -1;

export const init = (opts = {}) => {
	const options = {
		container: null,
		id: '',
		resize_event: 'modern_tribe/resize_executed',
		type: 'youtube',
	};

	// merge options
	Object.assign(options, opts);

	if (!options.container || !options.id.length) {
		// need these, leaving town
		return;
	}

	// setup shared elements and values
	let h;
	let w;
	let iframeVideo;
	let player;
	const iframe = document.createElement('iframe');
	const ytId = _.uniqueId('yt-container-');
	const iframeId = _.uniqueId('video-bg-');

	// setup shared defaults on the iframe and container
	iframe.id = iframeId;
	iframe.setAttribute('webkitallowfullscreen', '');
	iframe.setAttribute('mozallowfullscreen', '');
	iframe.setAttribute('allowfullscreen', '');

	// on init and resize detect the container height and width and work out how to fill the area with video and no
	// crop lines. Basically simulating background size cover for video.
	const fitVideoToContainer = () => {
		if (w === 0 || h === 0 || !iframeVideo) {
			return;
		}

		const dH = options.container.offsetHeight;
		const dW = options.container.offsetWidth;
		let sH = 0;
		let sW = 0;

		sW = (dH / h) * w;
		sW = (sW >= dW) ? sW : dW;

		sH = dH;
		if (sW === dW) {
			sH = (dW / w) * h;
		}

		const left = sW === dW ? 0 : -(Math.abs(sW - dW) / 2);

		iframeVideo.style.position = 'absolute';
		iframeVideo.style.width = `${sW}px`;
		iframeVideo.style.height = `${sH}px`;
		iframeVideo.style.top = 0;
		iframeVideo.style.left = `${left}px`;
	};

	const embedYoutube = () => {
		const ytContainer = document.createElement('div');
		ytContainer.id = ytId;
		options.container.appendChild(ytContainer);
		const tag = document.createElement('script');
		tag.src = 'https://www.youtube.com/iframe_api';
		const firstScriptTag = document.getElementsByTagName('script')[0];
		firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

		window.onYouTubeIframeAPIReady = () => {
			player = new window.YT.Player(ytId, {
				videoId: options.id,
				controls: 0,
				showinfo: 0,
				modestbranding: 1,
				iv_load_policy: 3,
				loop: 1,
				playlist: options.id,
				events: {
					onReady: (event) => {
						iframeVideo = document.getElementById(ytId);
						h = iframeVideo.getAttribute('height');
						w = iframeVideo.getAttribute('width');
						iframeVideo.style.transform = 'scale(1.1)';
						fitVideoToContainer();
						event.target.loadPlaylist([options.id]);
						event.target.setLoop(true);
						event.target.mute();
						event.target.playVideo();
						_.delay(() => options.container.classList.add('loaded'), 800);
					},
				},
			});
		};
	};

	const embedVimeo = () => {
		iframe.src = `//player.vimeo.com/video/${options.id}?background=1&api=1&player_id=${iframeId}`;
		options.container.appendChild(iframe);
		iframeVideo = document.getElementById(iframeId);
		player = window.$f(iframeVideo);
		player.addEvent('ready', () => {
			player.api('getVideoHeight', (value) => {
				h = value;
			});
			player.api('getVideoWidth', (value) => {
				w = value;
				fitVideoToContainer();
				_.delay(() => options.container.classList.add('loaded'), 800);
			});
		});
	};

	const bindEvents = () => {
		document.addEventListener(options.resize_event, fitVideoToContainer);
	};

	bindEvents();

	switch (options.type) {
	case 'vimeo':
		embedVimeo();
		break;
	case 'youtube':
		embedYoutube();
		break;
	default:
		break;
	}
};
