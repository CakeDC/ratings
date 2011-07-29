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
 * Ratings component
 *
 * @package 	ratings
 * @subpackage 	ratings.controllers.components
 */
App::uses('Component', 'Controller');

class RatingsComponent extends Component {

/**
 * Components that are required
 *
 * @var array $components
 */
	public $components = array('Cookie', 'Session', 'Auth', 'RequestHandler');

/**
 * Enabled / disable the component
 *
 * @var boolean
 */
	public $enabled = true;

/**
 * Name of actions this component should use
 *
 * Customizable in beforeFilter()
 *
 * @var array $actionNames
 */
	public $actionNames = array('view');

/**
 * Name of 'rateable' model
 *
 * Customizable in beforeFilter(), or default controller's model name is used
 *
 * @var string $modelName
 */
	public $modelName = null;

/**
 * Name of association for ratings
 *
 * Customizable in beforeFilter()
 *
 * @var string $assocName
 */
	public $assocName = 'Rating';

/**
 * List of named args used in component
 *
 * @var array $parameters
 */
	public $parameters = array('rate' => true, 'rating'=> true, 'redirect' => true);

/**
 * Constructor. 
 *
 * @throws CakeException when Acl.classname could not be loaded.
 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
 		if ($this->enabled == true) {
			foreach ($settings as $setting => $value) {
				if (isset($this->{$setting})) {
					$this->{$setting} = $value;
				}
			}
		}
	} 	
/**
 * Callback
 *
 * @param object Controller object
 */
	public function initialize(&$Controller) {
	//public function initialize(&$Controller, $settings = array()) {
		$this->Controller = $Controller;
 		if ($this->enabled == true) {
			$this->Controller->request->params['isJson'] = (isset($this->Controller->request->params['url']['ext']) && $this->Controller->request->params['url']['ext'] === 'json');
			if ($this->Controller->request->params['isJson']) {
				Configure::write('debug', 0);
			}
			if (empty($this->modelName)) {
				$this->modelName = $Controller->modelClass;
			}
			if (!$Controller->{$this->modelName}->Behaviors->attached('Ratable')) {
				$Controller->{$this->modelName}->Behaviors->load('Ratings.Ratable', $this->settings);
			}
			$Controller->helpers[] = 'Ratings.Rating';
		}
	}

/**
 * Callback
 *
 * @param object Controller object
 */
	public function startup(&$Controller) {
		$message = '';
		$rating = null;
		$params = $Controller->request->params['named'];
		if (empty($params['rating']) && !empty($Controller->request->data[$Controller->modelClass]['rating'])) {
			$params['rating'] = $Controller->request->data[$Controller->modelClass]['rating'];
		}
		if (!method_exists($Controller, 'rate')) {
			if (isset($params['rate']) && isset($params['rating']) && $this->enabled == true) {
				$this->rate($params['rate'], $params['rating'], $Controller->Auth->user('id'), !empty($params['redirect']));
			}
		}
	}

/**
 * Adds as user rating for a model record
 *
 * @param string $rate the model record id
 * @param string $rating
 * @param mixed $redirect boolean to redirect to same url or string or array to use it for Router::url()
 */
	public function rate($rate, $rating, $user, $redirect = false) {
		$Controller = $this->Controller;
		$Controller->{$this->modelName}->id = $rate;
		if ($Controller->{$this->modelName}->exists(true)) {
			if ($Controller->{$this->modelName}->saveRating($rate, $user, $rating)) {
				$rating = round($Controller->{$this->modelName}->newRating);
				$message = __d('ratings', 'Your rate was successfull.');
				$status = 'success';
			} else {
				$message = __d('ratings', 'You have already rated.');
				$status = 'error';
			}
		} else {
			$message = __d('ratings', 'Invalid rate.');
			$status = 'error';
		}
		$result = compact('status', 'message', 'rating');
		$this->Controller->set($result);
		if (!empty($redirect)) {
			if (is_bool($redirect)) {
				$this->redirect($this->buildUrl());
			} else {
				$this->redirect($redirect);
			}
		} else {
			return $result;
		}
	}

/**
 * Clean url from rating parameters
 *
 * @return array
 */
	public function buildUrl() {
		$params = array('plugin' => $this->Controller->request->params['plugin'], 'controller' => $this->Controller->request->params['controller'],  'action' => $this->Controller->request->params['action']);
		$params = array_merge($params, $this->Controller->request->params['pass']);
		foreach ($this->Controller->request->params['named'] as $name => $value) {
			if (!isset($this->parameters[$name])) {
				$params[$name] = $value;
			}
		}
		return $params;
	}

/**
 * Overload Redirect.  Many actions are invoked via Xhr, most of these
 * require a list of current favorites to be returned.
 *
 * @param string $url
 * @param unknown $code
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $code = null, $exit = true) {
		if (!empty($this->Controller->viewVars['authMessage']) && !empty($this->Controller->request->params['isJson'])) {
			$this->RequestHandler->renderAs($this->Controller, 'json');
			$this->set('message', $this->Controller->viewVars['authMessage']);
			$this->set('status', 'error');
			echo $this->Controller->render('rate');
			$this->_stop();
		} elseif (!empty($this->viewVars['authMessage'])) {
			$this->Session->setFlash($this->viewVars['authMessage']);
		}
		if (!empty($this->Controller->request->params['isAjax']) || !empty($this->Controller->request->params['isJson'])) {
			$this->Controller->setAction('rated', $this->Controller->request->params['named']['rate']);
			return $this->Controller->render('rated');
		} else if (isset($this->Controller->viewVars['status']) && isset($this->Controller->viewVars['message'])) {
			$this->Controller->Session->setFlash($this->Controller->viewVars['message'], 'default', array(), $this->Controller->viewVars['status']);
		}

		$this->Controller->redirect($url, $code, $exit);
	}
}
