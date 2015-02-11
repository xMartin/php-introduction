# Inheritance

*Note: in this chapter, I will use a thing that I won't explain: [`sprintf()`](http://php.net/manual/de/function.sprintf.php), look it up. Learn what it does from the manual. Experiment with it. It isn't really related to the current topic but it's very useful.*

As an object oriented language, PHP also supports inheritance. In PHP, inheritance is class based, like in Java or Ruby. That means, a class may have a superclass from which it inherits all properties and methods.

Class inheritance is single-inheritance, meaning a class can only inherit from one superclass, not multiple, although PHP supports a kind multiple-inheritance with Traits but more on that later.

PHP also offers interfaces which support multiple-inheritance, again as in Java. In general much of PHP's object system shows a lot of influence from the Java language.

## Class inheritance

Let's define a class that represents a news article. It will have a title, a short teaser text and the full text body. We will also give it a `__toString()` method so it can be represented as a string. It should render itself as [Markdown](http://en.wikipedia.org/wiki/Markdown), a simple text markup language, in that case.

```php
<?php

class Article
{
  protected $title;
  protected $teaser;
  protected $full_text;

  public function __construct($title, $teaser, $full_text)
  {
    $this->title = $title;
    $this->teaser = $teaser;
    $this->full_text = $full_text;
  }

  public function __toString()
  {
    return sprintf(
      "%s\n---\n\n%s\n",
      $this->renderTeaser(),
      $this->full_text
    );
  }

  protected function renderTeaser()
  {
    return sprintf(
      "# %s\n\n%s\n",
      $this->title,
      $this->teaser
    );
  }
}


```

Now we can use this class to represent an Article:

```php
$article = new Article(
  'Lorem Ipsum',
  'Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui.'
  'Donec ullamcorper nulla non metus auctor fringilla. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam id dolor id nibh ultricies vehicula ut id elit.

Nullam id dolor id nibh ultricies vehicula ut id elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet.'
);

echo $article;

/*prints:
# Lorem Ipsum

Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui.

---

Donec ullamcorper nulla non metus auctor fringilla. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam id dolor id nibh ultricies vehicula ut id elit.

Nullam id dolor id nibh ultricies vehicula ut id elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet.
*/
```

