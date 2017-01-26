import React from 'react';
import { shallow } from 'enzyme';
import Test from 'app/components/test';
import CheckboxWithLabel from 'app/containers/CheckboxWithLabel';
import expect from 'expect';

const props = {
	isLoading: false,
	error: null,
	checked: false,
	data: [{ test: 'hello' }],
};

const render = (overrideProps) => {
	const wrapper = shallow(<Test
		{...props}
		{...overrideProps}
	/>);

	return {
		wrapper,
		error: wrapper.find('.error'),
		loading: wrapper.find('.loading'),
		dataContainer: wrapper.find('pre'),
		checkbox: wrapper.find(CheckboxWithLabel),
	};
};

describe('Test Component', () => {
	it('can display data when checked', () => {
		const { dataContainer } = render({ checked: true });
		expect(dataContainer.length).toBeTruthy();
		expect(dataContainer.text()).toBe(props.data.toString());
	});

	it('can display error container', () => {
		const { error, dataContainer } = render({ error: { test: 'test' } });

		expect(error.length).toBeTruthy();
		expect(dataContainer.length).toBeFalsy();
	});

	it('can display loading container', () => {
		const { loading, dataContainer } = render({ isLoading: true });

		expect(loading.length).toBeTruthy();
		expect(dataContainer.length).toBeFalsy();
	});

	it('can hide data when not checked', () => {
		const { dataContainer } = render({ checked: false });
		expect(dataContainer.length).toBeFalsy();
	});
});
