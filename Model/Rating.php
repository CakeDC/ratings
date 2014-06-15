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
App::uses('RatingsAppModel', 'Ratings.Model');

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
 * @param bool $id
 * @param mixed $table
 * @param mixed $ds
 * @return \Rating Rating
 */
	public function __construct($id = false, $table = null, $ds = null) {
		$userClass = Configure::read('App.UserClass');
		if (empty($userClass)) {
			$userClass = 'User';
		}

		$this->belongsTo['User'] = array(
			'className' => $userClass,
			'foreignKey' => 'user_id'
		);

		parent::__construct($id, $table, $ds);

		$rules = array(
			'notEmpty' => array(
				'required' => true,
				'rule' => 'notEmpty'
			)
		);

		$this->validate = array(
			'user_id' => array(
				'required' => $rules['notEmpty']),
			'model' => array(
				'required' => $rules['notEmpty']),
			'foreign_key' => array(
				'required' => $rules['notEmpty']),
			'value' => array(
				'required' => $rules['notEmpty']
			)
		);
	}
}
