=== WP XHProf Profiler ===
Contributors: Artberri
Donate link: http://www.berriart.com/donate/
Tags: plugin, debug, profiling, profiler, php, debugging, wordpress, facebook, xhprof
Requires at least: 2.6.0
Tested up to: 3.3.1
Stable tag: trunk

Adds PHP profiling support to your Wordpress using Facebook's XHProf Profiler.

== Description ==

WP XHProf Profiler plugin is an easy way to profile your plugins and themes when you are coding or debugging. To get it done it uses XHProf, a PHP profiler made by the Facebook Dev Team (you must install it before activating the plugin).

= Usage = 

By default the profiling is disabled, activate `WP_DEBUG` on your `wp-config.php` to enable it. After doing it, all your wordpress pages will have a link at bottom with the profiling data.

= ¿What is XHProf? =

From [http://www.php.net/manual/en/intro.xhprof.php]("From PHP documentation")

XHProf is a light-weight hierarchical and instrumentation based profiler. During the data collection phase, it keeps track of call counts and inclusive metrics for arcs in the dynamic callgraph of a program. It computes exclusive metrics in the reporting/post processing phase, such as wall (elapsed) time, CPU time and memory usage. A functions profile can be broken down by callers or callees. XHProf handles recursive functions by detecting cycles in the callgraph at data collection time itself and avoiding the cycles by giving unique depth qualified names for the recursive invocations.

XHProf includes a simple HTML based user interface (written in PHP). The browser based UI for viewing profiler results makes it easy to view results or to share results with peers. A callgraph image view is also supported.

XHProf reports can often be helpful in understanding the structure of the code being executed. The hierarchical nature of the reports can be used to determine, for example, what chain of calls led to a particular function getting called.

XHProf supports ability to compare two runs (a.k.a. "diff" reports) or aggregate data from multiple runs. Diff and aggregate reports, much like single run reports, offer "flat" as well as "hierarchical" views of the profile.

Additional documentation can be found via the xhprof website » [http://pecl.php.net/package/xhprof]

= Support =

Comments, questions, feature requests and bug reports are welcome: [http://www.berriart.com/en/xhprof-profiler/](http://www.berriart.com/en/xhprof-profiler/ "XHProf Profiler")

== Installation ==

1. Install XHProf on your server [http://www.php.net/manual/en/xhprof.installation.php]
1. Extract and upload the directory `xhprof-profiler` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Enable `WP_DEBUG` on your `wp-config.php` file for profiling
1. That's all

= Requeriments =

* Wordpress >= v2.6.0

== Frequently Asked Questions ==

= Is this plugin developed by Facebook Dev Team? =

No. This plugin is not made by the creators of the XHProf profiler, this plugin has been developed by [Berriart](http://www.berriart.com/ "Berriart").

= Have you any more question?  =

Comments, questions, feature requests and bug reports are welcome: [http://www.berriart.com/en/xhprof-profiler/](http://www.berriart.com/en/xhprof-profiler/ "XHProf Profiler")

For further information about XHProf go to [http://pecl.php.net/package/xhprof]

== Screenshots ==

1. Profiler output
