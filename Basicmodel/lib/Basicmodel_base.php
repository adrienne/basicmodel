<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel_base
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Contains query methods

error_reporting(E_ALL);

class Basicmodel_base extends Basicmodel
{
    
    # Contains collected property names from the database
    protected $attributes;
    
    # Contains model properties, such as model name or database table name
    protected $properties;

    # Contains overrides for properties
    protected $overrides = array();
    
    # public __construct();
    # ---------------------
    #
    # When the model is loaded (in the controller for example), will populate $this->properties with
    # model's attributes fetched from the database.
    #
    # Sometimes various options need to be overriden. In that case, the `init()` method needs to be defined
    # in the model file and override methods need to be called there.
    #
    public function __construct()
    {
        parent::__construct();

        if (method_exists($this, 'init'))
        {
            $this->init();
        }

        $this->properties = $this->get_model_properties();
        $this->attributes = $this->get_model_attributes();
    }

    # public __call();
    # ----------------
    #
    # Allows usage of methods such as
    #
    #     $mymodel = $this->model->find_by_id(2012);
    #
    # which are generated from model attributes.
    #
    # Figures out what is the search key by removing the `find_by_` part of the method,
    # prepends it to `args` array and passes it to `find_by` method.
    #
    public function __call($method, $args)
    {
        if (preg_match('/^find_by_/', $method))
        {
            $method_name = substr_replace($method, '', 0, strlen('find_by_'));
            if (!in_array($method_name, $this->attributes)) return FALSE;
            array_unshift($args, $method_name);
            return call_user_func_array(array($this, 'find_by'), $args);
        }

        parent::__call($method, $args);
    }


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Public utility methods
    # =============================================================================

    
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
    #
    # Override methods
    # =============================================================================


    # public set_table_name();
    # ------------------------
    #
    # Set's a different table name for the models.
    #
    public function set_table_name($name = '')
    {
        if (!empty($name))
        {
            $this->overrides['table_name'] = $name;
        }

        return $this;
    }


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Private utility methods
    # =============================================================================
    
    
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
        $out['table_name'] = (isset($this->overrides['table_name']) ? $this->overrides['table_name'] : $this->_make_db_name($name));
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
    #
    # Public query methods
    # =============================================================================

    
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
    public function find($values = array())
    {
        if (empty($values)) return FALSE;

        return $this->find_by($this->properties['primary_key'], $values);
    }

    # public find_by();
    # -----------------
    #
    # Allows to search by passing **attribute_name** and arguments.
    #
    #     $mymodel = $this->model->find_by('name', 'John');
    #
    # Or:
    #
    #     $mymodel = $this->model->find_by('name', 'John', array('status' => 'single'));
    #
    public function find_by($key, $values, $conditions = array())
    {
        if (empty($key) || empty($values)) return FALSE;

        $keys = $this->_prepare_find_array($values);
        return $this->_find_by($key, $keys, $conditions);
    }
    
    
    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Private model generation utilities
    # =============================================================================
    
    
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
                $model->$attribute = '';
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

    # protected _prepare_find_array();
    # --------------------------------
    #
    # Prepares $keys for usage in find() methods.
    #
    protected function _prepare_find_array($keys)
    {
        if (!is_array($keys))
        {
            $keys = array($keys);
        }

        return $keys;
    }


    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Private query methods
    # =============================================================================
    
    
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

    # protected _find_by();
    # ------------------
    #
    # Expects an array with single or multiple params for the same attribute.
    #
    protected function _find_by($key, $values, $conditions)
    {
        $primary_key_flag = ($key === $this->properties['primary_key']);
        $result = FALSE;

        if (count($values) === 1)
        {
            $this->db->where($key, $values[0]);
        }

        else
        {
            $this->db->where_in($key, $values);
        }

        if ($primary_key_flag)
        {
            $this->db->limit(1);
        }

        if (!empty($conditions))
        {
            $this->db->where($conditions);
        }

        $query = $this->db->get($this->properties['table_name']);

        if ($query->num_rows() > 0)
        {
            if ($primary_key_flag)
            {
                $result = $query->row_array();
                $result = $this->make($result);
            }

            else
            {
                $result = $query->result_array();
                $result = $this->make_many($result);
            }
        }

        return $result;
    }
    
}
