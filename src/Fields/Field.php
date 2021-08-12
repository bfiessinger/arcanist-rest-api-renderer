<?php

namespace Suenerds\ArcanistRestApiRenderer\Fields;

use Arcanist\Field as ArcanistField;
use JsonSerializable;

class Field extends ArcanistField implements JsonSerializable
{
    public string $component = 'Field';

    public array $meta = [];

    public string $label = '';
    public string $description = '';

    protected $displayCallback = null;
    protected $readOnly = false;

    public function isEditable()
    {
        return !$this->readOnly;
    }

    public function isReadOnly()
    {
        return $this->readOnly;
    }

    public function readOnly()
    {
        $this->readOnly = true;
        return $this;
    }

    public function display(mixed $value): mixed
    {
        $callback = $this->displayCallback ?: fn ($val) => $val;
        return $callback($value);
    }

    public function displayUsing(callable $callback): self
    {
        $this->displayCallback = $callback;
        return $this;
    }

    public function label(string $label)
    {
        $this->label = $label;
        return $this;
    }

    public function description(string $description)
    {
        $this->description = $description;
        return $this;
    }

    public function meta($meta) : Field
    {
        if (is_callable($meta)) {
            $meta = $meta();
        }

        if (is_array($meta)==false) {
            $meta = array($meta);
        }

        $this->meta = array_merge($this->meta, $meta);
        return $this;
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'rules' => $this->rules,
            'dependencies' => $this->dependencies,
            'component' => $this->component,
            'meta' => $this->meta,
            'readOnly' => $this->readOnly,
            'label' => $this->label,
            'description' => $this->description,
        ];
    }
}
