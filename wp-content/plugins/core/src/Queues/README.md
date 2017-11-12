Interfaces:
    Task
    Backend
 
Abstract Classes
    Queue

Classes:
    Queue
    Tasks\Mail
    Tasks\Action
    Backend\wpdb
    Backend\WP_Cache
    Backend\Redis
    Backend\RabbitMQ
    Backend\SQS
    Backend\Beanstalkd
    Backend\Gearman


$some_queue->dispatch( SampleTask::class, args = [], priority = 10 )
SomeQueue::dispatch( SampleTask::class, args = [], priority = 10 )
