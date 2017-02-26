import React, { Component, PropTypes } from 'react';

export default class CheckboxWithLabel extends Component {

	static propTypes = {
		labelOn: PropTypes.string,
		labelOff: PropTypes.string,
	};

	constructor(props) {
		super(props);
		this.state = { isChecked: false };

		this.onChange = this.onChange.bind(this);
	}

	onChange() {
		this.setState({ isChecked: !this.state.isChecked });
	}

	render() {
		return (
			<label htmlFor="checkbox">
				<input
					id="checkbox"
					type="checkbox"
					checked={this.state.isChecked}
					onChange={this.onChange}
				/>
				{this.state.isChecked ? this.props.labelOn : this.props.labelOff}
			</label>
		);
	}
}
