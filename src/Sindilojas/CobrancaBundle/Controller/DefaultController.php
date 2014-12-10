<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="_teste")
     */
    public function indexAction()
    {
        return new Response("Index");
    }
    
    /**
     * @Route("/login", name="_login")
     * @Template()
     */
    public function loginAction()
    {
        return array();
    }
}
