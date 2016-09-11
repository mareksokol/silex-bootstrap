<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    public function indexAction(): HttpResponse
    {
        return $this->format();
    }
}
