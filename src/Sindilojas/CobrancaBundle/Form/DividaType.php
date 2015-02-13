<?php

namespace Sindilojas\CobrancaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * Description of DividaType
 *
 * @author Luciano
 */
class DividaType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder->add('loja', 'entity', array(
                        'class' => 'SindilojasCobrancaBundle:Loja',
                        'empty_value' => 'Selecione',
                        'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
                            return $er->createQueryBuilder('l')
                                        ->orderBy('l.nome', 'ASC');
                        },
                    ))
                ->add("valor", 'money', array("currency"=>"", "grouping"=>false))
                ->add("vencimento", "date", array(
                        'label'  => 'Data de Nascimento',
                        'widget' => 'single_text',
                        'format' => 'dd/MM/yyyy',
                    ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'    => 'Sindilojas\CobrancaBundle\Entity\Divida',
            'label'         => false
        ));
    }

    public function getName() 
    {
        return "divida";
    }

    
    
}
