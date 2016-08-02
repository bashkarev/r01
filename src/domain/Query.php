<?php

namespace bashkarev\r01\domain;

use bashkarev\r01\soap\Exception;


/**
 * toDo
 *  * scenario SCENARIO_PUSH not working
 * Class Query
 * @package bashkarev\r01\domain
 * where
 *  * domain    -   имя или часть имени домена для поиска
 *  * state     -   состояние домена (ALL/DELEGATED/NOT DELEGATED/Active/Deleted/Suspended)
 *  * date_from -   продлен до - начальная дата поиска
 *  * date_to   -   продлен до - конечная дата поиска
 *  * admin-o   -   NIC-HDL администратора домена
 *  * isorg     -   организационный тип администратора домена (ALL/ORG/PERSON)
 *  * name_rus  -   имя или название администратора домена по-русски
 *  * name_eng  -   имя или название администратора домена по-английски
 */
class Query extends \bashkarev\r01\Query
{

    const SCENARIO_GET = 'getDomains';
    const SCENARIO_PUSH = 'getDomainsForPushIn';
    const SCENARIO_MY = 'getDomainsAllSimple';
    const SCENARIO_MY_DATE = 'getDomainsAllSimpleModified';

    /**
     * Формат даты для возвращаемого поля reg-till. 0 = 'd-m-Y', 1 = 'Y-m-d H:i:s
     * @var
     */
    public $date_format = 0;
    

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_GET => [
                'params',
                'strict',
                'sort_field',
                'sort_dir',
                'limit',
                'page'
            ],
            self::SCENARIO_PUSH => [
                'page', 'limit', 'sort_field', 'sort_dir'
            ],
            self::SCENARIO_MY => [
                'page'
            ],
            self::SCENARIO_MY_DATE => [
                'date_format'
            ]
        ];
    }

    /**
     * @return $this
     */
    public function push()
    {
        return $this->setScenario(self::SCENARIO_PUSH);
    }

    /**
     * @param bool $time show time
     * @return $this
     */
    public function my($time = false)
    {
        if ($time === false) {
            $this->setScenario(self::SCENARIO_MY);
        } else {
            $this->setScenario(self::SCENARIO_MY_DATE);
            $this->date_format = 1;
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function where($where = [])
    {
        if ($this->getScenario() != self::SCENARIO_GET) {
            throw new Exception('Query::where() only for scenario ' . self::SCENARIO_GET);
        }
        return parent::where($where);
    }

    /**
     * @inheritdoc
     */
    public function order($field, $order = SORT_ASC)
    {
        if ($this->getScenario() != self::SCENARIO_GET) {
            throw new Exception('Query::order() only for scenario ' . self::SCENARIO_GET);
        }
        return parent::order($field, $order);
    }

}