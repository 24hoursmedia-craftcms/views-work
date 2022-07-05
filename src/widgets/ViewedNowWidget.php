<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 13/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\widgets;

use craft\elements\Entry;

use craft\helpers\DateTimeHelper;
use twentyfourhoursmedia\viewswork\ViewsWork;
use twentyfourhoursmedia\viewswork\assetbundles\viewsworkwidgetwidget\ViewsWorkWidgetWidgetAsset;

use Craft;
use craft\base\Widget;

class ViewedNowWidget extends Widget
{

    public $seconds = 30;
    public $count = 5;
    public $widgetTitle = '';
    public $enableAutoRefresh = true;

    public $allSites = true;

    /**
     * @var int|null The site ID that the widget should pull entries from
     */
    public $siteId;

    public static function displayName(): string
    {
        return 'Viewed now';
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
    public static function maxColspan(): ?int
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        if ($this->siteId === null) {
            $this->siteId = Craft::$app->getSites()->getCurrentSite()->id;
        }
    }

    public function getTitle(): string
    {
        return '' !== trim($this->widgetTitle) ? $this->widgetTitle : self::displayName();
    }

    /**
     * @inheritdoc
     */
    public function rules(): array
    {
        $rules = parent::rules();
        $rules = array_merge(
            $rules,
            [
                ['widgetTitle', 'string'],
                ['seconds', 'integer', 'integerOnly' => true],
                ['seconds', 'default', 'value' => 30],
                ['count', 'integer', 'integerOnly' => true],
                ['count', 'default', 'value' => 5],
                ['enableAutoRefresh', 'boolean'],
                ['allSites', 'boolean'],
                ['siteId', 'number', 'integerOnly' => true]
            ]
        );
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml(): ?string
    {
        return Craft::$app->getView()->renderTemplate(
            'views-work/_components/widgets/ViewedNowWidget_settings',
            [
                'widget' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getBodyHtml(): ?string
    {
        Craft::$app->getView()->registerAssetBundle(ViewsWorkWidgetWidgetAsset::class);

        $viewModel = [
            'seconds' => $this->seconds,
            'count' => $this->count,
            'enableAutoRefresh' => $this->enableAutoRefresh,
            'siteId' => $this->siteId,
            'allSites' => $this->allSites
        ];

        $html = Craft::$app->getView()->renderTemplate(
            'views-work/_components/widgets/ViewedNowWidget_body',
            $viewModel
        );

        return $html;
    }
}
