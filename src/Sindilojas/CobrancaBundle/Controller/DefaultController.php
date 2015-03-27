<?php

namespace Sindilojas\CobrancaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/carrega/parcelas", name="_carrega_parcelas")
     * @return Response
     */
    public function paginationAction()
    {
        $rep = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela");
        $parcelas = $rep->getParcelasVencidas();
        $dados = array();
        foreach ($parcelas as $parcela) {
            $parcela = (object) $parcela;
            $vencimento = new \DateTime($parcela->vencimento);
            $linha = array();
            $linha[] = "<a href=\"".$this->generateUrl("_cliente_cadastro", array("id"=>$parcela->id_cliente))."\">". $parcela->cliente_nome."</a>";
            $linha[] = $parcela->loja_nome;
            $linha[] = "R$ ".number_format($parcela->valor, 2, ",", ".");
            $linha[] = $vencimento->format("d/m/Y");
            $dados[] = $linha;
        }
        $return['recordsTotal'] = count($parcelas);
        $return['recordsFiltered'] = count($parcelas);
        $return['data'] = $dados;
        return new Response(json_encode($return));
    }

    /**
     * @Route("/login", name="_login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        $errorMsg = "";
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->set(SecurityContext::AUTHENTICATION_ERROR, "");
        }
        
        if (is_object($error)) {
            $errorMsg = $this->get("translator")->trans($error->getMessage());
        }

        return array(
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $errorMsg,
        );
    }
}
