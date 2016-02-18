# Artscore Studio Document Bundle

Document Bundle is a Symfony 2+ component providing models for manage documents in your Symfony 2+ application. This package is a part of Artscore Studio Framework.

What is a document in this bundle ? A document can be an HTML page, a PDF, an XML file, etc.

> IMPORTANT NOTICE: This bundle is still under development. Any changes will be done without prior notice to consumers of this package. Of course this code will become stable at a certain point, but for now, use at your own risk.
 
## Prerequisites

This version of the bundle requires :
* Symfony 2.0+

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

If you are using the component inside of Artscore Studio Framework (ASF), you have to enable the ASF support.

```yaml
# app/config/config.yml
asf_doc:
    enable_asf_support: true
```

### Step 4 : Update database schema

You have to persist entities in a custom bundle how extends ASFDocumentBundle. ASFDocumentBundle is like an abstract bundle.
ASFDocumentBundle provides entity interfaces for create document entities. You have two interfaces available :

* ASF\DocumentBundle\Model\Page\PageInterface : This is for a web page
* ASF\DocumentBundle\Model\Post\PostInterface : This is a blog post

This two interfaces extends base interface *ASF\DocumentBundle\Model\DocumentInterface*. You have also a base class DocumentModel that is an abstract class that you can extend in your custom bundle.

```php
namespace Acme\DemoBundle\Entity;

use ASF\DocumentBundle\Model\DocumentModel;

class Document extends DocumentModel
{
	// [...] Your own code here
}
```

Next, you can create Page and Post entities who extends Document entity :

```php
namespace Acme\DemoBundle\Entity;

class Page extends Document
{
	// [...] Your own code here
}
```

```php
namespace Acme\DemoBundle\Entity;

class Post extends Document
{
	// [...] Your own code here
}
```

Document Bundle provides doctrine ORM files (in XML) that you can found in directoy *vendor/artscorestudio/document-bundle/Resources/config/doctrine-mapping/*. 

Finally, you can update your database schema :

```bash
$ php bin/console doctrine:schema:update --force
```

#### Document versioning system 

If you want to activate versioning system on documents, your entites have to implements *VersionnableInterface* :

```php
namespace Acme\DemoBundle\Entity;

use ASF\DocumentBundle\Model\Document\VersionableInterface

class Page extends Document implements VersionableInterface
{
	// [...] Your own code here
}
```

### Next Steps

Now you have completed the basic installation and configuration of the ASFDocumentBundle, you are ready to learn about more advanced features and usages of the bundle.

The following documents are available :
* [Enable Artscore Studio Framework Support](asf-support.md)
* [ASFDocumentBundle Configuration Reference](configuration.md)