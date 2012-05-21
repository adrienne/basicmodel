<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel_model
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Contains model methods

class Basicmodel_model extends Basicmodel
{

    # Contains `key => value` pairs for model's attributes
    public $attributes = array();

    # Contains an array of model's property names
    public $properties = array();
    
    # public __construct();
    # ---------------------
    #
    # If attributes are passed, sets them as model properties
    #
    public function __construct($attributes = array())
    {
        parent::__construct();

        if (!empty($attributes))
        {
            $this->set($attributes);
        }
    }


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Setter and getter
    # =============================================================================


    # public set();
    # -------------
    #
    # Accepts either two arguments as strings, or one argument as array.
    #
    #     $mymodel->set('title', 'My awesome model');
    #
    # Or:
    #
    #     $mymodel->set(array('title' => 'My awesome model', 'type' => 'wicked'));
    #
    public function set($arg1, $arg2 = '')
    {
        if (is_array($arg1))
        {
            foreach($arg1 as $key => $value)
            {
                $this->set($key, $value);
            }
        }

        else
        {
            $this->attributes[$arg1] = $arg2;
        }

        return $this;
    }

    # public get();
    # -------------
    #
    # Returns an attribute from the attributes list.
    #
    public function get($attribute_key)
    {
        return $this->attributes[$attribute_key];
    }


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Public query methods
    # =============================================================================


    # public save();
    # --------------
    #
    # Saves model.
    #
    public function save()
    {
        if ($this->is_new())
        {
            return $this->_save();
        }
    }
    
    # public is_new();
    # ----------------
    #
    # Returns `true` if the model has not been persisted or `false` otherwise.
    #
    public function is_new()
    {
        return true;
    }
    
    
    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Private query methods
    # =============================================================================
    
    
    # protected _save();
    # ----------------
    #
    # Attempts to save the model to the database. If succeeds, returns the Basicmodel_model
    # object with newly set ID, otherwise triggers PHP error.
    #
    protected function _save()
    {
        $query_success = $this->db->insert($this->properties['table_name'], $this->attributes);
        
        if ($query_success)
        {
            $primary_key = $this->properties['primary_key'];
            $this->attributes[$primary_key] = $this->db->insert_id();
        }
        
        else
        {
            trigger_error('Entry not saved');
        }
        
        return $this;
    }


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Public utility methods
    # =============================================================================


    public function to_array()
    {
        $out = array();

        foreach ($this->properties['attributes'] as $property)
        {
            $out[$property] = null;
        }

        foreach($this as $key => $value)
        {
            if (!is_callable($this->$key))
            {
                $out[$key] = $value;
            }
        }

        return $out;
    }

    public function to_json()
    {
        return json_encode($this->to_array());
    }
    
}