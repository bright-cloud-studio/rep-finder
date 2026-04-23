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

namespace BrightCloudStudio\RepFinderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RepFinderBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension(): ?\Symfony\Component\DependencyInjection\Extension\ExtensionInterface
    {
        return null; // No custom extension needed; services.yaml is loaded via Plugin.php
    }
}
