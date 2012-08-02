<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package   Basicmodel
 * @version   0.0.1
 * @author    Mindaugas Bujanauskas <bujanauskas.m@gmail.com>
 * @copyright (c) 2012 Mindaugas Bujanauskas
 * @license   http://www.opensource.org/licenses/mit-license.php/ MIT
 */

/**
 * Basicmodel Collection
 *
 * @package Basicmodel
 */
class Basicmodel_Collection extends ArrayObject
{
    
    public function is_empty()
    {
        return (bool) $this->count() ? FALSE : TRUE;
    }

}
