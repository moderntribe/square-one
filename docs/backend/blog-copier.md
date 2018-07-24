# Blog Copier

The blog copier is a WP Multisite tool to copy a template blog to a new blog. All content and settings will be copied
from the source blog to the new blog.

## Usage

Enable the blog copier by registering the `Blog_Copier_Provider` to the core service provider. The line to do so
should already exist, commented out, in `\Tribe\Project\Core::load_service_providers()`.

Once enabled, the blog copier can be accessed in the network admin, under the "Sites" menu.

## Architecture

The blog copier depends on queues for processing. Initiating a copy adds a task to the queue to run the first step of
the copy. As each step completes, it will add the next step to the queue.

The configuration for the copy is stored as JSON in the content of a hidden post on blog 1. This JSON is a
representation of a `Copy_Configuration` object. Each task will be passed the post ID of this object, which it can
reference for the original form submission data (e.g., what is the new blog's domain?) or for state that has been
built by previous steps in the process (e.g., what is the new blog's ID?).

The tasks that need to run for the copy are defined in a `Task_Chain` object, configured in the `Blog_Copier_Provider`.
See `\Tribe\Project\Service_Providers\Blog_Copier_Provider::CHAIN`. If you need to add additional steps to the copy,
create a new `Task` for that step and insert its class into the `Task_Chain` at the appropriate point.

As each task finishes, it must trigger an action to signify that it has completed, passing the class name of the task
and the args that were originally passed to the `Task`'s `handle()` method.

```
do_action( Copy_Manager::TASK_COMPLETE_ACTION, static::class, $args );
```

If the action has failed and the copy cannot continue, trigger the failure action, passing a WP_Error object
as the third parameter to the action.

```
do_action( Copy_Manager::TASK_ERROR_ACTION, static::class, $args, $error );
```

## Automated Testing

Some tests require multisite to run successfully. To enable these tests, run the integration test suite with the
flag `--env multisite`.