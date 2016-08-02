# Администратор доменов

## Поиск.
```php
use bashkarev\r01\admin\Admin;

$query = Admin::find();
$query
    ->notStrict()// Нестрогий поиск (по умолчанию строгий)
    ->where([
        'nic_hdl' => '',
        'fiorus' => '',         // ФИО или название по-русски
        'fioeng' => '',         // ФИО или название по-английски
        'is_org' => '',         // Организация или персона
        'e_mail' => '',         // Email
        'simplech' => '',       // Упрещенная смена администратора включена
        'default' => '',        // Администратор по-умолчанию
    ])
    ->limit(10)                 // Количество записей (-1 все, 10, 25, 100, 1000)
    ->order(Admin::ORDER_FIORUS, SORT_ASC);
                                // Person::ORDER_NIC_HDL, Person::ORDER_FIORUS, Person::ORDER_FIOENG;
                                // SORT_ASC, SORT_DESC
$query->all();                  // Все записи
$admin = $query->one();         // Одну
$admin->domains();              // Домены пользователя
// или
$admin = new Admin(['nic_hdl' => 'TEST-R01']);
$admin->domains();              // Домены пользователя

```

# Добавить / Обновить

| Name          | TYPE_PERSON | TYPE_ORG | TYPE_IP |
| --------------|:-----------:|:--------:|:-------:|
| nic_hdl       |      *      |    *     |    *    |
| fiorus        |      *      |          |         |
| fioeng        |      *      |          |         |
| passport      |      *      |          |    *    |
| birth_date    |      *      |          |    *    |
| postal_addr   |      *      |    *     |    *    |
| phone         |      *      |    *     |    *    |
| fax           |      *      |    *     |    *    |
| e_mail        |      *      |    *     |    *    |
| isprotected   |      *      |          |    *    |
| isresident    |      *      |    *     |    *    |
| inn           |      *      |    *     |    *    |
| orgname_ru    |             |    *     |    *    |
| orgname_en    |             |    *     |    *    |
| ogrn          |             |    *     |         |
| legal_addr    |             |    *     |         |
| postal_addr   |             |    *     |         |
| director_name |             |    *     |         |
| bank          |             |    *     |         |
| ras_schet     |             |    *     |         |
| kor_schet     |             |    *     |         |
| bik           |             |    *     |         |


```php

use bashkarev\r01\admin\Admin;
use \bashkarev\r01\Helper;

$admin = new Admin([
    'nic_hdl' => Helper::hdl(10),
    'type'=> Admin::TYPE_PERSON, // Admin::TYPE_PERSON|Admin::TYPE_ORG|Admin::TYPE_IP,
    //... см. таблицу
]);
try {
    $admin->add();                          // boolean
    $admin->phone = '+7 999 9999999';
    $admin->update();                       // boolean
} catch (\bashkarev\r01\soap\Exception $exception) {
    echo $exception->getMessageRu();
}
```