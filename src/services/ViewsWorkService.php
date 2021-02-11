<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * plugin for handling pageviews
 *
 * @link      http://www.24hoursmedia.com
 * @copyright Copyright (c) 2018 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork\services;

use Craft;
use craft\base\Component;
use craft\base\Element;
use craft\helpers\DateTimeHelper;
use twentyfourhoursmedia\viewswork\events\BlockElementViewRegistrationEvent;
use twentyfourhoursmedia\viewswork\helper\SiteIdHelper;
use twentyfourhoursmedia\viewswork\records\ViewRecording;

/**
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 */
class ViewsWorkService extends Component
{

    public const EVENT_BLOCK_ELEMENT_VIEW_REGISTRATION = 'blockElementViewRegistration';

    // Public Methods
    // =========================================================================


    private function getIncrement(float $factor): int
    {
        $inc = (int)floor($factor);
        $frac = (int)(fmod($factor, 1) * 100);
        if (random_int(1, 100) <= $frac) {
            $inc++;
        }
        return $inc;
    }

    /**
     * Get or create a recording record
     * @param Element $element
     * @param null $site
     * @return array|ViewRecording|\yii\db\ActiveRecord|null
     * @throws \craft\errors\SiteNotFoundException
     */
    public function getRecordingRecord(Element $element, $site = null)
    {
        $siteId = SiteIdHelper::determineSiteId($element, $site);
        $record = ViewRecording::find()
            ->andWhere('elementId=:elementId')
            ->andWhere('siteId=:siteId')
            ->addParams(['elementId' => (int)$element->id, 'siteId' => $siteId])
            ->one();
        if (!$record) {
            $record = new ViewRecording();
            $record->setAttribute('elementId', (int)$element->id);
            $record->setAttribute('siteId', $siteId);
        }
        return $record;
    }

    public function recordView(Element $element, $site = null, float $factor = 1)
    {
        $event = new BlockElementViewRegistrationEvent();
        $event->element = $element;
        $event->site = $site;
        $this->trigger(self::EVENT_BLOCK_ELEMENT_VIEW_REGISTRATION, $event);
        if ($event->blocked) {
            return;
        }

        $siteId = SiteIdHelper::determineSiteId($element, $site);
        $transaction = Craft::$app->getDb()->beginTransaction();
        $recording = $this->getRecordingRecord($element, $siteId);
        $inc = $this->getIncrement($factor);
        $recording->setAttribute('viewsTotal', $recording->getAttribute('viewsTotal') + $inc);
        $recording->setAttribute('viewsToday', $recording->getAttribute('viewsToday') + $inc);
        $recording->setAttribute('viewsThisMonth', $recording->getAttribute('viewsThisMonth') + $inc);
        $recording->setAttribute('viewsThisWeek', $recording->getAttribute('viewsThisWeek') + $inc);
        $recording->save();
        $transaction->commit();
    }


    const SCOPE_DAILY = 'daily';
    const SCOPE_MONTHLY = 'monthly';
    const SCOPE_WEEKLY = 'weekly';
    const SCOPES_ALL = [self::SCOPE_DAILY, self::SCOPE_WEEKLY, self::SCOPE_MONTHLY];
    const RESET_VIEWS_OPTS = ['force' => false];

    /**
     * @param array $scopes = self::SCOPES_ALL
     * @param array $options = self::RESET_VIEWS_OPTS
     * @return array
     * @throws \Exception
     */
    public function resetViews(array $scopes = self::SCOPES_ALL, array $options = self::RESET_VIEWS_OPTS): array
    {
        $options += self::RESET_VIEWS_OPTS;
        $force = $options['force'];
        $resetted = [];
        foreach ($scopes as $scope) {
            switch ($scope) {
                case self::SCOPE_DAILY:
                    if ($force || $this->canResetDailyViews()) {
                        ViewRecording::updateAll(['viewsToday' => 0]);
                        $resetted[] = $scope;
                    }
                    break;
                case self::SCOPE_WEEKLY:
                    if ($force || $this->canResetWeeklyViews()) {
                        ViewRecording::updateAll(['viewsThisWeek' => 0]);
                        $resetted[] = $scope;
                    }
                    break;
                case self::SCOPE_MONTHLY:
                    if ($force || $this->canResetMonthlyViews()) {
                        ViewRecording::updateAll(['viewsThisMonth' => 0]);
                        $resetted[] = $scope;
                    }
                    break;
            }
        }
        return $resetted;
    }

    /**
     * Check if daily views can reset
     * @return bool
     * @throws \Exception
     */
    public function canResetDailyViews(): bool
    {
        return true;
    }

    /**
     * Check if daily views can reset
     * @return bool
     * @throws \Exception
     */
    public function canResetMonthlyViews(): bool
    {
        $date = new \DateTime('first day of this month 00:00');
        return DateTimeHelper::isToday($date);
    }

    /**
     * Check if daily views can reset
     * @return bool
     * @throws \Exception
     */
    public function canResetWeeklyViews(): bool
    {
        $date = (new \DateTime())->setTimestamp(strtotime('this week'));
        return DateTimeHelper::isToday($date);
    }
}
