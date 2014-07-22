Ratable Behavior
================

Behavior configuration
----------------------

* **modelClass:** Must be set in the case of a plugin model to make the behavior work with plugin models like `Plugin.Model`. Required to define for plugin's models, and for model of app which name does not equal to class name.
* **saveToField:** Boolean, true if the calculated result should be saved in the rated model
* **calculation:** `average` or `sum`, default is average.
* **update:** Boolean flag, that define permission to re-rate (change previous rating)
* **modelValidate:** Validate the model before save, default is false
* **modelCallbacks:** Run model callbacks when the rating is saved to the model, default is false

The following options provide common defaults that, in most cases, need not be redefined:

* **rateClass:** Name of the rate class model, by default is `Ratings.Rating`.
* **foreignKey:** Foreign key field, contains rated model id.
* **field:** Name of the field that is updated with the calculated rating,
* **fieldSummary:** Optional cache field that will store summary of all ratings that allow to implement quick rating calculation,
* **fieldCounter:** Optional cache field that will store count of all ratings that allow to implement quick rating calculation.

Behavior callbacks
------------------

The `beforeRate()` and `afterRate()` callbacks are currently supported, and are defined in the rated model. These are called before and after the rate operation respectively.

The `beforeRate()` callback takes one $data parameter that is an array containing:

* **foreignKey:** Rated object id.
* **userId:** Rated user id.
* **value:** Rating value.
* **type:** Rating mode: saveRating or removeRating.

The `afterRate()` callback takes one $data parameter that is an array containing:

* **foreignKey:** Rated object id.
* **userId:** Rated user id.
* **value:** Rating value.
* **type:** Rating mode: saveRating or removeRating.
* **result:** New rating value.
* **update:** Update mode value based on configuration.
* **oldRating:** Previous rating state.

The Provided API is:

* **saveRating($foreignKey, $userId, $value):** Allow to add new rating.
* **removeRating($foreignKey, $userId):** Allow to remove undesired rating.
* **rate($foreignKey, $userId, $rating, $options):** Allow to rate against not numeric values like ```up``` / ```down``` that are defined in the ```$options``` array.
* **isRatedBy($foreignKey, $userId):** Acheck method that user already rate defined model object.
* **cacheRatingStatistics($data):** Caches the sum of the different ratings for each of them if fields with database structure contain fields ```rating_{$value}```.