<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class PowerGridTableGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        try {
            $dataTableName = $this->commandData->modelName . 'Table';
            $outputPath = FileUtil::getLivewireTablePath($dataTableName);

            $template = FileUtil::getStubContents('powergrid-table');

            $replacements = array_merge($this->getReplacements(), [
                '{{POWERGRID_FIELDS}}' => $this->getFields(),
                '{{POWERGRID_COLUMNS}}' => $this->getColumns(),
                '{{POWERGRID_FILTERS}}' => $this->getFilters(),
            ]);

            return $this->generateFile($template, $outputPath, $replacements);
        } catch (\Exception $e) {
            \Log::error("DataTableGenerator failed: " . $e->getMessage());
            return false;
        }
    }

    public function rollback(): bool
    {
        $outputPath = FileUtil::getLivewireTablePath($this->commandData->modelName . 'Table');
        return FileUtil::delete($outputPath);
    }

    private function getFields(): string
    {
        $fields = [];
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($this->commandData->fields as $field) {
            $fieldName = $field->name;

            // Skip timestamps
            if (in_array($fieldName, $timestampFields)) {
                continue;
            }

            // Skip file/image fields
            if (in_array($field->htmlType, ['file', 'image']) || str_contains(strtolower($fieldName), 'image') || str_contains(strtolower($fieldName), 'photo') || str_contains(strtolower($fieldName), 'file')) {
                continue;
            }

            // Boolean fields render as badges
            if ($field->htmlType === 'checkbox' || $field->dbType === 'boolean') {
                $modelClass = $this->commandData->modelName;
                $fields[] = "            ->add('{$fieldName}_display', function ({$modelClass} \$row) {";
                $fields[] = "                if (\$row->{$fieldName}) {";
                $fields[] = "                    return '<span class=\"px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300\">Active</span>';";
                $fields[] = "                }";
                $fields[] = "                return '<span class=\"px-2 py-0.5 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300\">Inactive</span>';";
                $fields[] = "            })";
            } elseif ($field->htmlType === 'date' || $field->dbType === 'date') {
                $fields[] = "            ->add('{$fieldName}_formatted', fn (\$model) => \$model->{$fieldName} ? \Carbon\Carbon::parse(\$model->{$fieldName})->format('d/m/Y') : '-')";
            } elseif (in_array($field->htmlType, ['datetime', 'timestamp']) || in_array($field->dbType, ['datetime', 'timestamp'])) {
                $fields[] = "            ->add('{$fieldName}_formatted', fn (\$model) => \$model->{$fieldName} ? \Carbon\Carbon::parse(\$model->{$fieldName})->format('d/m/Y H:i') : '-')";
            } else {
                $fields[] = "            ->add('{$fieldName}')";
            }
        }

        return implode("\n", $fields);
    }

    private function getColumns(): string
    {
        $columns = [];
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($this->commandData->fields as $field) {
            $fieldName = $field->name;
            $fieldTitle = ucwords(str_replace('_', ' ', $fieldName));

            // Skip timestamps
            if (in_array($fieldName, $timestampFields)) {
                continue;
            }

            // Skip file/image fields
            if (in_array($field->htmlType, ['file', 'image']) || str_contains(strtolower($fieldName), 'image') || str_contains(strtolower($fieldName), 'photo') || str_contains(strtolower($fieldName), 'file')) {
                continue;
            }

            if ($field->htmlType === 'checkbox' || $field->dbType === 'boolean') {
                $columns[] = "            Column::make('{$fieldTitle}', '{$fieldName}_display'),";
            } elseif (in_array($field->htmlType, ['date', 'datetime', 'timestamp']) || in_array($field->dbType, ['date', 'datetime', 'timestamp'])) {
                $columns[] = "            Column::make('{$fieldTitle}', '{$fieldName}_formatted', '{$fieldName}')->sortable()->searchable(),";
            } else {
                $columns[] = "            Column::make('{$fieldTitle}', '{$fieldName}')->sortable()->searchable(),";
            }
        }

        return implode("\n", $columns);
    }

    private function getFilters(): string
    {
        $filters = [];
        $timestampFields = ['created_at', 'updated_at', 'deleted_at'];

        foreach ($this->commandData->fields as $field) {
            $fieldName = $field->name;
            
            if (in_array($fieldName, $timestampFields)) {
                continue;
            }

            if (in_array($field->htmlType, ['file', 'image']) || str_contains(strtolower($fieldName), 'image') || str_contains(strtolower($fieldName), 'photo') || str_contains(strtolower($fieldName), 'file')) {
                continue;
            }

            if ($field->htmlType === 'checkbox' || $field->dbType === 'boolean') {
                $filters[] = "            Filter::boolean('{$fieldName}_display', '{$fieldName}'),";
            }
        }

        if ($this->commandData->withSoftDeletes) {
            $filters[] = "            // TODO: Add Soft Deletes filter if needed";
            $filters[] = "            // Filter::boolean('deleted_at')->label('Trashed', 'Active'),";
        }

        return implode("\n", $filters);
    }
}
