<?php
/**
 * Created by PhpStorm
 * User: eapbachman
 * Date: 26/02/2020
 */

namespace twentyfourhoursmedia\viewswork\helper;

use craft\base\ElementInterface;
use craft\elements\Entry;
use craft\models\Site;

/**
 * Class SiteIdHelper
 */
class SiteIdHelper
{

    /**
     * Determines site id. Uses indicated site id, then entry's site id or falls back to current site id.
     *
     * @param ElementInterface $element
     * @param null $site
     * @return int|null
     * @throws \craft\errors\SiteNotFoundException
     */
    public static function determineSiteId(ElementInterface $element, $site = null)
    {
        if ($site instanceof Site) {
            return $site->id;
        }
        if (is_numeric($site)) {
            return (int)$site;
        }
        if ($element instanceof Entry) {
            return $element->siteId;
        }
        return \Craft::$app->sites->getCurrentSite()->id;
    }

}