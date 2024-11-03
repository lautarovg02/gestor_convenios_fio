//Indicamos las rutas de los archivos que queremos compilar con Vite
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel([
            //CSS
            'resources/css/app.css',
            //JS
            'resources/js/app.js',
            'resources/js/modals/modalDelete.js',
        ]),
    ],
});

