<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package   Basicmodel
 * @author    Mindaugas Bujanauskas <bujanauskas.m@gmail.com>
 * @copyright (c) 2012 Mindaugas Bujanauskas
 */

class Basicmodel
{
    
    /**
     * Prints a log message
     *
     * @todo set initial static properties
     * @todo create a new model instance.
     */
    public function __construct()
    {
        log_message('debug', "Basicmodel Initialized");
    }

    /**
     * Allows models to access CI's loaded classes using the same
     * syntax as controllers.
     *
     * @param string $key
     * @todo  allow models to access own attributes
     */
    public function __get($key)
    {
        $CI =& get_instance();
        return $CI->$key;
    }

}
