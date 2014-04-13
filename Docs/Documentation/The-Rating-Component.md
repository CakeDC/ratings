The Rating Component
--------------------

As the component starts up, it attaches the Ratable behavior to the default controller model.

It will handle the `rate`, `rating`, and `redirect` query parameters.

When rate and rating parameters passed and current controller action is inside $actionNames list (that by default contain 'view' action only), the 'rate' action of component is executed. The rate action possible to redifine in controller, if needed some specific interaction during rate process.

Component settings
------------------

* **modelClass:** Must be set in the case of a plugin model to make the behavior work with plugin models like 'Plugin.Model'
* **rateClass:** Name of the rate class model
* **foreignKey:** Foreign key field
* **saveToField:** Boolean, true if the calculated result should be saved in the rated model
* **field:** Name of the field that is updated with the calculated rating
* **fieldSummary:** Optional cache field that will store summary of all ratings that allow to implement quick rating calculation
* **fieldCounter:** Optional cache field that will store count of all ratings that allow to implement quick rating calculation
* **calculation:** `average` or `sum`, default is average
* **update:** boolean flag, that define permission to rerate(change previous rating)
* **modelValidate:** Validate the model before save, default is false
* **modelCallbacks:** Run model callbacks when the rating is saved to the model, default is false
