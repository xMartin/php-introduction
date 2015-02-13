# All of it

We now have covered most of the PHP language and the most important parts of its general ecosystem.

There is of course still much to learn, though. We haven't talked about databases at all for example. But at the enf of this chapter you'll have all the tools and the vocabulary you need to learn about all the rest of the PHP world on your own. The purpose of this introduction was to get you familiar with PHP itself and the way modern PHP applications are built. We'll finish this off by building something again: a dynamic image gallery.

Here's how it should work:

You put image files (JPGs, PNGs, ...) in a directory and YAML files with some extra information (e.g. titles) in another one. The YAML files should be related to the image files by name, so `foo.jpg` will have its additional info in `foo.jpg.yml`.

Our application will present a web page listing all images and clicking on one of them will lead to another page with just that one image.

This project will use most of the things that we covered in the past 10 chapters as well as some new things:

* Composer
* Silex
* closures
* classes
  * inheritance
  * traits
* exceptions
* services
* TWIG templates
  * template inheritance

The complete code, along with some sample data can be found [here](examples/12). We won't discuss every piece of it in this chapter, just the parts that are new so read through all of the code carefully, look up things that are not clear, use the PHP manual and find documentation on the used componetent online. This is where the training wheels come off. ;)

Initial Steps:

* composer init
* dependencies:
  * silex/silex - framework
  * symfony/yaml - YAML files
  * twig/twig - templates
  * symfony/finder - searching for files
* composer install
