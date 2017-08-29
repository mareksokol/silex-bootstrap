<?php
namespace Tests\App\Controller;

use App\Controller\JsonResponseTrait;
use PHPUnit\Framework\TestCase;

class JsonResponseTraitTest extends TestCase
{
    use JsonResponseTrait;

    public function testSimpleResponseFormat()
    {
        $out = $this->response([
            'test' => 1,
        ]);

        $this->assertInternalType('string', $out->getContent());
        $this->assertEquals('{"data":{"test":1}}', $out->getContent());
        $this->assertEquals(200, $out->getStatusCode());
        $this->assertEquals('application/json', $out->headers->get('Content-Type'));
    }

    public function testCustomResponseFormat()
    {
        $out = $this->response('marek', false, \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);

        $this->assertInternalType('string', $out->getContent());
        $this->assertEquals('{"data":"marek"}', $out->getContent());
        $this->assertEquals(201, $out->getStatusCode());
        $this->assertEquals('application/json', $out->headers->get('Content-Type'));
    }

    public function testErrorResponse()
    {
        $out = $this->response(null, 'unknown', \Symfony\Component\HttpFoundation\Response::HTTP_BAD_GATEWAY);

        $this->assertInternalType('string', $out->getContent());
        $this->assertEquals('{"meta":{"error":"unknown"}}', $out->getContent());
        $this->assertEquals(502, $out->getStatusCode());
        $this->assertEquals('application/json', $out->headers->get('Content-Type'));
    }

    public function testErrorDefaultCodeResponse()
    {
        $out = $this->error('unknown');

        $this->assertInternalType('string', $out->getContent());
        $this->assertJsonStringEqualsJsonString(
            json_encode([
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
