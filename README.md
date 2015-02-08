[GCTrade](http://gctrade.ru)
========

An application for reviewing statuses of GreenCubes web-services: Site, Forum, API, Support System, Game server.<br>
Powered by [Yii2](https://github.com/yiisoft/yii2).
#### Features:
* For buyers
    * Selecting store
    * Search for items
* For sellers
    * Available information
    * Simple controls
* Common
    * General information
    * Compare prices
    * Information about the economy

##Installation
1. Clone repo and move to directory<br>
```sh
git clone https://github.com/astappev/GCTrade && cd GCTrade
```
2. Install [Composer](https://getcomposer.org/)<br>
```sh
curl -sS https://getcomposer.org/installer | php
```
3. Install [NPM/Bower Dependency Manager for Composer](https://github.com/francoispluchino/composer-asset-plugin/)<br>
```sh
php composer.phar global require "fxp/composer-asset-plugin:1.0.0"
```
4. Install dependency
```sh
php composer.phar update --prefer-dist
```
5. Configure app
6. Inmort DB from `create_table.sql`
7. Config Apache or Nginx host to `web/`

###Todo's
- Add Library module
- Translate
- Refactor code
- Write Tests
- see [more](https://trello.com/b/nPvXK1mB/gctrade)

License
----
MIT