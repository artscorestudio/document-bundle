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

### Next Steps

Now you have completed the basic installation and configuration of the ASFDocumentBundle, you are ready to learn about more advanced features and usages of the bundle.

The following documents are available :
* [ASFDocumentBundle Configuration Reference](configuration.md)