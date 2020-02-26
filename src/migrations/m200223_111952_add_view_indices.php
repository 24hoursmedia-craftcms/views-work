<?php

namespace twentyfourhoursmedia\viewswork\migrations;

use Craft;
use craft\db\Migration;

/**
 * m200223_111952_add_view_indices migration.
 */
class m200223_111952_add_view_indices extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $fields = ['viewsTotal', 'viewsToday', 'viewsThisMonth'];
        foreach ($fields as $field) {
            $this->createIndex(
                $this->db->getIndexName('{{%viewswork_viewrecording}}', [$field], false),
                '{{%viewswork_viewrecording}}', [$field], false
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $fields = ['viewsTotal', 'viewsToday', 'viewsThisMonth'];
        foreach ($fields as $field) {
            $this->dropIndex(
                $this->db->getIndexName('{{%viewswork_viewrecording}}',[$field],false),
                '{{%viewswork_viewrecording}}'
            );
        }
    }
}
