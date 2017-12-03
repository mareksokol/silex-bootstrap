<?php

declare(strict_types=1);

namespace Tests\App\Controller;

use App\Controller\JsonResponseTrait;
use PHPUnit\Framework\TestCase;

/**
 * Class JsonResponseTraitTest.
 *
 * @coversNothing
 */
class JsonResponseTraitTest extends TestCase
{
    use JsonResponseTrait;

    public function testSimpleResponseFormat(): void
    {
        $out = $this->response([
            'test' => 1,
        ]);

        $this->assertInternalType('string', $out->getContent());
        $this->assertSame('{"data":{"test":1}}', $out->getContent());
        $this->assertSame(200, $out->getStatusCode());
        $this->assertSame('application/json', $out->headers->get('Content-Type'));
    }

    public function testCustomResponseFormat(): void
    {
        $out = $this->response('marek', false, \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);

        $this->assertInternalType('string', $out->getContent());
        $this->assertSame('{"data":"marek"}', $out->getContent());
        $this->assertSame(201, $out->getStatusCode());
        $this->assertSame('application/json', $out->headers->get('Content-Type'));
    }

    public function testErrorResponse(): void
    {
        $out = $this->response(null, 'unknown', \Symfony\Component\HttpFoundation\Response::HTTP_BAD_GATEWAY);

        $this->assertInternalType('string', $out->getContent());
        $this->assertSame('{"meta":{"error":"unknown"}}', $out->getContent());
        $this->assertSame(502, $out->getStatusCode());
        $this->assertSame('application/json', $out->headers->get('Content-Type'));
    }

    public function testErrorDefaultCodeResponse(): void
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
        $this->assertSame(500, $out->getStatusCode());
        $this->assertSame('application/json', $out->headers->get('Content-Type'));
    }
}
