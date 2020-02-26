<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * plugin for handling pageviews
 *
 * @link      http://www.24hoursmedia.com
 * @copyright Copyright (c) 2018 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork\widgets;

use craft\elements\Entry;
use twentyfourhoursmedia\viewswork\ViewsWork;
use twentyfourhoursmedia\viewswork\assetbundles\viewsworkwidgetwidget\ViewsWorkWidgetWidgetAsset;

use Craft;
use craft\base\Widget;

/**
 * Views Work Widget
 *
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 */
class ViewsWorkWidget extends Widget
{

    // Public Properties
    // =========================================================================

    /**
     * @var int
     */
    public $count = 5;

    public $showTotal = true;
    public $showMonthly = true;
    public $showWeekly = true;
    public $showDaily = true;

    /**
     * @var string|int[] The section IDs that the widget should pull entries from
     */
    public $section = '*';

    // Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('views-work', 'Popular content');
    }

    /**
     * @inheritdoc
     */
    public static function iconPath()
    {
        return Craft::getAlias("@twentyfourhoursmedia/viewswork/assetbundles/viewsworkwidgetwidget/dist/img/ViewsWorkWidget-icon.svg");
    }

    /**
     * @inheritdoc
     */
    public static function maxColspan()
    {
        return null;
    }

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge(
            $rules,
            [
                ['count', 'integer'],
                ['count', 'default', 'value' => 5],
                ['showTotal', 'boolean'],
                ['showMonthly', 'boolean'],
                ['showWeekly', 'boolean'],
                ['showDaily', 'boolean'],
            ]
        );
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'views-work/_components/widgets/ViewsWorkWidget_settings',
            [
                'widget' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml()
    {
        Craft::$app->getView()->registerAssetBundle(ViewsWorkWidgetWidgetAsset::class);

        $facade = ViewsWork::$plugin->viewsWork;

        $total = null;
        $monthly = null;
        $weekly = null;
        $daily = null;

        $section = $this->section;


        if ($this->showTotal) {
            $total = $facade->sortPopular(Entry::find()->site('*')->limit($this->count), 'total', ['min_views' => 1]);
            is_numeric($section) ? $total->sectionId($section) : $total->section($section);
        }
        if ($this->showMonthly) {
            $monthly = $facade->sortPopular(Entry::find()->site('*')->limit($this->count), 'month', ['min_views' => 1]);
            is_numeric($section) ? $monthly->sectionId($section) : $monthly->section($section);
        }
        if ($this->showWeekly) {
            $weekly = $facade->sortPopular(Entry::find()->site('*')->limit($this->count), 'week', ['min_views' => 1]);
            is_numeric($section) ? $weekly->sectionId($section) : $weekly->section($section);
        }

        if ($this->showDaily) {
            $daily = $facade->sortPopular(Entry::find()->site('*')->limit($this->count), 'day', ['min_views' => 1]);
            is_numeric($section) ? $daily->sectionId($section) : $daily->section($section);
        }


        return Craft::$app->getView()->renderTemplate(
            'views-work/_components/widgets/ViewsWorkWidget_body',
            [
                'count' => $this->count,
                'showTotal' => $this->showTotal,
                'showMonthly' => $this->showMonthly,
                'showWeekly' => $this->showWeekly,
                'showDaily' => $this->showDaily,

                'total' => $total ? $total->all() : null,
                'monthly' => $monthly ? $monthly->all() : null,
                'weekly' => $weekly ? $weekly->all() : null,
                'today' => $daily ? $daily->all() : null,

                'section' => is_numeric($section) ? Craft::$app->sections->getSectionById($section) : null

            ]
        );
    }
}
