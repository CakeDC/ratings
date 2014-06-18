<?php
/**
 * CakePHP Ratings
 *
 * Copyright 2009 - 2014, Cake Development Corporation
 *                        1785 E. Sahara Avenue, Suite 490-423
 *                        Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright 2009 - 2014, Cake Development Corporation
 * @link      http://github.com/CakeDC/Ratings
 * @package   plugsin.ratings
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Short description for class.
 *
 * @package		ratings
 * @subpackage	config.migrations
 */

class InitializeRatingsSchema extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Initialize Ratings Schema';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'ratings' => array(
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
				)
			)
		),
		'down' => array(
			'drop_table' => array('ratings')
		)
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}

}
