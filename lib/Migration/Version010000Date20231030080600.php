<?php

declare(strict_types=1);

namespace OCA\FilesRating\Migration;

use Closure;
use OCP\DB\ISchemaWrapper;
use OCP\DB\Types;
use OCP\Migration\IOutput;
use OCP\Migration\SimpleMigrationStep;

class Version010000Date20231030080600 extends SimpleMigrationStep {

    /**
     * @param IOutput $output
     * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
     * @param array $options
     */
    public function preSchemaChange(IOutput $output, Closure $schemaClosure, array $options) {
    }

    /**
     * @param IOutput $output
     * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
     * @param array $options
     * @return null|ISchemaWrapper
     */
    public function changeSchema(IOutput $output, Closure $schemaClosure, array $options) {
        /** @var ISchemaWrapper $schema */
        $schema = $schemaClosure();

        if (!$schema->hasTable('filesrating_rate')) {
            $table = $schema->createTable('filesrating_rate');
            $table->addColumn('id', Types::BIGINT, [
                'autoincrement' => true,
                'notnull' => true,
                'length' => 4,
            ]);
            $table->addColumn('user_id', Types::STRING, [
                'notnull' => true,
                'length' => 64,
            ]);
			$table->addColumn('file_id', Types::BIGINT, [
                'notnull' => true,
                'length' => 4,
            ]);
			$table->addColumn('is_avg', Types::BOOLEAN, [
                'notnull' => false,
				'default' => false,
                'length' => 1,
            ]);
            // $table->addColumn('name', Types::STRING, [
            //     'notnull' => true,
            //     'length' => 300,
            // ]);
            $table->addColumn('rate', Types::BIGINT, [
                'notnull' => true,
            ]);
            $table->setPrimaryKey(['id']);
            $table->addIndex(['user_id'], 'filesrating_rate_uid');
			$table->addIndex(['file_id'], 'filesrating_rate_fid');
        }

        return $schema;
    }

    /**
     * @param IOutput $output
     * @param Closure $schemaClosure The `\Closure` returns a `ISchemaWrapper`
     * @param array $options
     */
    public function postSchemaChange(IOutput $output, Closure $schemaClosure, array $options) {
    }
}