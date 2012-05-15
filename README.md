basicmodel
==========

Basicmodel is a CodeIgniter library to ease your work with standard models in CodeIgniter.


setup
-----

Fork/clone this repo. You will need to put **Basicmodel/** folder into your **application/libraries/** folder. On Mac and Linux it's easiest to create a symbolic link:

	$ cd my/CI/application/libraries/
	$ ln -s my/basicmodel/Basicmodel/ Basicmodel

That way you'll keep your CI folder in sync with Basicmodel repo. It is not recommended to use in production yet, though, we still do it in Apollo Music.


usage (boring part)
-------------------

Basicmodel assumes each model in your **application/models/** folder has a table in your database. It tries to figure out the table name from model name. Your model name should be singular, and table name plural. For example, if you have model named `user_model` Basicmodel will assume that you have table in your database called `users`. It uses either the default CodeIgniter's Inflector helper or [Inflector library](http://codeigniter.com/wiki/Inflector/).

To set which Inflector should be used, put **basicmodel.php** config file in your **application/config/** folder:

	$config['basicmodel']['inflector'] = 'Inflector';

Default value is `default` and it will use CI helper.


To be continued...
