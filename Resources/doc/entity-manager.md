# ASFDocumentBundle embedded Entity Manager

If you want to use DocumentBundle has standalone bundle, you have to know its particularities :

## The bundle's entities

All entities are managed throught their corresponding Entity Managers. This allows to build forms without an hardcoded dependency. For example, the entity *Page* have a form *PageType*. Without Entity Manager, we pass the entity class name like this :

```php
<?php
namespace ASF\DocumentBundle\Form\Type;

class PageType extends AbstractType
{
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'ASF\DocumentBundle\Entity\Page',
			'translation_domain' => 'asf_document',
		));
	}
}
```

But the DocumentBundle provides you models for your entities but it is not possible to persist entities in vendor. So, you have to change the data_class parameter. The example below is one of ways for do this. Whatever the way you take, you have to recode a part of the bundle.

```php
<?php
namespace Acme\DocumentBundle\Form\Type;

use ASF\DocumentBundle\Form\Type\PageType as BaseFormType;

class PageType extends BaseFormType
{
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Acme\DocumentBundle\Entity\Page',
			'translation_domain' => 'acme_document',
		));
	}
}
```

To avoid rewriting everything, and for a quick start to use bundle, the forms use Entity Managers :

```php
<?php
namespace Acme\DocumentBundle\Form\Type;

use ASF\DocumentBundle\Form\Type\PageType as BaseFormType;
use ASF\DocumentBundle\Entity\Manager\ASFDocumentEntityManagerInterface;

class PageType extends BaseFormType
{
	/**
     * @param ASFDocumentEntityManagerInterface $pageManager
     */
    public function __construct(ASFDocumentEntityManagerInterface $pageManager)
    {
        $this->pageManager = $pageManager;
    }
    
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => $this->pageManager->getClassName(),
			'translation_domain' => 'asf_document',
		));
	}
}
```

All entity managers provided in this bundle are based on [ASFEntityManager provided by ASFCoreBundle](https://github.com/artscorestudio/core-bundle/blob/master/Resources/doc/entity-manager.md). If you do not want to install ASFCoreBUndle, you have to create your own Entity Manager implementing the ASFDocumentEntityManagerInterface and overriding the Page Entity Manager class parameter.

### Default Page Entity Manager
```xml
<!-- @ASFDocumentBundle/Resources/config/services/page.xml -->
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

	<parameters>
    	
    	<!-- Generic Entity Manager -->
    	<parameter key="asf_document.entity.manager.class">ASF\DocumentBundle\Entity\Manager\ASFDocumentEntityManager</parameter>

		<!-- Person Manager -->
		<parameter key="asf_document.page.entity.class">ASF\DocumentBundle\Entity\Page</parameter>
    	
    </parameters>

    <services>
    
        <!-- Page Entity Manager -->
        <service id="asf_document.page.manager" class="%asf_document.entity.manager.class%">
            <tag name="asf_core.manager" entity="%asf_document.page.entity.class%" />
        </service>
        
    </services>
    
</container>
```

If you take a look on the parameter *asf_document.entity.manager.class*, its default value is *ASF\DocumentBundle\Entity\Manager\ASFDocumentEntityManager*) :

```php
<?php
namespace ASF\DocumentBundle\Entity\Manager;

use ASF\CoreBundle\Entity\Manager\ASFEntityManager;

class ASFDocumentEntityManager extends ASFEntityManager implements ASFDocumentEntityManagerInterface {}
```

You can see that this default parameter implements *ASFDocumentEntityManagerInterface*, yours should be the same.

### Using defaults

If you use DocumentBundle with its default configuration and services. When you extends the bundle and define persisted entities, you just have to override  *asf_document.page.entity.class* with the name of your entity :

```xml
<!-- @AcmeDocumentBundle/Resources/config/services.xml -->
<?xml version="1.0" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

	<parameters>

		<!-- Person Manager -->
		<parameter key="asf_document.page.entity.class">Acme\DocumentBundle\Entity\Page</parameter>
    	
    </parameters>
    
</container>
```

You can also do it in bundle extension class :

```php
<?php
// @AcmeDocumentBundle/DependencyInjection/AcmeDocumentExtension.php
namespace Acme\DocumentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AcmeDocumentExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
	    $config = $this->processConfiguration($configuration, $configs);
		// [...]
	    $container->setParameter('asf_document.page.entity.class', 'Acme\DocumentBundle\Entity\Page');
	   // [...]
    }
}
```
