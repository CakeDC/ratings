The Ratable Behavior
====================

Behavior configuration
----------------------

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

Behavior callbacks
------------------

The `beforeRate` and `afterRate` callbacks are currently supported, and are defined in the rated model. These are called before and after the rate operation respectively.

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