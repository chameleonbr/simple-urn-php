<?php

class SimpleURN
{

    protected $handlers = [];
    protected $notFound = null;

    public function registerUrnHandler($idx, callable $handler)
    {
        if (is_string($idx)) {
            $idx = array($idx);
        }
        foreach ($idx as $type) {
            $this->handlers[$type] = $handler;
        }
        return $this;
    }

    public function registerUrnHandlers($handlersArray = [])
    {
        foreach ($handlersArray as $name => $handler) {
            $this->registerURNHandler($name, $handler);
        }
        return $this;
    }

    public function registerNotFoundHandler(callable $handler)
    {
        $this->notFound = $handler;
        return $this;
    }

    public function parse($urn = '')
    {
        $urn = str_replace('urn:', '', strtolower($urn));
        $sep = strrpos($urn, ':');
        if ($sep === false) {
            return false;
        }
        $name = substr($urn, 0, $sep);
        $value = substr($urn, $sep + 1);
        return compact('name', 'value');
    }

    public function handle($urn = '')
    {
        $urnArr = $this->parse($urn);
        var_dump($urnArr);
        if (is_array($urnArr) && isset($this->handlers[$urnArr['name']]) && is_callable($this->handlers[$urnArr['name']])) {
            $func = $this->handlers[$urnArr['name']];
            return $func($urnArr['name'], $urnArr['value']);
        } elseif (is_array($urnArr) && is_callable($this->notFound)) {
            $func = $this->notFound;
            return $func($urnArr['name'], $urnArr['value']);
        } elseif (is_callable($this->notFound)) {
            $func = $this->notFound;
            return $func(null, $urn);
        } else {
            return false;
        }
    }

}
