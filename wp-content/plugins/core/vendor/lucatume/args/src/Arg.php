<?php

/**
 * Utility functions
 */
if ( ! function_exists( 'is_associative_array' ) ) {
	function is_associative_array( $var ) {
		return is_array( $var ) and ( array_values( $var ) !== $var );
	}
}

if ( ! function_exists( 'array_has_structure' ) ) {
	function array_has_structure( array $arr, array $structure ) {
		$merged        = @array_merge_recursive( $arr, $structure );
		$diffed        = @array_diff( $merged, $arr );
		$has_structure = count( $diffed ) === 0;

		return $has_structure;
	}
}

if ( ! function_exists( 'array_extends_structure' ) ) {
	function array_extends_structure( array $arr, array $structure ) {
		$merged         = @array_merge_recursive( $arr, $structure );
		$diffed         = @array_diff( $merged, $arr );
		$same_structure = count( @array_intersect_key( $structure, $diffed ) ) === 0;

		return $same_structure;
	}
}

if( class_exists( 'Arg' )) {
	return;
}

class Arg {

	/**
	 * @var string
	 */
	protected static $exception;

	/** @var  ArgObject */
	protected $arg;

	public function __construct( $arg, $arg_name = null ) {
		$type      = gettype( $arg );
		$arg_name  = $arg_name ? $arg_name : $type;
		$this->arg = $this->get_arg_for_type( $type, $arg, $arg_name );
	}

	private function get_arg_for_type( $type, $arg, $arg_name ) {
		$class_name = ucwords( $type . 'Arg' );
		$class_name = str_replace( ' ', '', $class_name );

		return new $class_name( $type, $arg, $arg_name, self::$exception );
	}

	public static function _( $arg, $arg_name = null ) {
		$instance = new self( $arg, $arg_name );

		return $instance->arg;
	}

	public static function set_exception( $exception ) {
		if ( ! class_exists( $exception ) ) {
			throw new InvalidArgumentException( "$exception is not a defined class" );
		}
		self::$exception = $exception;
	}

	public static function reset_exception() {
		self::$exception = null;
	}
}


abstract class ArgObject {

	/**
	 * @var mixed
	 */
	public $value;
	/**
	 * @var string
	 */
	protected $name;
	/**
	 * @var string
	 */
	protected $type;

	/** @var  bool */
	public $match_true = true;

	/**
	 * @var bool
	 */
	protected $skip = false;

	/**
	 * @var string
	 */
	protected $message = '';

	/**
	 * @var bool/string
	 */
	protected $reason = false;
	/**
	 * @var bool/string
	 */
	protected $previous_reason = false;

	/** @var  bool */
	protected $or_condition;

	/**
	 * @var string
	 */
	protected $excpeption;

	/**
	 * @var tad_Arg_Check
	 */
	protected $check;

	/**
	 * @var bool
	 */
	protected $has_thrown = false;

	/**
	 * @var bool
	 */
	protected $should_return;

	/**
	 * @var mixed
	 */
	protected $return_value;

	/**
	 * @return string
	 */
	protected function get_negation() {
		$negation = $this->match_true ? '' : ' not';
		$this->reset_negation();

		return $negation;
	}

	public function not() {
		$this->match_true = false;

		return $this;
	}

	public function __construct( $type, $value, $name, $exception, tad_Arg_Check_CheckState $checkState = null ) {
		$this->type       = $type;
		$this->value      = $value;
		$this->name       = $name;
		$this->excpeption = $exception ? $exception : 'InvalidArgumentException';
		$this->check      = $checkState ? $checkState : new tad_Arg_Check( new tad_Arg_Check_PassingState() );
	}

	public function __destruct() {
		if ( $this->is_failing() ) {
			$this->throw_exception( $this->reason );
		}
	}

	public function else_throw( $exception, $message = null ) {
		if ( $this->is_failing() ) {
			$this->has_thrown = true;
			if ( is_object( $exception ) ) {
				throw $exception;
			} else if ( class_exists( $exception ) ) {
				throw new $exception( $message );
			} else {
				$exception = $exception . 'Exception';
				throw new $exception ( $message );
			}
		}

		return $this;
	}

