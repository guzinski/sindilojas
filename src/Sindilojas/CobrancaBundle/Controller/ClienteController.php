<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ClienteController
 *
 * @author Luciano
 */
class ClienteController extends Controller
{
    
    /**
     * @Route("/clientes", name="_clientes")
     * @Template()
     */
    public function indexAction() 
    {
        return array();
    }
    
    
    /**
     * @Route("/clientes/pagination", name="_clientes_pagination")
     * @Template()
     */
    public function paginationAction(Request $request)
    {
        $return['draw'] = $request->get("draw");
        $return['recordsTotal'] = 0;
        $return['recordsFiltered'] = 0;
        $return['data'] = array();
        return new Response(json_encode($return));
    }
    
}
