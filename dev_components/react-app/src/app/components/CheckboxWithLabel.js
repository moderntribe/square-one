import React, { Component, PropTypes } from 'react';

export default class CheckboxWithLabel extends Component {

	static propTypes = {
		checked: PropTypes.bool,
		onChange: PropTypes.func.isRequired,
	};

	constructor(props) {
		super(props);
		this.changed = this.changed.bind(this);
	}

	changed() {
		const { onChange } = this.props;
		onChange();
	}

	render() {
		const { checked } = this.props;
		return (
			<label htmlFor="checkbox">
				<input
					id="checkbox"
					type="checkbox"
					checked={checked}
					onChange={this.changed}
				/>
				Show Data
			</label>
		);
	}
}
