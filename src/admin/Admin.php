<?php

namespace bashkarev\r01\admin;

use bashkarev\r01\Api;
use bashkarev\r01\domain\Domain;
use bashkarev\r01\soap\Exception;


/**
 * Class Person
 * @package bashkarev\r01\admin
 * @property string $nic_hdl Nic-hdl администратора доменов
 * @property string $fiorus Ф.И.О. администратора по-русски
 * @property string $fioeng Ф.И.О. администратора по-английски (транслитерация)
 * @property string $passport Паспортные данные администратора
 * @property string $birth_date Дата рождения администратора
 * @property string $postal_addr Почтовый адрес администратора // Почтовый адрес организации
 * @property string $phone Телефон(ы) администратора
 * @property string $fax  Факс(ы) администратора
 * @property string $e_mail e-mail(ы) администратора
 * @property int $isprotected Флаг сокрытия персональных данных
 * @property int $isresident Флаг - является ли администратор резидентом РФ
 * @property string $orgname_ru Название организации по-русски
 * @property string $orgname_en Название организации по-английски (транслитерация)
 * @property string $inn ИНН
 * @property string $kpp КПП
 * @property string $legal_addr Юридический адрес организации
 * @property string $director_name Ф.И.О. директора организации
 * @property string $bank Название банка организации
 * @property string $ras_schet Номер расчетного счета организации
 * @property string $kor_schet Номер корреспондентского счета организации
 * @property string $bik БИК
 */
class Admin extends Api
{

    const ORDER_NIC_HDL = 'nic_hdl';
    const ORDER_FIORUS = 'fiorus';
    const ORDER_FIOENG = 'fioeng';


    const SCENARIO_ADD = 'addDadminPerson';
    const SCENARIO_UPDATE = 'updateDadminPerson';
    const SCENARIO_ADD_ORG = 'addDadminOrg';
    const SCENARIO_UPDATE_ORG = 'updateDadminOrg';
    const SCENARIO_ADD_IP = 'addDadminIP';
    const SCENARIO_UPDATE_IP = 'updateDadminIP';

    const TYPE_PERSON = 'Person';
    const TYPE_ORG = 'Org';
    const TYPE_IP = 'IP';

    private $_type = 'Person';

    /**
     * @param $type
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setType($type)
    {
        if (!isset($this->scenarios()["addDadmin$type"])) {
            throw new \InvalidArgumentException("available types: Person,Org,IP");
        }
        $this->_type = $type;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            self::SCENARIO_ADD => [
                'nic_hdl', 'fiorus', 'fioeng', 'passport', 'birth_date',
                'postal_addr', 'phone', 'fax', 'e_mail', 'isprotected',
                'isresident', 'inn'
            ],
            self::SCENARIO_UPDATE => [
                'nic_hdl', 'fiorus', 'fioeng', 'passport', 'birth_date',
                'postal_addr', 'phone', 'fax', 'e_mail', 'isprotected',
                'isresident', 'sync_def_contact',
            ],
            self::SCENARIO_ADD_ORG => [
                'nic_hdl', 'orgname_ru', 'orgname_en', 'inn', 'kpp', 'ogrn',
                'legal_addr', 'postal_addr', 'phone', 'fax', 'e_mail', 'director_name',
                'bank', 'ras_schet', 'kor_schet', 'bik', 'isresident'
            ],
            self::SCENARIO_UPDATE_ORG => [
                'nic_hdl', 'orgname_ru', 'orgname_en', 'inn', 'kpp', 'ogrn',
                'legal_addr', 'postal_addr', 'phone', 'fax', 'e_mail', 'director_name',
                'bank', 'ras_schet', 'kor_schet', 'bik', 'isresident', 'sync_def_contact'
            ],
            self::SCENARIO_ADD_IP => [
                'nic_hdl', 'orgname_ru', 'orgname_en', 'passport', 'birth_date',
                'postal_addr', 'phone', 'fax', 'e_mail', 'isprotected',
                'isresident', 'inn'
            ],
            self::SCENARIO_UPDATE_IP => [
                'nic_hdl', 'orgname_ru', 'orgname_en', 'passport', 'birth_date',
                'postal_addr', 'phone', 'fax', 'e_mail', 'isprotected',
                'isresident', 'inn', 'sync_def_contact'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function defaults()
    {
        return [
            'isresident' => 1
        ];
    }

    /**
     * Добавляет нового администратора доменов
     * @return bool
     * @throws Exception
     */
    public function add()
    {
        $scenario = 'addDadmin' . $this->_type;
        $this->setScenario($scenario);
        return ($this->getClient()->$scenario($this->attributes())->status->name == 'OK');
    }

    /**
     * Редактирует администратора доменов.
     * @return bool
     * @throws Exception
     */
    public function update()
    {
        $scenario = 'updateDadmin' . $this->_type;
        $this->setScenario($scenario);
        return in_array($this->getClient()->$scenario($this->attributes())->status->name, ['WARNING_CANT_CHANGE_READONLY', 'OK']);
    }

    /**
     * @return Query
     */
    public static function find()
    {
        return (new Query(__CLASS__))->setScenario(Query::SCENARIO_GET);
    }

    /**
     * @return Domain[]
     * @throws Exception
     */
    public function domains()
    {
        if (empty($this->nic_hdl)) {
            return [];
        }
        return Domain::find()->where(['admin-o' => $this->nic_hdl])->all();
    }

}