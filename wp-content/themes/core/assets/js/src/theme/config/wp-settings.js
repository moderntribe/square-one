// It's possible our context is the block editor in the admin theme, so fall
// back to that config.
const wp = window.modern_tribe_config || window.modern_tribe_admin_config || {};

export const IMAGES_URL = wp.images_url;
export const TEMPLATE_URL = wp.template_url;
export const HMR_DEV = wp.hmr_dev || 0;
export const ENABLE_THEME_SERVICE_WORKER = wp.enable_theme_service_worker || false;
export const SCRIPT_DEBUG = wp.script_debug || 0;
