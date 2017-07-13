<?php
/**
 * Copyright © 2017 Toan Nguyen. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace TableRates\Generator\Controller;

use Interop\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class AbstractController
 *
 * @package TableRates\Generator\Controller
 * @author  Toan Nguyen <toan.nguyen@balanceinternet.com.au>
 */
abstract class AbstractController
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $args;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Action constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Any controller will call this method and return the response object
     *
     * @param Request  $request
     * @param Response $response
     * @param array    $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;
        $this->execute();

        return $this->response;
    }

    /**
     * Action execute method
     *
     * @return mixed
     */
    abstract public function execute();
}
