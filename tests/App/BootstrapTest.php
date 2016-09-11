<?php
namespace Tests\App;

use Silex\Application;
use App\Bootstrap;
use Symfony\Component\HttpKernel\KernelEvents;
use Silex\ExceptionListenerWrapper;

class BootstrapTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Bootstrap
     */
    private $bootstrap;

    public function setUp()
    {
        $this->bootstrap = new Bootstrap();
    }

    public function testCallLoadServiceMethodReturnSelf()
    {
        $app = $this->getMockBuilder(Application::class)
            ->setMethods(['register'])
            ->setConstructorArgs([[
                'monolog.level' => 400,
            ]])
            ->getMock();

        $app->method('register')
            ->with(
                $this->logicalOr(
                    $this->isInstanceOf(\App\Service\Provider\ConfigServiceProvider::class),
                    $this->isInstanceOf(\Silex\Provider\MonologServiceProvider::class),
                    $this->isInstanceOf(\App\Service\Provider\DbServiceProvider::class),
                    $this->isInstanceOf(\Silex\Provider\ServiceControllerServiceProvider::class),
                    $this->isInstanceOf(\Silex\Provider\ValidatorServiceProvider::class)
                )
            );

        $this->assertInstanceOf(Bootstrap::class, $this->bootstrap->loadServices($app));
    }

    public function testCallloadConsoleServicesMethodReturnSelf()
    {
        $app = $this->getMockBuilder(Application::class)
            ->setMethods(['register'])
            ->getMock();

        $app->expects($this->once())
            ->method('register')
            ->with($this->isInstanceOf(\App\Service\Provider\ConsoleServiceProvider::class));

        $this->assertInstanceOf(Bootstrap::class, $this->bootstrap->loadConsoleServices($app));
    }

    public function testCallErrorMethodNumberInErrorHandler()
    {
        $observer = $this->getMockBuilder(Application::class)
            ->setMethods(['error'])
            ->getMock();
        $observer->expects($this->once())
            ->method('error')
            ->with($this->isInstanceOf(\Closure::class), $this->isType('integer'));
        $this->bootstrap->errorHandler($observer);
    }

    public function testIssetErrorHandlerInKernelException()
    {
        $app = new \Silex\Application();
        $out = $this->bootstrap->errorHandler($app);

        $this->assertInstanceOf(Bootstrap::class, $out);
        $listeners = $app->offsetGet('dispatcher')->getListeners();
        $this->assertArrayHasKey(KernelEvents::EXCEPTION, $listeners);
        $this->assertInstanceOf(ExceptionListenerWrapper::class, current($listeners[KernelEvents::EXCEPTION]));
    }
}
