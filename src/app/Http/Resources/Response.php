<?php

namespace Convidera\WYSIWYG\Http\Resources;

class Response
{
    protected $data;
    protected $originalData;

    protected static $displayTextElementKeys = false;

    public function __construct($data)
    {
        $this->originalData = $data;
        $this->data = new \stdClass();
        foreach ($data as $name => $element) {
            if (is_array($data->$name)) {
                $this->data->$name = array_map(function ($item) {
                    return self::make($item);
                }, $data->$name);
                continue;
            }

            if (is_object($data->$name)) {
                $this->data->$name = self::make($data->$name);
                continue;
            }

            $this->data->$name = $element;
        }
    }

    public static function make($data)
    {
        return new self($data);
    }

    public function __get($name)
    {
        if (isset($this->data->$name)) {
            return $this->data->$name;
        }

        if ($element = $this->element($name)) {
            return $element;
        }

        return null;
    }

    public function get($name)
    {
        return $this->data->$name;
    }

    public function __isset($name)
    {
        return isset($this->data->$name);
    }

    public function __set($name, $value)
    {
        throw_if(isset($this->data->$name), new Exception('Value is already set'));
        throw_if($this->getLocalElement('text', $name), new Exception('TextElement is already set'));
        throw_if($this->getLocalElement('media', $name), new Exception('MediaElement is already set'));
        throw_if($this->getSubResourceElement('text', $name), new Exception('SubResource TextElement is already set'));
        throw_if($this->getSubResourceElement('media', $name), new Exception('SubResource MediaElement is already set'));

        $this->$name = $value;
    }

    public function __($key)
    {
        return ($element = $this->element($key)) ? $element->value : null;
    }

    public function element($key) {
        if ($textElement = $this->textElement($key)) {
            return $textElement;
        }
        if ($mediaElement = $this->mediaElement($key)) {
            return $mediaElement;
        }

        return null;
    }

    public function textElement($key)
    {
        return $this->getElement('text', $key);
    }

    public function text($key)
    {
        return ($textElement = $this->textElement($key)) ? $textElement->value : null;
    }

    public function mediaElement($key)
    {
        return $this->getElement('media', $key);
    }

    public function mediaUrl($key) {
        return ($mediaElement = $this->mediaElement($key)) ? $mediaElement->value : null;
    }

    private function getElement(string $type, $key)
    {
        if ($element = $this->getLocalElement($type, $key)) {
            return $element;
        }
        if ($element = $this->getSubResourceElement($type, $key)) {
            return $element;
        }
        return null;
    }

    private function getLocalElement(string $type, $key)
    {
        $type = $type . 'Elements';

        if (!isset($this->data->$type)) {
            return null;
        }
        foreach ($this->data->$type as $element) {
            if ($element->key == $key) {
                return $element;
            }
        }
        return null;
    }

    private function getSubResourceElement(string $type, $key)
    {
        $type = $type . 'Element';
        $splits = explode('.', $key);
        $subResource = $splits[0];
        if (isset($this->data->$subResource) && count($splits) > 1) {
            if (is_array($this->$subResource) && $this->$subResource[$splits[1]]) {
                return $this->$subResource[$splits[1]]->$type(implode('.', array_slice($splits, 2)));
            }
            return $this->$subResource->$type(implode('.', array_slice($splits, 1)));
        }
        return null;
    }

    public function dump()
    {
        dump($this->originalData);
    }

    public function dd()
    {
        $this->dump();
        die;
    }

    public static function displayTextElementKeys($bool = false)
    {
        self::$displayTextElementKeys = $bool;
    }

    public function __toString()
    {
        return json_encode($this->originalData);
    }
}
