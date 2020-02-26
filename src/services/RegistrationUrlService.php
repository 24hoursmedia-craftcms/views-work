<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 26/02/2020
 */

namespace twentyfourhoursmedia\viewswork\services;

use craft\base\Element;

use craft\base\ElementInterface;
use craft\elements\Entry;
use craft\helpers\DateTimeHelper;
use craft\helpers\UrlHelper;
use twentyfourhoursmedia\viewswork\helper\SiteIdHelper;
use twentyfourhoursmedia\viewswork\records\ViewRecording;
use twentyfourhoursmedia\viewswork\ViewsWork;

use Craft;
use craft\base\Component;
use yii\web\BadRequestHttpException;

class RegistrationUrlService extends Component
{

    const VIEWSWORK_DEFAULT_OPTS = ['factor' => 1];

    const SIGNATURE_FIELD = 'signature';
    const SIGNED_PARAMS_FIELD = '_signedVal';

    protected function getSigningKey() : string {
        return (string)ViewsWork::$plugin->settings->signKey;
    }

    public function createImageUrl(ElementInterface $entry, $opts = []): string
    {
        $opts+= self::VIEWSWORK_DEFAULT_OPTS;
        $id = $entry->id;
        $parms = [
            'id' => $id,
            'f' => (float)$opts['factor'],
            'sid' => SiteIdHelper::determineSiteId($entry)
        ];
        $signedVal = json_encode($parms);


        $query = [
            self::SIGNATURE_FIELD => sha1($this->getSigningKey() . $signedVal),
            self::SIGNED_PARAMS_FIELD => $signedVal
        ];
        return UrlHelper::actionUrl('views-work/register-view/beacon-image', $query);
    }


    public function verifySignedParams(array $queryParams) : array
    {
        $signature = $queryParams[self::SIGNATURE_FIELD];
        $expectedSignature = sha1($this->getSigningKey() . $queryParams[self::SIGNED_PARAMS_FIELD]);
        if ($expectedSignature !== $signature) {
            throw new BadRequestHttpException('Invalid signature');
        }
        return json_decode($queryParams[self::SIGNED_PARAMS_FIELD] ?? '', true);
    }


}