<?php

namespace App\Generators\Generators;

use App\Generators\Utils\FileUtil;
use Illuminate\Support\Facades\Schema;

class MenuGenerator extends BaseGenerator
{
    public function generate(): bool
    {
        try {
            $menuData = $this->getMenuData();
            $this->updateConfigMenu($menuData);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function rollback(): bool
    {
        // Revert is handled by RevertScaffoldCommand
        return true;
    }

    private function getMenuData(): array
    {
        $routeName = 'admin.' . $this->commandData->modelNameSnakePlural . '.index';
        $menuName = $this->getReadableMenuName($this->commandData->modelNamePlural);
        $sectionTitle = $this->commandData->sectionTitle ?? $this->autoDetectSectionTitle();
        $permissionName = 'view-' . $this->commandData->modelNameSnakePlural;

        return [
            'name' => $menuName,
            'route' => $routeName,
            'icon' => $this->getMenuIcon(),
            'permission' => $permissionName,
            'section_title' => $sectionTitle
        ];
    }

    private function autoDetectSectionTitle(): ?string
    {
        $modelName = strtolower($this->commandData->modelName);
        $sectionMap = [
            'user' => 'User Management',
            'role' => 'User Management',
            'permission' => 'User Management',
            'product' => 'Content Management',
            'category' => 'Content Management',
            'post' => 'Media & Blog',
            'blog' => 'Media & Blog',
            'album' => 'Media & Blog',
            'tag' => 'Media & Blog',
            'page' => 'Content Management',
            'banner' => 'Content Management',
            'setting' => 'Settings',
            'order' => 'E-Commerce',
            'transaction' => 'E-Commerce',
            'payment' => 'E-Commerce',
        ];

        foreach ($sectionMap as $pattern => $title) {
            if (str_contains($modelName, $pattern)) {
                return $title;
            }
        }
        return 'App Settings';
    }

    private function getReadableMenuName(string $name): string
    {
        return \preg_replace('/(?<!^)([A-Z])/', ' $1', $name);
    }

    private function getMenuIcon(): string
    {
        $iconMap = [
            'product' => 'shopping-cart',
            'category' => 'folder',
            'user' => 'users',
            'order' => 'shopping-bag',
            'post' => 'file-text',
            'page' => 'file',
            'setting' => 'settings',
            'role' => 'shield',
            'permission' => 'key'
        ];
        $modelName = strtolower($this->commandData->modelName);
        return $iconMap[$modelName] ?? 'circle';
    }

    private function updateConfigMenu(array $menuData): void
    {
        $configPath = base_path('config/menu.php');
        if (!file_exists($configPath)) return;

        $content = file_get_contents($configPath);
        
        $menuStub = "        [\n";
        $menuStub .= "            'name' => '{$menuData['name']}',\n";
        $menuStub .= "            'route' => '{$menuData['route']}',\n";
        $menuStub .= "            'icon' => '{$menuData['icon']}',\n";
        $menuStub .= "            'permission' => '{$menuData['permission']}',\n";
        $menuStub .= "        ],\n";

        $section = $menuData['section_title'];
        $sectionSearch = "'{$section}' => [\n";

        if (str_contains($content, $sectionSearch)) {
            // Inject into existing section
            FileUtil::injectIntoFile($configPath, $sectionSearch, $menuStub, true);
        } else {
            // Create new section at the end (before return closing bracket)
            $newSection = "\n    '{$section}' => [\n{$menuStub}    ],\n];";
            $content = preg_replace('/];\s*$/', $newSection, $content);
            file_put_contents($configPath, $content);
        }
    }
}
