<?php
namespace Tests\App\Service\Provider;

use App\Service\Provider\DbServiceProvider;
use Pimple\Container;

class DbServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $config = [
        'db.driver'               => 'pdo_pgsql',
        'db.dbname'               => 'test_db',
        'db.host'                 => '127.0.0.1',
        'db.user'                 => 'postgres',
        'db.password'             => 'secret',
        'db.port'                 => '6432',
    ];

    public function testServiceProvider()
    {
        $app = new Container($this->config);

        $provider = new DbServiceProvider();
        $provider->register($app);

        $this->assertInstanceOf(\Doctrine\DBAL\Connection::class, $app['db']);
        $this->assertInstanceOf(\Doctrine\DBAL\Driver\PDOPgSql\Driver::class, $app['db']->getDriver());
        $this->assertInstanceOf(\Doctrine\ORM\EntityManager::class, $app['orm.em']);
        $this->assertEquals('test_db', $app['db']->getDatabase());
        $this->assertEquals('127.0.0.1', $app['db']->getHost());
        $this->assertEquals('postgres', $app['db']->getUsername());
        $this->assertEquals('secret', $app['db']->getPassword());
        $this->assertEquals('6432', $app['db']->getPort());
    }
}
