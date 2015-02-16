# Intro

## Isn't PHP that horrible language?

Among some developers who use other languages,
you can often hear snarky remarks about PHP and
how it's "a terrible language", "badly designed" or just "ugly"
which is rarely backed up by actual arguments.

Most of that resentment probably comes from a time,
somewhere around the early 2000s, when PHP was the only option
in server-side web development that people had. Web hosting
companies didn't usually offer any alternatives and PHP
was the only language of it's that you could find
substantial helpful resources on the web.

Back then, PHP 4 was the current major version and it was indeed not a good
language. It's object system was rudimentary, it's APIs were inconsistent because
over the years, people had added features to it without adhering to a consistent style.

When Ruby (mostly through Rails) became popular, many people left PHP and never looked back. So now, a lot of people compare the languages and frameworks they use today, in 2015, with what they used in PHP around 2006, almost a decade years ago.

So much has changed since then ...

With PHP 5 and especially it's minor releases 5.2 and 5.3 a lot of things were improved.
There's now a much better object model,
there are important built in data structures that were missing before,
higher order functions were added,
performance has greatly increased
and some weird configuration options that were outright dangerous have been deprecated and removed.

[Composer][1], a new package manager for PHP, has fixed the tedious installation of third party libraries.
The popular application frameworks from the Ruby world now have PHP equivalents
(Rails -> [Symfony][2], Sinatra -> [Silex][3]).

Some things still remain weird, though, like PHP's "type juggling"
which is similar to JavaScript's implicit type conversion and causes similar problems.
The APIs of the built in functions is still inconsistent and
Unicode strings still need special attention to handle them correctly.

PHP is flawed, sure, just like most other languages/platforms. And just like the others, it's worth learning it.

# How to use this guide

We will start with how to install PHP and Composer and then go through a small example program to get familiar with the syntax ad general look and feel of PHP.

Then we'll make a "Hello world" web application using a minimalistic framework called "Silex". This will serve as an introduction on how to install and use third party dependencies.

In the following chapters, we will explore the features of PHP in more detail.
The chapters are intended to be read in the correct order since they built on previous chapters.



[1]: https://getcomposer.org
[2]: http://symfony.com
[3]: http://silex.sensiolabs.org
