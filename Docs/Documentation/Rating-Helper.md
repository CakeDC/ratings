Rating Helper
=============

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

This generated form will be compatible with jQuery UI Stars. This jQuery plugin is included in the plugins webroot folder.

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

Ajax support
------------

If url finished with ".json" extension then response should generate json object instead of page redirect.

The json object contains the following structure:

```json
{
	"status": "success",
	"data": {
		"message": "Result message"
	}
}
```

There is a sample json layout included in the ratings plugin, but views need to be implement for each rate action.

Helper methods
--------------

* **display():** Displays a bunch of rating links wrapped into a list element of your choice.
* **bar($value, $total, $options):** Bar rating.
* **starForm($options, $urlHtmlAttributes):** Displays a star form.
