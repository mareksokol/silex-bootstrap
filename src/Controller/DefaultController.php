<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class DefaultController.
 */
class DefaultController
{
    use JsonResponseTrait;

    public function indexAction(): HttpResponse
    {
        return $this->response();
    }
}
