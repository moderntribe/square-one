# Queues

### Creating a queue
Add a queue by extending the `Tribe\Project\Queues\Contracts\Queue` class.
A queue class only requires the method `get_name()`. the class "DefaultQueue" is a good example. 
To create a Queue object, inject a backend object. See the Queues_Provider class for an example.

### Adding a message
Now that you have a queue you will use it by dispatching a task to the queue. 
ex:
`$queue->dispatch( Task::class, $args );` 

### Creating a task
If you are putting things into queue, it is very likely you will need to create a custom task.
To create a Task class implement `Tribe\Project\Queues\Contracts\Task`.
The method `handle()` is required and must return true on success, false on failure.

### Processing a queue
Using WP-CLI `wp queues process <queue-name>`. This will process all items in the queue.

### Built-in tasks
#### Noop
A good task to test that you have a functional Queue, Noop mostly processes tasks correctly the first time.
Add whatever message you'd like to `$args['fake']`
ex: `$queue->dispatch( Noop::class, [ 'fake' => 'custom message' ] );`

#### Email
Built in is a task for wp_mail(). To use it you'll need to add the following to your WP config:
`define( 'QUEUE_MAIL', true );`
You can also optionally define a default queue name with QUEUE_MAIL_QUEUE_NAME. If this value is not set, it will default to `default`.
To process the queued mail items `wp queues process <queue-name>` with WP-CLI.