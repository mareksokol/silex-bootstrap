<?php
namespace Tests\App\Service\Provider;

use App\Service\Provider\ConfigServiceProvider;
use Ivoba\Silex\EnvProvider;
use Pimple\Container;

class ConfigServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testCallRegisterMethodNumber()
    {
        $_ENV = array_merge($_ENV, [
            'app_debug'         => true,
            'app_monolog.level' => 200,
            'app_db.dbname'     => 'test',
            'app_db.password'   => 'postgres',
        ]);

        $pimple = new Container();
        $provider = new ConfigServiceProvider(new EnvProvider(), false);

        $provider->register($pimple);

        $this->assertTrue($pimple['debug']);
        $this->assertEquals(\Monolog\Logger::INFO, $pimple['monolog.level']);
        $this->assertEquals('test', $pimple['db.dbname']);
        $this->assertEquals('localhost', $pimple['db.host']);
        $this->assertEquals('postgres', $pimple['db.user']);
        $this->assertEquals('postgres', $pimple['db.password']);
        $this->assertEquals('5432', $pimple['db.port']);
        $this->assertEquals('test', $pimple['db.dbname']);
    }
}
