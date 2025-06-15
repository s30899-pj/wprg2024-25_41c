<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'event_manager');
define('DB_USER', 'root');
define('DB_PASS', 'root');

define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('LOG_DIR', __DIR__ . '/../logs/');
define('BASE_URL', 'http://localhost/EventManager/public/');

if (!is_dir(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
if (!is_dir(LOG_DIR)) {
    mkdir(LOG_DIR, 0777, true);
}