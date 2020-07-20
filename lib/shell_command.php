<?php

use Psy\Configuration;
use Psy\Output\ShellOutput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @internal
 */
class rex_shell_command extends rex_console_command
{
    protected function configure()
    {
        $this
            ->setDescription('Interactive shell');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        require_once rex_addon::get('shell')->getPath('vendor/psy/psysh/src/functions.php');

        $output = new ShellOutput();

        $config = new Configuration();
        $config->setOutput($output);
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

        return $shell->run($input, $output);
    }
}
