<?php

namespace DumbMVC;

/**
 * just to mock log4php basic logger behavior
 * for internal purpose
 *
 * @author Tomáš
 */
class FakeLogger {

    public function debug($message, Exception $throwable = null ) {

    }
    public function info($message, Exception $throwable = null ) {

    }
    public function error($message, Exception $throwable = null ) {

    }
}
