<?php

declare(strict_types=1);

namespace N1215\LaraTodo;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
