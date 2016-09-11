<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class AbstractController provide pagination mechanism and helper methods for creating responses from application in REST format.
 */
abstract class AbstractController
{
    /**
     * Return response in common format.
     *
     * @param mixed $data
     * @param int $code
     * @param string $error
     * @return HttpResponse
     */
    public function format($data, int $code = HttpResponse::HTTP_OK, string $error = null): HttpResponse
    {
        $response = new HttpResponse();

        $meta = [];

        if ($error) {
            $meta['error'] = $error;
        }

        $response->setStatusCode($code);

        $response->headers->set(
            'Content-Type',
            'application/json'
        );

        $response->setContent(json_encode([
            'data' => $data,
            'meta' => $meta,
        ]));

        return $response;
    }

    /**
     * Return error response in common format.
     *
     * @param string $msg
     * @param int $code
     * @return HttpResponse
     */
    public function error(string $msg, int $code = HttpResponse::HTTP_INTERNAL_SERVER_ERROR): HttpResponse
    {
        return $this->format(null, $code, $msg);
    }
}
