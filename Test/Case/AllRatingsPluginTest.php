<?php
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
