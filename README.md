V3 Labs Doctrine ORM Extensions
===============================

I am aware that there are several other libraries like this, but in each of them there are things I don't like.

This is a work in progress!

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
