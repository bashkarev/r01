<?php

namespace bashkarev\r01\soap;


class Exception extends \Exception
{

    protected $name;

    public function __construct($name, $message = "", $code = 0, Exception $previous = null)
    {
        $this->name = $name;
        parent::__construct($message, $code, $previous);
    }


    /**
     * toDo
     * @return array
     */
    public function translate()
    {
        return [
            'Error occured while trying to add domain update task' => 'Возникла ошибка при постановке задания в очередь',
            'You can not clear zone' => 'Вы не можете очистить зону. Недостаточно прав'
        ];
    }

    /**
     * @return string
     */
    public function getMessageRu()
    {
        $message = $this->getMessage();
        if (isset($this->translate()[$message])) {
            return $this->translate()[$message];
        } else {
            return $message;
        }
    }

    public function getName()
    {
        return $this->name;
    }
}