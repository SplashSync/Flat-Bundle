<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) 2015-2021 Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\Flat\Form;

use Burgov\Bundle\KeyValueFormBundle\Form\Type\KeyValueType;
use Splash\Connectors\Flat\Form\Type\FlatFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Base Form Type for Flat Files Connectors Servers
 */
abstract class AbstractFlatType extends AbstractType
{
    /**
     * Add Flat Objects Field to FormBuilder
     *
     * @param FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addObjectTypesField(FormBuilderInterface $builder): self
    {
        $builder
            ->add('Objects', KeyValueType::class, array(
                'label' => "var.objects.label",
                'required' => false,
                'key_type' => TextType::class,
                'key_options' => array(
                    'label' => "var.object.label",
                    'help' => "var.object.desc",
                ),
                'value_type' => FlatFileType::class,
                'value_options' => array(
                    'label' => false,
                ),
                'translation_domain' => "FlatBundle",
            ))
        ;

        return $this;
    }
}
