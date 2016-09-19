<?php

namespace Dmc;

/*
 * bunch of dumbMVC methods + loader
 */

//vendor/tomaskraus
require_once __DIR__ . "/../../tomaskraus/php-includer/src/PI.php";
require_once __DIR__ . "/src/FakeLogger.php";

//includer object
$pi = new \PhpIncluder\PI(__DIR__ . "/../../../");
error_log("pi path=" . $pi->path());

//holds the data during the request
$context = array();
//a root directory of the php app (example: /var/www/myapp)
$context["rootPath"] = null;
$context["config"] = array();
//DI
$context["container"] = array();
$context["logger"] = new \Dmc\Utils\FakeLogger;
//place values computed during the request here
$context["out"] = array();
$context["exceptions"] = array();


function logger() {
    global $context;
    return $context["logger"];
}

function config() {
    global $context;
    return $context["config"];
}

function container() {
    global $context;
    return $context["container"];
}

function out() {
    global $context;
    return $context["out"];
}

//------------------------------------------------------------------------------

function setLogger($logger) {
    global $context;
    $context["logger"] = $logger;
}

function getExceptions() {
    global $context;
    return $context["exceptions"];
}

function getRootPath() {
    global $context;
    return $context["rootPath"];
}


function addContextException($exception) {
    exceptions()[] = $exception;
    logger()->debug("context: add exception: [" . $exception->getMessage() . "]");
}

function isAnyContextException() {
    return (count(exceptions()[]) > 0);
}

/**
 * appends a path to the aplication root path
 * adds/removes path separators if neccessary
 *
 * @param string $path
 * @return string absolute path
 */
function fullPath($path) {
    global $pi;
    return $pi->path($path);
}

/**
 * smart require
 *
 * @global PI $pi php-includer object
 * @param type $path
 */
function requirePart($path) {
    global $pi;

    logger()->debug("requirePart START [" . $pi->path($path) . "]");
    require_once $pi->path($path);
    logger()->debug("requirePart END [" . $pi->path($path) . "]");
}


//----- loader -----------------------------------------------------------------

////auto-loader
//$autoLoaderFile = __DIR__ . "/vendor/autoload.php";
//if (file_exists($autoLoaderFile)) {
//    require_once $autoLoaderFile;
//}

//include file in app root dir
$bootstrapFilename = $pi->path("dmc.bootstrap.php");
if (file_exists($bootstrapFilename)) {
    logger()->debug("including bootstrap file [$bootstrapFilename]");
    include_once $bootstrapFilename;
}




