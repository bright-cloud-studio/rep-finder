<?php

declare(strict_types=1);

namespace BrightCloudStudio\RepFinderBundle\ContaoManager;

use BrightCloudStudio\RepFinderBundle\RepFinderBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(RepFinderBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
