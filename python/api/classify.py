"""Endpoint Vercel: terima byte gambar (POST), balas JSON hasil prediksi."""
import hmac
import json
import os
import sys
import tempfile
from http.server import BaseHTTPRequestHandler
from pathlib import Path

PYTHON_DIR = Path(__file__).resolve().parent.parent
sys.path.insert(0, str(PYTHON_DIR))

import predict  # noqa: E402

MODEL = PYTHON_DIR / "model_random_forest.pkl"
MAX_BYTES = 4_500_000


class handler(BaseHTTPRequestHandler):
    def _kirim(self, kode, data):
        isi = json.dumps(data).encode()
        self.send_response(kode)
        self.send_header("Content-Type", "application/json")
        self.send_header("Content-Length", str(len(isi)))
        self.end_headers()
        self.wfile.write(isi)

    def do_POST(self):
        token = os.environ.get("CLASSIFIER_TOKEN", "")
        kirim = self.headers.get("X-Api-Token", "")
        if not token or not hmac.compare_digest(kirim, token):
            return self._kirim(401, {"error": "Tidak diizinkan."})
        if predict.IMPORT_ERROR:
            return self._kirim(500, {"error": "Library Python belum lengkap: " + predict.IMPORT_ERROR})

        panjang = int(self.headers.get("Content-Length") or 0)
        if panjang <= 0 or panjang > MAX_BYTES:
            return self._kirim(413, {"error": "Ukuran gambar tidak valid (maks 4 MB)."})

        with tempfile.NamedTemporaryFile(delete=False) as f:
            f.write(self.rfile.read(panjang))
            path = f.name
        try:
            self._kirim(200, predict.analisis(path, str(MODEL)))
        except ValueError as e:
            self._kirim(422, {"error": str(e)})
        except Exception as e:
            self._kirim(500, {"error": "Kesalahan saat prediksi: " + str(e)})
        finally:
            os.unlink(path)
