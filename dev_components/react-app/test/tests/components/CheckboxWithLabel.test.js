import React from 'react';
import { shallow } from 'enzyme';
import expect, { createSpy } from 'expect';
import CheckboxWithLabel from 'app/components/CheckboxWithLabel';

const props = {
	checked: true,
	onChange: () => {},
};

const render = (overrideProps) => {
	const wrapper = shallow(<CheckboxWithLabel
		{...props}
		{...overrideProps}
	/>);

	return {
		wrapper,
		checkbox: wrapper.find('#checkbox'),
	};
};

describe('Checkbox Component', () => {
	it('checkbox is checked if checked is true', () => {
		const { checkbox } = render();
		expect(checkbox.length).toBeTruthy();
		expect(checkbox.node.props.checked).toBe(true);
	});

	it('checkbox is not checked if checked is false', () => {
		const { checkbox } = render({ checked: false });
		expect(checkbox.length).toBeTruthy();
		expect(checkbox.node.props.checked).toBe(false);
	});

	it('checkbox change calls change function', () => {
		const onChange = createSpy();
		const { checkbox } = render({ onChange });

		checkbox.simulate('change');

		expect(onChange).toHaveBeenCalled();
	});
});
