@php
    $steps = [
        [
            'name'  => 'Unggah foto',
            'sub'   => 'Pilih atau ambil foto daun',
            'icon'  => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>',
            'dur'   => 8,
            'title' => 'Unggah foto daun padi',
            'desc'  => 'Pilih foto dari galeri atau ambil langsung dari kamera HP. Format JPG, PNG, dan WEBP hingga 4 MB diterima.',
            'list'  => ['Seret dan lepas foto ke area unggah', 'Pratinjau tampil sebelum dianalisis', 'Foto dekat, terang, dan berlatar polos memberi hasil terbaik'],
            'stats' => [['3', 'format foto'], ['4 MB', 'ukuran maksimal'], ['1', 'foto per analisis']],
        ],
        [
            'name'  => 'Analisis otomatis',
            'sub'   => 'Foto dibaca oleh model',
            'icon'  => '<path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/>',
            'dur'   => 10,
            'title' => 'Analisis otomatis',
            'desc'  => 'Foto diperkecil menjadi 64×64 piksel, lalu rata-rata warna biru, hijau, dan merah dihitung. Model Random Forest yang terdiri dari 100 pohon keputusan menentukan penyakitnya.',
            'list'  => ['Pengolahan foto sama persis dengan saat model dilatih', 'Setiap pohon memberi suara, hasilnya berupa peluang tiap penyakit', 'Prosesnya hanya beberapa detik'],
            'stats' => [['64×64', 'piksel foto'], ['100', 'pohon keputusan'], ['BGR', 'rata-rata warna']],
        ],
        [
            'name'  => 'Terima rekomendasi',
            'sub'   => 'Penyakit dan saran tindakan',
            'icon'  => '<rect width="8" height="4" x="8" y="2" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/>',
            'dur'   => 9,
            'title' => 'Terima rekomendasi',
            'desc'  => 'Lihat penyakit yang terdeteksi, tingkat keyakinan, kondisi tanaman, dan langkah penanganan yang disarankan. Hasil otomatis tersimpan di riwayat.',
            'list'  => ['Tingkat keyakinan dalam persen', 'Tindakan rekomendasi dan ciri-ciri penyakit', 'Riwayat dapat dibuka kembali kapan saja'],
            'stats' => [['0–100%', 'tingkat keyakinan'], ['Saran', 'tindakan penanganan'], ['Riwayat', 'tersimpan otomatis']],
        ],
    ];

    // Konfeti langkah 3: [geser-x, naik, jatuh (em), putar (deg), warna, jeda (detik), bentuk]
    $cf = [
        [-12, -6, 8, 420, '#22c55e', 0.00, 'r'], [-8, -9, 10, -380, '#f59e0b', 0.08, 'o'],
        [-4, -11, 11, 300, '#38bdf8', 0.16, 'r'], [0, -12, 12, -460, '#ef4444', 0.04, 'o'],
        [4, -11, 11, 360, '#a855f7', 0.12, 'r'], [8, -9, 10, -300, '#22c55e', 0.20, 'o'],
        [12, -6, 8, 440, '#f59e0b', 0.06, 'r'], [-13, -3, 11, -340, '#38bdf8', 0.24, 'o'],
        [13, -3, 11, 380, '#ef4444', 0.28, 'r'], [-6, -8, 12, 500, '#fbbf24', 0.32, 'o'],
        [6, -8, 12, -420, '#22c55e', 0.36, 'r'], [-10, -5, 9, 260, '#a855f7', 0.40, 'o'],
        [10, -5, 9, -280, '#38bdf8', 0.44, 'r'], [-2, -10, 13, -240, '#f59e0b', 0.48, 'o'],
        [2, -10, 13, 320, '#22c55e', 0.50, 'r'], [0, -7, 9, 200, '#38bdf8', 0.30, 'o'],
    ];

    $tick = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>';
    $leaf = '<svg viewBox="0 0 24 24" fill="currentColor" fill-opacity=".18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12" fill="none"/></svg>';
    $spin = '<svg class="cp-spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true"><path d="M12 3a9 9 0 1 0 9 9"/></svg>';
    $padi = asset('images/padi.png');
    $fallback = "this.onerror=null;this.src='".asset('images/padisehat.png')."'";
