<?php

namespace Splash\Connectors\Flat\Form\Type;

use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType;
use Splash\Connectors\Flat\Services\FileReader;
use Splash\Connectors\Flat\Services\FileParser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class FlatFileType extends AbstractType
{
    public function __construct(
        private FileReader $adapterManager,
        private FileParser $parserManager,
    ) {

    }


    /**
     * Build Optilog Edit Form
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('targets', KeyValueType::class, array(
                'label' => "var.object.targets.label",
                'required' => false,
                'key_type' => TextType::class,
                'key_options' => array(
                    'label' => "var.object.url.label",
                    'help' => "var.object.url.desc",
                    'required' => true,
                ),
                'value_type' => ChoiceType::class,
                'value_options' => array(
                    'label' => "var.object.parser.label",
                    'help' => "var.object.parser.desc",
                    'required' => true,
                    'choices' => $this->parserManager->getChoices()
                ),
                'translation_domain' => "FlatBundle",
            ))
            ->add('model', TextType::class, array(
                'label' => "var.object.model.label",
                'help' => "var.object.model.desc",
                'required' => true,
                'translation_domain' => "FlatBundle",
            ))
            ->add('ttl', ChoiceType::class, array(
                'label' => "var.object.ttl.label",
                'help' => "var.object.ttl.desc",
                'required' => true,
                'choices' => array(
                    "ASAP" => "-5 seconds",
                    "Every 15 Minutes" => "-15 minutes",
                    "Every Hours" => "-60 minutes",
                    "Every 6 Hours" => "-6 hours",
                ),
                'translation_domain' => "FlatBundle",
            ))
        ;
    }
}