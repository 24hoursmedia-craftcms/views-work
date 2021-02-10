<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * plugin for handling pageviews
 *
 * @link      http://www.24hoursmedia.com
 * @copyright Copyright (c) 2018 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork;


use craft\events\RegisterUrlRulesEvent;
use craft\services\Fields;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use twentyfourhoursmedia\viewswork\fields\ViewsWorkField;
use twentyfourhoursmedia\viewswork\services\Facade;
use twentyfourhoursmedia\viewswork\services\RegistrationUrlService;
use twentyfourhoursmedia\viewswork\services\ViewsWorkService;
use twentyfourhoursmedia\viewswork\twigextensions\ViewsWorkTwigExtension;
use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\variables\ViewsWorkVariable;
use twentyfourhoursmedia\viewswork\widgets\ViewsWorkWidget as ViewsWorkWidgetWidget;
use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\services\Dashboard;
use craft\events\RegisterComponentTypesEvent;


use yii\base\Event;

/**
 * Class ViewsWork
 *
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 *
 * @property ViewsWorkService $viewsWorkService
 * @property Facade $viewsWork
 * @property RegistrationUrlService $registrationUrlService
 */
class ViewsWork extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var ViewsWork
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        $this->setComponents([
            'viewsWork' => Facade::class,
            'viewsWorkService' => ViewsWorkService::class,
            'registrationUrlService' => RegistrationUrlService::class
        ]);
        Craft::$app->view->registerTwigExtension(new ViewsWorkTwigExtension());

        // validate the settings
        $this->initSettings($this->getSettings());


        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            static function (RegisterComponentTypesEvent $event) {
                $event->types[] = ViewsWorkField::class;
            }
        );

        // Register our variables
        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                /** @var CraftVariable $variable */
                $variable = $event->sender;
                $variable->set('views_work', ViewsWorkVariable::class);
            }
        );

        Event::on(
            Dashboard::class,
            Dashboard::EVENT_REGISTER_WIDGET_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = ViewsWorkWidgetWidget::class;
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            static function (RegisterUrlRulesEvent $event) {
                $event->rules['views-work/reset'] = 'views-work/reset/reset';
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'views-work',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        $settings = new Settings();
        $settings->signKey = Craft::$app->security->generateRandomString();
        $settings->urlResetSecret = Craft::$app->security->generateRandomString();
        return $settings;
    }

    /**
     * Ensure there are some secret keys in settings saved
     * @param Settings $settings
     * @deprecated
     */
    protected function initSettings(Settings $settings) {

    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'views-work/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
