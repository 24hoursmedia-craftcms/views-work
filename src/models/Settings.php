<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * plugin for handling pageviews
 *
 * @link      http://www.24hoursmedia.com
 * @copyright Copyright (c) 2018 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork\models;

use twentyfourhoursmedia\viewswork\ViewsWork;

use Craft;
use craft\base\Model;

/**
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $signKey = '';

    /**
     * Allow resetting daily, weekly views etc with an url
     * @var bool
     */
    public $allowUrlReset = false;

    /**
     * Allow url reset GET method
     * @var bool
     */
    public $allowUrlResetGET = false;

    /**
     * Secret to append to the url reset url
     * @var string
     */
    public $urlResetSecret = '';

    /**
     * Value for a cookie to block views for certain users
     * used in urls to block/unblock view recordings for persons
     *
     * @var string
     */
    public $blockByCookieSecret = '';


    // Private properties
    private $secretsFields = ['urlResetSecret', 'blockByCookieSecret', 'signKey'];

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['signKey', 'string'],
            ['allowUrlReset', 'boolean'],
            ['allowUrlResetGET', 'boolean'],
            ['urlResetSecret', 'string'],
        ];
    }

    /**
     * Check if the settings require some secrets to be defined
     *
     * @return bool
     */
    public function requiresSecrets() : bool
    {
        foreach ($this->getAttributes(['urlResetSecret', 'blockByCookieSecret', 'signKey']) as $k => $v) {
            if (trim($v) === '') {
                return true;
            }
        }
        return false;
    }

    public function populateMissingSecrets()
    {

        foreach ($this->getAttributes(['urlResetSecret', 'blockByCookieSecret', 'signKey']) as $k => $v) {
            if (trim($v) === '') {
                $this->{$k} = Craft::$app->security->generateRandomString();
            }
        }
    }
}
