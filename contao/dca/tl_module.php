<?php

declare(strict_types=1);

/**
 * Rep Finder - Location Plugin for Contao
 *
 * Copyright (C) 2021 Bright Cloud Studio
 *
 * @package    bright-cloud-studio/rep-finder
 * @link       https://www.brightcloudstudio.com/
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

use BrightCloudStudio\RepFinderBundle\Controller\Backend\LocationsController;

// The FE module palette is auto-registered via the #[AsFrontendModule] attribute.
// This file only adds the custom item template selector field.

$GLOBALS['TL_DCA']['tl_module']['fields']['locations_customItemTpl'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_module']['customItemTpl'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => [LocationsController::class, 'getItemTemplates'],
    'eval'             => ['includeBlankOption' => true, 'chosen' => true, 'tl_class' => 'w50'],
    'sql'              => ['type' => 'string', 'length' => 64, 'default' => ''],
];
