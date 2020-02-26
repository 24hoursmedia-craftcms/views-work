<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * plugin for handling pageviews
 *
 * @link      http://www.24hoursmedia.com
 * @copyright Copyright (c) 2018 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork\twigextensions;

use craft\base\Element;
use craft\elements\Entry;
use craft\helpers\UrlHelper;
use twentyfourhoursmedia\viewswork\ViewsWork;

use Craft;

/**
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 */
class ViewsWorkTwigExtension extends \Twig_Extension
{
    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'ViewsWork';
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('views_work_image', [$this, 'viewsWorkImage'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
        ];
    }

    const VIEWSWORK_DEFAULT_OPTS = ['factor' => 1];

    public function viewsWorkImage(Element $entry, $opts = []): string {
        $url = ViewsWork::$plugin->registrationUrlService->createImageUrl($entry, $opts);
        return '<img src="' . htmlspecialchars($url) . '" alt="" width="1" height="1"/>';
    }
}
