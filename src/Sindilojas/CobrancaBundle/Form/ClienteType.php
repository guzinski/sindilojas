<?php

namespace Sindilojas\CobrancaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


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
        $builder->add("nome", "text")
                ->add("cpf", "text")
                ->add("rg", "text", array("required"=>false))
                ->add("cep", "text", array("required"=>false))
                ->add("uf", "text", array("required"=>false, 'attr'=> array('disabled'=>'disabled')))
                ->add("cidade", "text", array("required"=>false, 'attr'=> array('disabled'=>'disabled')))
                ->add("bairro", "text", array("required"=>false))
                ->add("rua", "text", array("required"=>false))
                ->add("numero", "text", array("required"=>false))
                ->add("complemento", "text", array("required"=>false))
                ->add("email", 'email', array("required"=>false))
                ->add("telefone", "text", array("required"=>false))
                ->add("telefone1", "text", array("required"=>false))
                ->add("telefone2", "text", array("required"=>false))
                ->add("nascimento", "date", array(
                            'label'  => 'Data de Nascimento',
                            'widget' => 'single_text',
                            'format' => 'dd/MM/yyyy',
                            "required"=>false,
                  ))
                ->add("dividas", "collection", array(
                        'type'          => new DividaType(),
                        'allow_add'     => true,
                        'allow_delete'  => true,
                        'label'         => false,
                ))
                ->add('cobrancaJudicial', 'choice', array(
                    'choices' => array('1' => 'Sim', '0' => 'Não'),
                    'expanded' => true,
                    'label' => "Enviado para cobrança judicial",
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) 
    {
        $resolver->setDefaults(array(
                        'data_class' => 'Sindilojas\CobrancaBundle\Entity\Cliente',
                    ));
    }

    
    
}
