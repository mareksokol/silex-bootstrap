<?php
namespace App\Service\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Application;
use Knp\Console\Application as ConsoleApplication;
use Knp\Console\ConsoleEvents;
use Knp\Console\ConsoleEvent;
use Symfony\Component\Console\Helper as SymfonyConsoleHelper;
use Doctrine\DBAL\Tools\Console\Helper as DoctrineConsoleHelper;
use Doctrine\ORM as DoctrineORM;
use Doctrine\DBAL\Migrations as DoctrineMigrations;
use Doctrine\ORM\Tools\Console;

class ConsoleServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers service
     *
     * @param Container $app
     */
    public function register(Container $app)
    {
        $app['console'] = function () use ($app) {
            $cli = new ConsoleApplication(
                $app,
                __DIR__ . '/../../..',
                'Console App'
            );

            $this->registerMigrations($app, $cli);
            $this->registerOrm($cli);

            $cli->setCatchExceptions(true);

            $app['dispatcher']->dispatch(ConsoleEvents::INIT, new ConsoleEvent($cli));

            return $cli;
        };
    }

    /**
     * Register configuration for and orm mapping.
     *
     * @param ConsoleApplication $cli
     */
    public function registerOrm(ConsoleApplication $cli)
    {
        $cli->add(new Console\Command\GenerateEntitiesCommand());
        $cli->add(new Console\Command\GenerateProxiesCommand());
        $cli->add(new Console\Command\GenerateRepositoriesCommand());
        $cli->add(new Console\Command\ConvertMappingCommand());
        $cli->add(new Console\Command\SchemaTool\CreateCommand());
        $cli->add(new Console\Command\SchemaTool\UpdateCommand());
        $cli->add(new Console\Command\SchemaTool\DropCommand());
    }

    /**
     * Register configuration for migrations mapping.
     *
     * @param Application $app
     * @param ConsoleApplication $cli
     */
    public function registerMigrations(Application $app, ConsoleApplication $cli)
    {
        $helperSet = new SymfonyConsoleHelper\HelperSet([
            'db'     => new DoctrineConsoleHelper\ConnectionHelper($app['db']),
            'em'     => new DoctrineORM\Tools\Console\Helper\EntityManagerHelper($app['orm.em']),
            'dialog' => new SymfonyConsoleHelper\QuestionHelper(),
        ]);

        $cli->setHelperSet($helperSet);

        $configuration = new DoctrineMigrations\Configuration\Configuration($app['db']);
        $configuration->setMigrationsDirectory($app['db.migrations.directory']);
        $configuration->setName($app['db.migrations.name']);
        $configuration->setMigrationsNamespace($app['db.migrations.namespace']);
        $configuration->setMigrationsTableName($app['db.migrations.tableName']);

        foreach ([
                     'Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand',
                     'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
                     'Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand',
                     'Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand',
                     'Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand',
                     'Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand',
                 ] as $name) {
            $command = new $name();
            $command->setMigrationConfiguration($configuration);
            $cli->add($command);
        }
    }
}