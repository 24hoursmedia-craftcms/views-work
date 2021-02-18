<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 11/02/2021
 */
declare(strict_types=1);

namespace twentyfourhoursmedia\viewswork\controllers;

use craft\web\Controller;
use craft\web\Response;
use twentyfourhoursmedia\viewswork\ViewsWork;

class BlockByCookieController extends Controller
{

    public $enableCsrfValidation = false;
    protected $allowAnonymous = self::ALLOW_ANONYMOUS_LIVE;

    public function actionStatus(): Response
    {
        $addon = ViewsWork::$plugin->blockByCookieAddOn;
        $request = \Craft::$app->getRequest();
        $response = \Craft::$app->getResponse();
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        $response->format = $response::FORMAT_RAW;
        $response->headers->set('Content-Type', 'text/plain');
        $response->statusCode = 200;
        $content = $this->getStatusMessage($request);
        $desc = $addon->describeCookie($request);
        if ($desc) {
            $content .= PHP_EOL . 'TTL: ' . $desc['expire_str'];
        }

        $hostPfx = 'For host: ' . \Craft::$app->request->hostName;

        $response->content = $hostPfx . ' - ' . $content;
        return $response;
    }

    /**
     * Allows to block view recordings by a user by visiting
     * /actions/views-work/block-by-cookie/block
     *
     * @param string $key
     * @return Response
     */
    public function actionBlock(string $key): Response
    {
        $success = ViewsWork::$plugin->blockByCookieAddOn->block($key);
        $response = \Craft::$app->getResponse();
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        $response->format = $response::FORMAT_RAW;
        $response->headers->set('Content-Type', 'text/plain');

        $hostPfx = 'For host: ' . \Craft::$app->request->hostName;

        $response->content = $success ? $hostPfx. ' -  View registrations are now blocked.' : $hostPfx. ' -  Warning - some error occurred. Did you supply the right key?';
        return $response;
    }

    /**
     * Allows to block view recordings by a user by visiting
     * /actions/views-work/block-by-cookie/block
     *
     * @param string $key
     * @return Response
     */
    public function actionUnblock(string $key): Response
    {

        $success = ViewsWork::$plugin->blockByCookieAddOn->unBlock($key);
        $response = \Craft::$app->getResponse();
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        $response->format = $response::FORMAT_RAW;
        $response->headers->set('Content-Type', 'text/plain');

        $hostPfx = 'For host: ' . \Craft::$app->request->hostName;

        $response->content = $success ? $hostPfx. ' -  Views will now be registered.' : $hostPfx. ' -  Warning - some error occurred. Did you supply the right key?';
        return $response;
    }

    private function getStatusMessage($requestOrResponse): string
    {
        $addon = ViewsWork::$plugin->blockByCookieAddOn;
        return $addon->isBlocked(\Craft::$app->getRequest()) ?
            'View registrations are blocked by this browser.'  :
            'Views are registered by this browser.';
    }
}