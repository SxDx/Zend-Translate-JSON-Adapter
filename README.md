Zend-Translate-JSON-Adapter
===========================

A JSON Adapter for the Zend Translate Module

Allows the use of JSON resource files.
Example:

    {
      "app": {
        "name": "MY AWSOME APP",
        "version": "1.0"
      },
      "errors": {
        "not_found": "Not found",
        "network_error": "Network error",
        "fatal": {
            "search": "Search Error Nr. __1__ : __2__"
        }
      }
    }

You can access the translations hierarchical e.g. `$translate->_("app.name")`

Simply pass parameters, no need for sprintf, e.g. `$translate->_("app.error.fatal.search", $errorNumber, $errorDescription)`

# Initialisation #

You can simply put the file into the Zend/Translate directory to initialise it like the other adapters:

    $translate = new Zend_Translate(
        array(
            'adapter' => 'Json',
            'content' => 'translations/de.json',
            'locale'  => 'de'
        )
    );

or follow the [Zend Translate Guidelines](http://framework.zend.com/manual/en/zend.translate.adapter.html#zend.translate.adapter.selfwritten) on integrating self written adapters.


# Motivation #

I primarly wrote this adapter to use the same translation resource files for Javascript/JQuery and PHP.
I use [i18next](http://i18next.com) for translation in Javascript in conjunction with [i18next Webtranslate](http://i18next.com/pages/ext_webtranslate.html)
