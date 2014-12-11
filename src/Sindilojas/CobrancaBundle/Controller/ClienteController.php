<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sindilojas\CobrancaBundle\Form\ClienteType;
use Sindilojas\CobrancaBundle\Entity\Cliente;



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
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("Sindilojas\CobrancaBundle\Entity\Cliente");
        $clientes = $repository->findAll();
        $dados = array();
        foreach ($clientes as $cliente) {
            $linha = array();
            $linha[] = "<a href=\"".$this->generateUrl("_cliente_cadastro", array("id"=>$cliente->getId()))."\">". $cliente->getNome()."</a>";
            $linha[] = $cliente->getTelefone();
            $linha[] = $cliente->getCidade();
            $dados[] = $linha;
        }
        $return['draw'] = $request->get("draw");
        $return['recordsTotal'] = count($clientes);
        $return['recordsFiltered'] = count($clientes);
        $return['data'] = $dados;
        return new Response(json_encode($return));
    }
 
    
    /**
     * @Route("/clientes/cadastro/{id}", name="_cliente_cadastro")
     * @Template()
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cadastroAction(Request $request, $id=0)
    {
        $em = $this->getDoctrine()->getManager();
        
        if ($id>0) {
            $cliente = $em->find("Sindilojas\CobrancaBundle\Entity\Cliente", $id);
        } else {
            $cliente = new Cliente();
        }
        
        $form = $this->createForm(new ClienteType(), $cliente);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $dividas   = $cliente->getDividas();
                        
            foreach ($dividas as $divida) {
                $divida->setCliente($cliente);
                $cliente->getDividas()->add($divida);
            }
            
            $em->persist($cliente);
            $em->flush();
                        
            return new RedirectResponse($this->generateUrl('_clientes'));
        }
        
        return array('form' => $form->createView(), 'cliente'=>$cliente);
    }
    
    

    
}
