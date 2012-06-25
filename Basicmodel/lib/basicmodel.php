<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package   Basicmodel
 * @author    Mindaugas Bujanauskas <bujanauskas.m@gmail.com>
 * @copyright (c) 2012 Mindaugas Bujanauskas
 * @license   http://www.opensource.org/licenses/mit-license.php/ MIT
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
     * Contains model's attributes
     * 
     * @var array
     */
    public $attributes = array();

    /**
     * Either sets the $CI property or creates a new instance of the model.
     *
     * CodeIgniter works in a way, that when you load the model, it automatically
     * instantiates it, meaning that `__construct()` will be called even when you're not
     * trying to make an instance of your object. Thus, this is a good place to set up a
     * reference to CI itself.
     *
     * If attributes array is passed, will loop through each of the attributes and set
     * them on the model, causing `__set()` to be called.
     *
     * @param array $attributes Attributes to be set on a model
     */
    public function __construct($attributes = array())
    {
        if (empty(static::$CI))
        {
            static::$CI =& get_instance();
        }

        if ( ! empty($attributes))
        {
            foreach($attributes as $key => $value)
            {
                $this->$key = $value;
            }
        }
    }

    /**
     * Allows models to access CI object via $this->CI or returns model's attribute.
     *
     * @param  string $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key === "CI") return static::$CI;
        else return $this->attributes[$key];

        // Return own property
    }

    /**
     * Sets an attribute value for this model
     * 
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Persists the model to the DB and returns the Basicmodel instance with the new ID.
     *
     * @todo   Save the model (sets the ID)
     * @todo   Return false on unsuccessful save
     * @param  array $attributes Key value pairs of attributes to be saved in the database
     * @return Basicmodel|bool
     */
    public static function create($attributes)
    {
        $model = new static($attributes);
        return $model;
    }

}
