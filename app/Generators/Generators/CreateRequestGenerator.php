<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;

class CreateRequestGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        $template = FileUtil::getStubContents('request/create');
        $outputPath = FileUtil::getRequestPath($this->commandData->createRequestName);

        $replacements = array_merge($this->getReplacements(), [
            '{{VALIDATION_RULES}}' => $this->getValidationRules(),
        ]);

        return $this->generateFile($template, $outputPath, $replacements);
    }

    public function rollback(): bool
    {
        return FileUtil::delete(FileUtil::getRequestPath($this->commandData->createRequestName));
    }

    private function getValidationRules(): string
    {
        $rules = [];
        $timestampFields = ['id', 'created_at', 'updated_at', 'deleted_at'];

        foreach ($this->commandData->fields as $field) {
            // Skip timestamp fields - Laravel handles them automatically
            if (in_array($field->name, $timestampFields)) {
                continue;
            }

            if ($field->enumData) {
                $enumClass = $field->enumData['class'];
                $baseRules = explode('|', $field->getValidationRules());
                $rulesArray = [];
                foreach($baseRules as $r) {
                    $rulesArray[] = "'{$r}'";
                }
                $rulesArray[] = "\Illuminate\Validation\Rule::enum(\App\Enums\\{$enumClass}::class)";
                $rulesStr = implode(', ', $rulesArray);
                $rules[] = "            '{$field->name}' => [{$rulesStr}],";
            } else {
                $rule = $field->getValidationRules();
                $rules[] = "            '{$field->name}' => '{$rule}',";
            }
        }

        return implode("\n", $rules);
    }
}
