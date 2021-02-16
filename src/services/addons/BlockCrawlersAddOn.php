<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 12/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\services\addons;

use Craft;
use craft\helpers\UrlHelper;
use craft\web\Request;
use craft\web\Response;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use twentyfourhoursmedia\viewswork\events\BlockElementViewRegistrationEvent;
use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\base\Event;
use twentyfourhoursmedia\viewswork\services\ViewsWorkService;
use yii\web\Cookie;

class BlockCrawlersAddOn extends AbstractViewsWorkAddOn
{

    /**
     * @var CrawlerDetect | null
     */
    private $detector;

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

    private function isBlocked(Request $request): bool
    {
        !$this->detector && $this->detector = new CrawlerDetect();
        // see: https://github.com/JayBizzle/Crawler-Detect
        return $this->detector->isCrawler($request->getUserAgent());
    }

}