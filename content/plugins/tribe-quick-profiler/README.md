Modern Tribe Quick Profiler
====================

A tool to easily and rapidly profile WordPress load time and memory usage with an extremely low overhead.

Installation
--------------------

1. Place this file in your wp-content/mu-plugins folder (create the folder if it doesn't already exist.)
2. Add the following definitions to your wp-config.php file with desired settings.
3. View your site and watch the logs to see what's slow.

**Sampling rate**

This determines the odds that this plugin will run. Set this very low if you are testing on a high traffic site. Or set it to '1' if you want every page load to be profiled.

define('TRIBE_PROFILE_SAMPLE_RATE', '0.5');`


**Time Threshold (milliseconds)**

Determine the minimum amount of time increase that should trigger logging in ms. If the time spent between filters exceeds this amount, it will be logged.

define('TRIBE_PROFILE_TIME_THRESHOLD', '10');


**Memory Threshold (kilobytes)**

Determine the minimum amount of memory increase that should trigger logging in kB. If the memory consumed between filters exceeds this amount, it will be logged.

define('TRIBE_PROFILE_MEMORY_THRESHOLD', '1024');


**Log File**

If this is not set, then logging will be directed to the standard php error log. If set to '1', then logging will be directed to wp-content/tribe_profile.log. If set to a full path, then logging will be directed to the specified file.

define('TRIBE_PROFILE_LOG_FILE', '1');


Todo
--------------------

* Perhaps we should dump the results on shutdown and build a profiling viewer.
* This plugin does not currently assess cumulative effects. The cumulative effect of running a single hook 10000 times could be useful.
* It might be useful to add a log cycling function so that we avoid massive log files.
* Sometimes memory actually drops substantially. Is it worth logging that too?