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

// Define directories to watch
$watch_dirs = [
    __DIR__ . '/../mvc',
    __DIR__ . '/../library',
    __DIR__ . '/../app',
    __DIR__ . '/../web',
    __DIR__ . '/../bootstrap',
];

/**
 * Function to get the latest modification time of all files in watched directories
 */
function getLatestMTime($dirs) {
    $max_mtime = 0;
    foreach ($dirs as $dir) {
        if (!is_dir($dir)) continue;
        
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $mtime = $file->getMTime();
                if ($mtime > $max_mtime) {
                    $max_mtime = $mtime;
                }
            }
        }
    }
    return $max_mtime;
}

$last_mtime = getLatestMTime($watch_dirs);

// Send initial heartbeat to establish connection
echo "data: " . json_encode(['status' => 'connected', 'time' => $last_mtime]) . "\n\n";
ob_flush();
flush();

while (true) {
    // Check if client connection is still active
    if (connection_aborted()) break;

    clearstatcache();
    $current_mtime = getLatestMTime($watch_dirs);

    if ($current_mtime > $last_mtime) {
        // Send reload event to client
        echo "event: reload\n";
        echo "data: " . json_encode(['last_modified' => $current_mtime]) . "\n\n";
        $last_mtime = $current_mtime;
    } else {
        // Send heartbeat to keep connection alive
        echo ": heartbeat\n\n";
    }

    ob_flush();
    flush();

    // Sleep for 1 second before next check to save CPU resources
    sleep(1);
}
