# dotgen

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

For now, use this as a template:

```ini
[global]
include_comments    = true
template_engine     = twig
template_dir        = path/to/templates
output_dir          = path/to/write/rendering/output

[name_of_a_text_file_group]
text_file_paths[] = relative/path/to/text/file/1
text_file_paths[] = relative/path/to/text/file/2
some_var = some_value
some_other_var = false
this_is_an_array[] = this_is_the_first_element
this_is_an_array[] = this_is_the_second_element
;this is a comment
```

The `[global]` section is mandatory and has to include the variables
`template_engine`, `template_dir` and `output_dir`.

Right now, the only possible value for `template_engine` is `twig`.

Further sections are optional, but make sense for creating groups of files
that use the same scope of variables.
Variables defined in the `[global]` section are accessible in any template,
but are overwritten by variables with the same name defined at file_group
scope.


### download and run dotgen

Pre-compiled phar packages are available [here](https://github.com/cedrichaase/dotgen/releases).

Download one, then
```
$ php dotgen.phar render path/to/config.ini
```

Command line interface is subject to change :)

### roll your own

To get started developing, clone the repo, [get composer](https://getcomposer.org/)
if you haven't already, `composer install` the dependencies and you're good to go.

```
$ git clone git@github.com:cedrichaase/dotgen.git

$ cd dotgen

$ composer install

$ bin/dotgen render path/to/config.ini
```

You can also create a single-file executable phar package using
[clue/phar-composer](https://github.com/clue/phar-composer):

```
$ cd dotgen

$ wget https://github.com/clue/phar-composer/releases/download/v1.0.0/phar-composer.phar

$ php phar-composer.phar build
```
