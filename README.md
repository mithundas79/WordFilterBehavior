WordFilterBehavior
==================

This is a CakePHP behavior that sends an email any time the word bomb is present in the data to be saved.

Usage
==================

Need to add the following in Model:

```php
var $actsAs = array(
                        'WordFilter'=>array(
                                        'fields'=>array('username', 'role'),
                                        'type'=>'save'
                        )
        );
```
Also change the email to your email in the notify function

```php

$Email->to('global.tester.mitz@gmail.com');

```