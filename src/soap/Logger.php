<?php
namespace bashkarev\r01\soap;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

class Logger implements LoggerInterface
{
    use LoggerTrait;

    private $_file;

    public function getFile()
    {
        if ($this->_file === null) {
            $this->_file = fopen(__DIR__ . '/../../debug.log', 'a');
            if ($this->_file === false || !is_resource($this->_file)) {
                throw new \Exception('can\'t open file debug.log');
            }
        }
        return $this->_file;
    }

    public function __destruct()
    {
        if ($this->_file !== null && is_resource($this->_file)) {
            fclose($this->_file);
            $this->_file = null;
        }
    }

    /**
     * @inheritdoc
     */
    public function log($level, $message, array $context = array())
    {
        $time = isset($context['time']) ? $context['time'] : null;
        $data = isset($context['data']) ? $context['data'] : null;
        $exception = isset($context['exception']) ? $context['exception'] : null;
        if (isset($data->status)) {
            $txt = '';
            foreach ($data->status as $key => $value) {
                $txt .= PHP_EOL . "   $key => $value";
            }
            $data = $txt;
        }
        $data .= PHP_EOL . "   " . $exception;
        fwrite($this->getFile(), "$message  $level  $time   $data" . PHP_EOL);
    }
}