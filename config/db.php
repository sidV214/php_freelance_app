<?php

require_once __DIR__ . '/app.php';
require_once __DIR__ . '/env.php';

$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'freelancer_app';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS');

if ($password === false) {
    $password = '';
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

set_exception_handler(function (Throwable $exception) {
    http_response_code(500);

    $message = 'Something went wrong while connecting to the database or loading data.';

    if ($exception instanceof mysqli_sql_exception) {
        $message = 'Database setup is incomplete or unavailable. Please import the SQL tables and try again.';
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Application Error</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <span class="badge text-bg-warning mb-3">Application Error</span>
                            <h1 class="h3 mb-3">The page could not be loaded</h1>
                            <p class="text-muted mb-4"><?php echo htmlspecialchars($message); ?></p>
                            <div class="alert alert-secondary mb-4">
                                Check your database settings and make sure [database.sql] has been imported into the configured database.
                            </div>
                            <a href="<?php echo BASE_URL; ?>/index.php" class="btn btn-primary">Back to App</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
});

$conn = new mysqli($host, $username, $password, $dbname);
$conn->set_charset('utf8mb4');
