<?php
namespace bashkarev\r01;

/**
 * toDo next(), list
 * Class Query
 * @package bashkarev\r01
 */
abstract class Query extends Api
{

    public $modelClass;

    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        parent::__construct($config);
    }

    private static $_names = [];

    /**
     * @var array
     */
    public $params = [];

    /**
     * Строгий/нестрогий
     * @var integer
     */
    public $strict = 1;

    /**
     * поле сортировки
     * @var string
     */
    public $sort_field;

    /**
     * направление сортировки
     * @var string
     */
    public $sort_dir;

    /**
     * номер страницы (блока информации в ответе)
     * @var int
     */
    public $page;

    /**
     * принимаются значения
     * -1,10,25,100,1000; (-1 - выдать все записи одним списком)
     * @var int
     */
    public $limit = 10;

    /**
     * @param $page
     * @return $this
     */
    public function page($page)
    {
        $this->page = $page;
        return $this;
    }

    /**
     * @param $limit
     * @return $this
     */
    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return $this
     */
    public function notStrict()
    {
        $this->strict = 0;
        return $this;
    }

    /**
     * @param array $where
     * @return $this
     */
    public function where($where = [])
    {
        foreach ($where as $key => $value) {
            $this->params[$key] = $value;
        }
        return $this;
    }

    /**
     * @return static|null
     */
    public function one()
    {
        $data = $this->limit(10)->getData();
        if (!isset($data[0]) || empty($data[0])) {
            return null;
        }
        return new $this->modelClass($data[0]);
    }

    /**
     * @return static[]|[]
     */
    public function all()
    {
        $result = $this->getData();
        if (empty($result)) {
            return [];
        }
        foreach ($result as $values) {
            $data[] = new $this->modelClass($values);
        }
        return $data;
    }

    /**
     * @return array
     */
    protected function getData()
    {
        $result = $this->getClient()->{$this->getScenario()}($this->attributes());
        if (!isset($result->data)) {
            return [];
        }

        $name = $this->name($result->data);
        return $result->data->{$name};
    }

    /**
     * @param $data
     * @return mixed
     */
    public function name($data)
    {
        $scenario = $this->getScenario();
        if (!isset(self::$_names[$scenario])) {
            self::$_names[$scenario] = array_keys((array)$data)[1];

        }
        return self::$_names[$scenario];
    }

    /**
     * @param string $field
     * @param  int $order SORT_ASC || SORT_DESC
     * @return $this
     */
    public function order($field, $order = SORT_ASC)
    {
        $this->sort_field = $field;
        $this->sort_dir = ($order == SORT_ASC) ? 'asc' : 'desc';
        return $this;
    }
}