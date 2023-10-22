<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 23/02/2020
 */

namespace twentyfourhoursmedia\viewswork\services;

use craft\base\Element;
use craft\elements\db\EntryQuery;
use craft\helpers\DateTimeHelper;
use craft\helpers\Db;
use RuntimeException;
use twentyfourhoursmedia\viewswork\behaviors\IncrementableRecordingBehavior;
use twentyfourhoursmedia\viewswork\helper\SiteIdHelper;
use twentyfourhoursmedia\viewswork\models\ViewRecording;
use twentyfourhoursmedia\viewswork\ViewsWork;

/**
 * Class Facade
 * Available as front end
 *
 * @package twentyfourhoursmedia\viewswork\services
 */
class Facade
{


    public function getRecording(Element $element, $site = null)
    {
        $siteId = SiteIdHelper::determineSiteId($element, $site);
        $recording = ViewsWork::$plugin->viewsWorkService->getRecordingRecord($element, $siteId);
        $model = ViewRecording::createFromRecord($recording);
        $model->attachBehavior('incremental', IncrementableRecordingBehavior::create($element));
        return $model;
    }


    const SORT_POPULAR_OPTS = ['min_views' => 0];

    /**
     * @deprecated use entries.section().orderByPopular('total', 1).all instead
     *
     * @param EntryQuery $query
     * @param string $by
     * @param int[] $opts
     * @return EntryQuery
     */
    public function sortPopular(EntryQuery $query, $by = 'total', $opts = self::SORT_POPULAR_OPTS) : EntryQuery
    {
        $minViews = (int)$opts['min_views'];
        $query->orderByPopular($by, $minViews);
        return $query;
    }

    /**
     * Sort items by most recently viewed
     *
     * @deprecated use entries.section().orderByRecentlyViewed('-30 seconds').all instead
     * @param EntryQuery $query
     */
    public function sortRecent(EntryQuery $query, \DateTimeInterface $after) : EntryQuery
    {

        $query->orderByRecentlyViewed($after);
        return $query;
    }
}
