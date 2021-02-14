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
    public static function maxColspan()
    {
        return null;
    }

    public function getTitle() : string
    {
        return '' !== trim($this->widgetTitle) ? $this->widgetTitle : self::displayName();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules = array_merge(
            $rules,
            [
                ['widgetTitle', 'string'],
                ['seconds', 'integer'],
                ['seconds', 'default', 'value' => 30],
                ['count', 'integer'],
                ['count', 'default', 'value' => 5],
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
            'views-work/_components/widgets/ViewedNowWidget_settings',
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

        $viewModel = [
            'seconds' => $this->seconds,
            'count' => $this->count
        ];

        $html = Craft::$app->getView()->renderTemplate(
            'views-work/_components/widgets/ViewedNowWidget_body',
            $viewModel
        );

        return $html;
    }
}
