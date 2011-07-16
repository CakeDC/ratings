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
 * Callback
 *
 * @param object Controller object
 */
	public function initialize(&$controller, $settings = array()) {
		$this->controller = $controller;
 		if ($this->enabled == true) {
			foreach ($settings as $setting => $value) {
				if (isset($this->{$setting})) {
					$this->{$setting} = $value;
				}
			}
			$this->controller->params['isJson'] = (isset($this->controller->params['url']['ext']) && $this->controller->params['url']['ext'] === 'json');
			if ($this->controller->params['isJson']) {
				Configure::write('debug', 0);
			}
			if (empty($this->modelName)) {
				$this->modelName = $controller->modelClass;
			}
			if (!$controller->{$this->modelName}->Behaviors->attached('Ratable')) {
				$controller->{$this->modelName}->Behaviors->attach('Ratings.Ratable', $settings);
			}
			$controller->helpers[] = 'Ratings.Rating';
		}
	}

/**
 * Callback
 *
 * @param object Controller object
 */
	public function startup(&$controller) {
		$message = '';
		$rating = null;
		$params = $controller->params['named'];
		if (empty($params['rating']) && !empty($controller->data[$controller->modelClass]['rating'])) {
			$params['rating'] = $controller->data[$controller->modelClass]['rating'];
			
		}
		if (!method_exists($controller, 'rate')) {
			if (isset($params['rate']) && isset($params['rating']) && $this->enabled == true) {
				$this->rate($params['rate'], $params['rating'], $this->Auth->user('id'), !empty($params['redirect']));
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
		$controller = $this->controller;
		$controller->{$this->modelName}->id = $rate;
		if ($controller->{$this->modelName}->exists(true)) {
			if ($controller->{$this->modelName}->saveRating($rate, $user, $rating)) {
				$rating = round($controller->{$this->modelName}->newRating);
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
		$this->controller->set($result);
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
		$params = array('plugin' => $this->controller->params['plugin'], 'controller' => $this->controller->params['controller'],  'action' => $this->controller->params['action']);
		$params = array_merge($params, $this->controller->params['pass']);
		foreach ($this->controller->params['named'] as $name => $value) {
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
		if (!empty($this->controller->viewVars['authMessage']) && !empty($this->controller->params['isJson'])) {
			$this->RequestHandler->renderAs($this->controller, 'json');
			$this->set('message', $this->controller->viewVars['authMessage']);
			$this->set('status', 'error');
			echo $this->controller->render('rate');
			$this->_stop();
		} elseif (!empty($this->viewVars['authMessage'])) {
			$this->Session->setFlash($this->viewVars['authMessage']);
		}
		if (!empty($this->controller->params['isAjax']) || !empty($this->controller->params['isJson'])) {
			$this->controller->setAction('rated', $this->controller->params['named']['rate']);
			return $this->controller->render('rated');
		} else if (isset($this->controller->viewVars['status']) && isset($this->controller->viewVars['message'])) {
			$this->controller->Session->setFlash($this->controller->viewVars['message'], 'default', array(), $this->controller->viewVars['status']);
		}

		$this->controller->redirect($url, $code, $exit);
	}
}
