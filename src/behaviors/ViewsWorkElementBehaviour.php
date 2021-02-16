<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 14/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\behaviors;

use craft\elements\Entry;
use craft\helpers\Db;
use twentyfourhoursmedia\viewswork\models\ViewRecording;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\base\Behavior;
use craft\elements\db\ElementQuery;
use Craft;

/**
 * Class ViewsWorkElementBehaviour
 * @package twentyfourhoursmedia\viewswork\behaviors
 */
class ViewsWorkElementBehaviour extends Behavior
{

    private $lastRecording = null;

    /**
     * Get the recording directly on the element
     *
     * @return ViewRecording
     */
    public function getViewsWork() : ViewRecording
    {
        if ($this->lastRecording) {
            return $this->lastRecording;
        }
        $el = $this->owner;
        /* @var $el \craft\base\ElementInterface */
        $this->lastRecording = ViewsWork::$plugin->viewsWork->getRecording($el, $el->siteId);
        return $this->lastRecording;
    }

}
