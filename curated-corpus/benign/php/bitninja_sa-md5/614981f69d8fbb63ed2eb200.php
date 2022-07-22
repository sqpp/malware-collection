<?php

use MetricsCube\Exceptions\TestConnectionException;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'autoload.php';
try {
    $events = new \MetricsCube\Requests\Events();
    if ($events->enabled() && $events->verifyEventToken()) {
        $events->prepareEvents()->sendResponse(FALSE, 'jobs/events/result');
        $events->cleanEvents();
    }
} catch (TestConnectionException $testExc) {
    echo $testExc->getMessage();
} catch (Exception $exc) {
    $events->registerException($exc);
}
exit();