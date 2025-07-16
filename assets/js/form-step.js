document.addEventListener("DOMContentLoaded", function () {
  // ======================
  // STEP FORM NAVIGATION
  // ======================
  const formSteps = document.querySelectorAll(".form-step");
  const nextBtn = document.getElementById("nextBtn");
  const prevBtn = document.getElementById("prevBtn");
  const submitBtn = document.getElementById("submitBtn");

  let currentStep = 0;

  function showStep(index) {
    formSteps.forEach((step, i) => {
      step.classList.remove("active");
      if (i === index) step.classList.add("active");
    });

    prevBtn.style.display = index === 0 ? "none" : "inline-block";
    nextBtn.style.display = index === formSteps.length - 1 ? "none" : "inline-block";
    submitBtn.style.display = index === formSteps.length - 1 ? "inline-block" : "none";
  }

  nextBtn?.addEventListener("click", () => {
    if (currentStep < formSteps.length - 1) {
      currentStep++;
      showStep(currentStep);
    }
  });

  prevBtn?.addEventListener("click", () => {
    if (currentStep > 0) {
      currentStep--;
      showStep(currentStep);
    }
  });

  showStep(currentStep);

  // ======================
  // TANDA TANGAN DIGITAL
  // ======================
  function enableDrawing(canvasId, hiddenInputId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    let isDrawing = false;

    canvas.addEventListener("mousedown", () => {
      isDrawing = true;
      ctx.beginPath();
    });

    canvas.addEventListener("mousemove", (e) => {
      if (!isDrawing) return;
      const rect = canvas.getBoundingClientRect();
      ctx.lineWidth = 2;
      ctx.lineCap = "round";
      ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
      ctx.stroke();
    });

    canvas.addEventListener("mouseup", () => {
      isDrawing = false;
      const dataUrl = canvas.toDataURL("image/png");
      const hiddenInput = document.getElementById(hiddenInputId);
      if (hiddenInput) hiddenInput.value = dataUrl;
    });

    canvas.addEventListener("mouseleave", () => {
      isDrawing = false;
    });
  }

  function clearCanvas(canvasId) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    const hiddenInputId = canvasId === "canvasAyah" ? "ttd_ayah_digital" : "ttd_ibu_digital";
    const hidden = document.getElementById(hiddenInputId);
    if (hidden) hidden.value = "";
  }

  window.clearCanvas = clearCanvas;
  enableDrawing("canvasAyah", "ttd_ayah_digital");
  enableDrawing("canvasIbu", "ttd_ibu_digital");

  // ======================
  // TOGGLE TANDA TANGAN METHOD
  // ======================
  window.toggleSignatureOption = function (type, method) {
    const uploadEl = document.getElementById(`upload_${type}_section`);
    const drawEl = document.getElementById(`draw_${type}_section`);

    if (uploadEl && drawEl) {
      if (method === "upload") {
        uploadEl.style.display = "block";
        drawEl.style.display = "none";
      } else {
        uploadEl.style.display = "none";
        drawEl.style.display = "block";
      }
    }
  };

  // ======================
  // DROPDOWN WILAYAH - ibnux.github.io API
  // ======================
  const wilayahBase = "https://ibnux.github.io/data-indonesia/";

  const resetSelect = (selectEl, label, loading = false) => {
    if (!selectEl) return;
    selectEl.innerHTML = "";
    const opt = document.createElement("option");
    opt.value = "";
    opt.textContent = loading ? "Memuat..." : label;
    selectEl.appendChild(opt);
  };

  const loadWilayah = async (url, selectEl, label) => {
    resetSelect(selectEl, label, true);
    try {
      const res = await fetch(url);
      const data = await res.json();
      resetSelect(selectEl, label);
      data.forEach((item) => {
        const opt = document.createElement("option");
        opt.value = item.id || item.nama;
        opt.textContent = item.nama;
        selectEl.appendChild(opt);
      });
    } catch (e) {
      console.error(`Gagal memuat ${label.toLowerCase()}:`, e);
      resetSelect(selectEl, `Gagal memuat ${label}`);
    }
  };

  const initWilayahDropdown = (provId, kabId, kecId, kelId) => {
    const prov = document.getElementById(provId);
    const kab = document.getElementById(kabId);
    const kec = document.getElementById(kecId);
    const kel = document.getElementById(kelId);

    if (prov && kab && kec && kel) {
      loadWilayah(`${wilayahBase}provinsi.json`, prov, "Pilih Provinsi");

      prov.addEventListener("change", () => {
        const id = prov.value;
        if (!id) return;
        loadWilayah(`${wilayahBase}kabupaten/${id}.json`, kab, "Pilih Kabupaten/Kota");
        resetSelect(kec, "Pilih Kecamatan");
        resetSelect(kel, "Pilih Kelurahan/Desa");
      });

      kab.addEventListener("change", () => {
        const id = kab.value;
        if (!id) return;
        loadWilayah(`${wilayahBase}kecamatan/${id}.json`, kec, "Pilih Kecamatan");
        resetSelect(kel, "Pilih Kelurahan/Desa");
      });

      kec.addEventListener("change", () => {
        const id = kec.value;
        if (!id) return;
        loadWilayah(`${wilayahBase}kelurahan/${id}.json`, kel, "Pilih Kelurahan/Desa");
      });
    }
  };

  // STEP B (Siswa)
  initWilayahDropdown("provinsi", "kabupaten", "kecamatan", "kelurahan");

  // STEP G (Wali)
  initWilayahDropdown("provinsi_wali", "kabupaten_wali", "kecamatan_wali", "kelurahan_wali");

  // ======================
  // KEWARGANEGARAAN - REST COUNTRIES
  // ======================
  const negaraDropdowns = [
    document.getElementById("kewarganegaraan"),
    document.getElementById("wn_ayah"),
    document.getElementById("wn_ibu"),
    document.getElementById("wn_wali"),
  ].filter(Boolean);

  if (negaraDropdowns.length > 0) {
    negaraDropdowns.forEach((el) => {
      el.innerHTML = '<option value="">Memuat daftar negara...</option>';
    });

    fetch("https://restcountries.com/v3.1/all?fields=name")
      .then((res) => res.json())
      .then((data) => {
        const negaraList = data.map((n) => n.name.common).sort();
        negaraDropdowns.forEach((el) => {
          el.innerHTML = '<option value="">Pilih Negara</option>';
          negaraList.forEach((n) => {
            const opt = document.createElement("option");
            opt.value = n;
            opt.textContent = n;
            el.appendChild(opt);
          });
        });
      })
      .catch(() => {
        negaraDropdowns.forEach((el) => {
          el.innerHTML = '<option value="">Gagal memuat negara</option>';
        });
      });
  }
});
