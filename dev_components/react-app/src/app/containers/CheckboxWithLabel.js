import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { toggleCheckbox } from 'app/ducks/test';
import CheckboxWithLabel from 'app/components/CheckboxWithLabel';

export function mapStateToProps({ test }) {
	const { checked } = test;
	return { checked };
}

export function mapDispatchToProps(dispatch) {
	return bindActionCreators({
		onChange: toggleCheckbox,
	}, dispatch);
}

export default connect(mapStateToProps, mapDispatchToProps)(CheckboxWithLabel);
