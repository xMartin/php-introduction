# DRAFT: Inheritance

As an object oriented language, PHP also supports inheritance. In PHP, inheritance is class based, like in Java or Ruby. That means, a class may have a superclass from which it inherits all properties and methods.

Class inheritance is single-inheritance, meaning a class can only inheit from one superclass, not multiple, although PHP supports a kind multiple-inheritance with Traits but more on that later.

PHP also offers interfaces which support multiple-inheritance, again as in Java. In general much of PHP's object system shows a lot of influence from the Java language.

## Class inheritance

Let's define a class that represents a news articele. It will have a title, a short teaser text and the full text body. We will also give it a `__toString()` method so it can be represented as a string. It should render itself as [Markdown](http://en.wikipedia.org/wiki/Markdown), a simple text markup language, in that case.

```
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
    $str = $this->renderTeaser();
    $str .= '---' . PHP_EOL;
    $str .= $this->renderBody();
    
    return $str;
  }
  
  protected function renderTeaser()
  {
    $str = '# ' . $this->title . PHP_EOL;
    $str .= PHP_EOL;
    $str .= $this->teaser;
    $str .= PHP_EOL;
    
    return $str;
  }
  
  protected function renderBody()
  {
    return $this->full_text . PHP_EOL;
  }
}

```

Now we can use this class to represent an Article:

```
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

Now, maybe later we'll need articles with an image. The original Article class doesn't support this. We could change it but then we could break compatibility with the rest of your code by doing that. Instead, we can extend it, which is PHP's keyword for class inheritance:

```
<?php

class ImageArticle extends Article
{
  $protected $image_title; 
  $protected $image_url;
  
  public function setImage($image_title, $image_url)
  {
    $this->image_title = $image_title;
    $this->image_url = $image_url;
  }
  
  protected function renderTeaser()
  {
    $str = '# ' . $this->title . PHP_EOL;
    $str .= PHP_EOL;
    $str .= '![' . $this->image_title . '](' . $this->image_url . ')' . PHP_EOL; 
    $str .= PHP_EOL;
    $str .= $this->teaser;
    $str .= PHP_EOL;
    
    return $str;
  }
}
```
