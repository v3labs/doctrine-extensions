<?php

namespace V3labs\DoctrineExtensions\ORM\Translatable;

trait Translation {

    protected $locale;

    protected $translatable;

    public function setTranslatable($translatable)
    {
        $this->translatable = $translatable;

        return $this;
    }

    public function getTranslatable()
    {
        return $this->translatable;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }
}