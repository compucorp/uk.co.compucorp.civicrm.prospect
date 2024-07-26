<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\TransactionalInterface;
use PHPUnit\Framework\TestCase;

abstract class BaseHeadlessTest extends TestCase implements
    HeadlessInterface,
    TransactionalInterface {

  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->install('uk.co.compucorp.civicase')
      ->installMe(__DIR__)
      ->apply();
  }

}
