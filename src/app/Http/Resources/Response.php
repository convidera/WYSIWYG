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

        if ($textElement = $this->getLocalTextElement($name)) {
            return $textElement;
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
        throw_if($this->getLocalTextElement($name), new Exception('TextElement is already set'));
        throw_if($this->getSubResourceTextElement($name), new Exception('SubResource TextElement is already set'));

        $this->$name = $value;
    }

    public function __($key)
    {
        if ($textElement = $this->getLocalTextElement($key)) {
            return $textElement;
        }

        if ($textElement = $this->getSubResourceTextElement($key)) {
            return $textElement;
        }

        return null;
    }

    public function plain($key)
    {
        return ($textElement = $this->__($key)) ? $textElement->value : null ;
    }

    private function getLocalTextElement($key)
    {
        if (!isset($this->data->textElements)) {
            return null;
        }
        foreach ($this->data->textElements as $textElement) {
            if ($textElement->key == $key) {
                return $textElement;
            }
        }
        return null;
    }

    private function getSubResourceTextElement($key)
    {
        $splits = explode('.', $key);
        $subResource = $splits[0];
        if (isset($this->data->$subResource) && count($splits) > 1) {
            if (is_array($this->$subResource)) {
                return $this->$subResource[$splits[1]]->__(implode('.', array_slice($splits,2)));
            }
            return $this->$subResource->__(implode('.',array_slice($splits,1)));
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

    public static function displayTextElementKeys($bool = true)
    {
        self::$displayTextElementKeys = $bool;
    }

    public function __toString()
    {
        return json_encode($this->originalData);
    }
}
