(function () {
  'use strict';

  var form = document.getElementById('detectForm');
  if (!form) return;

  var unknown = document.getElementById('unknownCard'); // ada bila foto sebelumnya ditolak
  var retryBtn = document.getElementById('retryBtn');

  var root = document.documentElement;
  var input = document.getElementById('foto');
  var zone = document.getElementById('dropzone');
  var preview = document.getElementById('preview');
  var frame = document.getElementById('previewFrame');
  var img = document.getElementById('previewImg');
  var nameEl = document.getElementById('previewName');
  var submitBtn = document.getElementById('submitBtn');
  var resetBtn = document.getElementById('resetBtn');
  var loading = document.getElementById('loading');
  var modal = document.getElementById('fileModal');
  var modalTitle = document.getElementById('fileModalTitle');
  var modalText = document.getElementById('fileModalText');
  var modalOk = document.getElementById('fileModalOk');
  var MAX = 4 * 1024 * 1024;
  var OK = ['image/jpeg', 'image/png', 'image/webp'];
  var objectUrl = null;
  var current = null;   // foto valid yang sedang dipilih
  var lastFocus = null;

  var mb = function (bytes) { return (bytes / 1024 / 1024).toFixed(2).replace('.', ','); };

  // ----- Pop up peringatan (klik di luar kotak atau tombol "Mengerti" untuk menutup) -----
  var openModal = function (title, text) {
    modalTitle.textContent = title;
    modalText.textContent = text;
    lastFocus = document.activeElement;
    modal.hidden = false;
    root.classList.add('modal-open');
    modalOk.focus();
  };
  var closeModal = function () {
    if (modal.hidden || modal.classList.contains('is-leaving')) return;
    modal.classList.add('is-leaving');
    setTimeout(function () {
      modal.hidden = true;
      modal.classList.remove('is-leaving');
      root.classList.remove('modal-open');
      if (lastFocus && lastFocus.focus) lastFocus.focus();
    }, 180);
  };
  modal.addEventListener('click', function (e) {
    if (e.target.closest && e.target.closest('[data-close]')) closeModal();
  });
  document.addEventListener('keydown', function (e) {
    if (modal.hidden) return;
    if (e.key === 'Escape') closeModal();
    else if (e.key === 'Tab') { e.preventDefault(); modalOk.focus(); }
  });

  // ----- Pratinjau -----
  var clearAll = function () {
    current = null;
    input.value = '';
    if (objectUrl) { URL.revokeObjectURL(objectUrl); objectUrl = null; }
    img.removeAttribute('src');
    frame.style.removeProperty('--img');
    preview.hidden = true;
    zone.hidden = false;
    resetBtn.hidden = true;
    submitBtn.disabled = true;
  };

  // kembalikan pilihan sebelumnya ke input (dialog dibatalkan atau foto baru ditolak)
  var restore = function () {
    if (!current) { clearAll(); return; }
    try {
      var dt = new DataTransfer();
      dt.items.add(current);
      input.files = dt.files;
    } catch (e) { /* abaikan */ }
  };

  var reject = function (title, text) {
    openModal(title, text);
    restore();
  };

  var handle = function (file) {
    if (OK.indexOf(file.type) === -1) {
      reject('Format foto tidak didukung', 'File "' + file.name + '" bukan JPG, PNG, atau WEBP. Pilih foto dengan salah satu format tersebut.');
      return;
    }
    if (file.size > MAX) {
      reject('Ukuran foto terlalu besar', 'Foto "' + file.name + '" berukuran ' + mb(file.size) + ' MB, melebihi batas 4 MB. Kecilkan ukuran foto atau pilih foto lain.');
      return;
    }

    // setelah foto ditolak: ganti kartu penolakan dengan form unggah (pratinjau + Analisis Sekarang)
    if (unknown) {
      unknown.hidden = true;
      form.hidden = false;
    }

    current = file;
    if (objectUrl) URL.revokeObjectURL(objectUrl);
    objectUrl = URL.createObjectURL(file);
    img.src = objectUrl;
    frame.style.setProperty('--img', 'url("' + objectUrl + '")');
    nameEl.textContent = file.name + ' (' + mb(file.size) + ' MB)';
    preview.hidden = false;
    zone.hidden = true;
    resetBtn.hidden = false;
    submitBtn.disabled = false;
  };

  input.addEventListener('change', function () {
    var file = input.files && input.files[0];
    if (file) handle(file); else restore();
  });

  // "Ganti Foto" langsung membuka pemilih file, seperti akan mengunggah
  resetBtn.addEventListener('click', function () { input.click(); });
  if (retryBtn) retryBtn.addEventListener('click', function () { input.click(); });

  if (zone) {
    ['dragenter', 'dragover'].forEach(function (ev) {
      zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('drag'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
      zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.remove('drag'); });
    });
    zone.addEventListener('drop', function (e) {
      var files = e.dataTransfer && e.dataTransfer.files;
      if (files && files.length) {
        try { input.files = files; } catch (err) { /* abaikan */ }
        handle(files[0]);
      }
    });
  }

  form.addEventListener('submit', function () {
    if (!(input.files && input.files.length) && current) restore();
    submitBtn.disabled = true;
    loading.hidden = false;
  });

  // tombol "kembali" browser: pastikan overlay tidak tertinggal
  window.addEventListener('pageshow', function () {
    loading.hidden = true;
    if (submitBtn) submitBtn.disabled = !(input.files && input.files.length);
  });
})();
