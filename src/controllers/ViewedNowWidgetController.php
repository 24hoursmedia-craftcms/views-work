<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 14/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\controllers;

use Craft;
use craft\elements\Entry;
use craft\helpers\DateTimeHelper;
use craft\web\Controller;
use twentyfourhoursmedia\viewswork\assetbundles\viewsworkwidgetwidget\ViewsWorkWidgetWidgetAsset;
use twentyfourhoursmedia\viewswork\ViewsWork;

class ViewedNowWidgetController extends Controller
{
    protected $allowAnonymous = self::ALLOW_ANONYMOUS_NEVER;

    public function actionContent()
    {
        Craft::$app->getView()->registerAssetBundle(ViewsWorkWidgetWidgetAsset::class);
        $r = $this->request;
        $seconds = (int)$r->getQueryParam('seconds', 30);
        $count = (int)$r->getQueryParam('count', 5);
        $facade = ViewsWork::$plugin->viewsWork;


        $after = DateTimeHelper::currentUTCDateTime();
        $after->modify('-' . (string)$seconds . ' seconds');

        $query = Entry::find()->site('*')->orderByRecentlyViewed($after);
        $query->limit($count);
        $total = $query->count();

        $entries = $query->all();

        $viewModel = [
            'total' => $total,
            'items' => $entries,
            'after' => $after
        ];

        return $this->renderTemplate(
            'views-work/_components/widgets/reactive/viewed_now_widget_content',
            $viewModel
        );
    }
}
