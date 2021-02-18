<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 11/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\services\addons;

use Craft;
use craft\helpers\UrlHelper;
use craft\web\Request;
use craft\web\Response;
use twentyfourhoursmedia\viewswork\events\BlockElementViewRegistrationEvent;
use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\base\Event;
use twentyfourhoursmedia\viewswork\services\ViewsWorkService;
use yii\web\Cookie;

class BlockByCookieAddOn extends AbstractViewsWorkAddOn
{

    private $cookieName = 'vw_block_token';

    public function setupListeners() : void
    {
        Event::on(
            ViewsWorkService::class,
            ViewsWorkService::EVENT_BLOCK_ELEMENT_VIEW_REGISTRATION,
            function (BlockElementViewRegistrationEvent $event) {
                $event->blocked = $event->blocked || $this->isBlocked(Craft::$app->getRequest());
            }
        );
    }

    /**
     * @param $requestOrResponse
     * @return array = ['expire_str' => 'test]
     */
    public function describeCookie($requestOrResponse)
    {
        /* @var Response $requestOrResponse */
        $cookieVal = $requestOrResponse->getCookies()->getValue($this->cookieName, null);
        if (!$cookieVal || !is_array($cookieVal)) {
            return null;
        }
        $expire = $cookieVal['expire'] ?? 0;

        $ttlStr = $expire > 0 ? self::secondsToTime($expire - time()) : 'the duration of session';

        return [
          'expire_str' => $ttlStr
        ];
    }

    public function isBlocked(Request $request): bool
    {
        $cookieVal = $request->getCookies()->getValue($this->cookieName, '');
        if (!$cookieVal || !is_array($cookieVal)) {
            return false;
        }
        $settings = ViewsWork::$plugin->getSettings();
        $key = $cookieVal['key'] ?? null;
        /* @var Settings $settings */
        return $key === $settings->blockByCookieSecret;
    }


    public function block(string $key)
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        if ($key !== $settings->blockByCookieSecret) {
            return false;
        }
        $expire = (new \DateTime('+3 years'))->getTimestamp();
        Craft::$app->getResponse()->getCookies()->remove($this->cookieName);
        Craft::$app->getResponse()->getCookies()->add(
            new Cookie([
                'name' => $this->cookieName,
                'value' => ['key' => $key, 'expire' => $expire],
                'expire' => $expire,

            ])
        );
        return true;
    }

    public function unBlock(string $key)
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        if ($key !== $settings->blockByCookieSecret) {
            return false;
        }
        Craft::$app->getResponse()->getCookies()->remove($this->cookieName);
        return true;
    }

    public function getStatusUrl(?int $siteId = null) : string
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        return UrlHelper::siteUrl('actions/views-work/block-by-cookie/status', null, null, $siteId);
    }


    public function getBlockUrl(?int $siteId = null) : string
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        return UrlHelper::siteUrl(
            'actions/views-work/block-by-cookie/block',
            ['key' => $settings->blockByCookieSecret],
            null,
            $siteId
        );
    }

    public function getUnblockUrl(?int $siteId = null) : string
    {
        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        return UrlHelper::siteUrl(
            'actions/views-work/block-by-cookie/unblock',
            ['key' => $settings->blockByCookieSecret],
            null,
            $siteId
        );
    }

    private static function secondsToTime($seconds)
    {
        $dtF = new \DateTime('@0');
        $dtT = new \DateTime("@$seconds");
        return $dtF->diff($dtT)->format('%a days, %h hours, %i minutes and %s seconds');
    }
}
