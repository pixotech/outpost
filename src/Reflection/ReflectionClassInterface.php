<?php

namespace Outpost\Reflection;

use Outpost\Files\SourceFileInterface;

interface ReflectionClassInterface
{
    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return int
     */
    public function getEndLine();

    /**
     * @return SourceFileInterface
     */
    public function getFile();

    /**
     * @return string
     */
    public function getFilename();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getStartLine();

    /**
     * @return string
     */
    public function getSummary();

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * @return bool
     */
    public function hasTemplate();

    /**
     * @return bool
     */
    public function isEntityClass();
}
