import { connect } from 'react-redux';
import Test from 'app/components/test';

export function mapStateToProps({ test }) {
	return { ...test };
}

export default connect(mapStateToProps)(Test);
