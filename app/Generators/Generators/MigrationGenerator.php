<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class MigrationGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('migration');
        $outputPath = FileUtil::getMigrationPath($this->commandData->getTableName());

        $replacements = array_merge($this->getReplacements(), [
            '{{MIGRATION_FIELDS}}' => $this->getMigrationFields(),
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        // Find and delete the migration file
        $migrationFiles = glob(database_path("migrations/*_create_{$this->commandData->getTableName()}_table.php"));

        if (!empty($migrationFiles)) {
            return FileUtil::delete($migrationFiles[0]);
        }

        return false;
    }

    private function getMigrationFields(): string
    {
        $fields = [];

        // Add ID field
        $fields[] = '            $table->id();';

        // Add timestamps
        $fields[] = '            $table->timestamps();';

        // Add soft deletes if enabled
        if ($this->commandData->withSoftDeletes) {
            $fields[] = '            $table->softDeletes();';
        }

        // Add custom fields
        foreach ($this->commandData->fields as $field) {
            $fields[] = '            ' . $field->getMigrationDefinition();
        }

        // Add indexes for better query performance
        $indexFields = [];
        $hasSort = false;
        $hasShow = false;
        $hasDeletedAt = false;

        // Check if sort, show, or deleted_at columns exist
        foreach ($this->commandData->fields as $field) {
            if ($field->name === 'sort') {
                $hasSort = true;
            }
            if ($field->name === 'show') {
                $hasShow = true;
            }
        }
        // deleted_at is added by softDeletes()
        $hasDeletedAt = $this->commandData->withSoftDeletes;

        // Add indexes
        if ($hasSort) {
            $indexFields[] = '            $table->index(\'sort\');';
        }
        if ($hasShow) {
            $indexFields[] = '            $table->index(\'show\');';
        }
        if ($hasDeletedAt) {
            $indexFields[] = '            $table->index(\'deleted_at\');';
        }

        // Add indexes if any
        if (!empty($indexFields)) {
            $fields[] = '';
            $fields[] = '            // Add indexes for better query performance';
            $fields = array_merge($fields, $indexFields);
        }

        return implode("\n", $fields);
    }
}
