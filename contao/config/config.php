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

/* Back end modules */
$GLOBALS['BE_MOD']['content']['locations'] = [
    'tables'          => ['tl_location'],
    'exportLocations' => [LocationsController::class, 'exportLocations'],
];

/* Models */
$GLOBALS['TL_MODELS']['tl_location'] = \BrightCloudStudio\RepFinderBundle\Model\LocationModel::class;
