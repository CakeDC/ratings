<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Ratings Plugin
 *
 * Rating model tests
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.cases.models
 */
class RatingTestCase extends CakeTestCase {

/**
 * 
 */
	public $Rating = null;
/**
 * 
 */
	public $plugin = 'ratings';

/**
 * 
 */
	public $fixtures = array(
		'plugin.ratings.user',
		'plugin.ratings.rating',
		'plugin.ratings.article');

/**
 * Start Test callback
 *
 * @param string $method
 * @return void
 * @access public
 */
	public function startTest($method) {
		Configure::write('App.UserClass', null); 
		parent::startTest($method);
		$this->Rating = ClassRegistry::init('Ratings.Rating');
	}

/**
 * 
 */
	public function testRatingInstance() {
		$this->assertTrue(is_a($this->Rating, 'Rating'));
	}

}
?>