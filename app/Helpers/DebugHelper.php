<?php
namespace App\Helpers;

class DebugHelper 
{
    private static $enabled = true;
    private static $logPath = __DIR__ . '/../../storage/logs/';

    // Simplified color scheme
    private const BG = '#1e1e1e';
    private const FG = '#f5f5f5';
    private const ACCENT = '#82b1ff';
    private const WARN = '#ff9800';
    private const ERROR = '#ef5350';
    private const SUCCESS = '#66bb6a';
    private const MUTED = '#9e9e9e';
    private const LOGFILEMAXSIZE = 10 * 1024 * 1024; //10 MB
    private const ZIPFILELIMIT = 20;

    public static function disable(): void { self::$enabled = false; }
    public static function enable(): void { self::$enabled = true; }

    public static function debug($data, ?string $label = null, bool $fileLog = false, bool $return = false): ?string
    {
        if (!self::$enabled) return null;

        $bt = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2)[1];
        if ($fileLog) {
            self::logToFile($data, $bt, $label);
        }

        $type = gettype($data);
        $typeColor = match ($type) {
            'boolean' => $data ? self::SUCCESS : self::ERROR,
            'NULL' => self::WARN,
            default => self::ACCENT
        };

        $output = "<div style='
            background:" . self::BG . ";
            color:" . self::FG . ";
            font-family:Fira Code,Consolas,monospace;
            font-size:13px;
            padding:12px 16px;
            margin:1em 0;
            border-left:3px solid " . self::ACCENT . ";
            border-radius:6px;
        '>";

        // Header
        $output .= "<div style='margin-bottom:10px;color:" . self::ACCENT . ";font-weight:bold;'>";
        $output .= "▸ " . basename($bt['file']) . ":" . $bt['line'];
        if ($label) $output .= " <span style='color:" . self::WARN . ";'>// $label</span>";
        $output .= "</div>";

        // Type info
        $output .= "<div style='margin-bottom:6px;'>";
        $output .= "<span style='color:" . self::MUTED . "'>Type:</span> ";
        $output .= "<span style='color:" . $typeColor . "'>$type</span>";

        if ($type === 'boolean') {
            $output .= " <span>(" . ($data ? 'TRUE' : 'FALSE') . ")</span>";
        } elseif ($type === 'string') {
            $output .= " <span style='color:" . self::MUTED . "'>(length: " . strlen($data) . ")</span>";
        } elseif ($type === 'array') {
            $output .= " <span style='color:" . self::MUTED . "'>(count: " . count($data) . ")</span>";
        } elseif ($type === 'object') {
            $output .= " <span style='color:" . self::MUTED . "'>(class: " . get_class($data) . ")</span>";
        }

        $output .= "</div>";

        // Data
        $output .= "<pre style='
            background:#272727;
            color:" . self::FG . ";
            padding:12px;
            overflow-x:auto;
            border-radius:4px;
        '>";

        if (in_array($type, ['array', 'object'])) {
            $output .= htmlspecialchars(print_r($data, true));
        } elseif ($type === 'string') {
            $output .= htmlspecialchars($data);
        } else {
            $output .= var_export($data, true);
        }

        $output .= "</pre>";
        $output .= "</div>";

        if ($return) return $output;

        echo $output;
        return null;
    }

    // Log to file
    private static function logToFile($data, array $backtrace, ?string $label): void
    {
        $logDir = self::$logPath;
        $baseName = 'debuglog_' . date('Y-m-d');
        $ext = '.log';
        $maxSize = self::LOGFILEMAXSIZE;

        if (!is_dir($logDir)) {
            @mkdir($logDir, 0777, true);
        }

        $existingFiles = glob($logDir . $baseName . '*.log');
        $lastFile = $logDir . $baseName . $ext; // default

        $maxPart = 0;
        foreach ($existingFiles as $file) {
            if (preg_match('/' . preg_quote($baseName, '/') . '(?:_(\d+))?\.log$/', basename($file), $matches)) {
                $part = isset($matches[1]) ? (int)$matches[1] : 0;
                if ($part > $maxPart) {
                    $maxPart = $part;
                    $lastFile = $file;
                }
            }
        }

        if (file_exists($lastFile) && filesize($lastFile) >= $maxSize) {
            $maxPart++;
            $lastFile = $logDir . $baseName . "_$maxPart" . $ext;
        }

        $logContent = date('Y-m-d H:i:s') . " - ";
        $logContent .= basename($backtrace['file']) . ":" . $backtrace['line'] . " ";
        $logContent .= $label ? "[$label] " : "";
        $logContent .= print_r($data, true) . PHP_EOL . PHP_EOL;

        file_put_contents($lastFile, $logContent, FILE_APPEND);

        self::zipFiles($logDir, $baseName);
    }

    private static function zipFiles(string $logDir,string $baseName): void
    {
        $logFiles = glob($logDir . $baseName . '*.log');
        if (count($logFiles) > self::ZIPFILELIMIT) {
            $zip = new \ZipArchive();
            $zipFile = $logDir . $baseName . '_' . time() . '.zip';
            if ($zip->open($zipFile, \ZipArchive::CREATE) === true) {
                foreach ($logFiles as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
                array_map('unlink', $logFiles); // Delete originals
            }
        }
    }
}
