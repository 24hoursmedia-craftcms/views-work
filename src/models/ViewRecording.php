<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 26/02/2020
 */

namespace twentyfourhoursmedia\viewswork\models;

use twentyfourhoursmedia\viewswork\records\ViewRecording as ViewRecordingRecord;
use yii\base\Model;

class ViewRecording extends Model
{

    public $total = 0;

    public $thisMonth = 0;

    public $thisWeek = 0;

    public $today = 0;

    public static function createFromRecord(ViewRecordingRecord $recording) : self
    {
        return (new self())->populateFromRecord($recording);
    }

    public function populateFromRecord(ViewRecordingRecord $recording) : self
    {
        $this->today = (int)$recording->getAttribute('viewsToday');
        $this->total = (int)$recording->getAttribute('viewsTotal');
        $this->thisMonth = (int)$recording->getAttribute('viewsThisMonth');
        $this->thisWeek = (int)$recording->getAttribute('viewsThisWeek');
        return $this;
    }
}
