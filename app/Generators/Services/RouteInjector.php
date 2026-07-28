<?php

namespace App\Generators\Services;

use Illuminate\Console\Command;
use App\Generators\Common\CommandData;

class RouteInjector
{
    protected Command $command;
    const ADMIN_MARKER = '// [ADMIN_ROUTES_MARKER]';
    const API_MARKER = '// [API_ROUTES_MARKER]';

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function injectRoutes(CommandData $commandData): void
    {
        $webCommandData = clone $commandData;
        $webCommandData->isApi = false;
        $this->addWebRoutes($webCommandData);

        if ($commandData->isApi) {
            $apiCommandData = clone $commandData;
            $apiCommandData->isApi = true;
            $apiCommandData->setControllerName($commandData->controllerName . 'Api');
            $this->addApiRoutes($apiCommandData);
        }
    }

    private function addApiRoutes(CommandData $commandData): void
    {
        $routePath = $commandData->modelNameKebabPlural;
        $controllerClass = "\\App\\Http\\Controllers\\Api\\" . $commandData->controllerName . "::class";
        $modelNameTitle = $commandData->modelNameTitle;

        $apiRoutesPath = base_path('routes/api.php');

        if (!file_exists($apiRoutesPath)) {
            $defaultContent = "<?php\n\nuse Illuminate\\Http\\Request;\nuse Illuminate\\Support\\Facades\\Route;\n\nRoute::prefix('v1')->group(function () {\n    " . self::API_MARKER . "\n});\n";
            file_put_contents($apiRoutesPath, $defaultContent);
        }

        $currentContent = file_get_contents($apiRoutesPath);

        if (str_contains($currentContent, "Route::apiResource('{$routePath}'")) {
            return;
        }

        if (!str_contains($currentContent, self::API_MARKER)) {
            $currentContent .= "\nRoute::prefix('v1')->group(function () {\n    " . self::API_MARKER . "\n});\n";
        }

        $routes = "// {$modelNameTitle} routes\n    Route::apiResource('{$routePath}', {$controllerClass});\n    " . self::API_MARKER;
        
        $newContent = str_replace(self::API_MARKER, $routes, $currentContent);
        file_put_contents($apiRoutesPath, $newContent);
        
        $this->command->info("✓ Added API routes for {$routePath} using marker injection");
    }

    private function addWebRoutes(CommandData $commandData): void
    {
        $routeName = $commandData->getRouteName();
        $routePath = str_replace('admin.', '', $routeName);
        $routeNameWithoutPrefix = str_replace('admin.', '', $routeName);
        $controllerClass = "\\App\\Http\\Controllers\\" . $commandData->controllerName . "::class";
        $modelNameTitle = $commandData->modelNameTitle;

        $importRoutesString = "";
        if ($commandData->withImport) {
            $importRoutesString = "
        Route::get('{$routePath}/import', [{$controllerClass}, 'importForm'])->name('{$routeNameWithoutPrefix}.importForm');
        Route::post('{$routePath}/import', [{$controllerClass}, 'import'])->name('{$routeNameWithoutPrefix}.import');
        Route::get('{$routePath}/export', [{$controllerClass}, 'export'])->name('{$routeNameWithoutPrefix}.export');
        Route::get('{$routePath}/sample/{format?}', [{$controllerClass}, 'downloadSample'])->name('{$routeNameWithoutPrefix}.downloadSample');";
        }

        $routes = "
        // {$modelNameTitle} routes{$importRoutesString}
        Route::resource('{$routePath}', {$controllerClass});
        " . self::ADMIN_MARKER;

        $webRoutesPath = base_path('routes/web.php');
        $currentContent = file_get_contents($webRoutesPath);

        if (!str_contains($currentContent, self::ADMIN_MARKER)) {
            $adminGroup = "\nRoute::prefix('admin')->name('admin.')->middleware(['auth', 'web'])->group(function () {\n        " . self::ADMIN_MARKER . "\n});\n";
            $currentContent .= $adminGroup;
        }

        if (str_contains($currentContent, "Route::resource('{$routePath}'") || str_contains($currentContent, "Route::resource(\"{$routePath}\"")) {
            // Already exists, just skip to avoid duplicates
            $this->command->info("✓ Routes for {$routePath} already exist, skipping.");
            return;
        }

        $newContent = str_replace(self::ADMIN_MARKER, ltrim($routes), $currentContent);
        file_put_contents($webRoutesPath, $newContent);
        
        $this->command->info("✓ Added Web routes for {$routePath} using marker injection");
    }
}
