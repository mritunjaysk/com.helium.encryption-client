<?php

namespace Helium\Encryption\Tests;

use Exception;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
	use SetsUpTests;
}