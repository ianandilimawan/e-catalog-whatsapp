<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class LaravelLogService
{
    /**
     * Get all log files from storage/logs directory
     *
     * @return array
     */
    public static function getLogFiles(): array
    {
        $logPath = storage_path('logs');

        if (!File::exists($logPath)) {
            return [];
        }

        $files = File::files($logPath);
        $logFiles = [];

        foreach ($files as $file) {
            // Only include .log files
            if ($file->getExtension() === 'log') {
                $fileName = $file->getFilename();
                $date = self::extractDateFromFileName($fileName);

                $logFiles[] = [
                    'name' => $fileName,
                    'path' => $file->getPathname(),
                    'size' => $file->getSize(),
                    'modified' => $file->getMTime(),
                    'size_human' => self::formatBytes($file->getSize()),
                    'modified_human' => date('Y-m-d H:i:s', $file->getMTime()),
                    'date' => $date,
                    'date_formatted' => $date ? date('d M Y', strtotime($date)) : null,
                    'is_daily' => $date !== null,
                ];
            }
        }

        // Sort by date (newest first), then by modified time
        usort($logFiles, function ($a, $b) {
            // If both have dates, sort by date
            if ($a['date'] && $b['date']) {
                $dateCompare = strtotime($b['date']) - strtotime($a['date']);
                if ($dateCompare !== 0) {
                    return $dateCompare;
                }
            }
            // Otherwise sort by modified time
            return $b['modified'] - $a['modified'];
        });

        return $logFiles;
    }

    /**
     * Extract date from daily log file name
     * Format: laravel-YYYY-MM-DD.log
     *
     * @param string $fileName
     * @return string|null
     */
    protected static function extractDateFromFileName(string $fileName): ?string
    {
        // Match laravel-YYYY-MM-DD.log format
        if (preg_match('/laravel-(\d{4}-\d{2}-\d{2})\.log$/', $fileName, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Group log files by date
     *
     * @param array $logFiles
     * @return array
     */
    public static function groupLogFilesByDate(array $logFiles): array
    {
        $grouped = [];

        foreach ($logFiles as $file) {
            $key = $file['date'] ?? 'other';
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'date' => $file['date'],
                    'date_formatted' => $file['date_formatted'] ?? 'Other',
                    'files' => [],
                ];
            }
            $grouped[$key]['files'][] = $file;
        }

        // Sort groups by date (newest first)
        uksort($grouped, function ($a, $b) {
            if ($a === 'other') return 1;
            if ($b === 'other') return -1;
            return strtotime($b) - strtotime($a);
        });

        return $grouped;
    }

    /**
     * Read log file content with pagination
     *
     * @param string $fileName
     * @param int $page
     * @param int $perPage
     * @param string|null $level
     * @param string|null $search
     * @return array
     */
    public static function readLogFile(
        string $fileName,
        int $page = 1,
        int $perPage = 50,
        ?string $level = null,
        ?string $search = null
    ): array {
        $logPath = storage_path('logs/' . $fileName);

        if (!File::exists($logPath)) {
            return [
                'entries' => [],
                'total' => 0,
                'current_page' => $page,
                'per_page' => $perPage,
                'last_page' => 1,
            ];
        }

        // Read file content
        $content = File::get($logPath);

        // Parse log entries
        $entries = self::parseLogContent($content, $level, $search);

        // Reverse to show newest first
        $entries = array_reverse($entries);

        $total = count($entries);
        $lastPage = max(1, ceil($total / $perPage));

        // Paginate
        $offset = ($page - 1) * $perPage;
        $paginatedEntries = array_slice($entries, $offset, $perPage);

        return [
            'entries' => $paginatedEntries,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'last_page' => $lastPage,
        ];
    }

    /**
     * Parse log content into structured entries
     *
     * @param string $content
     * @param string|null $level
     * @param string|null $search
     * @return array
     */
    protected static function parseLogContent(string $content, ?string $level = null, ?string $search = null): array
    {
        $entries = [];
        $lines = explode("\n", $content);
        $currentEntry = null;
        $buffer = [];

        foreach ($lines as $line) {
            // Check if this is a new log entry (starts with date pattern)
            // Laravel log format: [2024-01-01 12:00:00] local.ERROR: message
            // Also handle: [2024-01-01 12:00:00] production.ERROR: message
            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(.+?)\.(\w+):\s*(.*)$/', $line, $matches)) {
                // Save previous entry if exists
                if ($currentEntry !== null) {
                    $currentEntry['stack'] = trim(implode("\n", $buffer));
                    $buffer = [];

                    // Apply filters
                    if (self::shouldIncludeEntry($currentEntry, $level, $search)) {
                        $entries[] = $currentEntry;
                    }
                }

                // Create new entry
                $currentEntry = [
                    'timestamp' => $matches[1],
                    'environment' => $matches[2],
                    'level' => strtoupper($matches[3]),
                    'message' => trim($matches[4]),
                    'stack' => '',
                ];
            } elseif ($currentEntry !== null) {
                // Continuation of current entry (stack trace, etc.)
                // Skip empty lines at the start of stack trace
                if (!empty(trim($line)) || !empty($buffer)) {
                    $buffer[] = $line;
                }
            }
        }

        // Don't forget the last entry
        if ($currentEntry !== null) {
            $currentEntry['stack'] = trim(implode("\n", $buffer));
            if (self::shouldIncludeEntry($currentEntry, $level, $search)) {
                $entries[] = $currentEntry;
            }
        }

        return $entries;
    }

    /**
     * Check if entry should be included based on filters
     *
     * @param array $entry
     * @param string|null $level
     * @param string|null $search
     * @return bool
     */
    protected static function shouldIncludeEntry(array $entry, ?string $level = null, ?string $search = null): bool
    {
        // Filter by level
        if ($level && strtoupper($entry['level']) !== strtoupper($level)) {
            return false;
        }

        // Filter by search term
        if ($search) {
            $searchLower = strtolower($search);
            $messageLower = strtolower($entry['message']);
            $stackLower = strtolower($entry['stack']);

            if (strpos($messageLower, $searchLower) === false && strpos($stackLower, $searchLower) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get available log levels from a file
     *
     * @param string $fileName
     * @return array
     */
    public static function getLogLevels(string $fileName): array
    {
        $logPath = storage_path('logs/' . $fileName);

        if (!File::exists($logPath)) {
            return [];
        }

        $content = File::get($logPath);
        $entries = self::parseLogContent($content);

        $levels = [];
        foreach ($entries as $entry) {
            $level = $entry['level'];
            if (!in_array($level, $levels)) {
                $levels[] = $level;
            }
        }

        sort($levels);
        return $levels;
    }

    /**
     * Delete a log file
     *
     * @param string $fileName
     * @return bool
     */
    public static function deleteLogFile(string $fileName): bool
    {
        $logPath = storage_path('logs/' . $fileName);

        if (!File::exists($logPath)) {
            return false;
        }

        return File::delete($logPath);
    }

    /**
     * Clear log file content (truncate)
     *
     * @param string $fileName
     * @return bool
     */
    public static function clearLogFile(string $fileName): bool
    {
        $logPath = storage_path('logs/' . $fileName);

        if (!File::exists($logPath)) {
            return false;
        }

        return File::put($logPath, '') !== false;
    }

    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @return string
     */
    protected static function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Get log level color class
     *
     * @param string $level
     * @return string
     */
    public static function getLevelColor(string $level): string
    {
        $level = strtoupper($level);

        return match ($level) {
            'EMERGENCY', 'ALERT', 'CRITICAL' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'ERROR' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'WARNING' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'NOTICE' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'INFO' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'DEBUG' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }
}
