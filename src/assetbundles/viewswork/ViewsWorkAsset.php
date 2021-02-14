<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * plugin for handling pageviews
 *
 * @link      http://www.24hoursmedia.com
 * @copyright Copyright (c) 2018 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork\assetbundles\viewswork;

use Craft;
use craft\helpers\App;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 */
class ViewsWorkAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    public static function getSourcePath() : string {
        $isDev = App::env('ENVIRONMENT') === 'dev' || App::env('ENVIRONMENT') === 'development';
        return $isDev ?
            '@twentyfourhoursmedia/viewswork/assetbundles/viewswork/build-dev' :
            '@twentyfourhoursmedia/viewswork/assetbundles/viewswork/build';

    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = self::getSourcePath();

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'runtime.js',
            'viewswork.js',
        ];

        $this->css = [
            'viewswork.css'
        ];

        parent::init();
    }
}
