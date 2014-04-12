Installation
============

To create tables you can use migrations plugin or schema shell. To create the tables via schema shell execute:

```cake schema create --plugin Ratings --name ratings```

To create the tables via the migrations plugin run:

```cake migrations.migration run all -p ratings```

Make sure the plugin is loaded in the application.

```php
CakePlugin::load('Ratings');
// or
CakePlugin::loadAll();
```