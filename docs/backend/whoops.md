# Whoops - Better PHP Error Messages

In Local Environments (and local environments only, lest you hazard bringing the wrath of Jonathan down upon you), you can enable [Whoops Error Messaging](http://filp.github.io/whoops/).

Whoops provides a cleaner, more helpful interface for viewing errors when they happen, including a clear callstack, server details at the time of the error, preview of the code that errored, and all 
sorts of other goodies.  

To enable Whoops, simply add `define( 'WHOOPS_ENABLE', true );` to your local-config.php.

**NOTE**: Whoops is loaded via a Composer Package from within `require-dev`. As such, you'll need to make sure you are running `composer install` _without_ the `--no-dev` flag. 
