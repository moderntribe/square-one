<?php

namespace Tribe\Project\Queues;

class Message implements \JsonSerializable {

	private $task_handler;
	private $args;
	private $priority;
	private $job_id;

	public function __construct( string $task_handler, array $args = [], int $priority = 10, string $job_id = null ) {
		$this->task_handler = $task_handler;
		$this->args         = $args;
		$this->priority     = $priority;
		$this->job_id       = $job_id;
	}

	public function get_priority(): int {
		return $this->priority;
	}

	public function get_job_id(): string {
		return $this->job_id;
	}

	public function get_task_handler(): string {
		return $this->task_handler;
	}

	public function get_args(): array {
		return $this->args;
	}

	public function jsonSerialize() {

		return [
			'task_handler' => $this->get_task_handler(),
			'args'         => $this->get_args(),
		];
	}

}
