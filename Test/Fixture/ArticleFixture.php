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
 * Article fixture
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.fixtures
 */
class ArticleFixture extends CakeTestFixture {

/**
 * fields property
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'length' => 10),
		'title' => array('type' => 'string', 'null' => false),
		'rating' => array('type' => 'float', 'null' => false, 'default' => '0', 'length' => '10,2'),
		'integer_rating' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5),
		'rating_1' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5),
		'rating_2' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5),
		'rating_3' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5),
		'rating_4' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5),
		'rating_5' => array('type' => 'integer', 'null' => false, 'default' => 0, 'length' => 5)
	);

/**
 * records property
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => 0,
			'title' => 'First Article',
			'rating' => 1.0000,
			'integer_rating' => 1,
			'rating_1' => 0,
			'rating_2' => 0,
			'rating_3' => 0,
			'rating_4' => 0,
			'rating_5' => 0
		),
		array(
			'id' => 2,
			'user_id' => 0,
			'title' => 'First Article',
			'rating' => 3.0000,
			'integer_rating' => -3,
			'rating_1' => 0,
			'rating_2' => 0,
			'rating_3' => 0,
			'rating_4' => 0,
			'rating_5' => 0
		)
	);

}
