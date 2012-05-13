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


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

    
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

    
    # public make();
    # --------------
    #
    # Returns a model with attributes if any has been passed.
    #
    public function make($attributes = array())
    {
        return $this->_make($attributes);
    }

    # public make_many();
    # --------------------
    #
    # Returns an array of models with attributes if any has been passed
    #
    public function make_many($attributes = array())
    {
        return $this->_make_many($attributes);
    }


    # public find();
    # --------------
    #
    # Queries database to find a record that corresponds to the given primary key value.
    # Returns `false` if nothing was found.
    #
    #     $mymodel = $this->model->find(2);
    # 
    # TODO: pass in an array of primary keys to generate `WHERE ... IN` query.
    #
    public function find($keys = array())
    {
        if (empty($keys)) return FALSE;

        if (!is_array($keys))
        {
            $keys = array($keys);
        }

        return $this->_find($keys);
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
        $out['field_data'] = $this->db->field_data($out['table_name']);
        $out['primary_key'] = $this->_get_primary_key($out['field_data']);
        
        return $out;
    }
    
    # protected _get_model_attributes();
    # ----------------------------------
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
    # --------------------------
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
    # ------------------------------
    #
    # Strips `_model` from the model name.
    #
    protected function _clean_model_name($name)
    {
        return preg_replace('/_model/', '', $name);
    }
    
    # protected _get_plural();
    # ------------------------
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
    # ------------------------------
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
    
    # protected _get_primary_key();
    # -----------------------------
    #
    # Loops through `$this->properties['field_data']` and returns column name that's
    # set as primary key.
    #
    protected function _get_primary_key($columns)
    {
        foreach($columns as $column)
        {
            if ($column->primary_key === 1)
            {
                return $column->name;
            }
        }
        
        return '';
    }
    
    
    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    
    
    # protected _prepare_model();
    # ---------------------------
    #
    # Builds a Basicmodel_model object from passed parameters. Also, it
    # adds a function to the model, which enables it to find it's parameters
    # on the fly.
    #
    protected function _prepare_model($submitted_attributes)
    {
        $model = new Basicmodel_model();
        $properties = $this->properties;
        $properties['attributes'] = $this->attributes;
        
        foreach($this->attributes as $attribute)
        {
            if (FALSE !== array_key_exists($attribute, $submitted_attributes))
            {
                $model->$attribute = $submitted_attributes[$attribute];
            }

            else
            {
                $model->$attribute = null;
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


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    
    
    # protected _make();
    # ------------------
    #
    # Returns a Basicmodel_model object with properties assigned.
    #
    protected function _make($attributes)
    {
        return $this->_prepare_model($attributes);
    }

    # protected _make_many();
    # ------------------------
    #
    # Returns an array of Basicmodel_model objects.
    #
    protected function _make_many($models)
    {
        $out = array();

        foreach ($models as $attributes)
        {
            $out[] = $this->make($attributes);
        }

        return $out;
    }


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -


    # protected _find();
    # ------------------
    #
    # Expects an array with primary keys.
    #
    protected function _find($keys)
    {
        if (count($keys) === 1)
        {
            $this->db->where($this->properties['primary_key'], $keys[0]);
            $this->db->limit(1);
            $query = $this->db->get($this->properties['table_name']);

            if ($query->num_rows() > 0)
            {
                $result = $query->row_array();
                return $this->make($result);
            }
        }

        else
        {
            $this->db->where_in($this->properties['primary_key'], $keys);
            $query = $this->db->get($this->properties['table_name']);

            if ($query->num_rows() > 0)
            {
                $result = $query->result_array();
                return $this->make_many($result);
            }
        }

        return FALSE;
    }
    
}
