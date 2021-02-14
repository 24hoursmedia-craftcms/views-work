<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 11/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\services;

use Craft;
use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\ViewsWork;

/**
 * Class CpFacade
 * Facade exposed in twig in the CP
 *
 * @package twentyfourhoursmedia\viewswork\services
 */
class CpFacade extends Facade
{

    public function uniqid(string $prefix = 'uid_')
    {
        return uniqid($prefix, false);
    }
    public function getCookieBlockUrl() : string
    {
        return ViewsWork::$plugin->blockByCookieAddOn->getBlockUrl();
    }

    public function getCookieUnblockUrl() : string
    {
        return ViewsWork::$plugin->blockByCookieAddOn->getUnblockUrl();
    }

    public function getCookieBlockStatusUrl() : string
    {
        return ViewsWork::$plugin->blockByCookieAddOn->getStatusUrl();
    }

    /**
     * Return true if the settings require secrets
     *
     * @return bool
     */
    public function settingsRequiresSecrets() : bool
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        return $settings->requiresSecrets();
    }

    public function getBlockStatus() : array
    {
        return [
            'blocked' =>   ViewsWork::$plugin->blockByCookieAddOn->isBlocked(
                Craft::$app->getRequest()
            )
        ];
    }

    public function getSettings() : Settings
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        return $settings;
    }
}
