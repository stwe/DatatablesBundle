# Internationalisation

There are three options by which you can include internalisation options:

| Option                 | Type           | Default | Description |
|------------------------|----------------|---------|-------------|
| cdn_language_by_locale | bool           | false   | Get the actual language file by `app.request.locale` from CDN. |
| language_by_locale     | bool           | false   | Get the actual language by `app.request.locale`. |
| language               | null or string | null    | Set a language by given ISO 639-1 code. |

``` php
class PostDatatable extends AbstractDatatable
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->language->set(array(
            'cdn_language_by_locale' => true
            //'language_by_locale' => true
            //'language' => 'de'
        ));
        
        // ...
    }
    
    // ...
}
```
