<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 26/02/2020
 */

namespace twentyfourhoursmedia\viewswork\models;
use twentyfourhoursmedia\viewswork\records\ViewRecording as ViewRecordingRecord;

class ViewRecording
{

    public $total = 0;

    public $thisMonth = 0;

    public $thisWeek = 0;

    public $today = 0;

    public static function createFromRecord(ViewRecordingRecord $recording) : self
    {
        $me = new self();
        $me->today = (int)$recording->getAttribute('viewsToday');
        $me->total = (int)$recording->getAttribute('viewsTotal');
        $me->thisMonth = (int)$recording->getAttribute('viewsThisMonth');
        $me->thisWeek = (int)$recording->getAttribute('viewsThisWeek');
        return $me;
    }
}