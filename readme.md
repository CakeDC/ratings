# Rating plugin for CakePHP #

The ratings plugin will allow you by simply adding the ratings component to your controller to rate anyting. The component will auto load a helper and behavior.

The core part of this plugin is the ratable behavior that is attached to your models.
In most cases you don't need attach it yourself, because the Rating component will take care of it.

## Requirements ##

In order to create the tables required, you will need the [CakeDC Migrations Plugin](http://github.com/CakeDC/migrations), which is available under the MIT license.

## Installation ##

Place the ratings folder into any of your plugin directories for your app (for example app/plugins or cake/plugins)

Create database tables using either the schema shell or the migrations plugin:

	cake migration run all -plugin ratings

Attach the Ratable behavior to your models via the $actsAs variable or dynamically using the BehaviorsCollection object methods:

	public $actsAs = array('Ratings.Ratable');

or

	this->Behaviors->attach('Ratings.Ratable');

or just load 'Ratings.Ratings' component in the controller.

	public $components = array('Ratings.Ratings');
		
## Usage ##

Add the Rating helper to you controller

	public $helpers = array('Ratings.Rating');

Use the helper in your views to generate links mark a model record as favorite

	<?php 
	echo $this->Rating->display(array(
		'item' => $post['Post']['id'],
		'type' => 'radio',
		'stars' => 5,
		'value' => $item['rating'],
		'createForm' => array('url' => array($this->passedArgs, 'rate' => $item['id'], 'redirect' => true))));
	?>

This generated form will generate form compatible with [jQuery UI Stars](http://plugins.jquery.com/project/Star_Rating_widget).

Here is the sample of js that will stylize the form:

	$('#ratingform').stars({
		split:2,
		cancelShow:false,
		callback: function(ui, type, value) {
			ui.$form.submit();
		}
	});

## Behavior configuration  ##

* **modelClass**     - must be set in the case of a plugin model to make the behavior work with plugin models like 'Plugin.Model'. Required to define for plugin's models, and for model of app which name does not equal to class name,
* **saveToField**    - boolean, true if the calculated result should be saved in the rated model
* **calculation**    - 'average' or 'sum', default is average.
* **update**         - boolean flag, that define permission to rerate(change previous rating)
* **modelValidate**  - validate the model before save, default is false
* **modelCallbacks** - run model callbacks when the rating is saved to the model, default is false

The following options provide common defaults that, in most cases, need not be redefined:
 
* **rateClass**      - name of the rate class model, by default is 'Ratings.Rating'.
* **foreignKey**     - foreign key field, contains rated model id.
* **field**          - name of the field that is updated with the calculated rating,
* **fieldSummary**   - optional cache field that will store summary of all ratings that allow to implement quick rating calculation,
* **fieldCounter**   - optional cache field that will store count of all ratings that allow to implement quick rating calculation.

### Behavior callbacks  ##

The `beforeRate` and `afterRate` callbacks are currently supported, and are defined in the rated model.
These are called before and after the rate operation respectively.

BeforeRate callback get one $data parameter that is a array containing:

* **foreignKey** - rated object id
* **userId**     - rated user id
* **value**      - rating value
* **type**       - rating mode: saveRating or removeRating

AfterRate callback get one $data parameter that is a array containing:

* **foreignKey** - rated object id
* **userId**     - rated user id
* **value**      - rating value
* **type**       - rating mode: saveRating or removeRating
* **result**     - new rating value
* **update**     - update mode value based on configuration
* **oldRating**  - previous rating state

Provided API is:

* **saveRating($foreignKey, $userId, $value)**      - allow to add new rating.
* **removeRating($foreignKey, $userId)**            - allow to remove undesired rating.
* **rate($foreignKey, $userId, $rating, $options)** - allow to rate agains not numeric values like 'up'/'down' that defined in $options array.
* **isRatedBy($foreignKey, $userId)**               - check method that user already rate defined model object
* **cacheRatingStatistics($data)**                  - Caches the sum of the different ratings for each of them if fields with database structure contain fields rating_{$value}.
 
## Rating Component  ##

As the component starts up, it attaches the Ratable behavior to the default controller model.
It handle 'rate', 'rating', and 'redirect' ratings parameters.
When rate and rating parameters passed and current controller action is inside $actionNames list (that by default contain 'view' action only), the 'rate' action of component is executed. The rate action possible to redifine in controller, if needed some specific interaction during rate process.

### Component settings  ###

* **modelClass**      - must be set in the case of a plugin model to make the behavior work with plugin models like 'Plugin.Model'
* **rateClass**       - name of the rate class model
* **foreignKey**      - foreign key field
* **saveToField**     - boolean, true if the calculated result should be saved in the rated model
* **field**           - name of the field that is updated with the calculated rating
* **fieldSummary**    - optional cache field that will store summary of all ratings that allow to implement quick rating calculation
* **fieldCounter**    - optional cache field that will store count of all ratings that allow to implement quick rating calculation 
* **calculation**     - 'average' or 'sum', default is average
* **update**          - boolean flag, that define permission to rerate(change previous rating)
* **modelValidate**   - validate the model before save, default is false
* **modelCallbacks**  - run model callbacks when the rating is saved to the model, default is false
 
 
## Ajax support ##

If url finished with ".json" extension then response should generate json object instead of page redirect.
Object contains the following structure:

	{
		"status": "success",
		"data": {
			"message": "Result message"
		}
	}

There is a sample json layout included in the ratings plugin, but views need to implement for each rate action.

## Helper methods ##

* **display()**                              - Displays a bunch of rating links wrapped into a list element of your choice
* **bar($value, $total, $options)**          - Bar rating
* **starForm($options, $urlHtmlAttributes)** - Displays a star form

## Requirements ##

* PHP version: PHP 5.2+
* CakePHP version: Cakephp 1.3 Stable

## Support ##

For support and feature request, please visit the [Ratings Plugin Support Site](http://cakedc.lighthouseapp.com/projects/59751-ratings-plugin/).

For more information about our Professional CakePHP Services please visit the [Cake Development Corporation website](http://cakedc.com).

## License ##

Copyright 2009-2010, [Cake Development Corporation](http://cakedc.com)

Licensed under [The MIT License](http://www.opensource.org/licenses/mit-license.php)<br/>
Redistributions of files must retain the above copyright notice.

## Copyright ###

Copyright 2009-2010<br/>
[Cake Development Corporation](http://cakedc.com)<br/>
1785 E. Sahara Avenue, Suite 490-423<br/>
Las Vegas, Nevada 89104<br/>
http://cakedc.com<br/>
