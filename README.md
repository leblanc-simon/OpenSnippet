OpenSnippet
===========

The OpenSnippet project allow to manage snippets in a web application. Search the snippets by category (language) and tags.

Installation
------------

Use [composer](http://getcomposer.org/) to install dependencies

```bash
# Clone this repository
git clone https://github.com/leblanc-simon/OpenSnippet.git

# Create the config and database files
cd OpenSnippet
cp config/config.php{.example,}
cp db/opensnippet.sqlite{.example,}

# Install dependencies
composer install
```

You can show the application with your browser : http://localhost/OpenSnippet/web/index.php


Thanks
------

- [Silex](http://silex.sensiolabs.org/)
- [Twig](http://twig.sensiolabs.org/)
- [Doctrine](http://www.doctrine-project.org/)
- [GeSHi](http://qbnz.com/highlighter/)
- [Tag-Handler](http://ioncache.github.io/Tag-Handler/)
- [Tag-Handler patched version](https://github.com/wshafer/Tag-Handler)


Author
------

Simon Leblanc <contact@leblanc-simon.eu>


License
-------

[MIT](http://opensource.org/licenses/MIT)