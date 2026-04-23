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

namespace BrightCloudStudio\RepFinderBundle\Controller\Backend;

use BrightCloudStudio\RepFinderBundle\Model\LocationModel;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\DataContainer;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LocationsController
{
    public function __construct(private readonly Connection $db)
    {
    }

    /**
     * Returns available item templates (for the module palette dropdown).
     */
    public function getItemTemplates(): array
    {
        // Return templates matching the item_location prefix
        return \Contao\Controller::getTemplateGroup('item_location_');
    }

    /**
     * Export all locations as a CSV file.
     * Triggered via the global_operations 'exportLocations' key.
     */
    public function exportLocations(): never
    {
        $locations = LocationModel::findAll();

        if (!$locations) {
            throw new ResponseException(
                new \Symfony\Component\HttpFoundation\Response('Nothing to export', 200)
            );
        }

        $filename = 'locations_' . date('Y-m-d_Hi') . '.csv';

        $response = new StreamedResponse(function () use ($locations): void {
            $handle = fopen('php://output', 'w');
            $headerWritten = false;

            foreach ($locations as $location) {
                $row = $location->row();
                if (!$headerWritten) {
                    fputcsv($handle, array_keys($row));
                    $headerWritten = true;
                }
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        throw new ResponseException($response);
    }

    /**
     * Auto-generate a unique alias for a location record.
     */
    public function generateAlias(mixed $varValue, DataContainer $dc): string
    {
        $autoAlias = false;

        if ($varValue === '') {
            $autoAlias = true;
            $varValue = \Contao\System::getContainer()
                ->get('contao.slug.generator')
                ->generate(StringUtil::restoreBasicEntities((string) $dc->activeRecord->name));
        }

        $result = $this->db->fetchOne(
            'SELECT id FROM tl_location WHERE (id = ? OR alias = ?) AND id != ?',
            [(int) $dc->id, $varValue, (int) $dc->id]
        );

        if ($result !== false) {
            if (!$autoAlias) {
                throw new \RuntimeException(
                    sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'] ?? 'Alias "%s" already exists.', $varValue)
                );
            }
            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }
}
