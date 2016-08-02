<?php
/**
 * toDo phpDoc
 * * deleteDomainOuter
 * * addDomainOuter
 * * addNewRrRecordAsync
 *

 */
namespace bashkarev\r01\domain;


use bashkarev\r01\Api;
use bashkarev\r01\soap\Exception;
use bashkarev\r01\Task;


/**
 *
 * Class Domain
 * @package bashkarev\r01\domain
 * @property string $domain Имя нового домена
 * @property string $nservers Список ns-серверов
 * @property string $admin_o nic-handle администратора
 * @property string $descr Описание домена
 * @property string $check_whois Флаг - нужно ли проверять по whois доступность доменного имени для регистрации
 * @property string $hide_name_nichdl Cкрыть ФИО
 * @property string $hide_email Cкрыть E-Mail
 * @property string $spam_process Контроль спама
 * @property string $hide_phone Cкрыть телефон
 * @property string $hide_phone_email  E-mail для приема голосовой почты.
 * @property int $years Количество лет, на которое регистрируется
 * @property string $registrar Идентификатор регистратора
 * @property boolean $dont_test_ns Отключить проверку DNS-серверов
 * @property boolean $ya_mail Подключить услугу Яндекс.Почта для домена
 * @property boolean $purchase_privacy Подключить платную услугу защиты контактных данных до конца срока регистрации домена
 * @property int $need_replace Флаг - заменять ли предыдущее задание на редактирование 0 | 1
 * @property int $taskid - Номер проверяемого задания в очереди
 */
class Domain extends Api
{

    const ORDER_DOMAIN = 'domain';
    const ORDER_NIC_HDL = 'nic_hdl';
    const ORDER_REG_TILL = 'reg_till';
    const ORDER_STATE = 'state';

    const SCENARIO_ADD = 'addDomain';
    const SCENARIO_UPDATE = 'updateDomain';
    const SCENARIO_CHECK_TASK = 'checkTask';
    const SCENARIO_PROLONG = 'prolongDomain';
    const SCENARIO_CLEAR_ZONE = 'clearZone';

    const STATE_DELEGATED = 'DELEGATED';
    const STATE_NOT_DELEGATED = 'NOT DELEGATED';
    const STATE_ACTIVE = 'Active';
    const STATE_DELETED = 'Deleted';
    const STATE_SUSPENDED = 'Suspended';




    public function setName($value)
    {
        $this->setOptions('domain', $value);
    }

    public function setNserver($value)
    {
        $this->setOptions('nservers', $value);
    }

    public function setAdminO($value)
    {
        $this->setOptions('admin_o', $value);
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => [
                'domain', 'nservers', 'admin_o', 'descr',
                'check_whois', 'hide_name_nichdl', 'hide_email',
                'spam_process', 'hide_phone', 'hide_phone_email',
                'years', 'registrar', 'dont_test_ns', 'ya_mail', 'purchase_privacy'
            ],
            self::SCENARIO_UPDATE => [
                'domain', 'nservers', 'admin_o', 'descr', 'need_replace',
                'hide_name_nichdl', 'hide_email', 'spam_process', 'hide_phone', 'hide_phone_email',
                'dont_test_ns'
            ],
            self::SCENARIO_CHECK_TASK => [
                'taskid'
            ],
            self::SCENARIO_PROLONG => [
                'domain', 'years', 'purchase_privacy'
            ],
            self::SCENARIO_CLEAR_ZONE => [
                'domain'
            ]
        ];
    }

    /**
     * @return boolean
     * @throws Exception
     */
    public function add()
    {
        $this->setScenario(self::SCENARIO_ADD);
        $this->taskid = (int)$this->getClient()->addDomain($this->attributes())->taskid;
        return !empty($this->taskid);
    }

    /**
     * @param null $task
     * @return string
     * @throws Exception
     */
    public function checkTask($task = null)
    {
        if ($task !== null) {
            $this->taskid = $task;
        }
        return (new Task(['taskid' => $this->taskid]))->check();
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function update()
    {
        $this->setScenario(self::SCENARIO_UPDATE);
        $this->taskid = (int)$this->getClient()->updateDomain($this->attributes())->taskid;
        return !empty($this->taskid);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function prolong()
    {
        $this->setScenario(self::SCENARIO_PROLONG);
        $this->taskid = (int)$this->getClient()->prolongDomain($this->attributes())->taskid;
        return !empty($this->taskid);
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function clearZone()
    {
        $this->setScenario(self::SCENARIO_CLEAR_ZONE);
        $this->taskid = (int)$this->getClient()->clearZone($this->attributes())->taskid;
        return !empty($this->taskid);
    }

    /**
     * @return Query
     */
    public static function find()
    {
        return (new Query(__CLASS__))->setScenario(Query::SCENARIO_GET);
    }

}