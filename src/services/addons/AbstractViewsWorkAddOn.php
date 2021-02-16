<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 16/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\services\addons;

use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\ViewsWork;

abstract class AbstractViewsWorkAddOn
{

    abstract public function setupListeners() : void;

    public function getViewsWorkSettings() : Settings
    {
        return ViewsWork::$plugin->getSettings();
    }

}