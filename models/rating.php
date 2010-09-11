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
 * Rating model
 *
 * @package 	ratings
 * @subpackage 	ratings.models
 */
class Rating extends RatingsAppModel {

/**
 * Name
 *
 * @var string $name
 */
	public $name = 'Rating';

/**
 * Validation rules
 *
 * @var array $validate
 */
	public $validate = array();

/**
 * Constructor
 *
 * Set the translateable validation messages in the constructor.
 *
 * @return void
 */
	public function __construct($id = false, $table = null, $ds = null) {
		$userClass = Configure::read('App.UserClass');
		if (empty($userClass)) {
			$userClass = 'User';
		}

		$this->belongsTo['User'] = array(
			'className' => $userClass,
			'foreignKey' => 'user_id');
		parent::__construct($id, $table, $ds);
		$rules = array(
			'notEmpty' => array(
				'required' => true,
				'rule' => 'notEmpty'));

		$this->validate = array(
			'user_id' => array(
				'required' => $rules['notEmpty']),
			'model' => array(
				'required' => $rules['notEmpty']),
			'foreign_key' => array(
				'required' => $rules['notEmpty']),
			'value' => array(
				'required' => $rules['notEmpty']));
	}
}
