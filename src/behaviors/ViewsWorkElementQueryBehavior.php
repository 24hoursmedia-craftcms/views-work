<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 14/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\behaviors;

use craft\helpers\Db;
use yii\base\Behavior;
use craft\elements\db\ElementQuery;
use Craft;

class ViewsWorkElementQueryBehavior extends Behavior
{

    public const POPULAR_NONE = 'none';
    public const POPULAR_TOTAL = 'total';
    public const POPULAR_THIS_WEEK = 'thisWeek';
    public const POPULAR_THIS_MONTH = 'thisMonth';

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

    public function orderByRecentlyViewed($after) {
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
        if (!$this->orderByRecentlyViewed && $this->orderPopularFirst === self::POPULAR_NONE) {
            return true;
        }
        $query = $this->owner->subQuery;
        $query->leftJoin(
            '{{%viewswork_viewrecording}} AS _vr',
            'elements_sites.elementId=_vr.elementId AND elements_sites.siteId=_vr.siteId'
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
            $orderBy = $query->orderBy;
            $query->orderBy('_vr.' . $popularSortField . ' DESC');
            $query->addOrderBy($orderBy);
            // limit min views
            if ($this->minViews > 0) {
                $query->andWhere('_vr.' . $popularSortField . '>=' . $this->minViews);
            }
        }

        if ($this->orderByRecentlyViewed instanceof \DateTimeInterface) {
            $query->andWhere(Db::parseDateParam('_vr.dateUpdated', $this->orderByRecentlyViewed, '>='));
            $orderBy = $query->orderBy;
            $query->orderBy('_vr.dateUpdated DESC');
            $query->addOrderBy($orderBy);

        }

        return true;
    }
}
