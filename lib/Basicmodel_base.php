<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel_base
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Contains query methods

class Basicmodel_base extends Basicmodel
{
    
    # Contains collected properties from the database
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
    }
    
    # public get_model_properties();
    # ------------------------------
    #
    # Gets model's properties. If model name is passed, will use that, otherwise, will
    # use current class name.
    #
    public function get_model_properties($name = '')
    {
        if (empty($name) AND !empty($this->properties))
        {
            return $this->properties;
        }
        
        return $this->_get_model_properties($name);
    }
    
    # private _get_model_properties();
    # --------------------------------
    #
    # Returns an array containing model's properties. Properties come from the database
    # columns.
    #
    private function _get_model_properties($name)
    {
        $name = $this->_make_db_name($name);
        return $this->_get_table_struct($name);
    }
    
    # private _make_db_name();
    # ------------------------
    #
    # Returns a database name for the given model name.
    #
    private function _make_db_name($name)
    {
        if (empty($name))
        {
            $name = get_class($this);
        }
        
        $name = $this->_clean_model_name($name);
        return strtolower($this->_get_plural($name));
    }
    
    # private _clean_model_name();
    # ----------------------------
    #
    # Strips `_model` from the model name.
    #
    private function _clean_model_name($name)
    {
        return preg_replace('/_model/', '', $name);
    }
    
    # private _get_plural();
    # ----------------------
    #
    # Returns plural form of a given singular noun.
    #
    private function _get_plural($word)
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
    
    # private _get_table_struct();
    # ----------------------------
    #
    # Gets DB table structure for a given table name and returns it as an array.
    #
    private function _get_table_struct($table)
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
    
}
