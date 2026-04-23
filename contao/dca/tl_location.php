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
use Contao\DC_Table;

$GLOBALS['TL_DCA']['tl_location'] = [

    // Config
    'config' => [
        'dataContainer'    => DC_Table::class,
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id'    => 'primary',
                'alias' => 'index',
            ],
        ],
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'        => 1,
            'fields'      => ['name'],
            'flag'        => 1,
            'panelLayout' => 'filter;search,limit',
        ],
        'label' => [
            'fields' => ['name', 'contact_name', 'phone'],
            'format' => '%s (%s, %s)',
        ],
        'global_operations' => [
            'export' => [
                'label'      => 'Export Locations CSV',
                'href'       => 'key=exportLocations',
                'icon'       => 'bundles/repfinder/icons/file-export-icon-16.png',
            ],
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'href' => 'act=edit',
                'icon' => 'edit.svg',
            ],
            'copy' => [
                'href' => 'act=copy',
                'icon' => 'copy.svg',
            ],
            'delete' => [
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\'' . ($GLOBALS['TL_LANG']['MSC']['deleteConfirm'] ?? 'Are you sure?') . '\'))return false;Backend.getScrollOffset()"',
            ],
            'toggle' => [
                'icon'         => 'visible.svg',
                'toggleField'  => 'published',
            ],
            'show' => [
                'href' => 'act=show',
                'icon' => 'show.svg',
            ],
        ],
    ],

    // Palettes
    'palettes' => [
        'default' => '{location_legend},name,alias,contact_name;{address_legend},phone,url;{website_legend},territory_notes,zip;{publish_legend},published;',
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'autoincrement' => true],
        ],
        'tstamp' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'sorting' => [
            'sql' => ['type' => 'integer', 'unsigned' => true, 'default' => 0],
        ],
        'alias' => [
            'label'         => &$GLOBALS['TL_LANG']['tl_location']['alias'],
            'exclude'       => true,
            'inputType'     => 'text',
            'search'        => true,
            'eval'          => ['unique' => true, 'rgxp' => 'alias', 'doNotCopy' => true, 'maxlength' => 128, 'tl_class' => 'w50'],
            'save_callback' => [
                [LocationsController::class, 'generateAlias'],
            ],
            'sql'           => ['type' => 'string', 'length' => 128, 'default' => '', 'customSchemaOptions' => ['collation' => 'utf8_bin']],
        ],
        'name' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['name'],
            'inputType' => 'text',
            'search'    => true,
            'eval'      => ['mandatory' => true, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'contact_name' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['contact_name'],
            'inputType' => 'text',
            'search'    => true,
            'eval'      => ['mandatory' => false, 'tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'phone' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['phone'],
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'territory_notes' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['territory_notes'],
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'url' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['url'],
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50', 'rgxp' => 'url'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'zip' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['zip'],
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50'],
            'sql'       => ['type' => 'text', 'notnull' => true, 'default' => ''],
        ],
        'email' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['email'],
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50', 'rgxp' => 'email'],
            'sql'       => ['type' => 'string', 'length' => 255, 'default' => ''],
        ],
        'state' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['state'],
            'inputType' => 'text',
            'eval'      => ['tl_class' => 'w50', 'maxlength' => 10],
            'sql'       => ['type' => 'string', 'length' => 10, 'default' => ''],
        ],
        'published' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_location']['published'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'toggle'    => true,
            'eval'      => ['submitOnChange' => true, 'doNotCopy' => true],
            'sql'       => ['type' => 'boolean', 'default' => false],
        ],
    ],
];
