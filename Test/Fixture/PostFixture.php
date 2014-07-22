<?php
/**
 * Copyright 2010 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Ratings Plugin
 *
 * Post fixture
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.fixtures
 */
class PostFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false),
		'rating' => array('type' => 'float', 'null' => false, 'default' => '0', 'length' => '10,2'),
		'rating_sum' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => '10'),
		'rating_count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => '10'),
		'integer_rating' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5));

/**
 * records property
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => 'First Post',
			'rating' => 1.0000,
			'rating_sum' => 1,
			'rating_count' => 1,
			'integer_rating' => 1),
		array(
			'id' => 2,
			'title' => 'Second Post',
			'rating' => 3.0000,
			'rating_sum' => 3,
			'rating_count' => 1,
			'integer_rating' => -3),
		array(
			'id' => 3,
			'title' => '3rd Post',
			'rating' => 0.0000,
			'rating_sum' => 0,
			'rating_count' => 0,
			'integer_rating' => 0),
	);
}
