<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @group Lib
 */

class BasicmodelFetchingTest extends CIUnit_TestCase
{

    /**
     * Load `User` model and set attributes for each test
     */
    public function setUp()
    {
        $this->CI->load->model('user');

        $this->attributes = array(
            'name' => 'Mindaugas Bujanauskas',
            'email' => 'mindaugas@example.com'
        );

        $this->dbfixt('users');

        $this->total = $this->CI->db->count_all('users');
    }

    /**
     * Truncate `users` table in the database
     */
    public function tearDown()
    {
        $this->CI->db->from('users');
        $this->CI->db->truncate();
    }

    /**
     * Static method `find()` should return a correct model instance
     */
    public function testModelFindSuccess()
    {
        $user = User::find(1);

        $this->assertEquals('Mindaugas Bujanauskas', $user->name);
        $this->assertEquals('mindaugas@example.com', $user->email);
    }

    /**
     * Static method `find()` should return `false` if model not found
     */
    public function testModelFindFailure()
    {
        $this->assertFalse(User::find(397));
    }

    /**
     * Static method `find()` should return a collection of Users
     */
    public function testCollectionFindSuccess()
    {
        $users = User::find(array(1, 2, 3));

        $this->assertInstanceOf('Basicmodel_Collection', $users);
        $this->assertEquals($this->total, count($users));

        $this->assertEquals('Mindaugas Bujanauskas', $users[0]->name);
        $this->assertEquals('Jane Roe', $users[2]->name);
    }

    /**
     * Static method `find()` should return empty collection if no records were found
     */
    public function testCollectionFindFailure()
    {
        $users = User::find(array(397, 541));

        $this->assertInstanceOf('Basicmodel_Collection', $users);
        $this->assertEquals(0, count($users));
    }
    
}
