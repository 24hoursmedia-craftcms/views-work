<?php
/**
 * created by ward
 * since 2.1.4
*/

namespace twentyfourhoursmedia\viewswork\helper;

use Craft;
use craft\services\Entries;

class VersionHelper {
    /**
     * This function is used to get all sections or entries based on the Craft CMS version.
     * If the version is greater than 5.0.0-alpha, it will return all entries, otherwise, it will return all sections.
     *
     * @return string The result of calling the method 'getAllSections' on either the 'entries' or 'sections' service of Craft CMS.
     */
    public static function getAllSectionsHelper(): array {
        $craft = Craft::$app;
        $method = version_compare($craft->getVersion(), '5.0.0-alpha', '>') ? $craft->entries : $craft->sections;
        return $method->getAllSections();
    }
}