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

App::uses('Controller', 'Controller');
App::uses('HtmlHelper', 'View/Helper');
App::uses('FormHelper', 'View/Helper');
App::uses('RatingHelper', 'Ratings.View/Helper');


/**
 * CakePHP Ratings Plugin
 *
 * Rating helper tests
 *
 * @package 	ratings
 * @subpackage 	ratings.tests.cases.helpers
 */
class RatingHelperTestCase extends CakeTestCase {

/**
 * Helper being tested
 *
 * @var RatingHelper
 */
	public $Rating;

/**
 * (non-PHPdoc)
 * @see cake/tests/lib/CakeTestCase#startTest($method)
 */
	public function setUp() {
		parent::setUp();
		$this->Controller = new Controller();
		$this->View = new View($this->Controller);
		$this->Rating = new RatingHelper($this->View);
		$this->Rating->Form = new FormHelper($this->View);
		$this->Rating->Html = new HtmlHelper($this->View);
		$this->Rating->Form->Html = $this->Rating->Html;

		ClassRegistry::addObject('view', $this->View);
	}

/**
 * Test percentage method
 *
 * @return void
 */
	public function testPercentage() {
		$this->assertEquals($this->Rating->percentage(2, 5), '40');
		$this->assertEquals($this->Rating->percentage(0, 0), '0');
		$this->assertEquals($this->Rating->percentage(6, 6), '100');
	}

/**
 * Test bar method
 *
 * @return void
 */
	public function testBar() {
		$result = $this->Rating->bar(1, 2);
		$expected = '<div class="barRating"><div style="width: 50%" class="inner"><span>1</span></div></div>';
		$this->assertEquals($expected, $result);

		$result = $this->Rating->bar(1, 4, array('innerHtml' => '<span>%percentage%</span>'));
		$expected = '<div class="barRating"><div style="width: 25%" class="inner"><span>25</span></div></div>';
		$this->assertEquals($expected, $result);
	}

/**
 * Test display method exception
 *
 * @return void
 * @expectedException Exception
 */
	public function testDisplayException() {
		$this->Rating->display();
	}

/**
 * Test display method
 *
 * @return void
 */
	public function testDisplay() {
		$options = array(
			'item' => '42',
			'url' => array('controller' => 'articles', 'action' => 'rate'),
			'stars' => 5);
		$result = $this->Rating->display($options);
		$expected =
		'<ul class="rating rating-0">' .
			'<li class="star1"><a href="/articles/rate/rate:42/rating:1/redirect:1">1</a></li>' .
			'<li class="star2"><a href="/articles/rate/rate:42/rating:2/redirect:1">2</a></li>' .
			'<li class="star3"><a href="/articles/rate/rate:42/rating:3/redirect:1">3</a></li>' .
			'<li class="star4"><a href="/articles/rate/rate:42/rating:4/redirect:1">4</a></li>' .
			'<li class="star5"><a href="/articles/rate/rate:42/rating:5/redirect:1">5</a></li>' .
		'</ul>';
		$this->assertEquals($result, $expected);

		$options = array_merge($options, array(
			'type' => 'ol',
			'redirect' => false,
			'value' => '2.25',
			'stars' => '1'));
		$result = $this->Rating->display($options);
		$expected =
		'<ol class="rating rating-2">'.
			'<li class="star1"><a href="/articles/rate/rate:42/rating:1">1</a></li>'.
		'</ol>';
		$this->assertEquals($result, $expected);

		$options = array_merge($options, array(
			'type' => 'div'));
		$result = $this->Rating->display($options);
		$expected =
		'<ul class="rating rating-2">'.
			'<li class="star1"><a href="/articles/rate/rate:42/rating:1">1</a></li>'.
		'</ul>';
		$this->assertEquals($result, $expected);

		$options = array(
			'item' => '42',
			'type' => 'radio',
			'url' => array('controller' => 'articles', 'action' => 'rate'),
			'stars' => 2);
		$result = $this->Rating->display($options);
		$expected ='<div class="input radio"><input type="radio" name="data[rating]" id="rating1" value="1" /><label for="rating1">1</label><input type="radio" name="data[rating]" id="rating2" value="2" /><label for="rating2">2</label></div>';
		$this->assertEquals($result, $expected);

		$options = array(
			'item' => '42',
			'type' => 'radio',
			'url' => array('controller' => 'articles', 'action' => 'rate'),
			'stars' => 2);
		$result = $this->Rating->display($options);

		$expected ='<div class="input radio"><input type="radio" name="data[rating]" id="rating1" value="1" /><label for="rating1">1</label><input type="radio" name="data[rating]" id="rating2" value="2" /><label for="rating2">2</label></div>';
		$this->assertEquals($result, $expected);
	}

/**
 * (non-PHPdoc)
 * @see cake/tests/lib/CakeTestCase#endTest($method)
 */
	public function tearDown() {
		parent::tearDown();
		unset($this->Rating);
		ClassRegistry::flush();
	}
}
