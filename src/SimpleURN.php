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
    
    public function handle($urn = '')
    {
        $matches = null;
        $urn = str_replace(['urn:', 'URN:'], '', $urn);
        foreach ($this->handlers as $key => $func) {
            preg_match('/^' . $key . ':(.*)/i', $urn, $matches);
            if (!empty($matches)) {
                break;
            }
        }
        if (!empty($matches)) {
            if (strpos($matches[1], ':') !== false) {
                $value = explode(':', $matches[1]);
            } else {
                $value = $matches[1];
            }
            return $func($key, $value);
        } else {
            $key = null;
            $value = $urn;
        }

        if (is_callable($this->notFound)) {
            $func = $this->notFound;
            return $func($key, $value);
        }
        return false;
    }

}
