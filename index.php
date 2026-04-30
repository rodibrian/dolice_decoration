<?php
declare(strict_types=1);

// Front-controller for environments where the web root is the project folder
// (e.g. http://localhost/dolice_decoration/), not /public.
require __DIR__ . '/config/bootstrap.php';

$app = new App\Core\App();
$app->run();

