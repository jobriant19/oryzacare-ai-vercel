"""
Prediksi penyakit daun padi memakai model Random Forest.

Preprocessing untuk MODEL harus sama persis dengan di Google Colab:
  baca gambar (BGR) -> resize 64x64 -> rata-rata kanal B,G,R.

Sebelum itu ada PENYARING sederhana, karena model hanya mengenal 4 penyakit
(tidak punya kelas "bukan daun", "ilustrasi", maupun "daun sehat"):
  - not_leaf (graphic) : gambar buatan (latar transparan atau blok piksel identik)
  - not_leaf (color)   : hampir tidak ada warna hijau/kuning daun
  - no_symptoms        : sangat hijau dan nyaris tanpa warna bercak
  - ok                 : lanjut ke hasil model
Angka ambang di bawah bisa disesuaikan dengan python/calibrate.py.

Pemakaian:  python predict.py <path_gambar> [path_model.pkl]
Output   :  satu baris JSON di stdout.
"""
import sys
import os
import json
import warnings

warnings.filterwarnings("ignore")

try:
    import numpy as np
    import cv2
    import joblib
    IMPORT_ERROR = None
except Exception as e:  # dilaporkan sebagai JSON oleh main()
    IMPORT_ERROR = str(e)

# ---- Ambang penyaring (rasio piksel 0..1) ----
ALPHA_MAX = 0.02            # piksel transparan lebih dari ini -> logo/stiker, bukan foto
CONST_MAX = 0.40            # blok 8x8 yang pikselnya identik lebih dari ini -> gambar buatan
MIN_PLANT = 0.06            # hijau + kuning daun di bawah ini -> bukan daun
HEALTHY_MIN_GREEN = 0.60    # sehat jika hijau minimal segini ...
HEALTHY_MAX_LESION = 0.005  # ... dan piksel coklat/oranye/kuning tua maksimal segini


def keluar(data, kode=0):
    sys.stdout.write(json.dumps(data, ensure_ascii=True))
    sys.stdout.flush()
    sys.exit(kode)


def color_metrics(img_bgr):
    """Ukuran warna gambar (diperkecil 128x128) dalam rasio 0..1."""
    small = cv2.resize(img_bgr, (128, 128), interpolation=cv2.INTER_AREA)
    hsv = cv2.cvtColor(small, cv2.COLOR_BGR2HSV)
    h, s, v = [hsv[..., i].astype(int) for i in range(3)]
    n = float(h.size)
    green = ((h >= 30) & (h <= 90) & (s >= 35) & (v >= 35)).sum() / n
    yellow = ((h >= 18) & (h < 30) & (s >= 70) & (v >= 90)).sum() / n
    lesion = ((h >= 5) & (h <= 28) & (s >= 70) & (v >= 60)).sum() / n
    return {
        "green": round(float(green), 4),
        "plant": round(float(green + yellow), 4),
        "lesion": round(float(lesion), 4),
    }


def graphic_metrics(path, img_bgr):
    """Ciri gambar buatan (ilustrasi, animasi, logo, stiker). Foto kamera hampir selalu 0."""
    # 1) transparansi: foto kamera tidak punya latar transparan
    alpha = 0.0
    raw = cv2.imread(path, cv2.IMREAD_UNCHANGED)
    if raw is not None and raw.ndim == 3 and raw.shape[2] == 4:
        alpha = float((raw[..., 3] < 250).mean())

    # 2) blok 8x8 yang semua pikselnya identik (latar putih/warna datar gambar buatan)
    h, w = img_bgr.shape[:2]
    scale = 1000.0 / max(h, w)
    img = cv2.resize(img_bgr, (int(w * scale), int(h * scale)), interpolation=cv2.INTER_NEAREST) if scale < 1 else img_bgr
    hh, ww = (img.shape[0] // 8) * 8, (img.shape[1] // 8) * 8
    if hh == 0 or ww == 0:
        return {"alpha": round(alpha, 4), "const": 0.0}
    b = img[:hh, :ww].reshape(hh // 8, 8, ww // 8, 8, 3).swapaxes(1, 2).reshape(-1, 64, 3)
    rng = (b.max(axis=1).astype(int) - b.min(axis=1).astype(int)).max(axis=1)
    return {"alpha": round(alpha, 4), "const": round(float((rng == 0).mean()), 4)}


def judge(color, graphic):
    """Putusan penyaring -> (status, alasan)."""
    if graphic["alpha"] > ALPHA_MAX or graphic["const"] >= CONST_MAX:
        return "not_leaf", "graphic"
    if color["plant"] < MIN_PLANT:
        return "not_leaf", "color"
    if color["green"] >= HEALTHY_MIN_GREEN and color["lesion"] <= HEALTHY_MAX_LESION:
        return "no_symptoms", None
    return "ok", None


def main():
    if len(sys.argv) < 2:
        keluar({"error": "Path gambar tidak diberikan."}, 1)
    if IMPORT_ERROR:
        keluar({"error": "Library Python belum lengkap: " + IMPORT_ERROR}, 1)

    path_gambar = sys.argv[1]
    folder = os.path.dirname(os.path.abspath(__file__))
    path_model = sys.argv[2] if len(sys.argv) > 2 else os.path.join(folder, "model_random_forest.pkl")

    if not os.path.isfile(path_gambar):
        keluar({"error": "File gambar tidak ditemukan."}, 1)
    if not os.path.isfile(path_model):
        keluar({"error": "File model tidak ditemukan: " + path_model}, 1)

    try:
        keluar(analisis(path_gambar, path_model))
    except ValueError as e:
        keluar({"error": str(e)}, 1)


def analisis(path_gambar, path_model):
    """Jalankan penyaring + model. Melempar ValueError jika gambar tidak terbaca."""
    gambar = cv2.imread(path_gambar)
    if gambar is None:
        raise ValueError("Gambar tidak dapat dibaca. Gunakan JPG, PNG, atau WEBP yang valid.")

    color = color_metrics(gambar)
    graphic = graphic_metrics(path_gambar, gambar)

    # --- prediksi model (preprocessing identik dengan Colab) ---
    kecil = cv2.resize(gambar, (64, 64))
    fitur = np.mean(kecil, axis=(0, 1)).reshape(1, -1)

    model = joblib.load(path_model)
    proba = model.predict_proba(fitur)[0]
    kelas = [str(k) for k in model.classes_]
    idx = int(np.argmax(proba))
    label = kelas[idx]

    # Model baru yang sudah punya kelas tambahan dipercaya langsung;
    # model lama (4 penyakit) memakai penyaring.
    reason = None
    if label == "NotLeaf":
        status, reason = "not_leaf", "color"
    elif label == "Healthy":
        status = "no_symptoms"
    elif "NotLeaf" in kelas or "Healthy" in kelas:
        status = "ok"
    else:
        status, reason = judge(color, graphic)

    return {
        "status": status,
        "reason": reason,
        "label": label,
        "confidence": round(float(proba[idx]) * 100, 2),
        "probabilities": {k: round(float(p) * 100, 2) for k, p in zip(kelas, proba)},
        "metrics": {**color, **graphic},
    }


if __name__ == "__main__":
    try:
        main()
    except SystemExit:
        raise
    except Exception as e:
        keluar({"error": "Kesalahan saat prediksi: " + str(e)}, 1)
