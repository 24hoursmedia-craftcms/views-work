<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 14/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\behaviors;

use craft\elements\db\EntryQuery;
use craft\helpers\Db;
use yii\base\Behavior;
use craft\elements\db\ElementQuery;
use Craft;

class ViewsWorkElementQueryBehavior extends Behavior
{

    public const POPULAR_NONE = 'none';
    public const POPULAR_TOTAL = 'total';
    public const POPULAR_THIS_WEEK = 'week';
    public const POPULAR_THIS_MONTH = 'month';

    public static function getRecordingTableName() : string {
        return '___vr';
    }

    private $orderPopularFirst = self::POPULAR_NONE;
    private $minViews = 1;
    /**
     * @var \DateTimeInterface | null
     */
    private $orderByRecentlyViewed = null;


    public function events()
    {
        return [
            ElementQuery::EVENT_AFTER_PREPARE => 'afterPrepare',
        ];
    }

    public function orderByPopular(string $scope = self::POPULAR_TOTAL, int $minViews = 1)
    {
        $this->orderPopularFirst = $scope;
        $this->minViews = $minViews;
        return $this->owner;
    }

    public function orderByRecentlyViewed($after)
    {
        $criterium = null;
        if (is_string($after)) {
            $criterium = new \DateTime($criterium);
        }
        if ($after instanceof \DateTimeInterface) {
            $criterium = $after;
        }
        $this->orderByRecentlyViewed = $criterium;
        return $this->owner;
    }

    public function afterPrepare(): bool
    {
        $tblName = self::getRecordingTableName();

        $hasVrReference = false;
        $orderBys = is_array($this->owner->orderBy) ? $this->owner->orderBy : [$this->owner->orderBy];
        $keys = array_keys($orderBys);
        foreach ($keys as $key) {
            if (str_starts_with((string)$key, $tblName . '.')) {
                $hasVrReference = true;
            }
        }


        // exit;


        if (!$hasVrReference && !$this->orderByRecentlyViewed && $this->orderPopularFirst === self::POPULAR_NONE) {
            return true;
        }

        $query = $this->owner->query;
        $subQuery = $this->owner->subQuery;
        $query->leftJoin(
            '{{%viewswork_viewrecording}} AS ' . $tblName,
            '[[elements_sites.elementId]]=[[' . $tblName . '.elementId]] AND [[elements_sites.siteId]]=[[' . $tblName . '.siteId]]'
        );
        $subQuery->leftJoin(
            '{{%viewswork_viewrecording}} AS ' . $tblName,
            '[[elements_sites.elementId]]=[[' . $tblName . '.elementId]] AND [[elements_sites.siteId]]=[[' . $tblName . '.siteId]]'
        );


        // apply sorts
        static $popularSortFieldMap = [
            'total' => 'viewsTotal',
            'month' => 'viewsThisMonth',
            'week' => 'viewsThisWeek',
            'day' => 'viewsToday',
        ];
        $popularSortField = $popularSortFieldMap[$this->orderPopularFirst] ?? null;

        if ($popularSortField) {
            $useQuery = $subQuery;
            $otherQuery = $query;

            $orderBy = $useQuery->orderBy;
            $useQuery->orderBy($tblName . '.' . $popularSortField . ' DESC');
            $useQuery->addOrderBy($orderBy);
            // limit min views
            if ($this->minViews > 0) {
                $useQuery->andWhere('[[' . $tblName . '.' . $popularSortField . ']] >=' . $this->minViews);
            }
            $otherQuery->orderBy = null;
        }

        if ($this->orderByRecentlyViewed instanceof \DateTimeInterface) {
            $useQuery = $subQuery;
            $otherQuery = $query;

            $useQuery->andWhere(Db::parseDateParam($tblName . '.dateUpdated', $this->orderByRecentlyViewed, '>='));
            $orderBy = $useQuery->orderBy;
            $useQuery->orderBy($tblName . '.dateUpdated DESC');
            $useQuery->addOrderBy($orderBy);
            $otherQuery->orderBy = [];

        }

        return true;
    }
}
