<?php

namespace Vendor\UrlShortener\Tests\Unit\Generators;

use Vendor\UrlShortener\Generators\Base62Generator;
use Vendor\UrlShortener\Tests\TestCase;

class Base62GeneratorTest extends TestCase
{
    private Base62Generator $generator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->generator = new Base62Generator();
    }

    /** @test */
    public function it_generates_code_for_an_id()
    {
        $this->assertEquals('1', $this->generator->generate(1));
        $this->assertEquals('a', $this->generator->generate(36));
        $this->assertEquals('10', $this->generator->generate(62));
    }

    /** @test */
    public function it_generates_unique_codes_for_different_ids()
    {
        $codes = [];
        for ($i = 1; $i <= 100; $i++) {
            $code = $this->generator->generate($i);
            $this->assertNotContains($code, $codes, "Code $code should be unique");
            $codes[] = $code;
        }
    }
} 