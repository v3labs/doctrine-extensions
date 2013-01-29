V3 Labs Doctrine ORM Extensions
===============================

This library aims to be a minimalistic set of Doctrine 2 ORM extensions for php >=5.4. I'm developing this library for a project in my company. I am using it in production. The API is in no way stable(yet). More extensions will be added. I will try to keep the docs updated. Any help will be appreciated.

Timestampable
-------------

First you must register the timestampable event listener:

``` php

<?php

use V3labs\DoctrineExtensions\ORM\Timestampable\TimestampableListener;

// ...

$em->getEventManager()->addEventSubscriber(new TimestampableListener);

```

If you are a Symfony2 user you can add this to your config.yaml:

``` yaml
services:
  v3labs.doctrine_extensions.timestampable_listener:
    class:   "V3labs\DoctrineExtensions\ORM\Timestampable\TimestampableListener"
    public:  false
    tags:
       - { name: doctrine.event_subscriber }
```

An entity using this extension would loke like this:

``` php
<?php

namespace Some\Namespace;

use Doctrine\ORM\Mapping as ORM;
use V3labs\DoctrineExtensions\ORM\Timestampable\Timestampable;

/**
 * Event
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Event
{
    use Timestampable;

    // Rest of your entity
}
    
```
