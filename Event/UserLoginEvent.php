<?php

namespace Rz\UserBundle\Event;

use FOS\UserBundle\Event\UserInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserLoginEvent extends Event
{
    private $request;
    private $response;
    private $error = '';

    public function __construct(Request $request, $error)
    {
        $this->request = $request;
        $this->error = $error;
        $this->response = null;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return string|null
     */
    public function getError()
    {
        return $this->error;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }


}
