# Where to go from here

This is it. We're done. You now know enough about the PHP language to continue on your own.

You can read most PHP code and you know where to look things up, that you don't know yet.

Anything you can learn from here on will probably be too specific to include in this tutorial but here's a few things that you can do next:

## Learn frameworks

You've seen Silex, a very minimalist framework that doesn't so much but also gives you a lot of freedom. But it has still much more to offer. You can [read a full introduction to it here](http://silex.sensiolabs.org/doc/intro.html).

There are also other PHP frameworks that have a different approach. [Symfony](http://symfony.com) is by far the most influential one these days, it has a similar approach like Ruby on Rails.

## Learn about Databases

Most web applications read from and write to some kind of database, most commonly relational databases like MySQL. But things non-relational DBs like CouchDB are often a good option. For most databases, there are packages available via Composer to communicate with them. Just try a search on [packagist.org](https://packagist.org).

Support for MySQL and SQLite is built right into PHP. SQLite doesn't even require a separate DB installation, it stores everything in local files. SQLite is great to get started with relational databases. [The "Databases" section on PHP The Right Way](http://www.phptherightway.com/#databases) is a good place to start.

## Continue learning about PHP

[PHP The Right Way](http://www.phptherightway.com) in general is a great resource to get you started on a variety of topics. It's also a good reference to look things up.

For example, it can get you started on [hosting PHP applications on your server](http://www.phptherightway.com/#servers_and_deployment).

## Be wary. There be dragons.

We've already seen a few places where PHP is kind of weird and I'll be honest with you: Sometimes it's just horribly broken. That's the way it is right now, so you'll have to deal with it.

There's a detailed article called ["PHP - a fractal of bad design"](http://eev.ee/blog/2012/04/09/php-a-fractal-of-bad-design/) about the things that are bad and dangerous in PHP. I suggest you read it, now that you've seen some PHP code.

Keep in mind though that many of the things that this article points out are outdated and many of them have significantly improved over the past years or are just never actually used. Also it has a generally very negative tone. Still, it's good to know the hidden traps in PHP and this article is a pretty comprehensive list of those.
