<?php

namespace Sindilojas\CobrancaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;


/**
 * Description of ClienteType
 *
 * @author Luciano
 */
class ClienteType extends AbstractType
{
    
    public function getName()
    {
        return "cliente";
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add("nome", "text", array("attr"=>array("class"=>"form-control")))
                ->add("cpf", "text")
                ->add("rg", "text")
                ->add("cep", "text")
                ->add("uf", "text")
                ->add("cidade", "text")
                ->add("bairro", "text")
                ->add("rua", "text")
                ->add("numero", "text")
                ->add("complemento", "text")
                ->add("email")
                ->add("telefone", "text")
                ->add("nascimento", "date", array(
                            'label'  => 'Data de Nascimento',
                            'widget' => 'single_text',
                            'format' => 'dd/MM/yyyy',
                  ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) 
    {
        $resolver->setDefaults(array(
                        'required' => false,
                        'data_class' => 'Sindilojas\CobrancaBundle\Entity\Cliente',
                    ));
    }

    
    
}
