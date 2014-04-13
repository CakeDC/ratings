<?php
/**
 * Copyright 2010 - 1013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 1013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * CakePHP Ratings Plugin
 *
 * Rating helper
 *
 * @package 	ratings
 * @subpackage 	ratings.views.helpers
 */
class RatingHelper extends AppHelper {

/**
 * helpers variable
 *
 * @var array
 */
	public $helpers = array(
		'Html',
		'Form',
		'Js' =>
		'Jquery'
	);

/**
 * Allowed types of html list elements
 *
 * @var array $allowedTypes
 */
	public $allowedTypes = array(
		'ul',
		'ol',
		'radio'
	);

/**
 * Default settings
 *
 * @var array $defaults
 */
	public $defaults = array(
		'stars' => 5,
		'item' => null,
		'value' => 0,
		'type' => 'ul',
		'createForm' => false,
		'url' => array(),
		'link' => true,
		'redirect' => true,
		'class' => 'rating'
	);

/**
 * Displays a bunch of rating links wrapped into a list element of your choice
 *
 * @param array $options
 * @param array $urlHtmlAttributes Attributes for the rating links inside the list
 * @throws Exception
 * @return string markup that displays the rating options
 */
	public function display($options = array(), $urlHtmlAttributes = array()) {
		$options = array_merge($this->defaults, $options);
		if (empty($options['item'])) {
			throw new Exception(__d('ratings', 'You must set the id of the item you want to rate.'), E_USER_NOTICE);
		}

		if ($options['type'] === 'radio') {
			return $this->starForm($options, $urlHtmlAttributes);
		}

		if (!isset($options['named'])) {
			$options['named'] = true;
		}

		$stars = null;
		for ($i = 1; $i <= $options['stars']; $i++) {
			$link = null;
			if ($options['link'] === true) {
				if ($options['named'] === true) {
					$url = array_merge($options['url'], array('rate' => $options['item'], 'rating' => $i));
					if ($options['redirect']) {
						$url['redirect'] = 1;
					}
				} else {
					$url = array_merge($options['url'], array('?' => array('rate' => $options['item'], 'rating' => $i)));
					if ($options['redirect']) {
						$url['?']['redirect'] = 1;
					}
				}
				$link = $this->Html->link($i, $url, $urlHtmlAttributes);
			}
			$stars .= $this->Html->tag('li', $link, array('class' => 'star' . $i));
		}

		if (in_array($options['type'], $this->allowedTypes)) {
			$type = $options['type'];
		} else {
			$type = 'ul';
		}

		$stars = $this->Html->tag($type, $stars, array('class' => $options['class'] . ' ' . 'rating-' . round($options['value'], 0)));
		return $stars;
	}

/**
 * Bar rating
 *
 * @param integer value
 * @param integer total amount of rates
 * @param array options
 * @return string
 */
	public function bar($value = 0, $total = 0, $options = array()) {
		$defaultOptions = array(
			'innerClass' => 'inner',
			'innerHtml' => '<span>%value%</span>',
			'innerOptions' => array(),
			'outerClass' => 'barRating',
			'outerOptions' => array(),
			'element' => null);
		$options = array_merge($defaultOptions, $options);

		$percentage = $this->percentage($value, $total);

		if (!empty($options['element'])) {
			return $this->_View->element($options['element'], array(
				'value' => $value,
				'percentage' => $percentage,
				'total' => $total)
			);
		}

		$options['innerOptions']['style'] = 'width: ' . $percentage . '%';
		$innerContent = str_replace('%value%', $value, $options['innerHtml']);
		$innerContent = str_replace('%percentage%', $percentage, $innerContent);
		$inner = $this->Html->div($options['innerClass'], $innerContent, $options['innerOptions']);

		return $this->Html->div($options['outerClass'], $inner, $options['outerOptions']);
	}

/**
 * Calculates the percentage value
 *
 * @param integer value
 * @param integer total amount
 * @param integer precision of rounding
 * @return mixed float or integer based on the precision value
 */
	public function percentage($value = 0, $total = 0, $precision = 2) {
		if ($total) {
			return (round($value / $total, $precision) * 100);
		}
		return 0;
	}

/**
 * Displays a star form
 *
 * @param array $options
 * @param array $urlHtmlAttributes Attributes for the rating links inside the list
 * @return string markup that displays the rating options
 */
	public function starForm($options = array(), $urlHtmlAttributes = array()) {
		$options = array_merge($this->defaults, $options);
		$flush = false;
		if (empty($options['item'])) {
			trigger_error(__d('ratings', 'You must set the id of the item you want to rate.'), E_USER_NOTICE);
		}
		$result = '';
		if ($options['createForm']) {
			$result .= $this->Form->create($options['createForm']) . "\n";
		}
		$inputField = 'rating';
		if (!empty($options['inputField'])) {
			$inputField = $options['inputField'];
		}
		$result .= $this->Form->input($inputField, array(
			'type' => 'radio',
			'legend' => false,
			'value' => isset($options['value']) ? round($options['value']) : 0,
			'options' => array_combine(range(1, $options['stars']), range(1, $options['stars'])))
		);
		if ($options['createForm']) {
			if (!empty($options['target']) && !empty($options['createForm']['url']) && !empty($options['createForm']['ajaxOptions'])) {
				$result .= $this->Js->submit(__d('ratings', 'Rate!'), array_merge(array('url' => $options['createForm']['url']), $options['createForm']['ajaxOptions'])) . "\n";
				$flush = true;
			} else {
				$result .= $this->Form->submit(__d('ratings', 'Rate!')) . "\n";
			}
			$result .= $this->Form->end() . "\n";
			if ($flush) {
				$result .= $this->Js->writeBuffer();
			}
		}
		return $result;
	}
}