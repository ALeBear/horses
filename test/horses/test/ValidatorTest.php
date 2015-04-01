<?php

namespace horses\test;

use horses\Validator;

class ValidatorTest extends AbstractTest
{
    public function testBoolean()
    {
        $validator = Validator::booleanFactory();
        $this->assertTrue($validator->isValid(true));
        $this->assertTrue($validator->isValid(1));
        $this->assertTrue($validator->isValid('1'));
        $this->assertTrue($validator->isValid('true'));
        $this->assertTrue($validator->isValid('on'));
        $this->assertFalse($validator->isValid(false));
        $this->assertFalse($validator->isValid(null));
        $this->assertFalse($validator->isValid('foo'));
        $this->assertFalse($validator->isValid(0));
        $this->assertFalse($validator->isValid('0'));
    }

    public function testEmail()
    {
        $validator = Validator::emailFactory();
        $this->assertTrue($validator->isValid('aa@bb.com'));
        $this->assertTrue($validator->isValid('foo@bar.complicated'));
        $this->assertFalse($validator->isValid(false));
        $this->assertFalse($validator->isValid('foo'));
        $this->assertFalse($validator->isValid(0));
    }

    public function testFloat()
    {
        $validator = Validator::floatFactory('.');
        $this->assertTrue($validator->isValid(0));
        $this->assertTrue($validator->isValid(1));
        $this->assertTrue($validator->isValid(-1));
        $this->assertTrue($validator->isValid(0.3));
        $this->assertTrue($validator->isValid('0.3'));
        $this->assertTrue($validator->isValid(-0.3));
        $this->assertTrue($validator->isValid(1232264.254545));
        $this->assertFalse($validator->isValid('foo'));
        $validator = Validator::floatFactory(',');
        $this->assertTrue($validator->isValid(0));
        $this->assertTrue($validator->isValid(1));
        $this->assertTrue($validator->isValid(-1));
        $this->assertTrue($validator->isValid('0,3'));
    }

    public function testInt()
    {
        $validator = Validator::intFactory();
        $this->assertTrue($validator->isValid(0));
        $this->assertTrue($validator->isValid(1));
        $this->assertTrue($validator->isValid(-1));
        $this->assertTrue($validator->isValid(3));
        $this->assertTrue($validator->isValid('3'));
        $this->assertTrue($validator->isValid(123458));
        $this->assertFalse($validator->isValid('foo'));
        $validator = Validator::intFactory(3);
        $this->assertFalse($validator->isValid(0));
        $this->assertFalse($validator->isValid(-5));
        $this->assertFalse($validator->isValid(1));
        $this->assertTrue($validator->isValid(3));
        $this->assertTrue($validator->isValid(3333));
        $validator = Validator::intFactory(3, 5);
        $this->assertFalse($validator->isValid(0));
        $this->assertFalse($validator->isValid(-5));
        $this->assertFalse($validator->isValid(1));
        $this->assertTrue($validator->isValid(3));
        $this->assertFalse($validator->isValid(3333));
    }

    public function testRegexp()
    {
        $validator = Validator::regexpFactory('foo');
        $this->assertFalse($validator->isValid(0));
        $this->assertTrue($validator->isValid('foobar'));
        $this->assertTrue($validator->isValid('foo'));
        $this->assertFalse($validator->isValid('fo/o'));
        $validator = Validator::regexpFactory('fo*o');
        $this->assertFalse($validator->isValid(0));
        $this->assertTrue($validator->isValid('foobar'));
        $this->assertTrue($validator->isValid('fooasdfasdfooo'));
        $this->assertTrue($validator->isValid('fo/o'));
    }

    public function testCallback()
    {
        $validator = Validator::callbackFactory(function ($value) { return $value == 'bar'; });
        $this->assertFalse($validator->isValid(0));
        $this->assertFalse($validator->isValid('foobar'));
        $this->assertFalse($validator->isValid('foo'));
        $this->assertTrue($validator->isValid('bar'));
    }
}
