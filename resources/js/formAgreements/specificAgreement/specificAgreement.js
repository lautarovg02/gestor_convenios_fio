console.log('JS cargado correctamente');

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById('convenioMarcoSelect').addEventListener('change', function () {
        console.log("¿Existe contact_email?", document.getElementById('contact_email'));

        const contractId = this.value;
        if (!contractId) return;

        fetch(`/specificAgreement/getFrameworkData/${contractId}`)
            .then(response => response.json())
            .then(data => {
                console.log("Datos recibidos:", data);
              
                // 1. Razón social
                document.getElementById('razon_social').value = data.razon_social || "";

                // 2. Ámbito
                if (data.ambito?.toUpperCase() === 'NACIONAL') {
                    document.getElementById('ambitoNacional').checked = true;
                } else if (data.ambito?.toUpperCase() === 'INTERNACIONAL') {
                    document.getElementById('ambitoInternacional').checked = true;
                }

                // 3. CUIT (dividir en 3 partes si es posible)
                const cuitStr = data.cuit?.toString().padStart(11, '0'); // aseguro 11 dígitos
                document.getElementById('contraparte_cuit_prefijo').value = cuitStr?.slice(0, 2) || "";
                document.getElementById('contraparte_cuit_dni').value = cuitStr?.slice(2, 10) || "";
                document.getElementById('contraparte_cuit_dv').value = cuitStr?.slice(10, 11) || "";

                // 4. Rubro
                document.getElementById('contraparte_rubro').value = data.rubro || "";

                // 5. Titular
                document.getElementById('titular').value = /*data.titular ||*/ "Representante Legal";

                // 6. Confidencialidad
                if (data.confidencialidad === true) {
                    document.getElementById('confSi').checked = true;
                } else {
                    document.getElementById('confNo').checked = true;
                }

                // 7. Calle
                document.getElementById('domicilio_legal_calle').value = data.direccion?.empresa_calle || "";

                // 8. Número
                document.getElementById('domicilio_legal_numero').value = data.direccion?.empresa_numero || "321";

                // 9. Código Postal
                document.getElementById('codigo_postal').value = /*data.direccion?.codigo_postal ||*/ "7400";

                // 10. Ciudad
                document.getElementById('localidad').value = data.direccion?.empresa_ciudad || "";

                // 11. Provincia
                document.getElementById('provincia').value = data.direccion?.empresa_provincia || "Buenos Aires";

                // 12. País
                document.getElementById('pais').value = /*data.direccion?.pais ||*/ "Argentina";

                // 13-18. Representante de Contacto
                document.getElementById('contact_nombre').value = data.contacto?.nombre || "";
                document.getElementById('contact_apellido').value = data.contacto?.apellido || "";
                document.getElementById('contact_cargo').value = data.contacto?.cargo || "";
                //document.getElementById('contact_dni').value = data.contacto?.dni || "";
                document.getElementById('contact_celular').value = data.contacto?.celular || "4343";
                document.getElementById('contEmail').value = data.contacto?.email;
                // 19. Checkbox: representante es el contacto (NO incluido en el JSON)
                // Si lo querés implementar, el backend debería devolver un campo: `firma_igual_contacto`

                // 20-24. Representante de Firma
                document.getElementById('firma_nombre').value = data.firma?.nombre || "";
                document.getElementById('firma_apellido').value = data.firma?.apellido || "";
                document.getElementById('firma_dni').value = data.firma?.dni || "";
                document.getElementById('firma_email').value = data.firma?.email || "";
                document.getElementById('firma_cargo').value = data.firma?.cargo || "";

                // 25. Lugar de firma
                document.getElementById('lugar_firma').value = data.lugar || "";

                // 26. Fecha de firma
                document.getElementById('fecha_firma').value = data.fecha || "";
            })
            .catch(error => {
                console.error('Error al obtener los datos del convenio:', error);
            });
    });



    const select = document.getElementById('student_id');
    const becarioInput = document.getElementById('becario');

    select.addEventListener('change', function () {
        console.log("Cambio en el select de estudiantes");
        
        const selectedOption = this.options[this.selectedIndex];
        const nombre = selectedOption.getAttribute('data-nombre') || '';
        const apellido = selectedOption.getAttribute('data-apellido') || '';
        const dni = selectedOption.getAttribute('data-dni') || '';
        
        const nombreCompleto = `${nombre} ${apellido} - DNI ${dni}`;
        becarioInput.value = nombreCompleto;
        console.log("Valor seleccionado:", nombreCompleto);
    });


});




/*

{{-- Contraparte --}}

1.-Razon social
    2.-ambitoNacional/internacional
    3.-Cuit (tiene 3 partes)
    4.-Rubro
    5.-titular (Titular / Representante Legal / Apoderado)
    6.-Confidencialidad (Si / No)
    
    {{-- Domicilio Legal (Calle y Número) --}}
    
    7.-Calle
    8.-Número
    9.-codigoPostal
    10.-Ciudad
    11.-Provincia
    12.-Pais

    {{-- Representante Contacto --}}

    13.-Nombre
    14.-Apellido
    15.-Cargo
    17.-Celular
    18.-Email

    {{-- Firma --}}
    19.-Checkbox representante es el Contacto
    20.-Nombre
    21.-Apellido
    22.-DNI
    23.-email
    24.-Cargo

    {{-- Lugar y Fecha --}}

    25.-Lugar
    26.-Fecha

 
*/ 