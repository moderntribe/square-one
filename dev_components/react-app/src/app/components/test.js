import React, { PropTypes } from 'react';
import CheckboxWithLabel from 'app/containers/CheckboxWithLabel';

const Test = ({ isLoading, error, data, checked }) => {
	if (isLoading) {
		return <div className="loading" />;
	}

	if (error) {
		return <div className="error" />;
	}

	return (<div>
		<CheckboxWithLabel />
		{ checked && <pre>{ data.toString() }</pre> }
	</div>);
};

Test.propTypes = {
	isLoading: PropTypes.bool,
	data: PropTypes.array,
	error: PropTypes.object,
	checked: PropTypes.bool,
};

export default Test;
