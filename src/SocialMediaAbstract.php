<?php

namespace SocialMedia\Poster;

use Illuminate\Support\Str;

abstract class SocialMediaAbstract
{
    /**
     * @var \SocialMedia\Poster\Models\SocialMediaSetting
     */
    protected $socialMediaSettings;

    public function __construct(public $platforms = null, public $content = null, public $image = "DEFAULT", public $link = null)
    {
        $this->socialMediaSettings = $this->getSocialMediaSettings();
    }

    public function __call(string $name, array $arguments)
    {
        if (method_exists($this, $method = $this->getMethodName($name))) {
            return $this->$method(...$arguments);
        } else {
            throw new \ReflectionException("Method {$method} not exists in " . get_class($this));
        }
    }

    protected function getMethodName($name)
    {
        if (Str::startsWith($name, 'to')) {
            return $name;
        } else {
            return 'to' . Str::studly($name);
        }
    }

    abstract protected function getSocialMediaSettings();

    abstract public function publish();
}
