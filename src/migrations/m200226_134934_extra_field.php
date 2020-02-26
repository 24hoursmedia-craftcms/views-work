<?php

namespace twentyfourhoursmedia\viewswork\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200226_134934_extra_field migration.
 */
class m200226_134934_extra_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%viewswork_viewrecording}}', 'viewsThisWeek', $this->integer()->defaultValue(0));
        $this->createIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['viewsThisWeek'], false),
            '{{%viewswork_viewrecording}}', ['viewsThisWeek'], false
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropIndex(
            $this->db->getIndexName('{{%viewswork_viewrecording}}', ['viewsThisWeek'], false),
            '{{%viewswork_viewrecording}}'
        );
        $this->dropColumn('{{%viewswork_viewrecording}}', 'viewsThisWeek');
    }
}
