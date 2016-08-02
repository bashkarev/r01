<?php

namespace bashkarev\r01;

use bashkarev\r01\soap\Exception;

class Task extends Api
{
    /**
     * Задача, стоявшая в очереди, успешно выполнена
     */
    const SUCCESS = 'TASK_SUCCESS';

    /**
     * Не удалось выполнить задание и сообщение об ошибке
     */
    const FAILURE = 'TASK_FAILURE';

    /**
     * Задание находится в процессе выполнения
     */
    const QUEUED = 'TASK_QUEUED';

    const SCENARIO_CHECK = 'checkTask';

    /**
     * @var int
     */
    public $taskid;

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_CHECK => [
                'taskid'
            ]
        ];
    }

    /**
     * @return string self::SUCCESS, self::FAILURE
     * @throws Exception
     */
    public function check()
    {
        $this->setScenario(self::SCENARIO_CHECK);
        $result = $this->getClient()->checkTask($this->attributes());
        if ($result->status->name == self::FAILURE) {
            throw new Exception($result->status->name, $result->status->message);
        }
        return $result->status->name;
    }


}