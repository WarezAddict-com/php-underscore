<?php

namespace Ahc\Underscore;

class Underscore implements \ArrayAccess, \Countable, \IteratorAggregate, \JsonSerializable
{
    const VERSION = '0.0.1';

    /** @var array The array manipulated by this object */
    protected $data;

    /**
     * Constructor.
     *
     * @param array|mixed $data.
     */
    public function __construct($data = [])
    {
        $this->data = is_array($data) ? $data : Helper::asArray($data);
    }

    /**
     * Get the underlying array data.
     *
     * @param string|int|null $index
     *
     * @return mixed
     */
    public function get($index = null)
    {
        if (null === $index) {
            return $this->data;
        }

        return $this->data[$index];
    }

    public function each(callable $fn)
    {
        foreach ($this->data as $index => $value) {
            $fn($value, $index);
        }

        return $this;
    }

    public function forEach(callable $fn)
    {
        return $this->each($fn);
    }

    public function map(callable $fn)
    {
        $data = [];

        foreach ($this->data as $index => $value) {
            $data[$index] = $fn($value, $index);
        }

        return new static($data);
    }

    public function collect(callable $fn)
    {
        return $this->map($fn);
    }

    public function reduce(callable $fn, $memo)
    {
        return array_reduce($this->data, $fn, $memo);
    }

    public function foldl(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    public function inject(callable $fn, $memo)
    {
        return $this->reduce($fn, $memo);
    }

    public function reduceRight(callable $fn, $memo)
    {
        // fix!
        return array_reduce($this->data, $fn, $memo);
    }

    public function foldr(callable $fn, $memo)
    {
        return $this->reduceRight($fn, $memo);
    }

    public function find(callabe $fn)
    {
        foreach ($this->data as $index => $value) {
            if ($fn($value, $key)) {
                return $value;
            }
        }
    }

    public function detect(callabe $fn)
    {
        return $this->find($fn);
    }

    public function filter(callable $fn)
    {
        $data = [];

        foreach ($this->data as $index => $value) {
            if ($fn($value, $index)) {
                $data[$index] = $value;
            }
        }

        return new static($data);
    }

    public function select(callable $fn)
    {
        return $this->filter($fn);
    }

    public function reject(callable $fn)
    {
        $data = [];

        foreach ($this->data as $index => $value) {
            if (!$fn($value, $index)) {
                $data[$index] = $value;
            }
        }

        return new static($data);
    }

    public function every(callable $fn)
    {
        foreach ($this->data as $index => $value) {
            if (!$fn($value, $index)) {
                return false;
            }
        }

        return true;
    }

    public function all(callable $fn)
    {
        return $this->every($fn);
    }

    public function some(callable $fn)
    {
        foreach ($this->data as $index => $value) {
            if ($fn($value, $index)) {
                return true;
            }
        }

        return false;
    }

    public function any(callable $fn)
    {
        return $this->some($fn);
    }

    public function contains($item)
    {
        return \in_array($item, $this->data);
    }

    public function includes($item)
    {
        return $this->contains($item);
    }

    public function include($item)
    {
        return $this->contains($item);
    }

    public function invoke(callable $fn)
    {
        return \call_user_func_array($fn, $this->data);
    }

    public function pluck($columnKey, $indexKey = null)
    {
        if (\function_exists('array_column')) {
            $data = \array_column($this->data, $columnKey, $indexKey);
        } else {
            $data = Helper::arrayColumn($this->data, $columnKey, $indexKey);
        }

        return new static($data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($index)
    {
        return \array_key_exists($index, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($index)
    {
        return $this->data[$index];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($index, $value)
    {
        $this->data[$index] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($index)
    {
        unset($this->data[$index]);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return \count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return \json_encode($this->data);
    }
}

\class_alias('Ahc\Underscore\Underscore', 'Ahc\Underscore');
