<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 10/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\controllers;

use Craft;
use craft\web\Controller;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\web\Response;

class ResetController extends Controller
{

    public $enableCsrfValidation = false;
    protected $allowAnonymous = ['reset'];

    public function actionReset(): Response
    {
        $this->requirePostRequest();

        $settings = ViewsWork::$plugin->getSettings();
        /* @var Settings $settings */
        if (!filter_var($settings->allowUrlReset, FILTER_VALIDATE_BOOLEAN)) {
            throw new AccessDeniedException('Not enabled');
        };
        $key = Craft::$app->request->getQueryParam('key');
        if ($key !== $settings->urlResetSecret) {
            throw new AccessDeniedException('Invalid key');
        }
        $resetted = ViewsWork::$plugin->viewsWorkService->resetViews();
        if ($resetted) {
            $response = new Response();
            $response->statusCode = 200;
            $response->content = 'Views work says: views resetted successfully';
            return $response;
        }
        $response = new Response();
        $response->statusCode = 500;
        $response->content = 'Views work says: ERROR resetting views. Please retry.';
        return $response;

    }

}