<?php
/**
 * Views Work plugin for Craft CMS 3.x
 *
 * views
 *
 * @link      www.24hoursmedia.com
 * @copyright Copyright (c) 2020 24hoursmedia
 */

namespace twentyfourhoursmedia\viewswork\migrations;

use twentyfourhoursmedia\viewswork\ViewsWork;

use Craft;
use craft\config\DbConfig;
use craft\db\Migration;

/**
 * Views Work Install Migration
 *
 * If your plugin needs to create any custom database tables when it gets installed,
 * create a migrations/ folder within your plugin folder, and save an Install.php file
 * within it using the following template:
 *
 * If you need to perform any additional actions on install/uninstall, override the
 * safeUp() and safeDown() methods.
 *
 * @author    24hoursmedia
 * @package   ViewsWork
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Properties
    // =========================================================================

    /**
     * @var string The database driver to use
     */
    public $driver;

    // Public Methods
    // =========================================================================

    /**
     * This method contains the logic to be executed when applying this migration.
     * This method differs from [[up()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        if ($this->createTables()) {
            $this->createIndexes();
            $this->addForeignKeys();
            // Refresh the db schema caches
            Craft::$app->db->schema->refresh();
            $this->insertDefaultData();
        }

        return true;
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[down()]] if the DB logic
     * needs to be within a transaction.
     *
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->driver = Craft::$app->getConfig()->getDb()->driver;
        $this->removeTables();

        return true;
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates the tables needed for the Records used by the plugin
     *
     * @return bool
     */
    protected function createTables()
    {
        $tablesCreated = false;

    // viewswork_viewrecording table
        $tableSchema = Craft::$app->db->schema->getTableSchema('{{%viewswork_viewrecording}}');
        if ($tableSchema === null) {
            $tablesCreated = true;
            $this->createTable(
                '{{%viewswork_viewrecording}}',
                [
                    'id' => $this->primaryKey(),
                    'dateCreated' => $this->dateTime()->notNull(),
                    'dateUpdated' => $this->dateTime()->notNull(),
                    'uid' => $this->uid(),
                    // Custom columns in the table
                    'siteId' => $this->integer()->notNull(),
                    'elementId' => $this->integer()->notNull(),
                    'viewsTotal' => $this->integer()->defaultValue(0),
                    'viewsToday' => $this->integer()->defaultValue(0),
                    'viewsThisWeek' => $this->integer()->defaultValue(0),
                    'viewsThisMonth' => $this->integer()->defaultValue(0)
                ]
            );
        }

        return $tablesCreated;
    }

    /**
     * @return void
     */
    protected function createIndexes()
    {
        $this->createIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['viewsTotal'], false),
            '{{%viewswork_viewrecording}}', ['viewsTotal'], false
        );
        $this->createIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['viewsToday'], false),
            '{{%viewswork_viewrecording}}', ['viewsToday'], false
        );
        $this->createIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['viewsThisWeek'], false),
            '{{%viewswork_viewrecording}}', ['viewsThisWeek'], false
        );
        $this->createIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['viewsThisMonth'], false),
            '{{%viewswork_viewrecording}}', ['viewsThisMonth'], false
        );
    }

    /**
     * @return void
     */
    protected function addForeignKeys()
    {
        // viewswork_viewrecording table
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%viewswork_viewrecording}}', 'siteId'),
            '{{%viewswork_viewrecording}}',
            'siteId',
            '{{%sites}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            $this->db->getForeignKeyName('{{%viewswork_viewrecording}}', 'elementId'),
            '{{%viewswork_viewrecording}}',
            'elementId',
            '{{%elements}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * @return void
     */
    protected function insertDefaultData()
    {
    }

    /**
     * Removes the tables needed for the Records used by the plugin
     *
     * @return void
     */
    protected function removeTables()
    {
    // viewswork_viewrecording table
        $this->dropTableIfExists('{{%viewswork_viewrecording}}');
    }
}
