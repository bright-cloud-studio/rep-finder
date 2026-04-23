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

namespace BrightCloudStudio\RepFinderBundle\Controller\FrontendModule;

use BrightCloudStudio\RepFinderBundle\Model\LocationModel;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(
    type: 'locations_list',
    category: 'rep_finder',
    template: 'mod_locations_list'
)]
class LocationsListController extends AbstractFrontendModuleController
{
    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $locations = LocationModel::findBy('published', '1');

        if ($locations === null) {
            $template->set('empty', 'No Locations Found');
            $template->set('states', []);
            $template->set('stateOptions', $this->generateSelectOptions());
            return $template->getResponse();
        }

        $states = [];
        $statesData = $this->getStates();

        foreach ($locations as $location) {
            $stateKey = $location->state;
            $stateName = $statesData['United States'][$location->state]
                ?? $statesData['Canada'][$location->state]
                ?? '';

            // Group all Canadian provinces under a single key
            if (in_array($location->state, ['AB','BC','MB','NB','NL','NS','NT','NU','ON','PE','QC','SK','YT'], true)) {
                $stateKey = 'CAN';
                $stateName = 'Canada - All Provinces';
            }

            if (!array_key_exists($stateKey, $states)) {
                $states[$stateKey] = [
                    'name'      => $stateName,
                    'abbr'      => $stateKey,
                    'locations' => [],
                ];
            }

            $locationData = [
                'id'               => $location->id,
                'alias'            => $location->alias,
                'tstamp'           => $location->tstamp,
                'published'        => $location->published,
                'name'             => $location->name,
                'contact_name'     => $location->contact_name,
                'phone'            => $location->phone,
                'url'              => $location->url,
                'territory_notes'  => $location->territory_notes,
                'zip'              => $location->zip,
            ];

            $states[$stateKey]['locations'][] = $locationData;
        }

        // Sort US states alphabetically, keep Canada at end
        $canada = $states['CAN'] ?? null;
        unset($states['CAN']);
        uasort($states, static fn(array $a, array $b): int => strcmp($a['name'], $b['name']));
        if ($canada !== null) {
            $states['CAN'] = $canada;
        }

        $template->set('states', $states);
        $template->set('stateOptions', $this->generateSelectOptions());
        $template->set('empty', null);

        return $template->getResponse();
    }

    private function generateSelectOptions(bool $blank = true): string
    {
        $statesData = $this->getStates();
        $html = $blank ? '<option value="">Select Location...</option>' : '';
        $html .= '<optgroup label="United States">';
        foreach ($statesData['United States'] as $abbr => $state) {
            $html .= '<option value="' . htmlspecialchars($abbr, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '">'
                   . htmlspecialchars($state, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
                   . '</option>';
        }
        $html .= '</optgroup>';
        $html .= '<optgroup label="Canada"><option value="CAN">All Provinces</option></optgroup>';

        return $html;
    }

    private function getStates(): array
    {
        return [
            'United States' => [
                'AL' => 'Alabama',
                'AK' => 'Alaska',
                'AZ' => 'Arizona',
                'AR' => 'Arkansas',
                'CA' => 'California',
                'CO' => 'Colorado',
                'CT' => 'Connecticut',
                'DE' => 'Delaware',
                'FL' => 'Florida',
                'GA' => 'Georgia',
                'HI' => 'Hawaii',
                'ID' => 'Idaho',
                'IL' => 'Illinois',
                'IN' => 'Indiana',
                'IA' => 'Iowa',
                'KS' => 'Kansas',
                'KY' => 'Kentucky',
                'LA' => 'Louisiana',
                'ME' => 'Maine',
                'MD' => 'Maryland',
                'MA' => 'Massachusetts',
                'MI' => 'Michigan',
                'MN' => 'Minnesota',
                'MS' => 'Mississippi',
                'MO' => 'Missouri',
                'MT' => 'Montana',
                'NE' => 'Nebraska',
                'NV' => 'Nevada',
                'NH' => 'New Hampshire',
                'NJ' => 'New Jersey',
                'NM' => 'New Mexico',
                'NY' => 'New York',
                'NC' => 'North Carolina',
                'ND' => 'North Dakota',
                'OH' => 'Ohio',
                'OK' => 'Oklahoma',
                'OR' => 'Oregon',
                'PA' => 'Pennsylvania',
                'RI' => 'Rhode Island',
                'SC' => 'South Carolina',
                'SD' => 'South Dakota',
                'TN' => 'Tennessee',
                'TX' => 'Texas',
                'UT' => 'Utah',
                'VT' => 'Vermont',
                'VA' => 'Virginia',
                'WA' => 'Washington',
                'WV' => 'West Virginia',
                'WI' => 'Wisconsin',
                'WY' => 'Wyoming',
                'AS' => 'American Samoa',
                'DC' => 'District of Columbia',
                'FM' => 'Federated States of Micronesia',
                'GU' => 'Guam',
                'MH' => 'Marshall Islands',
                'MP' => 'Northern Mariana Islands',
                'PW' => 'Palau',
                'PR' => 'Puerto Rico',
                'VI' => 'Virgin Islands',
            ],
            'Canada' => [
                'AB' => 'Alberta',
                'BC' => 'British Columbia',
                'MB' => 'Manitoba',
                'NB' => 'New Brunswick',
                'NL' => 'Newfoundland and Labrador',
                'NS' => 'Nova Scotia',
                'NT' => 'Northwest Territories',
                'NU' => 'Nunavut',
                'ON' => 'Ontario',
                'PE' => 'Prince Edward Island',
                'QC' => 'Quebec',
                'SK' => 'Saskatchewan',
                'YT' => 'Yukon',
            ],
        ];
    }
}
