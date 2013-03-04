<?php

namespace V3labs\DoctrineExtensions\ORM\Translatable;

use Doctrine\Common\Collections\ArrayCollection;
use \RuntimeException;

trait Translatable {

    /**
     * @var ArrayCollection
     */
    protected $translations;

    public function getTranslations()
    {
        if (!$this->translations) {
            $this->translations = new ArrayCollection();
        }

        return $this->translations;
    }

    public function addTranslation($translation)
    {
        $this->getTranslations()->set($translation->getLocale(), $translation);
        $translation->setTranslatable($this);
    }

    public function removeTranslation($translation)
    {
        $this->getTranslations()->removeElement($translation);
    }

    public function translate($locale = null)
    {
        if (null === $locale) {
            throw new \InvalidArgumentException('Locale cannot be null.');
        }

        $translation = $this->getTranslations()->get($locale);

        if (!$translation) {
            $class = $this->getTranslationEntityClass();
            $translation = new $class();
            $translation->setLocale($locale);

            $this->getTranslations()->set($translation->getLocale(), $translation);
            $translation->setTranslatable($this);
        }

        return $translation;
    }

    public function getTranslationEntityClass()
    {
        $class = __CLASS__;

        do {
            $translationClass = "{$class}Translation";

            if (class_exists($translationClass)) {
                return $translationClass;
            }

            $class = get_parent_class($class);
        } while (!empty($class));

        throw new RuntimeException(sprintf('Translation class for %s cannot be found.', get_class($this)));
    }
}