@endphp
<section class="cp" id="cara-pakai" aria-label="Cara pakai">
    <div class="container cp-in">
        <header class="cp-head">
            <span class="cp-eyebrow">
                <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></i>
                Cara pakai
            </span>
            <div class="cp-titlewrap">
                <span class="cp-ico cp-ico-l" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg></span>
                <h2 class="cp-title">Tiga langkah menuju <em>diagnosis</em></h2>
                <span class="cp-ico cp-ico-r" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg></span>
            </div>
        </header>

        <div class="cp-card is-paused" id="cara" data-cur="1">
            <div class="cp-tabs" role="tablist" aria-label="Langkah pakai">
                @foreach ($steps as $s)
                    @php $n = $loop->iteration; @endphp
                    <button type="button" class="cp-tab {{ $loop->first ? 'is-active' : '' }}" role="tab" id="cp-tab-{{ $n }}" data-step="{{ $n }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-controls="cp-panel-{{ $n }}" @if (! $loop->first) tabindex="-1" @endif>
                        <span class="cp-no"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $s['icon'] !!}</svg></span>
                        <span class="cp-txt"><em>Langkah {{ $n }}</em><b>{{ $s['name'] }}</b><small>{{ $s['sub'] }}</small></span>
                    </button>
                @endforeach
            </div>

            <div class="cp-bar" aria-hidden="true"><i></i></div>

            <div class="cp-stage">
                @foreach ($steps as $s)
                    @php $n = $loop->iteration; @endphp
                    <article class="cp-panel {{ $loop->first ? 'is-active' : '' }}" id="cp-panel-{{ $n }}" data-step="{{ $n }}" data-dur="{{ $s['dur'] }}"
                             style="--dur: {{ $s['dur'] }}s" role="tabpanel" aria-labelledby="cp-tab-{{ $n }}">

                        @if ($n === 1)
                            {{-- Langkah 1: foto padi meluncur ke area unggah, diunggah, lalu siap dianalisis --}}
                            <div class="cp-visual" role="img" aria-label="Animasi foto padi diseret ke area unggah lalu diunggah">
                                <div class="cp-sq">
                                    <div class="p1-zone">
                                        <div class="p1-drop">
                                            <svg class="p1-ic" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 13v8"/><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="m8 17 4-4 4 4"/></svg>
                                            <b>Seret dan lepas foto</b>
                                        </div>
                                    </div>

                                    <span class="p1-leaf l1" aria-hidden="true">{!! $leaf !!}</span>
                                    <span class="p1-leaf l2" aria-hidden="true">{!! $leaf !!}</span>
                                    <span class="p1-leaf l3" aria-hidden="true">{!! $leaf !!}</span>
                                    <span class="p1-leaf l4" aria-hidden="true">{!! $leaf !!}</span>

                                    <span class="p1-rip"></span><span class="p1-rip r2"></span>
                                    <i class="p1-dot"></i><i class="p1-dot"></i><i class="p1-dot"></i><i class="p1-dot"></i><i class="p1-dot"></i><i class="p1-dot"></i>

                                    <div class="p1-flyx"><div class="p1-flyy"><div class="p1-photo">
                                        <div class="p1-img">
                                            <img src="{{ $padi }}" alt="" onerror="{{ $fallback }}" loading="eager" decoding="async">
                                            <span class="p1-shine"></span>
                                        </div>
                                        <span class="p1-cap">foto_daun_padi.jpg</span>
                                    </div></div></div>

                                    <span class="p1-ck"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg></span>

                                    <span class="p1-prog"><i></i></span>
                                    <span class="p1-done">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>
                                        <span>Foto siap dianalisis</span>
                                    </span>
                                </div>
                            </div>

                        @elseif ($n === 2)
                            {{-- Langkah 2: padi.png dipindai -> notfound.png, lalu didiagnosis -> padisakit.png --}}
                            <div class="cp-visual" role="img" aria-label="Animasi pemindaian foto padi sampai gejala penyakit terdeteksi">
                                <div class="cp-sq">
                                    <span class="cp-ground"></span>
                                    <div class="cp-float">
                                        <div class="p2-shake">
                                            <div class="p2-wA"><img src="{{ $padi }}" alt="" onerror="{{ $fallback }}" loading="eager" decoding="async"></div>
                                            <div class="p2-wB"><div class="p2-wB2"><img src="{{ asset('images/notfound.png') }}" alt="" loading="eager" decoding="async"></div></div>
                                            <div class="p2-wC"><img src="{{ asset('images/padisakit.png') }}" alt="" loading="eager" decoding="async"></div>
                                        </div>
                                        <div class="p2-edge eb"><i></i></div>
                                        <div class="p2-edge ec"><i></i></div>
                                        <i class="p2-cor c1"></i><i class="p2-cor c2"></i><i class="p2-cor c3"></i><i class="p2-cor c4"></i>
                                    </div>

                                    <span class="p2-chip k1"><span>Bercak terdeteksi</span></span>
                                    <span class="p2-chip k2"><span>Daun mengering</span></span>
                                    <span class="p2-chip k3"><span>Perlu penanganan</span></span>

                                    <span class="cp-pill p2-pill pa">{!! $spin !!}<span>Memindai foto…</span></span>
                                    <span class="cp-pill p2-pill pb">{!! $spin !!}<span>Mengenali gejala…</span></span>
                                    <span class="cp-pill p2-pill pc">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                                        <span>Gejala terdeteksi</span>
                                    </span>
                                </div>
                            </div>

                        @else
                            {{-- Langkah 3: rekomendasi diterapkan satu per satu, padisakit.png pulih menjadi padisehat.png --}}
                            <div class="cp-visual" role="img" aria-label="Animasi rekomendasi diterapkan sampai padi kembali sehat">
                                <div class="cp-sq">
                                    <span class="cp-ground"></span>
                                    <div class="p3-ring"><i></i></div>

                                    <span class="p3-pulse u1"></span><span class="p3-pulse u2"></span><span class="p3-pulse u3"></span>
                                    <div class="cp-float p3-float">
                                        <img class="p3-sick" src="{{ asset('images/padisakit.png') }}" alt="" loading="eager" decoding="async">
                                        <img class="p3-ok" src="{{ asset('images/padisehat.png') }}" alt="" loading="eager" decoding="async">
                                    </div>
                                    <span class="p3-glow"></span>

                                    <span class="p3-chip p3-c1">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 22a7 7 0 0 0 7-7c0-2-1-3.9-3-5.5s-3.5-4-4-6.5c-.5 2.5-2 4.9-4 6.5C6 11.1 5 13 5 15a7 7 0 0 0 7 7z"/></svg>
                                        <span class="t">Atur pengairan</span><span class="cp-ck">{!! $tick !!}</span>
                                    </span>
                                    <span class="p3-chip p3-c2">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M7 20h10"/><path d="M10 20c5.5-2.5.8-6.4 3-10"/><path d="M9.5 9.4c1.1.8 1.8 2.2 2.3 3.7-2 .4-3.5.4-4.8-.3-1.2-.6-2.3-1.9-3-4.2 2.8-.5 4.4 0 5.5.8z"/><path d="M14.1 6a7 7 0 0 0-1.1 4c1.9-.1 3.3-.6 4.3-1.4 1-1 1.6-2.3 1.7-4.6-2.7.1-4 1-4.9 2z"/></svg>
                                        <span class="t">Pupuk berimbang</span><span class="cp-ck">{!! $tick !!}</span>
                                    </span>
                                    <span class="p3-chip p3-c3">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="6" cy="6" r="3"/><path d="M8.12 8.12 12 12"/><path d="M20 4 8.12 15.88"/><circle cx="6" cy="18" r="3"/><path d="M14.8 14.8 20 20"/></svg>
                                        <span class="t">Pangkas daun parah</span><span class="cp-ck">{!! $tick !!}</span>
                                    </span>

                                    <span class="p3-badge"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg></span>

                                    <div class="p3-leaves" aria-hidden="true">
                                        <span class="p3-leaf l1">{!! $leaf !!}</span><span class="p3-leaf l2">{!! $leaf !!}</span>
                                        <span class="p3-leaf l3">{!! $leaf !!}</span><span class="p3-leaf l4">{!! $leaf !!}</span>
                                    </div>

                                    @foreach ($cf as $c)
                                        <i class="p3-cf {{ $c[6] === 'o' ? 'o' : '' }}" style="--dx: {{ $c[0] }}em; --uy: {{ $c[1] }}em; --dy: {{ $c[2] }}em; --r: {{ $c[3] }}deg; --c: {{ $c[4] }}; --d: {{ $c[5] }}s"></i>
                                    @endforeach

                                    <span class="cp-pill p3-pill pa">{!! $spin !!}<span>Menerapkan rekomendasi…</span></span>
                                    <span class="cp-pill p3-pill pb">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 12 5 5L20 7"/></svg>
                                        <span>Padi kembali sehat</span>
                                    </span>
                                </div>
                            </div>
                        @endif

                        <div class="cp-info">
                            <span class="cp-tag">Langkah {{ $n }} dari {{ count($steps) }}</span>
                            <h3>{{ $s['title'] }}</h3>
                            <p>{{ $s['desc'] }}</p>
                            <ul class="cp-list">
                                @foreach ($s['list'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            <div class="cp-stats">
                                @foreach ($s['stats'] as $st)
                                    <div class="cp-stat"><b>{{ $st[0] }}</b><span>{{ $st[1] }}</span></div>
                                @endforeach
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>