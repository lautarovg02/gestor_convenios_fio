document.addEventListener("DOMContentLoaded", () => {
  const selectCompany = document.getElementById("selectCompany");
  const selectEmployeeContact = document.getElementById("selectEmployeeContact");
  const selectEmployeeFirma = document.getElementById("selectEmployeeFirma");

  const preselectedContact = selectEmployeeContact?.dataset?.preselected || null;
  const preselectedFirma = selectEmployeeFirma?.dataset?.preselected || null;

  // Helpers
  function splitCUIT(cuitRaw) {
    const digits = String(cuitRaw ?? '').replace(/\D/g, '');
    const d = digits.length >= 11 ? digits.slice(0, 11) : digits.padStart(11, '0');
    return { prefijo: d.slice(0, 2), dni: d.slice(2, 10), dv: d.slice(10, 11) };
  }

  function firstPhoneFrom(emp) {
    // Soporta: [{phone_number: '...'}] o ['...', ...] o emp.phone
    const arr = Array.isArray(emp?.phones) ? emp.phones : [];
    console.log('Phones:', arr);
    if (arr.length) {
      const p = arr[0];
      return (typeof p === 'string') ? p : (p.number ?? p.number ?? '');
    }
    return emp?.phone || '';
  }

  // Fillers
  function fillContact(emp) {
    document.querySelector('input[name="contact_nombre"]').value = emp.name || "";
    document.querySelector('input[name="contact_apellido"]').value = emp.lastname || "";
    document.querySelector('input[name="contact_dni"]').value = emp.dni || "";
    document.querySelector('input[name="contact_email"]').value = emp.email || "";
    document.querySelector('input[name="contact_celular"]').value = firstPhoneFrom(emp);
    document.querySelector('input[name="contact_cargo"]').value = emp.position || "";

    // Solo pisar empresa si viene en la respuesta
    const empresaInput = document.querySelector('input[name="contact_empresa"]');
    if (empresaInput) {
      const companyName = emp?.company?.company_name || emp?.company?.denomination || null;
      if (companyName) empresaInput.value = companyName;
    }

    const { prefijo, dni, dv } = splitCUIT(emp.cuil);
    document.querySelector('input[name="cuil_prefijo"]').value = prefijo;
    document.querySelector('input[name="cuil_dni"]').value = dni;
    document.querySelector('input[name="cuil_dv"]').value = dv;
  }

  function fillFirma(emp) {
    document.querySelector('input[name="firma_nombre"]').value = emp.name || "";
    document.querySelector('input[name="firma_apellido"]').value = emp.lastname || "";
    document.querySelector('input[name="firma_dni"]').value = emp.dni || "";
    document.querySelector('input[name="firma_email"]').value = emp.email || "";
    document.querySelector('input[name="firma_celular"]').value = firstPhoneFrom(emp);
    document.querySelector('input[name="firma_cargo"]').value = emp.position || "";
  }

  // API
  async function fetchEmployeeAndFill(id, onFill) {
    if (!id) return;
    try {
      const res = await fetch(`/api/employee/${id}`);
      const ct = res.headers.get('content-type') || '';
      if (!res.ok) {
        // Si vino un 500, mostrá el HTML del error en consola
        const text = await res.text();
        console.error('API /api/employee error', res.status, text);
        return;
      }
      const emp = ct.includes('application/json') ? await res.json() : JSON.parse(await res.text());

      // 👇 VER TODO EL PAYLOAD
      console.log('[EMPLOYEE PAYLOAD]', emp);

      onFill(emp);
    } catch (err) {
      console.error('Error al obtener datos del empleado:', err);
    }
  }

  // Cargar representantes por empresa
  async function loadRepresentatives(companyId, targetSelect, preselectedId = null) {
    if (!targetSelect) return;
    targetSelect.innerHTML = '<option value="">Seleccione un representante</option>';
    targetSelect.disabled = true;

    if (!companyId) { targetSelect.disabled = false; return; }

    try {
      const res = await fetch(`/api/employees/${companyId}`);
      if (!res.ok) throw new Error('No se pudieron cargar los representantes');

      const items = await res.json(); // [{id,text}]
      if (!Array.isArray(items) || items.length === 0) {
        targetSelect.insertAdjacentHTML(
          'beforeend',
          '<option value="">No hay representantes para esta empresa</option>'
        );
      } else {
        for (const it of items) {
          const opt = document.createElement('option');
          opt.value = it.id;
          opt.textContent = it.text;
          targetSelect.appendChild(opt);
        }
        if (preselectedId) targetSelect.value = preselectedId;
      }
    } catch (e) {
      console.error(e);
    } finally {
      targetSelect.disabled = false;
    }
  }

  // Precarga y change de empresa
  if (selectCompany) {
    if (selectCompany.value) {
      loadRepresentatives(selectCompany.value, selectEmployeeContact, preselectedContact);
      if (selectEmployeeFirma) {
        loadRepresentatives(selectCompany.value, selectEmployeeFirma, preselectedFirma);
      }
    }




    // === checkbox: "El representante de contacto es también el de firma" ===
    const sameChk = document.getElementById('sameRepresentative');
    const selC = document.getElementById('selectEmployeeContact');
    const selF = document.getElementById('selectEmployeeFirma');

    // helper: copia campos de Contacto -> Firma
    function copyContactToFirma() {
      // si tenés selects de empleados, reflejá también el id seleccionado
      if (selC && selF) {
        selF.value = selC.value || '';
        // dispara el "change" para que se ejecute fillFirma() y traiga los datos del empleado
        selF.dispatchEvent(new Event('change'));
      }

      // además, copia manualmente los inputs por si no usás el select de firma
      const map = [
        ['contact_nombre', 'firma_nombre'],
        ['contact_apellido', 'firma_apellido'],
        ['contact_dni', 'firma_dni'],
        ['contact_email', 'firma_email'],
        ['contact_celular', 'firma_celular'],
        ['contact_cargo', 'firma_cargo'],
      ];
      map.forEach(([from, to]) => {
        const src = document.querySelector(`input[name="${from}"]`);
        const dst = document.querySelector(`input[name="${to}"]`);
        if (src && dst) dst.value = src.value || '';
      });
    }

    // mientras el check esté activo, mantené sincronizado al editar Contacto
    let syncHandlers = [];
    function startSync() {
      stopSync();
      const names = ['contact_nombre', 'contact_apellido', 'contact_dni', 'contact_email', 'contact_celular', 'contact_cargo'];
      names.forEach(n => {
        const el = document.querySelector(`input[name="${n}"]`);
        if (!el) return;
        const h = () => copyContactToFirma();
        el.addEventListener('input', h);
        syncHandlers.push([el, h]);
      });

      // si cambia el select de contacto, sincronizá también
      if (selC) {
        const h = () => copyContactToFirma();
        selC.addEventListener('change', h);
        syncHandlers.push([selC, h]);
      }
    }
    function stopSync() {
      syncHandlers.forEach(([el, h]) => el.removeEventListener('input', h));
      // algunos handlers son de 'change'
      syncHandlers.forEach(([el, h]) => el.removeEventListener('change', h));
      syncHandlers = [];
    }

    sameChk?.addEventListener('change', () => {
      if (sameChk.checked) {
        copyContactToFirma();
        startSync();
        // opcional: volver de solo lectura los campos de firma (NO los deshabilites si necesitás que se envíen)
        ['firma_nombre', 'firma_apellido', 'firma_dni', 'firma_email', 'firma_celular', 'firma_cargo']
          .forEach(n => { const el = document.querySelector(`input[name="${n}"]`); if (el) el.readOnly = true; });
      } else {
        stopSync();
        ['firma_nombre', 'firma_apellido', 'firma_dni', 'firma_email', 'firma_celular', 'firma_cargo']
          .forEach(n => { const el = document.querySelector(`input[name="${n}"]`); if (el) el.readOnly = false; });
      }
    });

    // por si la página carga con el check activo
    if (sameChk?.checked) {
      copyContactToFirma();
      startSync();
    }

    // extra: si ya tenés este listener, agregá SOLO esta línea dentro:
    selC?.addEventListener('change', () => {
      if (sameChk?.checked) copyContactToFirma();
    });








    selectCompany.addEventListener("change", () => {
      loadRepresentatives(selectCompany.value, selectEmployeeContact, null);
      if (selectEmployeeFirma) {
        loadRepresentatives(selectCompany.value, selectEmployeeFirma, null);
      }
    });
  }

  // Al elegir empleado, completar inputs
  selectEmployeeContact?.addEventListener("change", () => {
    fetchEmployeeAndFill(selectEmployeeContact.value, fillContact);
  });

  selectEmployeeFirma?.addEventListener("change", () => {
    fetchEmployeeAndFill(selectEmployeeFirma.value, fillFirma);
  });
});
