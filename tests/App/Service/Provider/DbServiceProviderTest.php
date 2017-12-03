<?php

declare(strict_types=1);

namespace Tests\App\Service\Provider;

use App\Service\Provider\DbServiceProvider;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

/**
 * Class DbServiceProviderTest.
 *
 * @coversNothing
 */
class DbServiceProviderTest extends TestCase
{
    /**
     * @var array
     */
    private $config = [
        'db.driver' => 'pdo_pgsql',
        'db.dbname' => 'test_db',
        'db.host' => '127.0.0.1',
        'db.user' => 'postgres',
        'db.password' => 'secret',
        'db.port' => '6432',
    ];

    public function testServiceProvider(): void
    {
        $app = new Container($this->config);

        $provider = new DbServiceProvider();
        $provider->register($app);

        $this->assertInstanceOf(\Doctrine\DBAL\Connection::class, $app['db']);
        $this->assertInstanceOf(\Doctrine\DBAL\Driver\PDOPgSql\Driver::class, $app['db']->getDriver());
        $this->assertInstanceOf(\Doctrine\ORM\EntityManager::class, $app['orm.em']);
        $this->assertSame('test_db', $app['db']->getDatabase());
        $this->assertSame('127.0.0.1', $app['db']->getHost());
        $this->assertSame('postgres', $app['db']->getUsername());
        $this->assertSame('secret', $app['db']->getPassword());
        $this->assertSame('6432', $app['db']->getPort());
    }
}
