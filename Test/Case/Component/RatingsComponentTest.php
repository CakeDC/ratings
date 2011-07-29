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

App::uses('Controller', 'Controller');
App::uses('Ratings.Ratings', 'Controller/Component');
App::uses('Session', 'Controller/Component');
App::uses('Auth', 'Controller/Component');

/**
 * Test Article Model
 *
 * @package ratings
 * @subpackage ratings.tests.cases.components
 */
class Article extends CakeTestModel {

/**
 * Model name
 *
 * @var string
 */
	public $name = 'Article';
}

/**
 * Test ArticlesTestController
 *
 * @package ratings
 * @subpackage ratings.tests.cases.components
 */
class ArticlesTestController extends Controller {

/**
 * Controller Name
 *
 * @var string
 */
	public $name = 'ArticlesTest';

/**
 * Models used
 *
 * @var array
 */
	public $uses = array('Article');

/**
 * Components used
 *
 * @var array
 */
	public $components = array('Ratings.Ratings', 'Session', 'Auth');

/**
 * test method
 *
 * @return void
 */
	public function test() {
		return;
	}

/**
 * Overloaded redirect
 *
 * @param string $url 
 * @param string $status 
 * @param string $exit 
 * @return void
 */
	public function redirect($url, $status = NULL, $exit = true) {
		$this->redirect = $url;
	}
}

/**
 * Test RatingsComponentTest
 *
 * @package ratings
 * @subpackage ratings.tests.cases.components
 */
class RatingsComponentTest extends CakeTestCase {

/**
 * Controller using the tested component
 *
 * @var ArticlesTestController
 */
	public $Controller;

/**
 * Mock AuthComponent object
 *
 * @var MockAuthComponent
 */
	public $AuthComponent;

/**
 * Mock SessionComponent object
 *
 * @var MockSessionComponent
 */
	public $SessionComponent;

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.ratings.rating',
		'plugin.ratings.article',
		'plugin.ratings.user');

/**
 * startTest method
 *
 * @return void
 */
	function startTest() {
		$this->Controller = new ArticlesTestController();
		$this->Controller->modelClass = 'Article';
		$this->Controller->constructClasses();

		$this->Collection = $this->getMock('ComponentCollection');

		if (!class_exists('MockAuthComponent')) {
 		$this->getMock('AuthComponent', array('user'), array($this->Collection), "MockAuthComponent");
		}
		if (!class_exists('MockSessionComponent')) {
 		$this->getMock('SessionComponent', array('destroy'), array($this->Collection), "MockSessionComponent");
		}
		
		$this->AuthComponent = new MockAuthComponent($this->Collection);
		$this->AuthComponent->enabled = true;
		$this->Controller->Auth = $this->AuthComponent;

		$this->SessionComponent = new MockSessionComponent($this->Collection);
		//$this->SessionComponent = $this->getMock('AuthComponent', array('user'),  array($this->Collection));
		$this->SessionComponent->enabled = true;
		$this->Controller->Session = $this->SessionComponent;
	}

/**
 * endTest method
 *
 * @return void
 */
	function endTest() {
		$this->Controller->Session->destroy();
		unset($this->Controller);
		ClassRegistry::flush();
	}

/**
 * testInitialize
 *
 * @return void
 */
	public function testInitialize() {
		$this->__initControllerAndRatings(array(), false);
		$this->assertEqual($this->Controller->helpers, array(
			'Session', 'Html', 'Form', 'Ratings.Rating'));
		$this->assertTrue($this->Controller->Article->Behaviors->attached('Ratable'));
		$this->assertEqual($this->Controller->Ratings->modelName, 'Article');
	}

/**
 * testInitializeWithParamsForBehavior
 *
 * @return void
 */
	public function testInitializeWithParamsForBehavior() {
		$this->Controller->components = array(
			'Ratings.Ratings' => array(
				'update' => true),
			'Session',
			'Auth');

		$this->__initControllerAndRatings(array(), false);
		$this->assertEqual($this->Controller->helpers, array(
			'Session', 'Html', 'Form', 'Ratings.Rating'));
		$this->assertTrue($this->Controller->Article->Behaviors->attached('Ratable'));
		$this->assertTrue($this->Controller->Article->Behaviors->Ratable->settings['Article']['update']);
		$this->assertEqual($this->Controller->Ratings->modelName, 'Article');
	}

