<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * plugin for handling pageviews
 *
 * @link      http://www.24hoursmedia.com
 * @copyright Copyright (c) 2018 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork\assetbundles\viewsworkwidgetwidget;

use Craft;
use craft\helpers\App;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;
use twentyfourhoursmedia\viewswork\assetbundles\viewswork\ViewsWorkAsset;
use twentyfourhoursmedia\viewswork\widgets\ViewsWorkWidget;

/**
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 */
class ViewsWorkWidgetWidgetAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = ViewsWorkAsset::getSourcePath();

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
           'viewswork.js',
        ];

        $this->css = [
            'viewswork.css',
        ];

        parent::init();
    }
}