*Note, how we didn't use `PHP_EOL` for line breaks this time. Instead we used `\n`, the default line break on UNIX-like systems (basically anything other than Windows). This is because we are producing Markdown code which should usually have UNIX line endings. In earlier chapters, we used `PHP_EOL` because we were writing things to the terminal. When generating code or writing files, `\n` is usually the right thing. We have to use double quoted strings (`""`) though for it to work. Single quoted strings `''` don't accept these "escape sequences" (special characters starting with a "\") instead they leave them as the two characters "\n".*

Now, maybe later we'll need articles with an image. The original Article class doesn't support this. We could change it but then we could break compatibility with the rest of your code by doing that. Instead, we can extend it, which is PHP's keyword for class inheritance:

```php
<?php

class ImageArticle extends Article
{
  protected $image_title;
  protected $image_url;

  public function setImage($image_title, $image_url)
  {
    $this->image_title = $image_title;
    $this->image_url = $image_url;
  }

  protected function renderTeaser()
  {
    return sprintf(
      "# %s\n\n![%s](%s)\n\n%s\n",
      $this->title,
      $this->image_title,
      $this->image_url,
      $this->teaser
    );
  }
}

```

So, now our `ImageArticle` class can also hold an image:

```php
$article = new ImageArticle(
  'Lorem Ipsum',
  'Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui.',
  'Donec ullamcorper nulla non metus auctor fringilla. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam id dolor id nibh ultricies vehicula ut id elit.

Nullam id dolor id nibh ultricies vehicula ut id elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet.'
);

$article->setImage('A Kitten', 'http://placekitten.com/800/400');

echo $article;

/*prints:
# Lorem Ipsum

![A Kitten](http://placekitten.com/800/400)

Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui.

---

Donec ullamcorper nulla non metus auctor fringilla. Donec sed odio dui. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Nulla vitae elit libero, a pharetra augue. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam id dolor id nibh ultricies vehicula ut id elit.

Nullam id dolor id nibh ultricies vehicula ut id elit. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras mattis consectetur purus sit amet fermentum. Integer posuere erat a ante venenatis dapibus posuere velit aliquet.
*/
```

So, we extended our `Article` into a class named `ImageArticle`. `ImageArticle` automatically get's all properties and methods from its superclass so we only need to give it the additional things that we want to add or change.

In this case, we added two properties, `image_url` and `image_title`, as well as a method to set them, `setImage()`. These don't yet change anything about the original article but enable us to store information about an image inside the article objects.

To change how the article is rendered into a string, we need to `override` (sometimes also called "overwrite") a method. We did that with the new `__toString()` method that now includes the image in the Markdown output. An overridden method replaces another method on an extended class while the superclass stays unchanged by all of this.

By just adding things and making a minimal change, we added a feature to our new `ImageArticle` class without changing the `Article` class.

## Interfaces

Staying with our example of articles, lets say our application handles all kinds of documents like invoices, memos and our articles. It may also have a function to send them to some web API somewhere for example. But how should that method know about all the different kinds of documents when all it needs is a way of getting a string representation of them that it can send away. And maybe it needs a way to get the title as well.

We could base all of our documents on a common superclass that has a `__toString()` and a `getTitle()` method but that would severely limit us in terms of how are classes can be extended. We can however set up a kind of "contract" that classes need to adhere to and that enforces certain methods to be available on a class. That is called an interface in PHP:

```php
<?php

interface Document
{
  public function getTitle();
  public function __toString();
}
```

This interface forces all classes that `implement` it to have these two methods. It doesn't care what the methods actually to, they just have to match the description in the interface. Now let's add that interface to our Article. I'll just write the `Article`class with empty methods now to save some space:

```php
<?php

class Article implements Document
{
  public function __construct()
  {
    //...
  }
  
  public function __toString()
  {
    //...
  }
  
  protected function renderTeaser()
  {
    //...
  }
}
```

If we just add the `Document` interface to our class without changing anything else, PHP will complain:

```
Fatal error: Class Article contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (Document::getTitle) in inheritance.php on line 39
```

It tells us that we forgot to implement the `getTitle()` method that the interface demands. These methods in the interface that have no actual code in them are called "abstract methods".

Let's add `getTitle()`:

```php
<?php

class Article implements Document
{

  //...
  
  public function getTitle()
  {
    return $this->title;
  }
  
  //...
}
```

Now PHP doesn't complain anymore and since interfaces also work with [type hinting](09_more_on_functions.md#type-hinting), any part of our application that expects a `Document` can now use it as a type hint without knowing about all the different kinds of documents. This way, we could later add more classes that implement the `Document` interface and the functions that are type hinted with it will still work as long as they as well adhere to the "contract" that is set up by the interface.


## Traits

We can add another useful feature to our `Article` class and it's child classes: converting the Markdown content to HTML. But maybe other classes in our project will need that feature as well so we want to implement it only once. We can't use inheritance to give it to all classes that need it because they may already have a superclass.

As mentioned at the start of this chapter, there is a way in PHP to inherit from multiple things. It doesn't work with classes ([for a good reason](http://en.wikipedia.org/wiki/Multiple_inheritance#The_diamond_problem)) but there are [Traits](http://php.net/manual/de/language.oop5.traits.php):

```php

<?php

trait toHTML
{
    public function toHTML()
    {
        //"Parsedown" is a library for handling markdown, check it out: http://parsedown.org
        $parser = new ParseDown();
        
        // we cast to string to execute the objects __toString() method
        // since we want to markdown representation.
        return $parser->text((string)$this);
    }
}
```

A trait is a small set of methods and properties that can be "injected" into classes:

```
<?php

class Article implements Document
{
 
  //...
  
  use toHTML;
  
  //...
}
```

Now, `Article`and it's subclasses like `ImageArticle` have a `toHTML()` method:

```php
<?php

//assuming $article is our ImageArticle from before:
echo $article->toHTML();

/* prints something like:
<h1>Lorem Ipsum</h1>
<p>
<img alt="A Kitten" src="(http://placekitten.com/800/400"/>
</p>
<p>Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Donec sed odio dui.</p>
<hr>
...
...
*/
```


## Visibility and inheritance

The [visibility modifiers](https://github.com/lnwdr/php-introduction/blob/master/04_objects_and_classes.md#visibility) `public`, `protected` and `private` play an important role in inehritance in PHP.

Back in chapter 4 we established that

> For now, public means "is accessible from outside the object and protected means "is not accessible from the outside".

Now that we know about inheritance, we can be more precise about that. Let's consider these two classes and objects:

```php
<?php

class Foo
{
  public function a_public_method()
  {
    echo "PUBLIC!!" . PHP_EOL;
  }
  
  protected function a_protected_method()
  {
    echo "PROTECTED!!" . PHP_EOL;
  }
  
  private function a_private_method()
  {
    echo "PRIVATE!!" . PHP_EOL;
  }
  
  public function foo_test()
  {
    $this->a_public_method();
    $this->a_protected_method();
    $this->a_private_method();
  }
}

class Bar extends Foo
{
  public function bar_test()
  {
    $this->a_public_method();
    $this->a_protected_method();
    $this->a_private_method();
  }
  
  public function external_test($external)
  {
    $external->a_public_method();
    $external->a_protected_method();
    $external->a_private_method();
  }
}

$foo = new Foo();
$bar = new Bar();
```

Remember, `$foo` in an instance if `Foo` and `$bar` in a `Bar` which inherits from `Foo`.

First, we'll try to run three methods on `$foo` from the outside. Then `$foo->foo_test()` runs three methods on `$foo` itself, `$bar->bar_test()` tries to do the same and `$bar->external_test($foo)` will try to run those methods on `$foo` but seen from inside `$bar`.

```php
$foo->a_public_function();
/* prints
"PUBLIC!!"
*/
```

```php
$foo->a_protected_function();
/* prints:
Fatal error: Call to protected method Foo::a_protected_method() from context '' in php shell code on line 1
*/

//protected methods are not visible from the outside
```

```php
$foo->a_private_function();
/* prints:
Fatal error: Call to private method Foo::a_private_method() from context '' in php shell code on line 1
*/

//private methods are not visible from the outside
```

```php
$foo->foo_test();
/* prints:
PUBLIC!!
PROTECTED!!
PRIVATE!!
*/

//Objects can see all methods of their own class, including private and protected ones.
```

```php
$bar->bar_test();
/* prints:
PUBLIC!!
PROTECTED!!

Fatal error: Call to private method Foo::a_private_method() from context 'Bar' in /Users/lnwdr/Desktop/temp/visibility.php on line 34
*/

//Objects can't see private methods from their class' superclasses. This is the difference between protected and private.
```

```php
$bar->external_test($foo);
/* prints:
PUBLIC!!
PROTECTED!!

Fatal error: Call to private method Foo::a_private_method() from context 'Bar' in /Users/lnwdr/Desktop/temp/visibility.php on line 41
*/

//Objects can see other object's protected methods as long as their classes have a common ancenstor. They're "family" so to speak. Private methods are still off limits, though.
```
