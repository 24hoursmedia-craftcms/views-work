<?php

namespace twentyfourhoursmedia\viewswork\migrations;

use Craft;
use craft\db\Migration;

/**
 * m210214_095743_add_viewrecording_index migration.
 */
class m210214_095743_add_viewrecording_index extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['dateUpdated'], false),
            '{{%viewswork_viewrecording}}', ['dateUpdated'], false
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['dateUpdated'], false),
            '{{%viewswork_viewrecording}}'
        );
        return true;
    }
}
