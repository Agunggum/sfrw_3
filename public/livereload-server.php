<?php
/**
 * Native Live Reload Server for S-FRW using SSE (Server-Sent Events)
 * Provides real-time updates without repeated HTTP requests
 */

// Disable execution time limit for persistent connection
set_time_limit(0);

// Set headers for Server-Sent Events
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('Access-Control-Allow-Origin: *');
header('X-Accel-Buffering: no'); // Crucial for Nginx/Laravel Herd

// Define directories to watch
$base_dir = realpath(__DIR__ . '/../');
$watch_dirs = [
    $base_dir . '/mvc',
    $base_dir . '/library',
    $base_dir . '/app',
    $base_dir . '/bootstrap',
    $base_dir . '/public',
];

// File extensions to watch
$allowed_extensions = ['php', 'css', 'js', 'env', 'html', 'json'];

/**
 * Function to get the latest modification time of all files in watched directories
 */
function getLatestMTime($dirs, $allowed_exts) {
    $max_mtime = 0;
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) continue;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                // Only watch specific extensions to save CPU
                if (in_array($ext, $allowed_exts)) {
                    $mtime = $file->getMTime();
                    if ($mtime > $max_mtime) {
                        $max_mtime = $mtime;
                    }
                }
            }
        }
    }
    return $max_mtime;
}

$last_mtime = getLatestMTime($watch_dirs, $allowed_extensions);

// Send initial heartbeat to establish connection
echo "data: " . json_encode(['status' => 'connected', 'time' => $last_mtime]) . "\n\n";

if (ob_get_level() > 0) ob_flush();
flush();

while (true) {
    // Check if client connection is still active
    if (connection_aborted()) break;

    clearstatcache();
    $current_mtime = getLatestMTime($watch_dirs, $allowed_extensions);

    if ($current_mtime > $last_mtime) {
        // Send reload event to client
        echo "event: reload\n";
        echo "data: " . json_encode(['last_modified' => $current_mtime]) . "\n\n";
        $last_mtime = $current_mtime;
    } else {
        // Send heartbeat to keep connection alive
        echo ": heartbeat\n\n";
    }

    if (ob_get_level() > 0) ob_flush();
    flush();

    // Sleep for 1 second before next check to save CPU resources
    sleep(1);
}
