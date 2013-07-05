Kraken
=================

Project description
-------------------

TODO

Features
--------

TODO

Installation
------------
1) Install on your system needed extensions/modules
```bash
$ sudo apt-get install php5-xsl
```

2) Install composer dependencies

```bash
$ php composer.phar install
```

3) Copy and rename parameters.yml.dist to parameters.yml and edit it

4) Prepare image directory used by Twitter Bootstrap

```bash
$ mkdir -p web/img
$ cd web/img
$ ln -s ../../vendor/twitter/bootstrap/img/glyphicons-halflings.png
$ ln -s ../../vendor/twitter/bootstrap/img/glyphicons-halflings-white.png
```

5) Rebuild assets with Assetic

```bash
$ php app/console assetic:dump
```

6) Create database and tables

```bash
$ php app/console doctrine:database:create
```


TIPS
----------
You can use http://www.shell-tools.net/?op=xslt to define your Xslt file