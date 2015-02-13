<?php

namespace ImageDemo;

class Image
{
    use toArray;

    public function getArrayKeys()
    {
        return [
            'id',
            'title',
            'url'
        ];
    }

    protected $title;
    protected $url;

    public function __construct($id, $title, $url) {
        $this->id = $id;
        $this->title = $title;
        $this->url = $url;
    }
}
