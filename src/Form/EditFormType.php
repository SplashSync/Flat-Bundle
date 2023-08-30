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
use Splash\Connectors\Flat\Helpers\AdapterBuilder;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Flat Account Edit Form
 */
class EditFormType extends AbstractFlatType
{
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
        //==============================================================================
        // Connector Objects Configuration
        $this->addObjectTypesField($builder);
    }
}
