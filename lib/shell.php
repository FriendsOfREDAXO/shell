<?php

use Psy\Shell;

/**
 * @internal
 */
class rex_shell extends Shell
{
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        // silenced warnings ("@" operator)
        if (0 === error_reporting() && ($errno & (E_WARNING | E_NOTICE | E_DEPRECATED))) {
            return false;
        }

        parent::handleError($errno, $errstr, $errfile, $errline);

        return true;
    }
}
