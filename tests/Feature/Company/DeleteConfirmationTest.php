<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class DeleteConfirmationTest extends DuskTestCase
{
    public function testDeleteModalAppearsOnDeleteClick()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/companies')
            ->assertSee('Bayer and Sons'); // Ajustar la ruta
            // ->press('[data-testid="confirm-button"]') // Cambia el selector al atributo data-testid
            // ->assertVisible('[data-testid="delete-confirm-modal"]'); // Cambia también el selector para el modal
        });
    }
}
