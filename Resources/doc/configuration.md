# ASFDocumentBundle Configuration Reference

All available configuration options are listed below with their default values.

```yaml
asf_document:
    form_theme: "ASFDocumentBundle:Form:fields.html.twig"
    page:
        versionable: false
        signable: false
        form:
            type: "ASF\DocumentBundle\Form\Type\PageType"
            name: "page_type"
    post:
        versionable: false
        signable: false
        form:
            type: "ASF\DocumentBundle\Form\Type\PostType"
            name: "post_type"
```

