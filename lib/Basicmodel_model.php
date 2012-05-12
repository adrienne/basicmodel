<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel_model
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Contains model methods

class Basicmodel_model extends Basicmodel
{
    
    # public save();
    # -------
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
    
    
    public function __call($method, $args)
    {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }
    
    
    # - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    
    
    private function _save()
    {
        $query_success = $query = $this->db->insert($this->get_property('table_name'), $this);
        
        if ($query_success)
        {
            $this->id = $this->db->insert_id();
        }
        
        else
        {
            trigger_error('Entry not saved');
        }
        
        return $this;
    }
    
}