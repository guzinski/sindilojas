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
     * @Template()
     */
    public function paginationAction()
    {
        $rep = $this->getDoctrine()->getRepository("Sindilojas\CobrancaBundle\Entity\Parcela");
        $parcelas = $rep->getParcelasVencidas();
        $dados = array();
        foreach ($parcelas as $parcela) {
            $linha = array();
            $linha[] = $parcela->getNegociacao()->getDivida()->getCliente()->getNome();
            $linha[] = $parcela->getNegociacao()->getDivida()->getLoja()->getNome();
            $linha[] = "R$ ".number_format($parcela->getValor(), 2, ",", ".");
            $linha[] = $parcela->getVencimento()->format('d/m/Y');
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
