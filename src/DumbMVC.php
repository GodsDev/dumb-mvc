<?php

namespace DumbMVC;

/**
 * DumbMVC provides just a context array, smart File includer and bunch of helper methods.
 *
 * @author Tomáš
 */
class DumbMVC {

    private $includer;

    /**
     * An associative array, holds custom and predefined objects during the request.
     *
     * predefined keys are:
     * <ul>
     *      <li>config
     *      <li>logger
     *      <li>container
     *      <li>out
     *      <li>exceptions
     * </ul>
     *
     * Has a public access, to ease new key additions.
     *
     * Several helper methods for read-only access to predefined context items are provided. Those method names start with "context".
     *
     * E.g. <code>$dumbMVC->contextOut()</code> is the same (for read-only) as the <code>$dumbMVC->context["out"]</code>
     *
     * @see contextConfig()
     * @see contextLogger()
     * @see contextContainer()
     * @see contextOut()
     * @see contextExceptions()
     *
     */
    public $context;

    private static $dmcInstance;

    /**
     * Initializes DumbMVC singleton.
     *
     * No need to be called, if you include dumb-mvc.php loader in your requested page.
     * @param \DumbMVC\DumbMVC $dumbMVCInstance DumbMVC instance (use constructor)
     *
     * @see instance()
     */
    public static function setInstance(\DumbMVC\DumbMVC $dumbMVCInstance) {
        self::$dmcInstance = $dumbMVCInstance;
    }

    /**
     * A DumbMVC singleton.
     *
     * Get DumbMVC object by using this method, to help the IDE with the type hinting.
     * @return \DumbMVC\DumbMVC DumbMVC instance. Null if not initialized (using setInstance() method).
     * @see setInstance()
     */
    public static function instance() {
        return self::$dmcInstance;
    }

    /**
     * Public constructor.
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
     * Sets a logger. That logger will be available via contextLogger() method or context["logger"] public variable.
     * @param type $logger logger
     * @return \DumbMVC\DumbMVC
     * @see contextLogger()
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
     * A Shorthand for ->context["config"] for reading.
     * @return array configuration
     */
    public function contextConfig() {
        return $this->context["config"];
    }

    /**
     * Sets a configuration object to the context. That config will be available via contextConfig() method or context["config"] public variable.
     * @param object|array $config configuration.
     * @return \DumbMVC\DumbMVC
     * @see contextConfig
     */
    public function setContextConfig($config) {
        $this->context["config"] = $config;
        return $this;
    }

    /**
     * A shorthand for ->context["out"] for reading.
     *
     * Data computed during the request can be stored here, e.g.
     * <code>$dumbMVC->context["out"]["newKey"] = $newValue</code>
     *
     * @return associative array of custom objects/values
     */
    public function contextOut() {
        return $this->context["out"];
    }

    /**
     * A shorthand for ->context["container"] for reading.
     *
     * A dependency injection container object can be stored here.
     *
     * @return object DI container instance.
     */
    public function contextContainer() {
        return $this->context["container"];
    }

    /**
     * Sets a dependency injection container. That container will be available via contextContainer() method or context["container"] public variable.
     * @param object $container DI container instance.
     * @return \DumbMVC\DumbMVC
     */
    public function setContextContainer($container) {
        $this->context["container"] = $container;
        return $this;
    }

    /**
     * Adds an exception to the context exception stack.
     * @param \Throwable $exception
     * @see contextException()
     */
    function addContextException($exception) {
        $this->context["exceptions"][] = $exception;
        $this->contextLogger()->debug("context: add exception: [" . $exception->getMessage() . "]");
    }

    /**
     * Tests a context exception presence.
     * @return boolean True if there is an exception in context exception stack. False otherwise.
     * @see contextException()
     */
    function isAnyContextException() {
        return (count($this->context["exceptions"]) > 0);
    }

    /**
     * Creates a string by appending a relative path to the project root absolute path.
     *
     * Uses a smart path join, adds or removes path separators if neccessary.
     *
     * @param string $path A path relative to the project root. Can be omitted.
     * @return string An absolute path. A project root absolute path, if null/empty path is provided.
     */
    function fullPath($path = null) {
        return $this->includer->path($path);
    }

    /**
     * Does a smart, path-predictable PHP require. Path needs to be always relative to the project root.
     *
     * <ul>
     *   <li>Uses a smart path join, adds or removes path separators if neccessary.
     *   <li>The beginning and the end of this require will be debug-logged.
     * </ul>
     * @param string $path Path of file to require, always relative to the project root.
     */
    function requireFile($path) {
        $requirePath = $this->fullPath($path);
        $this->contextLogger()->debug("requireFile START [" . $requirePath . "]");
        require_once $requirePath;
        $this->contextLogger()->debug("requireFile END [" . $requirePath . "]");
    }

}
