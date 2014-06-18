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
 * Rating fixture
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.fixtures
 */
class RatingFixture extends CakeTestFixture {

/**
 * Name
 *
 * @var string
 * @access pulbic
 */
	public $name = 'Rating';

/**
 * Table
 *
 * @var string
 * @access public
 */
	public $table = 'ratings';

/**
 * Fields
 *
 * @var array
 * @access public
 */
	public $fields = array(
		'id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36, 'key' => 'primary'),
		'user_id' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
		'foreign_key' => array('type' => 'string', 'null' => false, 'default' => null, 'length' => 36),
		'model' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index'),
		'value' => array('type' => 'float', 'null' => true, 'default' => '0', 'length' => '8,4'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'UNIQUE_RATING' => array('column' => array('user_id', 'foreign_key', 'model'), 'unique' => 1)
		)
	);

/**
 * Records
 *
 * @var array
 * @access public
 */
	public $records = array(
		array(
			'id' => 1,
			'user_id' => '1',
			'foreign_key' => '1', // first article
			'model' => 'Article',
			'value' => 1,
			'created' => '2009-01-01 12:12:12',
			'modified' => '2009-01-01 12:12:12'
		),
		array(
			'id' => 2,
			'user_id' => '1',
			'foreign_key' => '1', // first post
			'model' => 'Post',
			'value' => 1,
			'created' => '2009-01-01 12:12:12',
			'modified' => '2009-01-01 12:12:12'
		),
		array(
			'id' => 3,
			'user_id' => '1',
			'foreign_key' => '2', // second post
			'model' => 'Post',
			'value' => 3,
			'created' => '2009-01-01 12:12:12',
			'modified' => '2009-01-01 12:12:12')
	);
}
