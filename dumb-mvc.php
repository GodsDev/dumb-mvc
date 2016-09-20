<?php

/**
 * dumbMVC loader, initializer and bootstrap file handler
 *
 * @author Tomáš
 */
require_once __DIR__ . "/vendor/tomaskraus/php-includer/src/PI.php";
require_once __DIR__ . "/src/FakeLogger.php";
require_once __DIR__ . "/src/DumbMVC.php";

\DumbMVC\DumbMVC::setInstance(
        new \DumbMVC\DumbMVC(
        new \PhpIncluder\PI(__DIR__ . "/../../../"), new \FakeLogger()
        )
);


//include file in app root dir
$bootstrapFilename = $dmc->fullPath("dmc.bootstrap.php");
if (file_exists($bootstrapFilename)) {
    logger()->debug("including bootstrap file [$bootstrapFilename]");
    include_once $bootstrapFilename;
}

