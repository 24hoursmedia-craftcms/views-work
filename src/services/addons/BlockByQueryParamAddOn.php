<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 16/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\services\addons;

use Craft;
use craft\web\Request;
use twentyfourhoursmedia\viewswork\events\BlockElementViewRegistrationEvent;
use twentyfourhoursmedia\viewswork\services\ViewsWorkService;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\base\Event;

class BlockByQueryParamAddOn extends AbstractViewsWorkAddOn
{

    public static $defaultParamName = '_vw_block';

    protected $blockedParams = [

    ];

    public function add(string $name, string $value) : self
    {
        $this->blockedParams[$name] = $value;
        return $this;
    }

    public function setupListeners(): void
    {
        Event::on(
            ViewsWorkService::class,
            ViewsWorkService::EVENT_BLOCK_ELEMENT_VIEW_REGISTRATION,
            function (BlockElementViewRegistrationEvent $event) {
                $event->blocked = $event->blocked || $this->isBlocked(Craft::$app->getRequest());
            }
        );
        // Add the default param
        $this->add(self::$defaultParamName, (string)$this->getViewsWorkSettings()->blockByQueryParamSecret);
    }

    public function isBlocked(Request $request) : bool
    {
        foreach ($this->blockedParams as $name => $secret) {
            if ($request->getQueryParam($name) === $secret) {
                return true;
            }
        }
        return false;
    }


}