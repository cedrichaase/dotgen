# dotgen

[![Build Status](https://travis-ci.org/cedrichaase/dotgen.svg?branch=master)](https://travis-ci.org/cedrichaase/dotgen)

## what is dotgen?

dotgen is a templating system for dotfiles (or any other text files) written in
php.
It reads variables from a config file and renders templates using them as a
context.

For now, dotgen can read configuration from .ini files and process templates
written for [twig](https://github.com/twigphp/Twig).

## why?
Maintaining dotfiles for different machines in different branches of a repository
seemed tedious to me as I would either have to cherry-pick back and forth between
several orphan branches or I would have to keep rebasing onto a common ancestor.

What I really wanted was multiple, similar possible instances of the same text
file, depending on a few variables.
It turns out that in web development, we do this all the time when rendering
web pages.
As such, it seemed like a good idea to go ahead and use a templating engine in
combination with an easy to read and edit configuration file format and put it
all together.

## how?

### install php

* run `$ php -v`

* your php version should at least be `7.0.0`

* if necessary, install or upgrade

  - Arch Linux: `# pacman -Syy php`
  - Debian: `# apt-get update && apt-get install php7.0`
  - Mac OS: `$ brew install php70`

### create templates

For now, check out the [twig documentation for template designers](http://twig.sensiolabs.org/doc/templates.html)

### create a config file

Example configurations along with a simple template can be found in the `examples` folder.

Each configuration consists of multiple _collections_.
A _collection_ is a set of variable definitions along with names (paths) of templates
that should be rendered using the same, shared set of variables.
The names of the templates are defined in an array inside the collection named `__templates`.

A configuration can also include a collection with the special name `global`.
Variable definitions from this collection can be accessed from every collection defined
within the same configuration.
You can also choose to overwrite the global variable definition inside the scope of a collection
by simply redefining the variable inside of it.


### download and run dotgen

Pre-compiled phar packages are available [here](https://github.com/cedrichaase/dotgen/releases).

Download one, then
```
$ php dotgen.phar render path/to/config.ini
```

For a list of available command line options, run
```
$ php dotgen.phar help
```
or try
```
$ php dotgen.phar help render
```

Command line interface is subject to change :)

### roll your own

To get started developing, clone the repo, [get composer](https://getcomposer.org/)
if you haven't already, `composer install` the dependencies and you're good to go.

```
$ git clone git@github.com:cedrichaase/dotgen.git

$ cd dotgen

$ composer install

$ bin/dotgen render path/to/config/file
```

You can also create a single-file executable phar package using
[clue/phar-composer](https://github.com/clue/phar-composer):

```
$ cd dotgen

$ wget https://github.com/clue/phar-composer/releases/download/v1.0.0/phar-composer.phar

$ php phar-composer.phar build
```