/**
 * testInitializeWithParamsForComponent
 *
 * @return void
 */
	public function testInitializeWithParamsForComponent() {
		$this->Controller->components = array(
			'Ratings.Ratings' => array(
				'actionNames' => array('show')),
			'Session',
			'Auth');

		$this->__initControllerAndRatings(array(), false);
		$this->assertEqual($this->Controller->helpers, array(
			'Session', 'Html', 'Form', 'Ratings.Rating'));

		$this->assertTrue($this->Controller->Article->Behaviors->attached('Ratable'), 'Ratable behavior should attached.');
		$this->assertEqual($this->Controller->Ratings->actionNames, array('show'));
		$this->assertEqual($this->Controller->Ratings->modelName, 'Article');
	}

/**
 * testStartup
 *
 * @return void
 */
	public function testStartup() {
		$this->AuthComponent
			->expects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(array('1')));

		$params = array(
			'plugin' => null,
			'controller' => 'articles',
			'action' => 'test',
			'pass' => array(),
			'named' => array(
				'rating' => '5',
				'rate' => '2',
				'redirect' => true));
		$expectedRedirect = array(
			'plugin' => null,
			'controller' => 'articles',
			'action' => 'test');
/*
		$this->Controller->Session->expectCallCount('setFlash', 3);

		$this->Controller->Session->expectAt(0, 'setFlash', array('Your rate was successfull.', 'default', array(), 'success'));
		$this->Controller->Session->expectAt(1, 'setFlash', array('You have already rated.', 'default', array(), 'error'));
		$this->Controller->Session->expectAt(2, 'setFlash', array('Invalid rate.', 'default', array(), 'error'));
*/
//		$this->Controller->Session->write('Message', null);
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);

//		$this->Controller->Session->write('Message', null);
		$params['named']['rate'] = '1';
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);

//		$this->Controller->Session->write('Message', null);
		$params['named']['rate'] = 'invalid-record!';
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);
	}

/**
 * testStartupAcceptPost
 *
 * @return void
 */
	public function testStartupAcceptPost() {
		$this->AuthComponent
			->expects($this->any())
			->method('user')
			->with('id')
			->will($this->returnValue(1));

		$params = array(
			'plugin' => null,
			'controller' => 'articles',
			'action' => 'test',
			'pass' => array(),
			'named' => array(
				'rate' => '2',
				'redirect' => true));
		$expectedRedirect = array(
			'plugin' => null,
			'controller' => 'articles',
			'action' => 'test');
		$this->Controller->data = array('Article' => array('rating' => 2));
	
		//$this->Controller->Session->write('Message', null);

		$this->Controller->Session->expects($this->any())->method('setFlash');
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);
	}

/**
 * testBuildUrl
 *
 * @return void
 */
	public function testBuildUrl() {
		$params = array(
			'plugin' => null,
			'controller' => 'articles',
			'action' => 'test',
			'pass' => array(),
			'named' => array(
				'foo' => 'bar',
				'rating' => 'test',
				'rate' => '5',
				'redirect' => true));
		$this->__initControllerAndRatings($params);

		$result = $this->Controller->Ratings->buildUrl();
		$this->assertEqual($result, array(
			'plugin' => null,
			'controller' => 'articles',
			'action' => 'test',
			'foo' => 'bar'));
	}

/**
 * Convenience method for testing: Initializes the controller and the Ratings component
 *
 * @param array $params Controller params
 * @param boolean $doStartup Whether or not startup has to be called on the Ratings Component
 * @return void
 */
	private function __initControllerAndRatings($params = array(), $doStartup = true) {
		$_default = array('named' => array(), 'pass' => array());
		$this->Controller->request->params = array_merge($_default, $params);
		$this->Controller->Components->unload('Ratings');
		$this->Controller->Components->init($this->Controller);
		$this->Controller->Components->trigger('initialize', array(&$this->Controller));
		$this->Controller->Auth = $this->AuthComponent;
		$this->Controller->Ratings->startup($this->Controller);
	}

}
