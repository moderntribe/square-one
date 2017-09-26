<?php

namespace WP_CLI\AutoloadSplitter;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

/**
 * Class ComposerPlugin.
 *
 * Composer plugin class that hooks into the Composer plugin events.
 *
 * @package WP_CLI\AutoloadSplitter
 */
final class ComposerPlugin implements PluginInterface, EventSubscriberInterface
{

    const EXTRA_KEY = 'autoload-splitter';
    const LOGIC_CLASS_KEY = 'splitter-logic';
    const LOGIC_CLASS_LOCATION_KEY = 'splitter-location';
    const SPLIT_TARGET_PREFIX_TRUE_KEY = 'split-target-prefix-true';
    const SPLIT_TARGET_PREFIX_FALSE_KEY = 'split-target-prefix-false';

    private static $extra;

    /**
     * Get the event subscriber configuration for this plugin.
     *
     * @return array<string,string> The events to listen to, and their
     *                              associated handlers.
     */
    public static function getSubscribedEvents()
    {
        return array(
            ScriptEvents::PRE_AUTOLOAD_DUMP => 'dump',
        );
    }

    /**
     * Dump the event for discovery purposes.
     *
     * @param Event $event Event that was triggered.
     */
    public static function dump(Event $event)
    {
        $composer            = $event->getComposer();
        $installationManager = $composer->getInstallationManager();
        $repoManager         = $composer->getRepositoryManager();
        $localRepo           = $repoManager->getLocalRepository();
        $package             = $composer->getPackage();
        $config              = $composer->getConfig();

        // To enable class-based filtering, we force `--optimize` flag for our
        // custom autoloaders, so that all PSR-0 and PSR-4 namespaces are
        // iterated and translated to arrays of classes.
        $optimize = true;

        $vendorDir       = $config->get('vendor-dir', Config::RELATIVE_PATHS);
        $defaultLocation = "{$vendorDir}/wp-cli/wp-cli/php/WP_CLI/AutoloadSplitter.php";
        $suffix          = $config->get('autoloader-suffix');

        self::$extra = $event->getComposer()
            ->getPackage()
            ->getExtra();

        $splitterLogic    = self::getExtraKey(self::LOGIC_CLASS_KEY, 'WP_CLI\AutoloadSplitter');
        $splitterLocation = self::getExtraKey(self::LOGIC_CLASS_LOCATION_KEY, $defaultLocation);
        $filePrefixTrue   = self::getExtraKey(self::SPLIT_TARGET_PREFIX_TRUE_KEY, 'autoload_commands');
        $filePrefixFalse  = self::getExtraKey(self::SPLIT_TARGET_PREFIX_FALSE_KEY, 'autoload_framework');

        if (!class_exists($splitterLogic)) {
            $splitterClassPath = sprintf('%s/%s', getcwd(), $splitterLocation);

            // Avoid proceeding if the splitter class file does not exist.
            if (!is_readable( $splitterClassPath)) {
                return;
            }

            include_once $splitterClassPath;
        }

        $generator = new AutoloadGenerator(
            $composer->getEventDispatcher(),
            $event->getIO(),
            $splitterLogic,
            $filePrefixTrue,
            $filePrefixFalse
        );

        $generator->dump(
            $config,
            $localRepo,
            $package,
            $installationManager,
            'composer',
            $optimize,
            $suffix
        );
    }

    /**
     * Activate the Composer plugin.
     *
     * @param Composer    $composer Reference to the Composer instance.
     * @param IOInterface $io       Reference to the IO interface.
     */
    public function activate(Composer $composer, IOInterface $io)
    {
    }

    /**
     * Get the value of a config key from the "Extra" section.
     *
     * @param string $key      Key to look for.
     * @param string $fallback Fallback value to use if the key was not provided.
     *
     * @return string
     */
    private static function getExtraKey($key, $fallback)
    {
        static $autosplitter = null;

        if (null === $autosplitter) {
            $autosplitter = isset(self::$extra[self::EXTRA_KEY])
                ? self::$extra[self::EXTRA_KEY]
                : array();
        }

        return isset($autosplitter[$key]) ? $autosplitter[$key] : $fallback;
    }
}
