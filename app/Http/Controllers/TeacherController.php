<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Exception;

class TeacherController extends Controller
{
    /**
     * Muestra una lista de docentes con la posibilidad de aplicar filtros por carrera y rol.
     *
     * Este método permite:
     * - Obtener una lista de docentes paginados.
     * - Aplicar filtros opcionales por carrera y/o rol.
     * - Generar mensajes informativos en caso de no encontrar resultados.
     *
     * @param Request $request La solicitud HTTP que contiene los filtros opcionales.
     * @return \Illuminate\View\View La vista de la lista de docentes.
     *
      @lautarovg02
     */
    public function index(Request $request)
    {
        $teachers = collect(); // Colección vacía para inicializar
        $careers = collect();  // Lista de carreras
        $roles = [];           // Lista de roles disponibles
        $filters = $request->only(['career', 'role']); // Filtros recibidos del usuario
        $errorMessage = null;  // Mensaje de error para la vista

        try {
            // Obtener las carreras y roles disponibles
            $careers = Career::orderBy('name', 'ASC')->get();
            $roles = Teacher::AVAILABLE_ROLES;

            // Iniciar la consulta base para docentes con sus roles
            $query = Teacher::getAllWithRoles();

            // Aplicar filtros si están definidos
            if ($this->hasFilter($filters, 'career')) {
                $query->filterByCareer($filters['career']);
            }
            if ($this->hasFilter($filters, 'role')) {
                $query->having('role', '=', $filters['role']); // Filtro por rol
            }

            // Obtener los resultados paginados
            $teachers = $query->paginate(9);

            // Generar mensajes informativos si no hay resultados
            if ($teachers->isEmpty()) {
                $errorMessage = $this->generateErrorMessage($filters, $careers);
            }
        } catch (Exception $e) {
            // Manejo genérico de errores
            $errorMessage = 'No se pudo recuperar la información de Docentes en este momento. Por favor, inténtelo más tarde.';
            \Log::error('Error al obtener profesores: ' . $e->getMessage()); // Log para depuración
        }

        return view('teachers.index', compact('teachers', 'careers', 'roles', 'errorMessage'));
    }

    /**
     * Verifica si un filtro específico está presente en la solicitud.
     *
     * Este método se utiliza para evitar llamadas innecesarias cuando un filtro no está definido.
     *
     * @param array $filters Los filtros recibidos de la solicitud.
     * @param string $key La clave del filtro a verificar (por ejemplo, 'career' o 'role').
     * @return bool Devuelve `true` si el filtro está definido y no está vacío.
     *
      @lautarovg02
     */
    private function hasFilter(array $filters, string $key): bool
    {
        return !empty($filters[$key]);
    }

    /**
     * Genera un mensaje de error basado en los filtros aplicados.
     *
     * Este método construye mensajes específicos según los filtros aplicados y los resultados obtenidos:
     * - Si no se encuentra ningún docente en una carrera.
     * - Si no se encuentra ningún docente con un rol específico.
     * - Si no se encuentra ningún docente con ambos filtros combinados.
     *
     * @param array $filters Los filtros aplicados.
     * @param \Illuminate\Support\Collection $careers Lista de carreras disponibles para obtener sus nombres.
     * @return string|null El mensaje de error generado o `null` si no se requiere mensaje.
     *
     * @lautarovg02
     */
    private function generateErrorMessage(array $filters, $careers): ?string
    {
        // Obtener el nombre de la carrera a partir de su ID (si está presente en los filtros)
        $careerName = $careers->firstWhere('id', $filters['career'])->name ?? 'desconocida';

        // Generar mensajes según los filtros aplicados
        if ($this->hasFilter($filters, 'career') && $this->hasFilter($filters, 'role')) {
            return "No hay ningún docente que esté en la carrera '{$careerName}' y tenga el rol '{$filters['role']}'.";
        }
        if ($this->hasFilter($filters, 'career')) {
            return "No hay ningún docente que esté en la carrera '{$careerName}'.";
        }
        if ($this->hasFilter($filters, 'role')) {
            return "No hay ningún docente que tenga el rol '{$filters['role']}'.";
        }

        return null; // No hay mensaje si no se aplicaron filtros
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        //
    }
}
