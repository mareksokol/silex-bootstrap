<?php
namespace Tests\App\Controller;

use App\Controller\AbstractController;

class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractController
     */
    private $response;

    protected function setUp()
    {
        $this->response = new class() extends AbstractController
        {
        };
    }

    public function testSimpleResponseFormat()
    {
        $out = $this->response->format([
            'test' => 1,
        ]);

        $this->assertInternalType('string', $out->getContent());
        $this->assertEquals('{"data":{"test":1},"meta":[]}', $out->getContent());
        $this->assertEquals(200, $out->getStatusCode());
        $this->assertEquals('application/json', $out->headers->get('Content-Type'));
    }

    public function testCustomResponseFormat()
    {
        $out = $this->response->format('marek', \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);

        $this->assertInternalType('string', $out->getContent());
        $this->assertEquals('{"data":"marek","meta":[]}', $out->getContent());
        $this->assertEquals(201, $out->getStatusCode());
        $this->assertEquals('application/json', $out->headers->get('Content-Type'));
    }

    public function testErrorResponse()
    {
        $out = $this->response->error('unknown', \Symfony\Component\HttpFoundation\Response::HTTP_BAD_GATEWAY);

        $this->assertInternalType('string', $out->getContent());
        $this->assertEquals('{"data":null,"meta":{"error":"unknown"}}', $out->getContent());
        $this->assertEquals(502, $out->getStatusCode());
        $this->assertEquals('application/json', $out->headers->get('Content-Type'));
    }

    public function testErrorDefaultCodeResponse()
    {
        $out = $this->response->error('unknown');

        $this->assertInternalType('string', $out->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
                'data' => null,
                'meta' => [
                    'error' => 'unknown',
                ],
            ]),
            $out->getContent()
        );
        $this->assertEquals(500, $out->getStatusCode());
        $this->assertEquals('application/json', $out->headers->get('Content-Type'));
    }
}
