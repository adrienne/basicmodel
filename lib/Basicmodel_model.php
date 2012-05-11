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
            return $this;
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
    
}