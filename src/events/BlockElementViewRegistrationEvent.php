<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 11/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\events;

use craft\base\ElementInterface;
use craft\models\Site;
use yii\base\Event;

/**
 * Class RegisterElementViewEvent
 *
 * Dispatched before an element view is registered.
 * Allows to block the registration of a new view.
 *
 * @package craft\events
 */
class BlockElementViewRegistrationEvent extends Event
{

    /**
     * @var ElementInterface
     */
    public $element;

    /**
     * @var Site
     */
    public $site;

    /**
     * @var bool
     */
    public $blocked = false;
}
