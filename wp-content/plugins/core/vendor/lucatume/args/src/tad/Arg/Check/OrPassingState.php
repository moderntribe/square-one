<?php


	class tad_Arg_Check_OrPassingState implements tad_Arg_Check_State {

		public function pass() {
			return new tad_Arg_Check_PassingState;
		}

		public function fail() {
			return new tad_Arg_Check_PassingState();
		}

		public function or_condition() {
			return $this;
		}
	}