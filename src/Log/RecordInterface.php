<?php

namespace Outpost\Log;

interface RecordInterface
{
    /**
     * @return array
     */
    public function getContext();

    /**
     * @return int
     */
    public function getLevel();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return \DateTime
     */
    public function getTime();

    /**
     * @return bool
     */
    public function hasContext();
}
