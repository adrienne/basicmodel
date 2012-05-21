<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

#
#     Basicmodel_collection
#     (c) 2012 Mindaugas Bujanauskas, Apollo Music Aps
#

class Basicmodel_collection extends Basicmodel
{

	# Contains an array of models
	public $models = array();

	# public __construct();
	# ---------------------
	#
	# Expects an array of models.
	#
	public function __construct($models)
	{
		parent::__construct();
		$this->models = $models;
	}


	# - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
    #
    # Public utility methods
    # =============================================================================


	# public to_array();
	# ------------------
	#
	# Returns an array of models in collection.
	#
	public function to_array()
	{
		$out = array();

		foreach($this->models as $model)
		{
			$out[] = $model->to_array();
		}

		return $out;
	}

    # public to_json();
    # -----------------
    #
    # Returns JSON of all models in the collection
    #
    public function to_json()
    {
    	return json_encode($this->to_array());
    }

}