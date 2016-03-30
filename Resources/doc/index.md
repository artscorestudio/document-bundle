# Artscore Studio Document Bundle

Document Bundle is a Symfony 2/3 bundle for create and manage documents in your Symfony 2/3 application. This package is a part of Artscore Studio Framework.

What is a document in this bundle ? A document can be an HTML page, a PDF, an XML file, etc.

> IMPORTANT NOTICE: This bundle is still under development. Any changes will be done without prior notice to consumers of this package. Of course this code will become stable at a certain point, but for now, use at your own risk.
 
## Prerequisites

This version of the bundle requires :
* Symfony 2.8+/3.0+

### Translations

If you wish to use default texts provided in this bundle, you have to make sure you have translator enabled in your config.

```yaml
# app/config/config.yml
framework:
    translator: ~
```

For more information about translations, check [Symfony documentation](https://symfony.com/doc/current/book/translation.html).

## Installation

### Step 1 : Download ASFDocumentBundle using composer

Require the bundle with composer :

```bash
$ composer require artscorestudio/document-bundle "dev-master"
```

Composer will install the bundle to your project's *vendor/artscorestudio/document-bundle* directory. It also install dependencies. 

### Step 2 : Enable the bundle

Enable the bundle in the kernel :

```php
// app/AppKernel.php

public function registerBundles()
{
	$bundles = array(
		// ...
		new ASF\DocumentBundle\ASFDocumentBundle()
		// ...
	);
}
```

### Step 3 : Configure the bundle

If you want to use all the features provided by the bundle, you can configure it like the following :

```yaml
# app/config/config.yml
asf_document:
    page:
        versionable: true    # Default : false. This is for enable versioning for Page entities
        signable: true       # Default : false. This is for link a Page to an author
    post:
        versionable: true    # Default : false. This is for enable versioning for Post entities
        signable: true       # Default : false. This is for link a Post to an author
```
For more information about the versioning system and author attribute in bundle's entities, check [Bundle's Entities](entities.md).

### Step 4 : Import ASFDocumentBundle routes

```yaml
# app/config/routing.yml
asf_document:
    resource: "@ASFDocumentBundle/Resources/config/routing/routing.yml"
```

### Step 5 : Extends the bundle

DocumentBundle is an *abstract* bundle. You have to create an inherited bundle :

```php
<?php
namespace Acme\DemoBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeDemoBundle extends Bundle
{
	public function getParent()
	{
		return 'ASFDocumentBundle';
	}
}
```

For more information about bundle inheritance, check [Symfony documentation](http://symfony.com/doc/current/cookbook/bundles/inheritance.html).

### Next Steps

Now you have completed the basic installation and configuration of the ASFDocumentBundle, you are ready to learn about more advanced features and usages of the bundle.

The following documents are available :
* [Overriding default ASFDocumentBundle Templates](templates.md)
* [Overriding Default ASFDocumentBundle Forms](forms.md)
* [Bundle's entities](entities.md)
* [ASFDocumentBundle embedded Entity Manager](entity-manager.md)
* [ASFDocumentBundle Configuration Reference](configuration.md)