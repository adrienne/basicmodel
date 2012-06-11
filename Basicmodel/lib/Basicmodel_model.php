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
        if ($this->get($this->properties['primary_key']))
        {
            return false;
        }

        return true;
    }

    # public update_attributes();
    # ---------------------------
    #
    # Returns `true` if attributes updated successfully, otherwise returns `false`.
    #
    public function update_attributes($attributes = array())
    {
        if ( ! $this->is_new() AND ! empty($attributes))
        {
            foreach($attributes as $key => $value)
            {
                if ( ! in_array($key, $this->properties['attributes']))
                {
                    // trigger_error($key.' is not a valid column.');
                    return false;
                }
            }

            $query_success = $this->db->update($this->properties['table_name'], $attributes, array($this->properties['primary_key'] => $this->get($this->properties['primary_key'])));
            
            if ($query_success)
            {
                $this->attributes = array_merge($this->attributes, $attributes);
                return $this;
            }
        }

        return false;
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


    # public to_array();
    # ------------------
    #
    # Iterates through model's attributes, if current attribute is an instance of Basicmodel, it will be
    # converted to array, otherwise adds the attribute to the output array.
    #
    public function to_array()
    {
        $out = array();

        foreach ($this->attributes as $key => $value)
        {
            if ($this->attributes[$key] instanceof Basicmodel)
            {
                $out[$key] = $this->attributes[$key]->to_array();
            }

            else
            {
                $out[$key] = $value;
            }
        }

        return $out;
    }

    # public to_json();
    # -----------------
    #
    # Returns this model encoded to JSON.
    #
    public function to_json()
    {
        return json_encode($this->to_array());
    }
    
}