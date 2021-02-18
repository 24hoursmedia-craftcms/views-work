<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 11/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\services;

use Craft;
use craft\helpers\UrlHelper;
use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\services\addons\BlockByQueryParamAddOn;
use twentyfourhoursmedia\viewswork\ViewsWork;

/**
 * Class CpFacade
 * Facade exposed in twig in the CP, don't use in your front ends!
 *
 * @internal
 * @package twentyfourhoursmedia\viewswork\services
 */
class CpFacade extends Facade
{

    public function uniqid(string $prefix = 'uid_')
    {
        return uniqid($prefix, false);
    }

    public function getCookieBlockUrl(?int $siteId = null): string
    {
        return ViewsWork::$plugin->blockByCookieAddOn->getBlockUrl($siteId);
    }

    public function getCookieUnblockUrl(?int $siteId = null): string
    {
        return ViewsWork::$plugin->blockByCookieAddOn->getUnblockUrl($siteId);
    }

    public function getCookieBlockStatusUrl(?int $siteId = null): string
    {
        return ViewsWork::$plugin->blockByCookieAddOn->getStatusUrl($siteId);
    }

    public function getResetUrl(): ?string
    {
        if (!$this->getSettings()->allowUrlReset) {
            return null;
        }
        return UrlHelper::siteUrl('actions/views-work/reset/reset', ['key' => $this->getSettings()->urlResetSecret]);
    }

    public function addBlockParamToUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }
        if (!str_contains($url, '?')) {
            $url .= '?';
        }
        $url .= urlencode(BlockByQueryParamAddOn::$defaultParamName) . '=' . $this->getSettings()->blockByQueryParamSecret;
        return $url;
    }

    /**
     * Return true if the settings require secrets
     *
     * @return bool
     */
    public function settingsRequiresSecrets(): bool
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        return $settings->requiresSecrets();
    }

    public function getBlockStatus(): array
    {
        return [
            'blocked' => ViewsWork::$plugin->blockByCookieAddOn->isBlocked(
                Craft::$app->getRequest()
            )
        ];
    }

    public function getSettings(): Settings
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        return $settings;
    }
}