	public function get_name() {
		return $this->name;
	}

	/**
	 * @param $condition
	 * @param $reason
	 *
	 * @return $this
	 */
	public function assert( $condition, $reason ) {
		if ( $this->check->is_failed() ) {
			$this->throw_exception( $reason );
		}

		if ( ! $condition ) {
			$this->check->fail();
			$this->reason = $reason;
		} else {
			$this->check->pass();
		}

		return $this;
	}

	public function is_bool() {
		$condition = $this->match_true === is_bool( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a boolean' );

		return $this;
	}

	public function is_object() {
		$condition = $this->match_true === is_object( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an object' );

		return $this;
	}

	public function is_array() {
		$condition = $this->match_true === is_array( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an array' );

		return $this;
	}

	public function is_associative_array() {
		$this->is_array();
		$condition = $this->match_true === is_associative_array( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an associative array' );

		return $this;
	}

	public function is_scalar() {
		$condition = $this->match_true === is_scalar( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a scalar' );

		return $this;
	}

	public function did_pass() {
		return $this->check->is_passing();
	}

	public function is_int() {
		$condition = $this->match_true === is_int( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be an int' );

		return $this;
	}

	public function is_numeric() {
		$this->assert( $this->match_true === is_numeric( $this->value ), $this->name . ' must' . $this->get_negation() . ' be numeric' );

		return $this;
	}

	public function is_float() {
		$this->assert( $this->match_true === is_float( $this->value ), $this->name . ' must' . $this->get_negation() . ' be a float' );

		return $this;
	}

	public function is_double() {
		$condition = $this->match_true === is_double( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a double' );

		return $this;
	}

	public function is_string() {
		$condition = $this->match_true === is_string( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a string' );

		return $this;
	}

	public function is_resource() {
		$condition = $this->match_true === is_resource( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a resource' );

		return $this;
	}

	public function is_null() {
		$condition = $this->match_true === is_null( $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' be a resource' );

		return $this;
	}

	private function reset_negation() {
		$this->match_true = true;
	}

	public function vel() {
		return $this->_or();
	}

	public function _or() {
		$this->check->or_condition();

		return $this;
	}

	public function aut() {
		return $this->vel();
	}

	/**
	 * @param $reason
	 */
	protected function throw_exception( $reason ) {
		$this->reason = ucfirst( strtolower( $this->check->is_or_failing() ? $this->reason . ' or ' . $reason : $reason ) );
//			$this->reason     = ucfirst( strtolower( $this->reason ? $this->reason . ' or ' . $reason : $reason ) );
		$this->has_thrown = true;
		throw new $this->excpeption( $this->reason );
	}

	/**
	 * @return bool
	 */
	protected function is_failing() {
		$is_failing = ! $this->check->is_passing() && ! $this->has_thrown;

		return $is_failing;
	}

	public function in( $legit ) {
		if (!is_array($legit)){
			$legit = func_get_args();
		}

		$this->assert( in_array( $this->value, $legit ), ' must be one among these values: ' . print_r( $legit, true ) );

		return $this;
	}

	public function has_property( $first_property ) {
		$this->is_object();

		$has_property = true;
		$props        = func_get_args();
		foreach ( $props as $p ) {
			$has_property &= isset( $this->value->$p ) && ! empty( $this->value->$p );
		}

		$this->assert( $has_property, ' must have the following properties set and not empty: ' . print_r( $props, true ) );

		return $this;
	}

	public function has_method( $first_method ) {
		$this->is_object();

		$methods       = func_get_args();
		$has_method    = true;
		$value_methods = get_class_methods( $this->value );
		foreach ( $methods as $m ) {
			$has_method &= in_array( $m, $value_methods );
		}

		$this->assert( $has_method, ' must define the following accessible methods: ' . print_r( $methods, true ) );

		return $this;
	}

	public function has_key( $first_key ) {
		$this->is_array();

		$this->assert( ! empty( $this->value ), ' must not be an empty array' );

		$keys     = func_get_args();
		$has_keys = true;
		foreach ( $keys as $k ) {
			$has_keys &= array_key_exists( $k, $this->value );
		}

		$this->assert( $has_keys, ' must have the following keys: ' . print_r( $keys, true ) );

		return $this;
	}

	public function has_value( $first_value ) {
		$this->is_array();

		$values = func_get_args();

		$has_values = true;
		foreach ( $values as $v ) {
			$has_values &= in_array( $v, $this->value );
		}

		$this->assert( $has_values, ' must have the following values: ' . print_r( $values, true ) );

		return $this;
	}
}


abstract class ScalarArg extends ArgObject {

	public function at_least( $value ) {
		$condition = $this->match_true === $this->value < $value;
		$this->assert( $condition, $this->name . ' must be at least ' . $value );

		return $this;
	}

	public function at_most( $value ) {
		$condition = $this->match_true === $this->value > $value;
		$this->assert( $condition, $this->name . ' must be at most ' . $value );

		return $this;
	}

	public function greater_than( $value ) {
		$condition = $this->match_true === $this->value <= $value;
		$this->assert( $condition, $this->name . ' must be greater than ' . $value );

		return $this;
	}

	public function less_than( $value ) {
		$condition = $this->match_true === $this->value >= $value;
		$this->assert( $condition, $this->name . ' must be less than ' . $value );

		return $this;
	}


}


class  BooleanArg extends ScalarArg {

}


class  IntegerArg extends ScalarArg {

}


class  DoubleArg extends ScalarArg {

}


class  StringArg extends ScalarArg {

	public function length( $min, $max = null ) {
		$len       = strlen( $this->value );
		$condition = $this->match_true === ( $len >= $min );
		$this->assert( $condition, $this->name . ' must have a minimum length of ' . $min );

		$condition = $max && $this->match_true === $len <= $max;
		$this->assert( $condition, $this->name . ' must have a maximum length of ' . $max );

		return $this;
	}

	public function match( $pattern ) {
		$condition = $this->match_true === (bool) preg_match( $pattern, $this->value );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' match pattern ' . $pattern );
	}
}


class  ArrayArg extends ArgObject {

	public function count( $min, $max = null ) {
		$count     = count( $this->value );
		$condition = $this->match_true === $count >= $min;
		$this->assert( $condition, $this->name . ' must contain at least ' . $min . ' elements' );
		$condition = $max && $this->match_true === $count <= $max;
		$this->assert( $condition, $this->name . ' must contain at most ' . $max . ' elements' );

		return $this;
	}

	public function has_structure( $structure ) {
		$condition = $this->match_true === array_has_structure( $this->value, $structure );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' have the structure\\n' . print_r( $structure, true ) );

		return $this;
	}

	public function extends_structure( $structure ) {
		$condition = $this->match_true === array_extends_structure( $this->value, $structure );
		$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' extend the structure\\n' . print_r( $structure, true ) );

		return $this;
	}

	public function defaults( $defaults ) {
		$this->value = array_merge( $defaults, $this->value );

		return $this;
	}

	public function contains( $value ) {
		$values = func_get_args();
		foreach ( $values as $value ) {
			$condition = $this->match_true !== in_array( $value, $this->value );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' contain ' . $value );
		}

		return $this;
	}

}


class  ObjectArg extends ArgObject {

	public function is_set( $property ) {
		$properties = func_get_args();
		foreach ( $properties as $property ) {
			$condition = $this->match_true === isset( $this->value->$property );
			$this->assert( $condition, $this->name . ' must' . $this->get_negation() . ' have the ' . $property . 'property' );
		}

		return $this;
	}
}


class  ResourceArg extends ArgObject {

}


class  NULLArg extends ArgObject {

}


class  UnknownTypeArg extends ArgObject {

}

