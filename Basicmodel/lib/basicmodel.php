<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package   Basicmodel
 * @version   0.0.1
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
     * Change primary key column
     * 
     * @var string
     */
    public static $key = 'id';

    /**
     * Change table name
     * 
     * @var [type]
     */
    public static $table;

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
        foreach($attributes as $key => $value)
        {
            $this->$key = $value;
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
        if ($key === "CI")
        {
            return static::CI();
        }

        else
        {
            return $this->attributes[$key];
        }
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
     * Returns CI instance
     *
     * @return object CI object
     */
    public static function CI()
    {
        return get_instance();
    }

    /**
     * Persists the model to the DB and returns the Basicmodel instance with the new ID.
     *
     * @param  array $attributes Key value pairs of attributes to be saved in the database
     * @return Basicmodel|bool
     */
    public static function create($attributes)
    {
        $model = new static($attributes);
        $success = $model->save();
        return $success ? $model : false;
    }

    /**
     * Persists the model to the database
     *
     * @todo   If model is new, insert, otherwise update
     * @return bool INSERT/UPDATE success
     */
    public function save()
    {
        $this->CI->db->insert($this->table(), $this->attributes);
        $success = $this->CI->db->affected_rows() > 0;

        if ($success && $this->is_new())
        {
            $this->{static::$key} = $this->CI->db->insert_id();
        }

        return $success;
    }

    /**
     * Gets table name
     *
     * @todo   Use inflector
     * @return string
     */
    public function table()
    {
        return empty(static::$table) ? strtolower(get_class($this)).'s' : static::$table;
    }

    /**
     * Determines whether this model exists in the database
     *
     * This is done by checking for PK's value. If the value is set, this means that the
     * model exists in the database.
     * 
     * @return boolean
     */
    public function is_new()
    {
        return empty($this->attributes[static::$key]);
    }

}
