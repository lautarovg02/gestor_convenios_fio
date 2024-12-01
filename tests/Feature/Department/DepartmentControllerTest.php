<?php  

namespace Tests\Feature;  

use App\Models\Department;  
use App\Models\Teacher;  
use Illuminate\Foundation\Testing\RefreshDatabase;  
use Tests\TestCase;  
use App\Models\User; 

class DepartmentControllerTest extends TestCase  
{  
    use RefreshDatabase;  

    public function test_update_department()  
    {  
        // Crear un docente de prueba.  
        $teacher = Teacher::factory()->create(['lastname' => 'Gonzalez']);  
        
        // Crear un departamento, asegurando que tiene un director.  
        $department = Department::factory()->create(['name' => 'Ciencias', 'director_id' => $teacher->id]);  

        // Hacer la solicitud de actualización.  
        $response = $this->put(route('departments.update', $department->id), [  
            'name' => 'Ciencias Avanzadas',  
            'director_id' => $teacher->id, // Asegúrate de que el director existe.  
        ]);  

        // Asegúrate de que redirige a la lista de departamentos.  
        $response->assertRedirect(route('departments.index'));  

        // Verifica que el departamento fue actualizado en la base de datos.  
        $this->assertDatabaseHas('departments', [  
            'id' => $department->id,  
            'name' => 'Ciencias Avanzadas',  
        ]);  

        // Verifica que el mensaje de éxito esté en la sesión.  
        $this->assertTrue(session()->has('success'));  
        $this->assertEquals('Departamento editado exitosamente', session('success'));  
    }  

    public function test_update_department_validation()  
    {  
        // Crear un docente de prueba.  
        $teacher = Teacher::factory()->create(['lastname' => 'Gonzalez']);  
        
        // Crear un departamento con un director.  
        $department = Department::factory()->create(['name' => 'Ciencias', 'director_id' => $teacher->id]);  

        // Hacer una solicitud de actualización con datos inválidos (sin nombre).  
        $response = $this->put(route('departments.update', $department->id), [  
            'name' => '', // Nombre vacío debería valorarse como error.  
        ]);  

        // Asegúrate de que se devuelva la respuesta correcta.  
        $response->assertSessionHasErrors('name'); // Verifica que haya un error para el campo 'name'.  
    }  

    public function test_edit_button_is_visible_on_index()
    {
        // Crear un teacher para evitar el error de null
        $teacher = \App\Models\Teacher::factory()->create();

        // Crear un departamento asociado al teacher
        $department = \App\Models\Department::factory()->create([
            'director_id' => $teacher->id,
        ]);

        // Realizar la solicitud GET al índice de departamentos
        $response = $this->get(route('departments.index'));

        // Verificar que el botón "Editar" esté presente en la página
        $response->assertSee('Editar');
    }

    public function test_department_name_is_required()
    {
        // Crear un teacher para evitar el error de null en el factory
        $teacher = \App\Models\Teacher::factory()->create();

        // Crear un departamento asociado al teacher
        $department = \App\Models\Department::factory()->create([
            'name' => 'Original',  // Nombre original
            'director_id' => $teacher->id,  // Asignar un director válido
        ]);

        // Intentar actualizar el departamento con un nombre vacío
        $response = $this->put(route('departments.update', $department->id), [
            'name' => '',  // El campo 'name' está vacío
            'director_id' => $department->director_id,  // Usar el director ya asociado
        ]);

        // Verificar que se muestre un error para el campo 'name'
        $response->assertSessionHasErrors('name');  // Comprobamos si la validación falla para el campo 'name'
    }


    public function test_director_id_must_exist()
    {
        // Crear un teacher válido para asociar a un departamento
        $teacher = \App\Models\Teacher::factory()->create();

        // Crear un departamento asociado a un director válido
        $department = \App\Models\Department::factory()->create([
            'director_id' => $teacher->id,  // Usar un director válido
        ]);

        // Intentar actualizar el departamento con un director_id que no existe
        $response = $this->put(route('departments.update', $department->id), [
            'name' => 'New Name',
            'director_id' => 999, // ID que no existe
        ]);

        // Verificar que se muestre un error para el campo 'director_id'
        $response->assertSessionHasErrors('director_id');
    }

    public function test_success_message_on_update()  
    {  
        $teacher = Teacher::factory()->create();  
        $department = Department::factory()->create(['director_id' => $teacher->id]);  

        // Actualizar el departamento  
        $response = $this->put(route('departments.update', $department->id), [  
            'name' => 'Nuevo Nombre',  
            'director_id' => $teacher->id,  
        ]);  

        // Verificar que la redirección sea correcta
        $response->assertRedirect(route('departments.index'));  

        // Verificar que el mensaje de éxito esté en la sesión  
        $response->assertSessionHas('success', 'Departamento editado exitosamente');  
    }

    public function test_failure_message_on_invalid_update()  
    {  
        // Crear un teacher antes de crear el department
        $teacher = Teacher::factory()->create();  

        // Crear un departamento con el teacher
        $department = Department::factory()->create([
            'director_id' => $teacher->id, // Usar el ID del teacher creado
        ]);  

        // Intentar actualizar el departamento con un 'name' vacío (inválido)
        $response = $this->put(route('departments.update', $department->id), [  
            'name' => '',  // 'name' vacío
            'director_id' => $department->director_id,  // Usar el 'director_id' ya asignado
        ]);  

        // Verificar que se redirige y que se muestra un mensaje de error
        $response->assertRedirect();  
        $response->assertSessionHasErrors('name');  // Verifica que haya un error para 'name'
    }

}