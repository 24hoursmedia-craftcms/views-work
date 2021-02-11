<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 23/02/2020
 */

namespace twentyfourhoursmedia\viewswork\services;

use craft\base\Element;
use craft\elements\db\EntryQuery;
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
        return ViewRecording::createFromRecord($recording);
    }


    const SORT_POPULAR_OPTS = ['min_views' => 0];

    public function sortPopular(EntryQuery $query, $by = 'total', $opts = self::SORT_POPULAR_OPTS)
    {
        $opts+=self::SORT_POPULAR_OPTS;
        $query->leftJoin(
            '{{%viewswork_viewrecording}} AS _vr',
            'elements_sites.elementId=_vr.elementId AND elements_sites.siteId=_vr.siteId'
        );
        $sortFieldMap = [
            'total' => 'viewsTotal',
            'month' => 'viewsThisMonth',
            'week' => 'viewsThisWeek',
            'day' => 'viewsToday',
        ];
        $sortField = $sortFieldMap[$by] ?? null;
        if (!$sortField) {
            throw new \RuntimeException("Invalid sort field {$by} for popularity");
        }
        $orderBy = $query->orderBy;
        $query->orderBy('_vr.' . $sortField . ' DESC');
        $query->addOrderBy($orderBy);
        $minViews = (int)$opts['min_views'];
        if ($minViews > 0) {
            $query->andWhere('_vr.' . $sortField . '>=' . (int)$opts['min_views']);
        }
        return $query;
    }
}
