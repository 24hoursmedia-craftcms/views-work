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

use Craft;
use craft\base\Plugin;
use craft\events\PluginEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\services\Dashboard;
use craft\services\Fields;
use craft\services\Plugins;
use craft\web\Request;
use craft\web\twig\variables\Cp;
use craft\web\twig\variables\CraftVariable;
use craft\web\UrlManager;
use twentyfourhoursmedia\viewswork\fields\ViewsWorkField;
use twentyfourhoursmedia\viewswork\models\Settings;
use twentyfourhoursmedia\viewswork\services\addons\BlockByCookieAddOn;
use twentyfourhoursmedia\viewswork\services\addons\BlockCrawlersAddOn;
use twentyfourhoursmedia\viewswork\services\Facade;
use twentyfourhoursmedia\viewswork\services\RegistrationUrlService;
use twentyfourhoursmedia\viewswork\services\ViewsWorkService;
use twentyfourhoursmedia\viewswork\twigextensions\ViewsWorkTwigExtension;
use twentyfourhoursmedia\viewswork\variables\ViewsWorkCpVariable;
use twentyfourhoursmedia\viewswork\variables\ViewsWorkVariable;
use twentyfourhoursmedia\viewswork\widgets\ViewsWorkWidget as ViewsWorkWidgetWidget;
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
 * @property BlockByCookieAddOn $blockByCookieAddOn
 * @property BlockCrawlersAddOn $blockCrawlersAddOn
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
            'registrationUrlService' => RegistrationUrlService::class,
            // some standard add ons
            'blockByCookieAddOn' => BlockByCookieAddOn::class,
            'blockCrawlersAddOn' => BlockCrawlersAddOn::class
        ]);

        Craft::$app->view->registerTwigExtension(new ViewsWorkTwigExtension());

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

                $request = Craft::$app->getRequest();
                if ($request instanceof Request && $request->isCpRequest) {
                    $variable->set('views_work_cp', ViewsWorkCpVariable::class);
                } else {
                    $variable->set('views_work', ViewsWorkVariable::class);
                }
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
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Event::on(UrlManager::class, UrlManager::EVENT_REGISTER_CP_URL_RULES, static function (RegisterUrlRulesEvent $event) {
            $event->rules['views-work'] = ['template' => 'views-work/cp/_index'];
            $event->rules['views-work/block'] = ['template' => 'views-work/cp/block/_index'];
        });

        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {

                $event->navItems['views-work'] = [
                    'url' => 'views-work',
                    'label' => 'Views Work',
                    'icon' => '@twentyfourhoursmedia/viewswork/assetbundles/viewswork/dist/img/ViewsWork-icon.svg',
                    //'badgeCount' => 5,
                    'subnav' => [
                        'index' => ['label' => 'Overview', 'url' => 'views-work'],
                        'block' => ['label' => 'Block registrations', 'url' => 'views-work/block'],
                    ]
                ];
            }
        );

        Event::on(Settings::class, Settings::EVENT_BEFORE_VALIDATE, function (\yii\base\ModelEvent $event) {
            $settings = $event->sender;
            /* @var Settings $settings */
            $settings->populateMissingSecrets();
        });

        // dispatch registration to default event listeners
        $this->blockByCookieAddOn->setupListeners();
        $this->blockCrawlersAddOn->setupListeners();

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
        return new Settings();
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
