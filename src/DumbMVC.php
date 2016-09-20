<?php

namespace DumbMVC;

/**
 * context array and smart includer
 *
 * @author Tomáš
 */
class DumbMVC {

    private $includer;
    //context array
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
     *
     * @param \PhpIncluder\PI $includer
     * @param type $logger
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

    public function contextLogger() {
        return $this->context["logger"];
    }

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
     *
     * @return array configuration
     */
    public function contextConfig() {
        return $this->context["config"];
    }

    public function setContextConfig($config) {
        $this->context["config"] = $config;
        return $this;
    }

    public function contextOut() {
        return $this->context["out"];
    }

    public function contextContainer() {
        return $this->context["container"];
    }

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
     */
    function isAnyContextException() {
        return (count($this->context["exceptions"]) > 0);
    }

    /**
     * appends a path to the project root
     * adds/removes path separators if neccessary
     *
     * @param string $path if null/empty
     * @return string absolute path. if null/empty, a project root absolute path
     */
    function fullPath($path = null) {
        return $this->includer->path($path);
    }

    /**
     * smart require
     * the beginning and the end of require will be debug-logged
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
