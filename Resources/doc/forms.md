# Overriding Default ASFDocumentBundle Forms

## Overriding a form type

The default forms packaged with the ASFDocumentBundle provide functionality for manage pages and posts. These forms work well with the bundle's default classes and controllers. But, as you start to add more properties to your classes or you decide you want to add a few options to the forms you will find that you need to override the forms in the bundle.

Suppose that you want to add a author attribute in Page entity. You have to add this field in the Page form. The first step is to create your own Page Form who inherit from the ASFDocumentBundle Page Form Type. 

```php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('author', ChoiceType::class);
    }

    public function getParent()
    {
        return 'ASF\DocumentBundle\Form\Type\PageType';
    }

    public function getBlockPrefix()
    {
        return 'app_page_type';
    }

    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
```

> If you don't want to reuse the fields added in ASFDocumentBundle by default, you can omit the getParent method and configure all fields yourself.

The second step is to declare your form as a service and add a tag to it. The tag must have a name value of form.type and an alias value that is the equal to the string returned from the getName method of your form type class. The alias that you specify is what you will use in the ASFDocumentBundle configuration to let the bundle know that you want to use your custom form.

```xml
<!-- app/config/services.xml -->
<?xml version="1.0" encoding="UTF-8" ?>

<container xmlns="http://symfony.com/schema/dic/services"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>

        <service id="app.form.page" class="AppBundle\Form\PageType">
            <tag name="form.type" alias="app_page_type" />
        </service>

    </services>

</container>
```

The final step is to update the ASFDocumentBundle Configuration for use your Page Form Type :

```yaml
# app/config/config.yml
asf_document:
    page:
        form:
            type: AppBundle\Form\PageType
```
