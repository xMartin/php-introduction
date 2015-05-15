---
title: Installing PHP
layout: default
---

## Linux (Ubuntu)

```sh
sudo apt-get install php5-cli
```

## Installing Composer

[Composer](https://getcomposer.org) is a package manager for PHP,
much like `gem` for Ruby. It is used to download and manage
third party libraries for your project.

*Note: You need to have `git` installed for composer to work properly.*

1. Download <https://getcomposer.org/composer.phar>
1. Put it into your project directory
1. Rename it to `composer`
1. Make it executable: `chmod +x composer.phar`
1. Add it to `.gitignore`: `echo "/composer" >> .gitignore`

Now to run its self check with

```sh
./composer.phar diagnose
```

It should report something like this:

```
Checking platform settings: OK
Checking git settings: OK
Checking http connectivity: OK
Checking disk free space: OK
Checking composer version: OK
```

Your PHP installation is now ready for development!
