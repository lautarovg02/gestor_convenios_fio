<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;  
use Tests\TestCase; 
use App\Models\Teacher;  
use App\Models\Province; // Importar el modelo Province
use App\Models\City; // Importar el modelo City

class TeachersListViewTest extends TestCase  
{  

  use RefreshDatabase;

    /**
     * Test to verify that all teachers are displayed correctly.
     *
     * @test
     * 
     * @test
       @juan2645
     */
     
    public function it_displays_list_of_teachers()
    {
        Province::factory()->count(9)->create();
        City::factory()->count(9)->create();
        $teachers = Teacher::factory()->count(8)->create();

        $response = $this->get('/teachers');
        $response->assertStatus(200);

        foreach ($teachers as $teacher) {
            $response->assertSeeText($teacher->id);
            $response->assertSeeText($teacher->name);
            $response->assertSeeText($teacher->lastname);
            $response->assertSeeText($teacher->dni);
            $response->assertSeeText($teacher->cuil ?? 'N/A');
            $response->assertSeeText($teacher->role);
            $response->assertSeeText($teacher->is_rector ? 'Rector' : 'No es Rector');
            $response->assertSeeText($teacher->is_dean ? 'Decano' : 'No es Decano');
        }
    }

    /**
     * Test to verify that the teacher list updates automatically when a teacher is added or removed.
     *
     * @test
     */
    public function test_it_updates_teacher_list_when_teacher_is_added_or_removed()
    {
        Province::factory()->count(9)->create();
        City::factory()->count(9)->create();
        $teachers = Teacher::factory()->count(5)->create();

        $response = $this->get('/teachers');
        $response->assertStatus(200);
        foreach ($teachers as $teacher) {
            $response->assertSeeText($teacher->name);
        }

        // Add a new teacher
        $newTeacher = Teacher::factory()->create(['name' => 'Nuevo Docente', 'lastname' => 'Apellido']);

        // Verify the new teacher appears on the updated list
        $response = $this->get('/teachers');
        $response->assertSeeText($newTeacher->name);
        $response->assertSeeText($newTeacher->lastname);

        // Delete an existing teacher
        $teacherToDelete = $teachers->first();
        $teacherToDelete->delete();

        // Verify the deleted teacher no longer appears on the list
        $response = $this->get('/teachers');
        $response->assertDontSeeText($teacherToDelete->name);
        $response->assertDontSeeText($teacherToDelete->lastname);
    }

    /**
     * Test that the pagination controls appear on the teacher listing page.
     *
     * @test
     */
    public function test_pagination_controls_are_visible()
    {
        Province::factory()->count(9)->create();
        City::factory()->count(9)->create();
        Teacher::factory()->count(27)->create();

        $response = $this->get(route('teachers.index'));
        $response->assertStatus(200)
            ->assertSee('<nav>', false) // Verify the navigation wrapper exists
            ->assertSee('<ul class="pagination">', false); // Ensure pagination ul is present
    }

    /**
 * Test that the number of teachers displayed per page matches the defined limit.
 *
 * @test
 */
public function test_teachers_displayed_per_page()
{
    Province::factory()->count(9)->create();
    City::factory()->count(9)->create();
    Teacher::factory()->count(27)->create(); // Crea 27 docentes

    $response = $this->get(route('teachers.index', ['page' => 1]));
    $response->assertStatus(200);
    
    $this->assertCount(9, $response->viewData('teachers'));
}

/**
 * Test that each teacher row displays the action buttons: Ver, Editar, and Eliminar Details.
 *
 * @test
 */
public function test_buttons_are_visible_for_each_teacher()  
{  
    // Crea datos de prueba  
    $teachers = Teacher::factory()->count(3)->create(); // Crea 3 docentes para pruebas  

    // Realiza la solicitud GET a la ruta de índice  
    $response = $this->get(route('teachers.index'));  
    $response->assertStatus(200);  

    // Verifica la presencia de cada docente y los botones correspondientes  
    foreach ($teachers as $teacher) {  
        // Verifica la existencia de la fila del docente  
        $response->assertSee($teacher->id);  
        $response->assertSee($teacher->name);  
        $response->assertSee($teacher->lastname);  
        
        // Verifica que los botones "Ver" y "Editar" estén presentes  
        $response->assertSee('Ver', false);  
        $response->assertSee('Editar', false);  
        
        // Verifica el botón "Eliminar"  
        $response->assertSee('data-entity-id="' . $teacher->id . '"', false);  
        $response->assertSee('data-entity-name="' . trim($teacher->name . ' ' . $teacher->lastname) . '"', false);  
    }  
}  
}
