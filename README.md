Basicmodel for CodeIgniter 2.1.x
================================

Basicmodel is a new base for your CodeIgniter models. Built using CodeIgniter's functionality, it integrates with your projects and allows you to simplify your daily tasks.

In short, Basicmodel takes care of writing casual CRUD operations for your models. It figures out the table name, primary key, column names, etc. and extends your models with some basic functionality, that should've been there from the begining.


* * *


Usage
--------------------------------

Create your model by extending `Basicmodel`:

	// application/models/user.php
	class User extends Basicmodel { }

And now you can use it in your controllers like so:
	
	// application/controllers/show.php
	$this->load->model('user');
	
	$user = User::find(37);
	$user->update_attributes(array(
		'name' => 'Mindaugas Bujanauskas',
		'email' => 'mindaugas@example.com'
	));

Here's how to persist your model to the database:

	// application/controllers/create.php
	User::create(array(
		'name' => 'Mindaugas Bujanauskas',
		'email' => 'mindaugas@example.com'
	));

The `create` method returns an instance of `User` model, so you can easily use it in your view like so:
	
	// applications/controllers/create.php
	$user = User::create(array(
		'name' => 'Mindaugas Bujanauskas',
		'email' => 'mindaugas@example.com'
	))
	
	$this->load->view('show', array('user' => $user));

And in your view:

	// application/views/show.php
	<div id="user">
		<?= mailto($user->email, $user->name) ?>
	</div>


Development
--------------------------------

* Testing: [PHPUnit](http://www.phpunit.de/) via [CIUnit](https://bitbucket.org/kenjis/my-ciunit/)
* Documentation: [ApiGen](http://apigen.org/)
* Building: [Rake](http://rake.rubyforge.org/)


Preparation
--------------------------------

Before starting the development application, you need to create two databases: `basicmodel_development` and `basicmodel_testing`. Once you have them, direct your browser towards your Basicmodel application migrations URL; on my development machine it is <http://basicmodel/index.php/migrations>.


Style
--------------------------------

* General guidelines: [CodeIgniter's Style Guide](http://codeigniter.com/user_guide/general/styleguide.html)
* Indentation: 4 spaces
* Gutter: 90 spaces
