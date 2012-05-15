<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel 0.0.1
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Basic Model is a class for CodeIgniter that simplifies and automates your workflow when
# working with your data models in CodeIgniter.
#

# In your model:
#
#     class Mymodel_model extends Basicmodel_base
#     {
#     
#     }
#
# In your controller:
#
#     $this->load->model('mymodel_model', 'mymodel');
#     $mymodel = $this->mymodel->make();
#     $mymodel->set('name', 'John');
#     $mymodel->save();
#
# Or, in your controller:
#
#     $this->load->model('mymodel_model', 'mymodel');
#     $mymodels = $this->mymodel->all();
#
#     $this->load->view('myview', array('models' => $mymodels))
#
# And then in your view:
#
#     foreach($models as $model):
#        echo $model->name;
#     endforeach;
#

# Notes and todos
# ---------------
#
# 1. Hitting database each time to lookup model structure and verify that the table exists is slow.
# Should implement caching instead.
#
# 2. We should be able to set custom model parameters in the model file, such as ID column, custom
# table name, etc.
#
# 3. Need a file that would contain error messages.
#
# 4. Decide on testing framework and use it.
#

# Basicmodel
# ----------
#
# This class should be either loaded automatically or loaded in controller.
# It makes **Basicmodel_base** available to your models.
#
# When you instantiate your models, it returns **Basicmodel_model** instance.
#
class Basicmodel extends CI_Model
{
    
    # Loads config, **Basicmodel_base** and **Basicmodel_model** classes
    public function __construct()
    {
        parent::__construct();
        $path = rtrim(dirname(__FILE__), '/').'/';
        require_once($path.'lib/Basicmodel_base.php');
        require_once($path.'lib/Basicmodel_model.php');
        require_once($path.'lib/Basicmodel_collection.php');
        $this->config->load('basicmodel', FALSE, TRUE);
    }

    # magic __call();
    # ---------------
    #
    # Since a lot of methods in both Basicmodel_base and Basicmodel_model are generated on the
    # fly, this method is inherited by the mentioned classes and runs the generated methods.
    #
    public function __call($method, $args)
    {
        if (isset($this->$method)) {
            $func = $this->$method;
            return call_user_func_array($func, $args);
        }
    }
    
}
