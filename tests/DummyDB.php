<?php

class DummyDB
{
    private $results = [];
    private $index = 0;

    public function __construct(array $results)
    {
        $this->results = $results;
    }

    public function query($sql)
    {
        return $this->results[$this->index++] ?? null;
    }
}
