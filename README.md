Twig integration for Contao
===========================

This module provide twig support for Contao.
To use twig instead of the contao engine, just use the `TwigFrontendTemplate` or `TwigBackendTemplate` class.

For content elements, you can use the `TwigContentElement` class,
and for modules the `TwigModule` class. These basic classes allready uses the Twig*Template classes.

You have to suffix your templates with `.twig`, look at the examples in the `templates` directory.

Dependencies
------------

* ApcCache https://github.com/InfinitySoft/contao-apccache
* Autoloader https://github.com/InfinitySoft/contao2-autoloader

