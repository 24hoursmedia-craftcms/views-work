<?php
/**
 * Date: 20/03/2018
 */

namespace twentyfourhoursmedia\viewswork\controllers;

use Craft;
use craft\web\Controller;
use twentyfourhoursmedia\viewswork\ViewsWork;
use yii\web\Response;

class RegisterViewController extends Controller
{

    const PIXEL = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==';

    protected  array|bool|int $allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

    public function actionBeaconImage(): Response
    {

        $request = Craft::$app->getRequest();

        // get params and check signature
        $params = ViewsWork::$plugin->registrationUrlService->verifySignedParams($request->getQueryParams());

        $element = Craft::$app->elements->getElementById($params['id'], null, '*');
        $service = ViewsWork::$plugin->viewsWorkService;
        $success = $service->recordView($element, $params['sid'], (float)$params['f']);

        $response = Craft::$app->getResponse();
        $response->headers->set('X-VW-Blocked', $success ? 'false' : 'true');
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        $response->format = $response::FORMAT_RAW;
        $response->headers->set('Content-Type', 'image/png');
        $response->content = base64_decode(self::PIXEL);
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', strtotime('-10 year')));
        return $response;
    }
}
