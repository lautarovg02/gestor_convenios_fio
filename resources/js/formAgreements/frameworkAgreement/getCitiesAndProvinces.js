// resources/js/formAgreements/frameworkAgreement/getCitiesAndProvinces.js
document.addEventListener("DOMContentLoaded", () => {
    const $prov = document.getElementById("provincia");
    const $city = document.getElementById("ciudad");
  
    if (!$prov || !$city) return;
  
    // Estado para evitar loops cuando autocompletamos
    const state = { isAutofilling: false };
  
    // Normaliza texto (sin acentos / case-insensitive)
    const norm = (s) =>
      (s ?? "")
        .toString()
        .trim()
        .toLowerCase()
        .normalize("NFD")
        .replace(/\p{Diacritic}/gu, "");
  
    // Selecciona por value o por label (texto visible)
    function setSelectByTextOrValue(sel, target) {
      if (!target) return false;
      const goal = norm(target);
      let foundValue = null;
  
      for (const opt of sel.options) {
        if (norm(opt.value) === goal || norm(opt.textContent) === goal) {
          foundValue = opt.value;
          break;
        }
      }
      if (!foundValue) {
        // si no existe en el listado, lo agrego como opción temporal
        const opt = new Option(target, `__custom__${target}`);
        sel.add(opt);
        foundValue = opt.value;
      }
      sel.value = foundValue;
      return true;
    }
  
    async function loadCities(provinceValue) {
      $city.innerHTML = "<option>Cargando ciudades...</option>";
      $city.disabled = true;
  
      if (!provinceValue) {
        $city.innerHTML = '<option value="">Seleccione una ciudad</option>';
        $city.disabled = true;
        return;
      }
  
      try {
        const res = await fetch(`/cities?provincia=${encodeURIComponent(provinceValue)}`);
        const data = await res.json();
  
        $city.innerHTML = '<option value="">Seleccione una ciudad</option>';
        data.forEach((ciudad) => {
          // La API devuelve { nombre }, mantenemos value = nombre
          $city.add(new Option(ciudad.nombre, ciudad.nombre));
        });
        $city.disabled = false;
      } catch (err) {
        console.error(err);
        $city.innerHTML = "<option>Error al cargar</option>";
        $city.disabled = true;
      }
    }
  
    // Listener normal (cuando el usuario cambia la provincia)
    $prov.addEventListener("change", async function () {
      if (state.isAutofilling) return; // evita rebotes cuando autocompletamos
      await loadCities(this.value);
      $city.value = "";
    });
  
    // API pública para autocompletar por NOMBRE (desde getCompany.js)
    // Uso: await window.geoUI.selectByNames('Buenos Aires', 'Olavarría')
    window.geoUI = window.geoUI || {};
    window.geoUI.selectByNames = async (provinceName, cityName) => {
      state.isAutofilling = true;
      try {
        // Seleccionar provincia por nombre/valor
        setSelectByTextOrValue($prov, provinceName);
  
        // Cargar ciudades de esa provincia (sin disparar el listener normal)
        await loadCities($prov.value);
  
        // Seleccionar ciudad por nombre/valor
        setSelectByTextOrValue($city, cityName);
      } finally {
        state.isAutofilling = false;
      }
    };
  });
  