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

The complete code, along with some sample data can be found [here](examples/12). We won't discuss every piece of it in this chapter, just the parts that are new so read through all of the code carefully, look up things that are not clear, use the PHP manual, find documentation on the used third party components online. This is where the training wheels come off.

Just a quick recap of what I did to set this project up:

1. new directory
1. `composer init`
  * add dependencies during init
    * `silex/silex` - framework
    * `symfony/yaml` - YAML files
    * `twig/twig` - templates
    * `symfony/finder` - searching for files
1. composer install
1. make an 'src/ImageDemo/' directory and [register `src` as the root namespace](11_namespaces_and_autoloading.md#autoloading)

## Putting the M in MVC

Most modern web applications are designed with the ["Model View Controller Pattern (MVC)"](http://en.wikipedia.org/wiki/Model–view–controller). Basically it means, that an application is separated into three general parts:

* **Model**
  
  The rules and logic that describe what this application is about.

* **Controller**

  A relatively thin layer that coordinate model and view according to incoming input.

* **View**

  Everything that has to do with representation to the outside.

Our previous example application where more like "VC" without the "M" but with a controller that did way too many things.

In our little image gallery the model describes that there are images (the `Image` class), which have a URL and a title. It also knows where the files for that data are stored and how to read them (the `ImageService` does that).

Our controller is everything in `app.php`. It accepts incoming requests, calls appropriate model methods and passes their results along to the view. The controller also transforms the `Image` objects from the model into plain arrays to prevent the view from having too much access to the model.

The View layer consists of our Twig templates in the `views/` directory.The inly thing it does is to produce HTML output.

An application that follows this mattern can later easily be extended with new componenents. Also, part of it can be completely rewritten or replace by something else without affecting the rest. For example, we could add more views that produce RSS feeds instead of HTML pages, or we could replace the file based storage model with one that reads from a database.
