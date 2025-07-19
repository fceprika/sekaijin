<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\Traits\DuskCommonSetup;

class SimpleTest extends DuskTestCase
{
    use DuskCommonSetup;

    /**
     * Test très simple pour vérifier que Dusk fonctionne.
     */
    public function test_homepage_loads(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/');
            $this->waitForPageLoad($browser);
            $browser->assertSee('Le monde est vaste'); // Plus spécifique que juste "Sekaijin"
        });
    }
}
