<?php

namespace bashkarev\r01;


use bashkarev\r01\soap\Connection;

abstract class Api
{

    private $_scenario;

    private $_options = [];

    /**
     * get scenario attributes
     * @return array
     */
    abstract function scenarios();

    /**
     * Значения по умолчанию
     * @return array
     */
    public function defaults()
    {
        return [];
    }

    public function __construct($config = [])
    {
        if (!empty($config)) {
            foreach ($config as $key => $value) {
                $this->{$key} = $value;
            }
        }
    }

    public function __set($name, $value)
    {
        $setter = 'set' . str_replace(['-', '_'], '', $name);
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } else {
            $this->_options[$name] = $value;
        }
    }

    public function __isset($name)
    {
        return isset($this->_options[$name]) ? $this->_options[$name] : null;
    }

    public function __get($name)
    {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        } else {
            return null;
        }
    }

    /**
     * @return Connection
     */
    public function getClient()
    {
        return Connection::get();
    }

    /**
     * @param null|string $scenario
     * @return array
     */
    public function attributes($scenario = null)
    {
        if ($scenario === null) {
            $scenario = $this->_scenario;
        }
        $data = [];
        $defaults = $this->defaults();
        foreach ($this->scenarios()[$scenario] as $key) {
            $value = $this->{$key};
            if ($value === null && isset($defaults[$key])) {
                $value = $defaults[$key];
            }
            $data[] = $value;
        }
        return $data;
    }

    /**
     * @param string $scenario
     * @return $this
     */
    public function setScenario($scenario)
    {
        $this->_scenario = $scenario;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getScenario()
    {
        return $this->_scenario;
    }

    public function setOptions($name, $value)
    {
        $this->_options[$name] = $value;
    }

}