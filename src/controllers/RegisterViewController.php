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

    protected $allowAnonymous = ['beacon-image'];

    public function actionBeaconImage(): Response
    {

        $request = Craft::$app->getRequest();

        // get params and check signature
        $parms = ViewsWork::$plugin->registrationUrlService->verifySignedParams($request->getQueryParams());


        $element = Craft::$app->elements->getElementById($parms['id']);
        $service = ViewsWork::$plugin->viewsWorkService;
        $service->recordView($element, $parms['sid'], (float)$parms['f']);



        $response = new Response();
        $response->format = $response::FORMAT_RAW;
        $response->headers->set('Content-Type', 'image/png');
        $response->content = base64_decode(self::PIXEL);
        $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', strtotime('-10 year')));
        return $response;
    }

}