## Requirements ##

In order to create the tables required, you will need the [CakeDC Migrations Plugin](http://github.com/CakeDC/migrations), which is available under the MIT license.

## Installation ##

Place the ratings folder into any of your plugin directories for your app (for example app/plugins or cake/plugins)

Create database tables using either the schema shell or the migrations plugin:

	cake schema create --plugin Ratings

	cake migration run all --plugin Ratings

Attach the Ratable behavior to your models via the $actsAs variable or dynamically using the BehaviorsCollection object methods:

	public $actsAs = array('Ratings.Ratable');

or

```php
this->Behaviors->attach('Ratings.Ratable');
```

or just load 'Ratings.Ratings' component in the controller.

```php
public $components = array(
	'Ratings.Ratings'
);
```

## Usage ##

Add the Rating helper to you controller

```php
	public $helpers = array(
		'Ratings.Rating'
	);
```

Use the helper in your views to generate links mark a model record as favorite

```php
echo $this->Rating->display(array(
	'item' => $post['Post']['id'],
	'type' => 'radio',
	'stars' => 5,
	'value' => $item['rating'],
	'createForm' => array(
		'url' => array(
			$this->passedArgs, 'rate' => $item['id'],
			'redirect' => true
		)
	)
));
```

This generated form will generate form compatible with [jQuery UI Stars](http://plugins.jquery.com/project/Star_Rating_widget).

Here is the sample of js that will stylize the form:

```js
$('#ratingform').stars({
	split:2,
	cancelShow:false,
	callback: function(ui, type, value) {
		ui.$form.submit();
	}
});
```

## Ajax support ##

If url finished with ".json" extension then response should generate json object instead of page redirect.
Object contains the following structure:

```json
{
	"status": "success",
	"data": {
		"message": "Result message"
	}
}
```

There is a sample json layout included in the ratings plugin, but views need to implement for each rate action.

Helper methods
--------------

* **display()**                              - Displays a bunch of rating links wrapped into a list element of your choice
* **bar($value, $total, $options)**          - Bar rating
* **starForm($options, $urlHtmlAttributes)** - Displays a star form
