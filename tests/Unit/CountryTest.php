<?php

namespace tests\Unit;

use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class CountryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_returns_arabic_name_when_locale_is_arabic()
    {
        app()->setLocale('ar');
        $country = Country::factory()->create([
            'arabic_name' => 'الإمارات',
            'english_name' => 'UAE'
        ]);

        $this->assertEquals('الإمارات', $country->name);
    }

    #[Test]
    public function it_returns_english_name_when_locale_is_not_arabic()
    {
        app()->setLocale('en');
        $country = Country::factory()->create([
            'arabic_name' => 'الإمارات',
            'english_name' => 'UAE'
        ]);

        $this->assertEquals('UAE', $country->name);
    }

    #[Test]
    public function it_returns_english_name_for_unsupported_locale()
    {
        app()->setLocale('fr'); // any non-ar locale
        $country = Country::factory()->create([
            'arabic_name' => 'الإمارات',
            'english_name' => 'UAE'
        ]);

        $this->assertEquals('UAE', $country->name);
    }

    #[Test]
    public function it_returns_english_name_if_arabic_name_is_missing()
    {
        app()->setLocale('ar');
        $country = Country::factory()->create([
            'arabic_name' => null,
            'english_name' => 'UAE'
        ]);

        $this->assertEquals('UAE', $country->name);
    }

    #[Test]
    public function factory_creates_valid_country()
    {
        $country = Country::factory()->create();

        $this->assertNotNull($country->arabic_name);
        $this->assertNotNull($country->english_name);
}

}