Quick Start
===========

Make sure that you application is using the auth component, the plugin won't work properly without the auth component.

For this example we assume that we want to rate postings. We will cover here only the *very* basics of getting the plugin to work.

```php
class PostsController extends AppController {

	public $components = array(
		'Ratings.Ratings'
	);

	public function view($postId = null) {
		if (!$this->Post->exists($id)) {
			throw new NotFoundException(__('Invalid post'));
		}
		$options = array('conditions' => array('Post.' . $this->Post->primaryKey => $id));
		$this->set('post', $this->Post->find('first', $options));
		$this->set('isRated', $this->Post->isRatedBy($id, $this->Auth->user('id')));
	}
}
```

All you have to do is to add the ratings component to your controllers component array, this will already make ratings work and load the behavior for the controllers current `$modelClass` and also load the helper.

This line

```php
$this->set('isRated', $this->Post->isRatedBy($id, $this->Auth->user('id')));
```

is not required but shows you how you can check if the current record was already rated for the current logged in user.

In your ```view.ctp``` add this.

```php
if ($isRated === false) {
	echo $this->Rating->display(array(
		'item' => $post['Post']['id'],
		'url' => array($post['Post']['id'])
	));
} else {
	echo __('You have already rated.');
}
```

The RatingHelper::display() method needs two options, the `item` to rate and the target `url`. The `item` is the id of the record you want to rate. The `url` will take by default the current URL but you'll have to additional parameters to it. In our case we want to go back to the view we're currently on so we need to pass the post record id here as well.
