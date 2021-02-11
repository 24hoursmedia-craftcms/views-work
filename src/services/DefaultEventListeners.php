<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 11/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\services;

use Craft;
use craft\elements\db\ElementQuery;
use craft\elements\db\EntryQuery;
use craft\elements\Entry;
use craft\base\Element;
use craft\models\Site;
use twentyfourhoursmedia\viewswork\events\BlockElementViewRegistrationEvent;
use twentyfourhoursmedia\viewswork\helper\SiteIdHelper;
use twentyfourhoursmedia\viewswork\models\ViewRecording;
use twentyfourhoursmedia\viewswork\ViewsWork;
use craft\base\Component;
use yii\base\Event;

/**
 * Class DefaultEventListeners
 *
 * A collection of default event listeners
 *
 * @package twentyfourhoursmedia\viewswork\services
 */
class DefaultEventListeners extends Component
{

    public function setupListeners()
    {
        $this->setupCookieBlockListener();
    }


}
