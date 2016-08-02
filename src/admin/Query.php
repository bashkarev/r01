<?php

namespace bashkarev\r01\admin;


/**
 * toDo getInfoAboutDadmin
 * Class Query
 * @package bashkarev\r01\admin
 */
class Query extends \bashkarev\r01\Query
{

    const SCENARIO_GET = 'getDadmins';

    /**
     * @inheritdoc
     */
    public $sort_field = Admin::ORDER_FIORUS;

    /**
     * @inheritdoc
     */
    public $sort_dir = SORT_ASC;

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
            ]
        ];
    }

    /**
     * @return Admin|null
     */
    public function one()
    {
        return parent::one();
    }

    /**
     * @return Admin[]|[]
     */
    public function all()
    {
        return parent::all();
    }

}