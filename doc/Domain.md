# Домен

## Список всех доменов.
```php
Domain::find()->my()->all();         //
Domain::find()->my(true)->all();    // показывать время
```

## Поиск.
```php

$query = Domain::find();
$query
    ->notStrict()           // Нестрогий поиск (по умолчанию строгий)
    ->where([
        'domain' =>'',       // имя или часть имени домена для поиска
        'state' =>'',        // состояние домена (ALL/DELEGATED/NOT DELEGATED/Active/Deleted/Suspended)
        'date_from' =>'',    // продлен до - начальная дата поиска
        'date_to' =>'',      // продлен до - конечная дата поиска
        'admin-o' =>'',      // NIC-HDL администратора домена
        'isorg' =>'',        // организационный тип администратора домена (ALL/ORG/PERSON)
        'name_rus' =>'',     // имя или название администратора домена по-русски
        'name_eng' =>''      // имя или название администратора домена по-английски
    ])
    ->limit(10)             // Количество записей (-1 все, 10, 25, 100, 1000)
    ->order(Domain::ORDER_DOMAIN, SORT_ASC)
                             // Domain::ORDER_DOMAIN, Domain::ORDER_NIC_HDL, Domain::ORDER_REG_TILL,Domain::ORDER_STATE
                             // SORT_ASC, SORT_DESC
$query->all()               // Все записи
$query->one()               // Одну
```

## Добавление / Обновление

```php
    $domain = new Domain([

    ])
    $domain->add()          // Добавить

    $domain->update()       // Обновить
```

## Продление
```php

$domain = new Domain(
    [
        'domain' => 'yandex.ru',
        'years' => 1,
    ]
);
try {
    $domain->prolong();      // boolean
    $domain->checkTask();    // Task::QUEUED | Task::SUCCESS
} catch (\bashkarev\r01\soap\Exception $exception) {
    echo $exception->getMessageRu();
}

```

## Очистка DNS-зоны домена от rr-записей (кроме NS-записей).
```php

$domain = new Domain(['domain' => 'yandex.ru']);
try {
    $domain->clearZone();    // boolean
    $domain->checkTask();    // Task::QUEUED | Task::SUCCESS
} catch (\bashkarev\r01\soap\Exception $exception) {
    echo $exception->getMessageRu();
}

```