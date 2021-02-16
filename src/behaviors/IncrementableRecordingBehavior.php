<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 16/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\behaviors;

use craft\base\ElementInterface;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\base\Behavior;

class IncrementableRecordingBehavior extends Behavior
{

    public $element;
    public $siteId;

    const DEFAULT_INC_OPTIONS = [
      'factor' => 1
    ];

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public static function create(ElementInterface $element) : self
    {
        $m = new self();
        $m->element = $element;
        $m->siteId = $element->siteId;
        return $m;
    }

    public function increment($options = self::DEFAULT_INC_OPTIONS)
    {
        $options+= self::DEFAULT_INC_OPTIONS;
        $service = ViewsWork::$plugin->viewsWorkService;
        $service->recordView($this->element, $this->siteId, (float)$options['factor']);
        $record = $service->getRecordingRecord($this->element, $this->siteId);
        $owner = $this->owner;
        /* @var $owner \twentyfourhoursmedia\viewswork\models\ViewRecording */
        $owner->populateFromRecord($record);
    }
}
