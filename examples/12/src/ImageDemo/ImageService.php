<?php

namespace ImageDemo;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class ImageService
{
    public function getAll()
    {
        $finder = new Finder();
        $finder->files()->name('*.yml')->in('data');

        $images = [];

        foreach ($finder as $yml_file) {
            $images[] = $this->createImageFromFile($yml_file);
        }

        return $images;
    }

    protected function createImageFromFile(SplFileInfo $yml_file)
    {
        $id = $yml_file->getBasename('.yml');
        $data = Yaml::parse($yml_file->getContents());

        $title = $data['title'];
        $url = sprintf('/static/%s', $id);

        return new Image($id, $title, $url);
    }

    public function getById($id)
    {
        $finder = new Finder();
        $finder->name($id . '.yml')->in('data');

        $files = iterator_to_array($finder);

        if (count($files) === 0) {
            throw new NotFoundException($id);
        }

        return $this->createImageFromFile(array_pop($files));
    }
}
