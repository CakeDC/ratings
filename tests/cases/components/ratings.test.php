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
 * Ratings component tests
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.cases.components
 */
App::import('Controller', 'Controller', false);
App::import('Component', array('Ratings.Ratings', 'Session', 'Auth'));
Mock::generate('AuthComponent');
class Article extends CakeTestModel {
/**
 *
 */
	public $name = 'Article';
}

class ArticlesTestController extends Controller {

/**
 * @var string
 * @access public
 */
	public $name = 'ArticlesTest';

/**
 * @var array
 * @access public
 */
	public $uses = array('Article');

/**
 * @var array
 * @access public
 */
	public $components = array('Ratings.Ratings', 'Session', 'Auth');

	public function test() {
		return;
	}

	public function redirect($url, $status = NULL, $exit = true) {
		$this->redirect = $url;
	}
}


class RatingsComponentTest extends CakeTestCase {

/**
 * Controller using the tested component
 * @var ArticlesTestController
 */
	public $Controller;

/**
 * Mock AuthComponent object
 * @var MockAuthComponent
 */
	public $AuthComponent;

/**
 * Fixtures
 *
 * @var array
 * @access public
 */
	public $fixtures = array(
		'plugin.ratings.rating',
		'plugin.ratings.article',
		'plugin.ratings.user');

/**
 * startTest method
 *
 * @access public
 * @return void
 */
	function startTest() {
		$this->Controller = new ArticlesTestController();
		$this->Controller->modelClass = 'Article';
		$this->Controller->constructClasses();

		$this->AuthComponent = new MockAuthComponent();
		$this->AuthComponent->enabled = true;
		$this->Controller->Auth = $this->AuthComponent;
	}

/**
 * endTest method
 *
 * @access public
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
 * @access public
 * @return void
 */
	public function testInitialize() {
		$this->__initControllerAndRatings(array(), false);
		$this->assertEqual($this->Controller->helpers, array(
			'Session', 'Html', 'Form', 'Ratings.Rating'));
		$this->assertTrue($this->Controller->Article->Behaviors->attached('Ratable'), 'Ratable behavior should attached.');
		$this->assertEqual($this->Controller->Ratings->modelName, 'Article');
	}

	public function testInitializeWithParamsForBehavior() {
		$this->Controller->components = array('Ratings.Ratings' => array('update' => true), 'Session', 'Auth');
		$this->__initControllerAndRatings(array(), false);
		$this->assertEqual($this->Controller->helpers, array(
			'Session', 'Html', 'Form', 'Ratings.Rating'));
		$this->assertTrue($this->Controller->Article->Behaviors->attached('Ratable'), 'Ratable behavior should attached.');
		$this->assertTrue($this->Controller->Article->Behaviors->Ratable->settings['Article']['update'], 'Ratable behavior should be updatable.');
		$this->assertEqual($this->Controller->Ratings->modelName, 'Article');
	}

	public function testInitializeWithParamsForComponent() {
		$this->Controller->components = array('Ratings.Ratings' => array('actionNames' => array('show')), 'Session', 'Auth');
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
 * @access public
 * @return void
 */
	public function testStartup() {
		$this->AuthComponent->setReturnValue('user', '1', array('id'));

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

		$this->Controller->Session->write('Message', null);
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);
		$this->assertEqual($this->Controller->Session->read('Message.success.message'), 'Your rate was successfull.');
		
		$this->Controller->Session->write('Message', null);
		$params['named']['rate'] = '1';
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);
		$this->assertEqual($this->Controller->Session->read('Message.error.message'), 'You have already rated.');

		$this->Controller->Session->write('Message', null);
		$params['named']['rate'] = 'invalid-record!';
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);
		$this->assertEqual($this->Controller->Session->read('Message.error.message'), 'Invalid rate.');
	}

	public function testStartupAcceptPost() {
		$this->AuthComponent->setReturnValue('user', '1', array('id'));
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
	
		$this->Controller->Session->write('Message', null);
		$this->__initControllerAndRatings($params);
		$this->assertEqual($this->Controller->redirect, $expectedRedirect);
		$this->assertEqual($this->Controller->Session->read('Message.success.message'), 'Your rate was successfull.');
	}
	
/**
 * testBuildUrl
 *
 * @access public
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
		$this->Controller->params = array_merge($_default, $params);
		$this->Controller->Component->init($this->Controller);
		$this->Controller->Component->initialize($this->Controller);
		$this->Controller->Ratings->startup($this->Controller);
	}

}
?>