<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnvirotmentTest extends TestCase
{
    public function testGetEnv()
    {
        $coba = 'coba';

        self::assertEquals('coba', $coba);
        
    }
}
