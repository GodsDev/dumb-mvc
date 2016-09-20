<?php

namespace DumbMVC;

/**
 * context array and smart includer
 *
 * @author Tomáš
 */
class DumbMVC {

    private $includer;

    /**
     * context is an associative array, holds custom and pre-defined objects during the request
     */
    public $context;

    private static $dmcInstance;

    /**
     * dumb singleton
     * no need to call, it is initialized by the ./../dumb-mvc.php loader
     * @param \DumbMVC\DumbMVC $dumbMVCInstance
     */
    public static function setInstance(\DumbMVC\DumbMVC $dumbMVCInstance) {
        self::$dmcInstance = $dumbMVCInstance;
    }

    /**
     * just to help the IDE with the type hinting
     * @return \DumbMVC\DumbMVC instance, null if not initialized
     */
    public static function instance() {
        return self::$dmcInstance;
    }

    /**
     * constructor
     * no need to call, it is initialized by the ./../dumb-mvc.php loader
     * @param \PhpIncluder\PI $includer for path resolving
     * @param type $logger logger
     */
    public function __construct(\PhpIncluder\PI $includer, $logger) {
        $this->includer = $includer;

        $this->context = array();
        $this->setContextLogger($logger);
        $this->context["exceptions"] = array();
        $this->context["config"] = array();
        $this->context["out"] = array();
        $this->context["container"] = array();
    }

    /**
     * shorthand for ->context["logger"] for reading
     * @return object
     */
    public function contextLogger() {
        return $this->context["logger"];
    }

    /**
     * sets context logger
     * @param type $logger logger
     * @return \DumbMVC\DumbMVC
     */
    public function setContextLogger($logger) {
        $this->context["logger"] = $logger;
        return $this;
    }

    /**
     * exception stack
     * suitable for storing multiple exceptions
     *
     * @return array exception stack
     *
     */
    public function contextExceptions() {
        return $this->context["exceptions"];
    }

    /**
     * shorthand for ->context["config"] for reading
     * @return array configuration
     */
    public function contextConfig() {
        return $this->context["config"];
    }

    /**
     * set a configuration object to a context
     * @param object/array $config configuration
     * @return \DumbMVC\DumbMVC
     */
    public function setContextConfig($config) {
        $this->context["config"] = $config;
        return $this;
    }

    /**
     * shorthand for ->context["out"] for reading
     * data computed during the request can be stored here, using ->context["out"][{data-key}] = {something}
     * @return associative array of custom objects/values
     */
    public function contextOut() {
        return $this->context["out"];
    }

    /**
     * shorthand for ->context["container"] for reading
     * a dependency injection container object can be stored here
     * @return DI container
     */
    public function contextContainer() {
        return $this->context["container"];
    }

    /**
     * sets a dependency injection container
     * @param object $container DI container
     * @return \DumbMVC\DumbMVC
     */
    public function setContextContainer($container) {
        $this->context["container"] = $container;
        return $this;
    }

    /**
     * adds an exception to the context exception stack
     * @param \Throwable $exception
     * @see contextException()
     */
    function addContextException($exception) {
        $this->context["exceptions"][] = $exception;
        $this->contextLogger()->debug("context: add exception: [" . $exception->getMessage() . "]");
    }

    /**
     *
     * @return boolean true if there is an exception in context exception stack
     * @see contextException()
     */
    function isAnyContextException() {
        return (count($this->context["exceptions"]) > 0);
    }

    /**
     * appends a path to the project root
     * - uses smart path join, adds/removes path separators if neccessary
     *
     * @param string $path if null/empty
     * @return string absolute path. if null/empty, a project root absolute path
     */
    function fullPath($path = null) {
        return $this->includer->path($path);
    }

    /**
     * smart require
     * - uses smart path join, adds/removes path separators if neccessary
     * - the beginning and the end of this require will be debug-logged
     *
     * @param string $path file to require, relative to the project root
     */
    function requireFile($path) {
        $requirePath = $this->fullPath($path);
        $this->contextLogger()->debug("requireFile START [" . $requirePath . "]");
        require_once $requirePath;
        $this->contextLogger()->debug("requireFile END [" . $requirePath . "]");
    }

}
