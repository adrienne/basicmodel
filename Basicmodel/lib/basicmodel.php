<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package   Basicmodel
 * @author    Mindaugas Bujanauskas <bujanauskas.m@gmail.com>
 * @copyright (c) 2012 Mindaugas Bujanauskas
 */

/**
 * Main class
 *
 * @package Basicmodel
 */
class Basicmodel
{

    /**
     * An instance of CodeIgniter
     * 
     * @var object
     */
    public static $CI;

    /**
     * Either sets the $CI property or creates a new instance of the model.
     */
    public function __construct($attributes = array())
    {
        if (empty(static::$CI))
        {
            static::$CI =& get_instance();
        }

        else
        {
            // Loop through attributes and set them as properties
        }
    }

    /**
     * Allows models to access CI's loaded classes via $this->CI or returns model's attribtues.
     *
     * @todo   allow models to access own attributes
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key === "CI") return static::$CI;

        // Return own property
    }

    /**
     * Persists the model to the database and returns the Basicmodel instance with the newly set ID.
     * 
     * @param  array $attributes Key value pairs of attributes to be saved in the database
     * @return Basicmodel|bool
     */
    public static function create($attributes)
    {
        $model = new static($attributes);
        // Save the model (sets the ID)
        // Return a model or false
    }

}
