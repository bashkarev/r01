<?php

use bashkarev\r01\admin\Admin;
use \bashkarev\r01\Helper;

class AdminTest extends PHPUnit_Framework_TestCase
{

    public function testFind()
    {
        $this->assertTrue(Admin::find()->where(['nic_hdl' => '1000255_1359485341903-R01'])->one()->nic_hdl == '1000255_1359485341903-R01');
        $this->assertNull(Admin::find()->where(['fiorus' => 'Иванов'])->one());
        $this->assertNotNull(Admin::find()->notStrict()->where(['fiorus' => 'Иванов'])->one());
    }

    public function testGri()
    {
        $faker = Faker\Factory::create('ru_RU');
        $name = $faker->name;
        $admin = new Admin([
            'nic_hdl' => Helper::hdl(time()),
            'type' => Admin::TYPE_PERSON,
            'fiorus' => $name,
            'fioeng' => Helper::translit($name),
            'passport' => '111111 1111111 овд12  2000-01-11',
            'birth_date' => $faker->dateTimeInInterval('-20 years')->format('d.m.Y'),
            'postal_addr' => $faker->address,
            'phone' => $faker->numerify('+7 9## #######'),
            'fax' => $faker->numerify('+7 8### ######'),
            'e_mail' => $faker->email,
        ]);
        $this->assertTrue($admin->add());
        $admin->phone = $faker->numerify('+7 9## #######');
        $this->assertTrue($admin->update());
        $this->assertTrue(Admin::find()->where(['nic_hdl' => $admin->nic_hdl])->one()->phone == $admin->phone);
    }

    public function testGriOrg()
    {
        $faker = Faker\Factory::create('ru_RU');
        $company = $faker->company;
        $admin = new Admin([
            'nic_hdl' => Helper::hdl(time(), true),
            'type' => Admin::TYPE_ORG,
            'orgname_ru' => $company,
            'orgname_en' => Helper::translit($company),
            'inn' => $faker->numerify('##########'),
            'kpp' => $faker->numerify('#########'),
            'ogrn' => $faker->numerify('#############'),
            'legal_addr' => $faker->address,
            'postal_addr' => $faker->address,
            'phone' => $faker->numerify('+7 9## #######'),
            'fax' => $faker->numerify('+7 8### ######'),
            'e_mail' => $faker->email,
            'director_name' => $faker->name,
            'bank' => $faker->bank(),
            'ras_schet' => $faker->numerify('####################'),
            'kor_schet' => $faker->numerify('####################'),
            'bik' => $faker->numerify('########'),
        ]);
        $this->assertTrue($admin->add());
        $admin->phone = $faker->numerify('+7 9## #######');
        $this->assertTrue($admin->update());
        $this->assertTrue(Admin::find()->where(['nic_hdl' => $admin->nic_hdl])->one()->phone == $admin->phone);
    }

}