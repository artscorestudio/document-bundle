# Event system in controllers

An events system is fired in Document Bundle for allows you to customize treatment of submitted datas in forms.

Imagine you add an additionnal field in ASFDocumentBundle Page Form Type. The first step is to override this forms like explain in [Overriding Default ASFDocumentBundle Forms][1].

```yaml
asf_document:
    page:
        form:
            type: Acme\DocumentBundle\Form\Type\PageType
            name: acme_page_type
```

```php
<?php
namespace Acme\DocumentBundle\Form\Type\PageType;

class PageType extends AbstractType
{
    // [...]
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('extras', NumberType::class, array(
			'label' => 'Extras',
			'required' => true
		));
	}
	// [...]
}
```

After that, you want to check if extra is really extra in the controller. With the event system in place, no need to override controller. Simply create a Listener or an EventSubscriber to add your own logic :

```php
namespace Acme\DocumentBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ASF\DocumentBundle\Event\DocumentEvents;
use ASF\DocumentBundle\Event\PageEvent;

class DocumentSubscriber extends EventSubscriberInterface
{
	/**
	 * Subscribed Events
	 */
	public static function getSubscribedEvents()
	{
		return array(
			DocumentEvents::PAGE_EDIT_SUCCESS => array('onPageEditSuccess', 0)
		);
	}
	
	public function onPageEditSuccess(PageEvent $event)
	{
		$page = $event->getPage();
		// Your own logic here
	}
}
```

Register your EventSubscriber like a service :

```xml
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

	<parameters>
	
    	<parameter key="acme_document.menu.subscriber.class">Acme\DocumentBundle\Event\DocumentSubscriber</parameter>
		
	</parameters>
	
	<services>
		<service id="acme_document.menu.subscriber" class="%acme_document.menu.subscriber.class%">
        	<tag name="kernel.event_subscriber" />
        </service>
	</services>

</container>
```

[1]: forms.md 