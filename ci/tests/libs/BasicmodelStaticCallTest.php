<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @group Lib
 */

class BasicmodelStaticCallTest extends CIUnit_TestCase
{

    public function setUp()
    {
        $this->CI->load->model('user');
    }

    public function testFindBySuccess()
    {
        
    }
    
}
