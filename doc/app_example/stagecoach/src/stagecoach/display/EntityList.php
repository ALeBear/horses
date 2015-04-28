<?php

namespace stagecoach\display;

interface EntityList
{
    /**
     * Entities in the list must have a getField() method if you return ['Field'] here
     * @return string[]
     */
    public function getAccessibleFields();
}
