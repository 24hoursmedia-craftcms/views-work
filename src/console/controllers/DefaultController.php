<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 23/02/2020
 */
namespace twentyfourhoursmedia\viewswork\console\controllers;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\console\Controller;
use yii\helpers\Console;


class DefaultController extends Controller
{
    /**
     * Resets the daily and monthly view counters
     * Activate with cron job somewhere nightly
     *
     * ./craft views-work/default/reset-views
     */
    public function actionResetViews()
    {
        echo 'Resetting views' . PHP_EOL;

        $resetted = ViewsWork::$plugin->viewsWorkService->resetViews();
        echo 'Resetted: ' . implode(', ', $resetted) . PHP_EOL;

    }
}


