<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     CodeIgniter Basic Model class
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

# Basic Model is a class for CodeIgniter that simplifies and automates your workflow when
# working with your data models in CodeIgniter.
#

# In your model:
#
#    class Mymodel extends Basicmodel
#    {
#       
#    }
#
# In your controller:
#
#    $this->load->model('mymodel_model', 'mymodel');
#    $mymodel = $this->mymodel->new();
#    $mymodel->name = 'My name';
#    $mymodel->save();
#
# Or, in your controller:
#
#    $this->load->model('mymodel_model', 'mymodel');
#    $mymodels = $this->mymodel->all();
#
#    $this->load->view('myview', array('models' => $mymodels))
#
# And then in your view:
#
#    foreach($models as $model):
#       echo $model->name;
#    endforeach;
#

# Notes and todos
# ---------------
#
# Hitting database each time to lookup model structure and verify that the table exists is slow.
# Should implement caching instead.
#

# Basicmodel
# ----------
#
# This class should be either loaded or loaded in controller where you want to use Basicmodel.
# It makes **Basicmodel_base** available to your models.
#
# When you instantiate your models, it returns **Basicmodel_model** instance.
#
class Basicmodel
{
    
    
    
}