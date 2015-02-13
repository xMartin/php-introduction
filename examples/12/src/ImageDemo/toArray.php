<?php

namespace ImageDemo;

trait toArray
{
    abstract public function getArrayKeys();

    public function toArray()
    {
        $arr = [];
        $keys = $this->getArrayKeys();

        foreach($keys as $key) {
            $arr[$key] = $this->$key;
        }

        return $arr;
    }
}
