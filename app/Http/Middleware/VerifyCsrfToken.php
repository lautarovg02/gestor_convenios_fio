<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [];

    /**
     * Desactiva la verificación CSRF para simplificar el envío de solicitudes y
     * evitar que los tests fallen por problemas con los tokens.
     * El middleware de verificación CSRF ignorará todas las rutas ('*') durante las pruebas.
     * Solo se usa en entornos de pruebas (APP_ENV=testing)
     */
    public function __construct(Application $app, Encrypter $encrypter)
    {
        parent::__construct($app,$encrypter);

        if($_ENV['APP_ENV'] == 'testing'){
            $this->except = ['*'];
        }
    }
}
