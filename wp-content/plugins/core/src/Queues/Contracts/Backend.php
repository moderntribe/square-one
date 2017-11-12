<?php

namespace Tribe\Project\Queues\Contracts;

use Tribe\Project\Queues\Message;

interface Backend {

	public function enqueue( string $queue_name, Message $m );

	public function dequeue( string $queue_name );

	public function ack( string $job_id, string $queue_name );

	public function nack( string $job_id, string $queue_name );

	public function get_type(): string;
}
