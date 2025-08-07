document.addEventListener("DOMContentLoaded", () => {
    const selectEmployeeContact = document.getElementById("selectEmployeeContact");
    const selectEmployeeFirma = document.getElementById("selectEmployeeFirma");
  
    function splitCuil(cuil) {
      if (!cuil) return { prefijo: "", dni: "", dv: "" };
      // Soporta formatos "XXXXXXXXXXX" o "XX-XXXXXXXX-X"
      const limpio = String(cuil).replace(/\D/g, "");
      if (limpio.length === 11) {
        return {
          prefijo: limpio.slice(0, 2),
          dni: limpio.slice(2, 10),
          dv: limpio.slice(10, 11),
        };
      }
      return { prefijo: "", dni: "", dv: "" };
    }
  
    function fillContact(emp) {
      document.querySelector('input[name="contact_nombre"]').value   = emp.name || "";
      document.querySelector('input[name="contact_apellido"]').value = emp.lastname || "";
      document.querySelector('input[name="contact_dni"]').value      = emp.dni || "";
      document.querySelector('input[name="contact_email"]').value    = emp.email || "";
      document.querySelector('input[name="contact_celular"]').value  =
        (emp.phones?.[0]?.number) || emp.phone || "";
  
      document.querySelector('input[name="contact_cargo"]').value    = emp.position || "";
  
      // Empresa (si la API la manda)
      const empresa = emp.company?.denomination || emp.company?.company_name || "";
      const empresaInput = document.querySelector('input[name="contact_empresa"]');
      if (empresaInput) empresaInput.value = empresa;
  
      // CUIL en 3 campos
      const { prefijo, dni, dv } = splitCuil(emp.cuil);
      document.querySelector('input[name="cuil_prefijo"]').value = prefijo;
      document.querySelector('input[name="cuil_dni"]').value     = dni;
      document.querySelector('input[name="cuil_dv"]').value      = dv;
    }
  
    function fillFirma(emp) {
      document.querySelector('input[name="firma_nombre"]').value   = emp.name || "";
      document.querySelector('input[name="firma_apellido"]').value = emp.lastname || "";
      document.querySelector('input[name="firma_dni"]').value      = emp.dni || "";
      document.querySelector('input[name="firma_email"]').value    = emp.email || "";
      document.querySelector('input[name="firma_celular"]').value  =
        (emp.phones?.[0]?.number) || emp.phone || "";
      document.querySelector('input[name="firma_cargo"]').value    = emp.position || "";
      // Si necesitás CUIL para firma y también está partido, creá inputs análogos (firma_cuil_prefijo/dni/dv) y hacé split igual que arriba.
    }
  
    function fetchEmployeeAndFill(id, onFill) {
      if (!id) return;
      fetch(`/api/employee/${id}`)
        .then(r => r.json())
        .then(emp => onFill(emp))
        .catch(err => console.error("Error al obtener datos del empleado:", err));
    }
  
    if (selectEmployeeContact) {
      selectEmployeeContact.addEventListener("change", () => {
        fetchEmployeeAndFill(selectEmployeeContact.value, fillContact);
      });
    }
  
    if (selectEmployeeFirma) {
      selectEmployeeFirma.addEventListener("change", () => {
        fetchEmployeeAndFill(selectEmployeeFirma.value, fillFirma);
      });
    }
  });
  