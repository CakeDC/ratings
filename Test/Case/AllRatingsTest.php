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
 * Group test - Ratings
 */
class AllRatingsPluginTest extends PHPUnit_Framework_TestSuite {

/**
 * suite method, defines tests for this suite.
 *
 * @return void
 */
	public static function suite() {
		$Suite = new CakeTestSuite('All Plugin tests');
		$path = dirname(__FILE__);
		$Suite->addTestDirectory($path . DS . 'Helper');
		$Suite->addTestDirectory($path . DS . 'Model');
		$Suite->addTestDirectory($path . DS . 'Behavior');
		$Suite->addTestDirectory($path . DS . 'Component');
		return $Suite;
	}

}
