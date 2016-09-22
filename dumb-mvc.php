<?php

/**
 * dumbMVC loader, initializer and bootstrap file handler
 *
 * @author Tomáš
 */
require_once __DIR__ . "/../php-includer/src/PI.php";
require_once __DIR__ . "/src/FakeLogger.php";
require_once __DIR__ . "/src/DumbMVC.php";

if (\DumbMVC\DumbMVC::instance() == null) {
    \DumbMVC\DumbMVC::setInstance(
            new \DumbMVC\DumbMVC(
            new \PhpIncluder\PI(__DIR__ . "/../../../"), new \DumbMVC\FakeLogger()
            )
    );
}

//include file in app root dir
$bootstrapFilename = \DumbMVC\DumbMVC::instance()->fullPath("dmc.bootstrap.php");
if (file_exists($bootstrapFilename)) {
    \DumbMVC\DumbMVC::instance()->contextLogger()->debug("including bootstrap file [$bootstrapFilename]");
    include_once $bootstrapFilename;
}
