<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel_base
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Contains query methods

error_reporting(E_ALL);

class Basicmodel_base extends Basicmodel
{
    
    # Contains collected properties from the database
    protected $attributes;
    
    # Contains model properties, such as model name or database table name
    protected $properties;
    
    # public __construct();
    # ---------------------
    #
    # When the model is loaded, will populate $this->properties with model's attributes
    # fetched from the database.
    #
    public function __construct()
    {
        parent::__construct();
        $this->properties = $this->get_model_properties();
        $this->attributes = $this->get_model_attributes();
    }
    
    # public make();
    # --------------
    #
    # Returns a model with attributes if any has been passed.
    #
    public function make($attributes = array())
    {
        return $this->_make($attributes);
    }
    
    # public get_model_properties();
    # ------------------------------
    #
    # Get's model's properties such as table name and model name.
    #
    public function get_model_properties($name = '')
    {
        if (empty($name) AND !empty($this->properties))
        {
            return $this->properties;
        }
        
        if (empty($name))
        {
            $name = get_class($this);
        }
        
        return $this->_get_model_properties($name);
    }
    
    # public get_model_attributes();
    # ------------------------------
    #
    # Gets model's attributes. If model name is passed, will use that, otherwise, will
    # use current class name.
    #
    public function get_model_attributes($name = '')
    {
        if (empty($name) AND !empty($this->attributes))
        {
            return $this->attributes;
        }
        
        return $this->_get_model_attributes($name);
    }
    
    
    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    
    
    # protected _get_model_properties();
    # ----------------------------------
    #
    # Returns an array containing model's properties such as model's name and
    # table name
    #
    protected function _get_model_properties($name)
    {
        $out = array();
        $out['original_name'] = get_class($this);
        $out['model_name'] = $this->_clean_model_name($name);
        $out['table_name'] = $this->_make_db_name($name);
        
        return $out;
    }
    
    # protected _get_model_attributes();
    # --------------------------------
    #
    # Returns an array containing model's properties. Properties come from the database
    # columns.
    #
    protected function _get_model_attributes($name)
    {
        if (empty($name))
        {
            $name = $this->properties['table_name'];
        }
        
        else
        {
            $name = $this->_make_db_name($name);
        }
        
        return $this->_get_table_struct($name);
    }
    
    # protected _make_db_name();
    # ------------------------
    #
    # Returns a database name for the given model name.
    #
    protected function _make_db_name($name)
    {
        if (empty($name))
        {
            $name = get_class($this);
        }
        
        $name = $this->_clean_model_name($name);
        return strtolower($this->_get_plural($name));
    }
    
    # protected _clean_model_name();
    # ----------------------------
    #
    # Strips `_model` from the model name.
    #
    protected function _clean_model_name($name)
    {
        return preg_replace('/_model/', '', $name);
    }
    
    # protected _get_plural();
    # ----------------------
    #
    # Returns plural form of a given singular noun.
    #
    protected function _get_plural($word)
    {
        $inflector = $this->config->item('inflector', 'basicmodel');
        $inflector = ($inflector ? $inflector : 'default');
        
        if ($inflector === 'default')
        {
            $this->load->helper('inflector');
            return plural($word);
        }
        
        elseif ($inflector === 'Inflector')
        {
            $this->load->library('Inflector');
            return $this->inflector->pluralize($word);
        }
    }
    
    # protected _get_table_struct();
    # ----------------------------
    #
    # Gets DB table structure for a given table name and returns it as an array.
    #
    protected function _get_table_struct($table)
    {
        if ($this->db->table_exists($table))
        {
            return $this->db->list_fields($table);
        }
        
        else
        {
            return array();
        }
    }
    
    
    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    
    
    # protected _prepare_model();
    # -------------------------
    #
    # Builds a Basicmodel_model object from passed parameters. Also, it
    # adds a function to the model, which enables it to find it's parameters
    # on the fly.
    #
    protected function _prepare_model($attributes)
    {
        $model = new Basicmodel_model($attributes);
        $properties = $this->properties;
        $properties['attributes'] = $this->attributes;
        
        if (!empty($attributes))
        {
            foreach($attributes as $attribute => $value)
            {
                if (FALSE !== in_array($attribute, $this->attributes))
                {
                    $model->$attribute = $value;
                }
            }
        }
        
        $model->get_property = function($property) use($properties)
        {
            if ($property AND isset($properties[$property]))
            {
                return $properties[$property];
            }
            
            return '';
        };
        
        return $model;
    }
    
    # protected _make();
    # ----------------
    #
    # Returns a Basicmodel_model object.
    #
    protected function _make($attributes)
    {
        return $this->_prepare_model($attributes);
    }
    
}
