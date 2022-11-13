<?php

namespace SocialMedia\Poster;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

abstract class SocialMediaAbstract
{
    /**
     * @var \SocialMedia\Poster\Models\SocialMediaSetting
     */
    protected $socialMediaSettings;

    public function __construct(public $platforms = [], public $content = null, public $image = 'DEFAULT', public $link = null)
    {
        $this->socialMediaSettings = $this->getSocialMediaSettings();
        if (!$this->socialMediaSettings){
            throw new ModelNotFoundException("Social Media Settings is missing from your DB!");
        }
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $method = $this->getMethodName($name)))
        {
            return $this->$method(...$arguments);
        }
        else
        {
            throw new \ReflectionException("Method {$method} not exists in " . get_class($this));
        }
    }

    protected function getMethodName($name)
    {
        if (Str::startsWith($name, 'to'))
        {
            return $name;
        }
        else
        {
            return 'to' . Str::studly($name);
        }
    }

    abstract protected function getSocialMediaSettings();

    abstract public function publish();

    public function setContent($content = '')
    {
        $this->content = $content;

        return $this;
    }

    public function setImage($image = 'DEFAULT')
    {
        $this->image = $image;

        return $this;
    }

    public function setLink($link = '')
    {
        $this->link = $link;

        return $this;
    }

    public function setPlatforms($platforms = '*')
    {
        $this->platforms = $platforms;

        return $this;
    }
}
