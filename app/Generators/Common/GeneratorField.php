<?php

namespace App\Generators\Common;

class GeneratorField
{
    public string $name;
    public string $dbType;
    public string $htmlType;
    public array $htmlInputs = [];
    public array $validations = [];
    public array $options = [];
    public bool $nullable = false;
    public bool $fillable = true;
    public bool $searchable = false;
    public bool $sortable = false;
    public ?string $defaultValue = null;
    public ?string $description = null;
    public ?array $foreignKey = null;
    public ?array $enumData = null;
    public ?string $belongsTo = null;

    public function __construct(
        string $name,
        string $dbType = 'string',
        string $htmlType = 'text',
        array $options = []
    ) {
        $this->name = $name;
        $this->dbType = $dbType;
        $this->htmlType = $htmlType;
        $this->options = $options;

        $this->parseOptions();
    }

    private function parseOptions(): void
    {
        foreach ($this->options as $option) {
            $this->parseOption($option);
        }
    }

    private function parseOption(string $option): void
    {
        if (str_contains($option, ':')) {
            [$key, $value] = explode(':', $option, 2);
        } elseif (str_contains($option, '=')) {
            [$key, $value] = explode('=', $option, 2);
        } else {
            $key = $option;
            $value = null;
        }

        if ($value !== null) {

            switch ($key) {
                case 'nullable':
                    $this->nullable = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'fillable':
                    $this->fillable = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'searchable':
                    $this->searchable = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'sortable':
                    $this->sortable = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'default':
                    $this->defaultValue = $value;
                    break;
                case 'description':
                    $this->description = $value;
                    break;
                case 'validation':
                    $this->validations = explode(',', $value);
                    break;
                case 'options':
                    $this->htmlInputs = explode(',', $value);
                    break;
                case 'enum':
                    // e.g., enum=App\Enums\StatusEnum(draft,published)
                    if (preg_match('/^([A-Za-z0-9_\\\\]+)\((.*?)\)$/', $value, $matches)) {
                        $this->enumData = [
                            'class' => class_basename(str_replace('\\\\', '\\', $matches[1])),
                            'full_class' => str_replace('\\\\', '\\', $matches[1]),
                            'cases' => explode(',', $matches[2])
                        ];
                    } else {
                        $this->enumData = [
                            'class' => class_basename(str_replace('\\\\', '\\', $value)),
                            'full_class' => str_replace('\\\\', '\\', $value),
                            'cases' => []
                        ];
                    }
                    break;
                case 'belongsTo':
                    $this->belongsTo = $value;
                    break;
            }
        } else {
            // Handle functions without value, e.g., belongsTo(Category)
            if (preg_match('/^belongsTo\((.*?)\)$/', $key, $matches)) {
                $this->belongsTo = $matches[1];
                return;
            }

            // Handle boolean flags
            switch ($key) {
                case 'nullable':
                    $this->nullable = true;
                    break;
                case 'searchable':
                    $this->searchable = true;
                    break;
                case 'sortable':
                    $this->sortable = true;
                    break;
            }
        }
    }

    public function getMigrationDefinition(): string
    {
        $dbType = $this->dbType;
        
        // Handle common HTML types mistakenly used as DB types
        if (in_array($dbType, ['select', 'checkbox', 'radio', 'textarea', 'file', 'image', 'password'])) {
            $dbType = 'string';
        }

        // If field name ends with _id and type wasn't explicitly foreignId/bigInteger etc, we assume it's a foreignId
        if (str_ends_with($this->name, '_id') && $dbType === 'string') {
            $dbType = 'foreignId';
        }

        if ($dbType === 'enum') {
            $optionsStr = implode("', '", $this->htmlInputs ?: ['default']);
            $definition = "\$table->enum('{$this->name}', ['{$optionsStr}'])";
        } else {
            $definition = "\$table->{$dbType}('{$this->name}')";
        }

        if ($this->nullable) {
            $definition .= '->nullable()';
        }

        if ($this->defaultValue !== null) {
            if (in_array(strtolower($this->defaultValue), ['true', 'false'])) {
                $default = strtolower($this->defaultValue);
                $definition .= "->default({$default})";
            } elseif (is_numeric($this->defaultValue)) {
                $definition .= "->default({$this->defaultValue})";
            } else {
                $definition .= "->default('{$this->defaultValue}')";
            }
        }

        return $definition . ';';
    }

    public function getValidationRules(): string
    {
        if (empty($this->validations)) {
            return $this->nullable ? 'nullable' : 'required';
        }

        return implode('|', $this->validations);
    }


    public function getTableHeader(): string
    {
        return "<th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">" .
            ucfirst(str_replace('_', ' ', $this->name)) .
            "</th>";
    }

    public function getTableCell(): string
    {
        return "<td class=\"px-6 py-4 whitespace-nowrap text-sm text-gray-900\">{{\$item->{$this->name}}}</td>";
    }
}
