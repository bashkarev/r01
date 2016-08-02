<?php

namespace bashkarev\r01\soap;

use bashkarev\r01\Helper;
use Psr\Log\LoggerInterface;

class Connection
{
    public $wsdl;

    public $options = [];

    /**
     * @var \SoapClient
     */
    public $soap;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * hdl-prefix
     * @var string
     */
    public $hdl;

    public $debug = false;

    private $_isAuth;

    private $_logger;

    private static $_connect;

    public function __construct($config = [])
    {
        Helper::configure($this, $config);
    }

    public function __destruct()
    {
        if ($this->soap !== null) {
            $this->logOut();
            $this->close();
        }
    }

    public function __clone()
    {
        throw new \Exception('__clone forbidden');
    }

    public function __wakeup()
    {
        throw new \Exception('__wakeup forbidden');
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if ($this->soap === null) {
            $this->open();
        }

        if ($this->_isAuth !== true) {
            $this->login();
        }
        $options = isset($arguments[0]) ? $arguments[0] : null;
        return $this->call($name, $options);
    }

    /**
     * @throws Exception
     */
    public function open()
    {
        if ($this->soap !== null) {
            return;
        }
        try {
            $this->soap = new \SoapClient($this->wsdl, $this->options);
        } catch (\SoapFault $e) {
            $this->getLogger()->error($e->getMessage());
            throw new Exception('SOAP_FAULT', $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function close()
    {
        if ($this->soap === null) {
            return null;
        }
        $this->_isAuth = false;
        $this->soap = null;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function login()
    {
        if ($this->_isAuth === true) {
            return;
        }
        $this->open();
        $this->soap->__setCookie('SOAPClient', $this->call('login', [$this->login, $this->password])->status->message);
        $this->_isAuth = true;
        return true;
    }

    /**
     * log out session
     */
    public function logOut()
    {
        if ($this->_isAuth === true && $this->soap !== null) {
            $this->call('logOut');
            unset($this->soap->_cookies);
            $this->_isAuth = false;
        }
    }

    /**
     * @param $method
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    protected function call($method, $options = [])
    {
        $time = microtime(true);
        $token = "SoapClient::$method";

        try {
            $result = call_user_func_array([$this->soap, $method], $options);
        } catch (\SoapFault $e) {
            $this->getLogger()->error($token, [
                'time' => microtime(true) - $time,
                'exception' => $e
            ]);
            throw new Exception('SOAP_FAULT', $e->getMessage(), $e->getCode(), $e);
        }

        if (isset($result->status)) {
            if ($result->status->code == 0) {
                $this->getLogger()->error($token, [
                    'time' => microtime(true) - $time,
                    'data' => $result
                ]);
                throw new Exception($result->status->name, $result->status->message, $result->status->code);
            } else {
                $this->getLogger()->log($result->status->name, $token, [
                    'time' => microtime(true) - $time,
                    'data' => $result
                ]);
            }
        }
        return $result;
    }

    /**
     * @return static
     */
    public static function get()
    {
        if (static::$_connect === null) {
            static::$_connect = new static();
        }
        return static::$_connect;
    }

    /**
     * @param Connection $connect
     * @return static
     */
    public static function set(Connection $connect)
    {
        static::$_connect = $connect;
        return static::$_connect;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->_logger = $logger;
    }

    /**
     * @return Logger
     */
    public function getLogger()
    {
        if ($this->_logger === null) {
            $this->_logger = new Logger();
        }
        return $this->_logger;
    }

}