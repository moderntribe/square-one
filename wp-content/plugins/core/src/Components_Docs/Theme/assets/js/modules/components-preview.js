/**
 * @module
 * @description JavaScript specific to the Components Docs pages.
 */

import delegate from 'delegate';
import * as tools from 'utils/tools';
import request from 'superagent';
import {AJAX_NONCE, AJAX_URL, PREVIEW_ACTION} from "../config/wp-settings";

const el = {
    container: tools.getNodes('components-preview-wrapper')[0],
    previewWrapper: tools.getNodes('component-rendered-preview')[0],
};

const getUpdatedPreview = (data) => request
    .get(`${AJAX_URL}?action=${PREVIEW_ACTION}&security=${AJAX_NONCE}`)
    .query(data);

const getFormFields = () => {
    const inputs = el.container.querySelectorAll('input');
    const values = {};
    inputs.forEach(input => {
        values[input.getAttribute('name')] = input.value == 'false' ? 0 : input.value;
    });

    return values;
};

const handleUpdate = (err, res) => {
    if (err) {
        return;
    }
    if (!res.body.success) {
        return;
    }

    el.previewWrapper.parentNode.innerHTML = res.body.data;
    el.previewWrapper = tools.getNodes('component-rendered-preview')[0];

    PR.prettyPrint();
};

const handleSubmit = (e) => {
    e.preventDefault();

    const data = getFormFields();

    getUpdatedPreview(data).end((err, response) => handleUpdate(err, response));
};

/**
 * @function bindEvents
 * @description Bind the events for this module.
 */

const bindEvents = () => {
    delegate(el.container, '[data-js="components-docs-update-preview"]', 'click', handleSubmit);
};

/**
 * @function init
 * @description Kick off this modules functions
 */

const componentsPreview = () => {
    if (!el.container) {
        return;
    }

    bindEvents();

    console.info('Square One FE: Initialized components docs preview scripts.');
};

export default componentsPreview;
