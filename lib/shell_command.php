<?php

use Psy\Configuration;
use Psy\Output\ShellOutput;
use Psy\VersionUpdater\Checker;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
class rex_shell_command extends rex_console_command
{
    protected function configure()
    {
        $this->setDescription('Interactive shell (REPL) via PsySH');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $addon = rex_addon::get('shell');

        require_once $addon->getPath('vendor/psy/psysh/src/functions.php');

        $output = new ShellOutput();

        $config = Configuration::fromInput($input);
        $config->setOutput($output);
        $config->setConfigDir($addon->getDataPath());
        $config->setRuntimeDir($addon->getCachePath());
        $config->setUpdateCheck(Checker::NEVER);
        $config->setStartupMessage(' '); // force additional line after "Psy Shell ..."
        $config->setEraseDuplicates(true);
        $config->setFormatterStyles([
            'aside' => ['yellow'],
            'comment' => ['yellow'],
            'object' => ['magenta'],
            'class' => ['magenta'],
        ]);

        $commands = $this->getApplication()->all();
        unset($commands['help']);
        unset($commands['list']);
        unset($commands['shell']);
        $config->addCommands($commands);

        $shell = new rex_shell($config);

        $io = $this->getStyle($input, $output);
        $io->title('REDAXO '.rex::getVersion().' shell');

        return $shell->run();
    }
}
