# Bundle's entities

DocumentBundle allows you to create and manage documents (like website page, blog post, etc.). As you will see, there are not entities that can be directly persisted in this bundle. This bundle provides a model that you can use. So, apart of the class *DocumentModel*, the bundle provides interfaces.

So, for persistance of the entities, you have to create your own bundle who inherit from DocumentBundle.

```php
<?php
namespace Acme\DocumentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeDocumentBundle extends Bundle
{
	public function getParent()
	{
		return 'ASFDocumentBundle';
	}
}
```

For more information about bundle inheritance, check [Symfony documentation](http://symfony.com/doc/current/cookbook/bundles/inheritance.html).

## DocumentModel and DocumentInterface

You have an abstract class on the top of the hierarchy : *DocumentModel* :

```php
<?php
// @ASFDocumentBundle/Model/Document/DocumentModel.php
namespace ASF\DocumentBundle\Model\Document;

abstract class DocumentModel implements DocumentInterface { // [...] }
```

As you can see, this class implements *DocumentInterface*. If you do not use this class, ensure that your entities implement this interface. This interface ensures that your entity may use forms and other services from the bundle. It define the class properties used for relations between bundle's entities.

```php
<?php
// @ASFDocumentBundle/Model/Document/DocumentInterface.php
namespace ASF\DocumentBundle\Model\Document;

interface DocumentInterface
{

}
```

## PageInterface and PostInterface

Two classes can inherit from *DocumentModel* : a classe implementing *PageInterface* and a class implementing *PostInterface*. A Page is a classic website page, the post is a blog post. If you want to use this schema, you have to create on a bundle inherited from DocumentBundle :

```php
<?php
// @AcmeDocumentBundle/Entity/Document.php
namespace Acme\DocumentBundle\Entity;

use ASF\DocumentBundle\Model\Document\DocumentModel;

class Document extends DocumentModel {}
```

```php
<?php
// @AcmeDocumentBundle/Entity/Page.php
namespace Acme\DocumentBundle\Entity;

use ASF\DocumentBundle\Model\Page\PageInterface;

class Page extends Document implements PageInterface {}
```

```php
<?php
// @AcmeDocumentBundle/Entity/Post.php
namespace Acme\DocumentBundle\Entity;

use ASF\DocumentBundle\Model\Post\PostInterface;

class Post extends Document implements PostInterface {}
```

## VersionnableInterface

DocumentBundle provides a versionning system. If you want to use it, just implements the VersionnableInterface like following :

```php
<?php
// @AcmeDocumentBundle/Entity/Page.php
namespace Acme\DocumentBundle\Entity;

use ASF\DocumentBundle\Model\Page\PageInterface;
use ASF\DocumentBundle\Model\Document\VersionnableInterface;

class Page extends Document implements PageInterface, VersionnableInterface {}
```

```php
<?php
// @AcmeDocumentBundle/Entity/Post.php
namespace Acme\DocumentBundle\Entity;

use ASF\DocumentBundle\Model\Post\PostInterface;
use ASF\DocumentBundle\Model\Document\VersionnableInterface;

class Post extends Document implements PostInterface, VersionnableInterface {}
```

### Doctrine ORM

The bundle provides a set of *.orm.xml files for define schema in folder *@ASFDocumentBundle/Resources/config/doctrine-mapping*.
