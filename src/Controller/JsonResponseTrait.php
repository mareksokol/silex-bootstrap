<?php
declare (strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Trait JsonResponseTrait
 * @package App\Controller
 */
trait JsonResponseTrait
{
    /**
     * Return configured response instance.
     *
     * @param mixed $data
     * @param bool $error
     * @param int $status
     * @return HttpResponse
     */
    protected function response($data = null, $error = false, int $status = HttpResponse::HTTP_OK): HttpResponse
    {
        $content = '';

        if (null !== $data || $error) {
            $content = [];

            if (null !== $data) {
                $content['data'] = $data;
            }
        }

        if ($error) {
            $content['meta'] = [
                'error' => $error
            ];
        }

        $response = new HttpResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($status);
        $response->setContent(json_encode($content));

        return $response;
    }

    /**
     * Create and return 404 (NotFound) response instance.
     *
     * @param string $msg
     * @return HttpResponse
     */
    protected function notFound(string $msg = null): HttpResponse
    {
        return $this->response(
            null, $msg, HttpResponse::HTTP_NOT_FOUND
        );
    }

    /**
     * Create and return 500 (InternalServerError) response instance with optional error message.
     *
     * @param string $msg
     * @return HttpResponse
     */
    protected function error(string $msg = null): HttpResponse
    {
        return $this->response(
            null, $msg, HttpResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
