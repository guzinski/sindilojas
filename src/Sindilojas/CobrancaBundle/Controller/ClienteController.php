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
use PhpOffice\PhpWord\PhpWord;


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
        $firstResult = $request->query->getInt("start");
        $maxResults = $request->query->getInt("length");
        $busca = $request->get("search");
        $ordem = $request->get("order");
        
        
        $repClientes = $this->getDoctrine()
                            ->getRepository("Sindilojas\CobrancaBundle\Entity\Cliente");
        $clientes = $repClientes->getClientes($busca['value'], $maxResults, $firstResult, $ordem);
        
        $dados = array();
        foreach ($clientes as $cliente) {
            $linha = array();
            $linha[] = "<a href=\"".$this->generateUrl("_cliente_cadastro", array("id"=>$cliente->getId()))."\">". $cliente->getNome()."</a>";
            $linha[] = $cliente->getTelefone();
            $linha[] = $cliente->getCidade();
            $dados[] = $linha;
        }
        $return['draw']             = $request->get("draw");
        $return['recordsTotal']     = $repClientes->count();
        $return['recordsFiltered']  = $repClientes->count($busca['value']);
        $return['data']             = $dados;
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
        
        $form = $this->createForm(new ClienteType(), $cliente, array('em'=>$em));
        
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
    
    /**
     * @Route("/cliente/carta/{nome}", name="_carta_cliente")
     * 
     * @Template
     * @param int $idParcela
     * @return Response
     */
    public function cartaAction($nome = null)
    {
        $hoje = new \DateTime("now");
    
        $PHPWord = new PhpWord();

        $file = time();
        
        try {
            $document = $PHPWord->loadTemplate('../web/bundles/sindilojas/doc/carta.docx');
        } catch (Exception $exc) {
            echo $exc->getMessage();
        }
        
        $document->setValue("cliente", $nome);
        $document->setValue("data", $hoje->format("d/m/Y"));
        
        $document->saveAs("../web/bundles/sindilojas/doc/carta{$file}.docx");
    
        return new Response($this->container->get('templating.helper.assets')->getUrl("bundles/sindilojas/doc/carta{$file}.docx"));
        
    }


    
}
