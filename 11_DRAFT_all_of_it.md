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

Now, there are some new conceptes in this application. Let's talk about those.

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

The view layer consists of our Twig templates in the `views/` directory.The inly thing it does is to produce HTML output.

An application that follows this mattern can later easily be extended with new componenents. Also, part of it can be completely rewritten or replace by something else without affecting the rest. For example, we could add more views that produce RSS feeds instead of HTML pages, or we could replace the file based storage model with one that reads from a database.


## Services and Entities

We could have put all of our model logic in the `Image` class but that class would have become quite cumbersome and complicated. Also, an `Image` should really just represent itself, not our entire storage mechanism. One way of separating entities like our `Image` from the rest of the model are services. Services are classes that provide functionality to the rest of the application. We already saw another service in action: Silex's TwigServiceProvider, a class that provides a functionality (rendering Twig templates) our application.

To handle our images, we have an `ImageService` which is responsible for loading data from files and transforming it into `Image` objects. Such objects that represent data are often called "Entities".

*To summarize:*

* Entities are objects that **are** things
* Services are objects that **do** things

## Custom exceptions

We have created a `NotFoundException` class that seemingly does nothing. It just extends the regular `Exception`. The point of this is to distinguish exceptions that we know from the ones we don't know. Our application only knwos how to handle the case when an image was not found. But there are other things that could go wrong and that need to be handled differently. Not every exception should result in showing the user the "not found" error page.

When catching an exception we can specify what type of Exception we want to catch:

```
try {
  //...
} catch (NotFoundException $e) {
  //handle only NotFoundException, not all exceptions
}
```

`catch` will only catch Exceptions of the specified type and those that inherit from it. All other Exceptions are not caught by this `catch` statement and can be handled elsewhere.

## The toArray trait and abstract methods

Our controller should convert all objects that come from the model into arrays before giving them to the view. That's because the view should not be allowed to touch model objects directly. We could have just added a `toArray()` method to our `Image` class but maybe we'll need that functinonality on other classes later as well.

The trait `toArray` has something new in it: an abstract method. Any class that uses this trait must implement this method or PHP will complain about it aith a fatal error. This way we can force classes who want to use this trait to provide the `getArrayKeys()` method that the trait needs in order to work.

Abstract methods can also be used in classes, forcing their inheritants to implement certain methods.

Interfaces contain only abstract methods. which is why they don't need the `abstract` keyword in front of them.

## Third party components

We used several third party libraries in this example, some of which we already know:

* Symfony Finder
* Symfony YAML
* Twig
* Silex

The [`Finder`](http://symfony.com/doc/current/components/finder.html) is one we haven't seen before. It's a component that helps us locate files and read them. There are PHP functions for most of that but the `Finder` component is more convenient.

This "Symfony" that keeps popping up is another PHP framework, like Silex, but much more complex. You might want to [have a look at it](http://symfony.com). Apart from the actual framework, it has many standalone components that can be used anywhere.

## Twig template inheritance

Our Twig templates use a yet unfamilar syntax: `{% extends ... %}`

[Twig templates can inherit](http://twig.sensiolabs.org/doc/templates.html#template-inheritance) from other Twig templates, just like PHP classes. And just like classes, the child template can override parts of its parent.

In `layout.twig` there's a `{% block ... %}` named "content" and it's empty. Any template that inherits from `layout.twig` can now also define a block with the same name to replace the empty block with actual content.

This way we only have to write the `<!doctype html><html><head> ...` part of our HTML code one and can reuse it in all of our templates.
