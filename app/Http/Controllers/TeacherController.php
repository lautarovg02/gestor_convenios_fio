<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeacherRequest;
use App\Models\Career;
use App\Models\Teacher;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Http\RedirectResponse;
use PhpParser\Node\Expr\Throw_;

class TeacherController extends Controller
{
    /**
     * Muestra una lista de docentes con la posibilidad de aplicar filtros y búsqueda.
     *
     * Este método permite:
     * - Obtener una lista de docentes paginados.
     * - Aplicar filtros opcionales por carrera y/o rol.
     * - Buscar docentes por un término específico.
     * - Generar mensajes informativos en caso de errores o ausencia de resultados.
     *
     * @param Request $request La solicitud HTTP que contiene los filtros y parámetros de búsqueda.
     * @return \Illuminate\View\View La vista de la lista de docentes.
     */
    public function index(Request $request): View
    {
        $teachers = collect(); // Colección vacía para inicializar
        $careers = collect();  // Lista de carreras
        $roles = [];           // Lista de roles disponibles
        $errorMessage = null;  // Mensaje de error para la vista
        $searchTerm = $request->input('search'); // Término de búsqueda
        $filters = $request->only(['career', 'role']); // Filtros recibidos del usuario

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

            // Aplicar la búsqueda por término si se proporcionó
            if ($searchTerm) {
                $query->search($searchTerm);
            }

            // Obtener los resultados paginados con los parámetros de búsqueda y filtros
            $teachers = $query->paginate(9)->appends(['search' => $searchTerm]);

            // Generar mensajes informativos si no hay resultados
            if ($teachers->isEmpty()) {
                $errorMessage = $this->generateErrorMessage($filters, $careers);
            }
        } catch (Exception $e) {
            // Manejo genérico de errores
            $errorMessage = 'No se pudo recuperar la información de Docentes en este momento. Por favor, inténtelo más tarde.';
            \Log::error('Error al obtener profesores: ' . $e->getMessage()); // Log para depuración
        }

        // Retornar la vista con los datos procesados
        return view('teachers.index', compact('teachers', 'careers', 'roles', 'searchTerm', 'errorMessage'));
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
    public function create(): View
    {
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        try {
            Teacher::create($request->validated());
            return redirect()->route('teachers.index')->with('success', 'Docente ingresado exitosamente.');
        } catch (Exception $e) {
            \Log::error('Error al crear la empresa: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher): View
    {
        $teacher = Teacher::getTeacherWithRoles($teacher->id);
        $teacherWithCareers = Teacher::getTeacherWithRelationToCareers($teacher->id);
        return view('teachers.show', compact('teacherWithCareers', 'teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher): View
    {
        $teacher = Teacher::getAllWithRoles()->find($teacher->id);

        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        try {
            // Guarda el mensaje en la sesión
            $teacher->update($request->validated());
            return redirect()->route('teachers.index')->with('success', 'Docente actualizado exitosamente.');
        } catch (Exception $e) {
            \Log::error($e->getMessage());

            return redirect()->route('teachers.edit', $teacher->id)->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Elimina un docente si no tiene roles asignados y no es decano o rector.
     *
     * @param Teacher $teacher El docente que se desea eliminar.
     * @return \Illuminate\Http\RedirectResponse Redirige a la lista de docentes con un mensaje de éxito o error.
     * @lautarovg02
     */
    public function destroy(Teacher $teacher)
    {
        //* Verifica si el docente tiene algún rol asociado (Director o Coordinador).
        if ($teacher->hasAnyRole()) {
            return redirect()
                ->route('teachers.index')
                ->with('error', 'El docente "<span class="fw-bold">' . $teacher->name . ' ' . $teacher->lastname . '</span>" no se puede eliminar porque tiene el rol de <span class="fw-bold">' . $teacher->getRoleName() . '</span>.');
        }

        //* Verifica si el docente es decano o rector.
        if ($teacher->is_dean || $teacher->is_rector) {
            return redirect()
                ->route('teachers.index')
                ->with('error', 'El docente "<span class="fw-bold">' . $teacher->name . ' ' . $teacher->lastname . '</span>" no se puede eliminar porque es <span class="fw-bold">' . $teacher->getRoleName() . '</span>.');
        }

        //* Si no tiene roles y no es decano/rector, procede a eliminarlo.
        $teacher->delete();

        return redirect()
            ->route('teachers.index')
            ->with('success', 'El docente "<span class="fw-bold">' . $teacher->name . ' ' . $teacher->lastname . '</span>" fue eliminado exitosamente!');
    }
}
