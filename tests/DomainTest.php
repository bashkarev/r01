<?php

use bashkarev\r01\domain\Domain;

class DomainTest extends PHPUnit_Framework_TestCase
{

    public function testException()
    {
        $this->setExpectedException('bashkarev\r01\soap\Exception', 'Domain yandex.ru is already registered.');
        (new Domain([
            'domain' => 'yandex.ru'
        ]))->add();
    }

    public function testFind()
    {
        $this->assertContains('Иванов',
            Domain::find()->notStrict()->where(['name_rus' => 'Иванов'])->one()->person_r
        );
    }

}