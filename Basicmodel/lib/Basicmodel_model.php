<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel_model
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Contains model methods

class Basicmodel_model extends Basicmodel
{
    
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
    
    
    # private _save();
    # ----------------
    #
    # Attempts to save the model to the database. If succeeds, returns the Basicmodel_model
    # object with newly set ID, otherwise triggers PHP error.
    #
    private function _save()
    {
        $query_success = $this->db->insert($this->get_property('table_name'), $this);
        
        if ($query_success)
        {
            $primary_key = $this->get_property('primary_key');
            $this->$primary_key = $this->db->insert_id();
        }
        
        else
        {
            trigger_error('Entry not saved');
        }
        
        return $this;
    }
    
}