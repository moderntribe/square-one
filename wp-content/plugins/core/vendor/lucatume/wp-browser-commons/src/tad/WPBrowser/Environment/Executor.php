<?php

namespace tad\WPBrowser\Environment;

/**
 * Class Executor
 *
 * Handles execution of stand-alone processes.
 *
 * @package tad\WPBrowser\Environment
 */
class Executor
{
    /**
     * Wraps the `exec` functions with some added debug information.
     *
     * Differently from PHP defaults `exec` function this method will return
     * the command exit status and not the last line of output.
     *
     * @see exec()
     *
     * @param string $command
     * @param array $output
     *
     * @return int string
     */
    public function exec($command, array &$output = null)
    {
        list($output, $return_var) = $this->realExec($command);

        return $return_var;
    }

    public function execAndOutput($command, &$return_var)
    {
        list($output, $return_var) = $this->realExec($command);

        return $output;
    }

    /**
     * @param $command
     * @return array
     */
    protected function realExec($command)
    {
	    exec($command, $output, $return_var);
	    codecept_debug($output);

	    return array($output, $return_var);
    }
}