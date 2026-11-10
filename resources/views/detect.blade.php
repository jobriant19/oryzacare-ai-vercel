@extends('layouts.app')

@section('title', 'Deteksi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/detect.css') }}?v=10">
@endpush

@push('scripts')
    <script src="{{ asset('js/fit.js') }}?v=1"></script>
    <script src="{{ asset('js/detect.js') }}?v=4"></script>
@endpush

@section('content')
@php
    // Gambar penyakit: public/images/penyakit/<Kelas>.jpg atau public/images/<Kelas>.jpg (jpg, jpeg, png, webp)
    $diseaseImg = function ($key) {
        foreach (['images/penyakit/', 'images/'] as $dir) {
            foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
                foreach ([$key, strtolower($key)] as $name) {
                    if (is_file(public_path($dir.$name.'.'.$ext))) {
                        return asset($dir.$name.'.'.$ext);
                    }
                }
            }
        }

        return null;
    };
    $why = session('unknown');
    // Nama tampilan khusus kartu "Penyakit yang dikenali"
    $cardName = ['Bacterialblight' => 'Bacterialblight', 'Blast' => 'Blast', 'Brownspot' => 'Brown Spot'];
@endphp
<section class="container dt">
    <div class="dt-head">
        <div class="dt-headtext">
            <span class="dt-eyebrow">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                Deteksi dengan foto
            </span>
            <h1>Deteksi Penyakit Daun Padi</h1>
            <p>Unggah <b>satu foto daun padi</b>, hasilnya keluar dalam hitungan detik.</p>
        </div>
        <ul class="dt-specs" aria-label="Ketentuan foto">
            <li class="dt-spec dt-spec-format" tabindex="0">
                <span class="dt-specicon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg></span>
                <span><small>Format foto</small><span class="dt-chips"><i>JPG</i><i>PNG</i><i>WEBP</i></span></span>
            </li>
            <li class="dt-spec dt-spec-size" tabindex="0">
                <span class="dt-specicon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="22" x2="2" y1="12" y2="12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" x2="6.01" y1="16" y2="16"/><line x1="10" x2="10.01" y1="16" y2="16"/></svg></span>
                <span><small>Ukuran maksimal</small><b>4 MB</b><span class="dt-meter"><i></i></span></span>
            </li>
            <li class="dt-spec dt-spec-one dt-spec-opt" tabindex="0">
                <span class="dt-specicon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg></span>
                <span><small>Per analisis</small><b>1 foto</b></span>
            </li>
        </ul>
    </div>

    <div class="dt-grid">
        @if ($why)
            {{-- Foto ditolak: bukan foto daun padi --}}
            <section class="dt-card dt-unknown" id="unknownCard" role="alert">
                <div class="dt-uicon">
                    <svg width="44" height="44" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m13.5 8.5-5 5"/><path d="m8.5 8.5 5 5"/><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
                @if ($why === 'graphic')
                    <h2>Ini bukan foto daun padi asli</h2>
                    <p>Gambar ini tampak seperti ilustrasi, animasi, atau logo. Model hanya mengenal foto asli dari kamera, jadi analisis dihentikan agar hasilnya tidak menyesatkan. Gambar ini tidak disimpan.</p>
                @else
                    <h2>Foto belum dikenali sebagai daun padi</h2>
                    <p>Sistem tidak menemukan warna daun yang cukup pada foto ini, jadi analisis dihentikan agar hasilnya tidak menyesatkan. Tenang, foto ini tidak disimpan.</p>
                @endif
                <ul class="dt-hints">
                    <li>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        <span>{{ $why === 'graphic' ? 'Gunakan foto asli dari kamera, bukan gambar buatan' : 'Dekatkan kamera ke satu helai daun' }}</span>
                    </li>
                    <li>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg>
                        <span>Gunakan cahaya alami yang cukup</span>
                    </li>
                    <li>
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"/></svg>
                        <span>Pakai latar polos, hindari orang atau benda lain</span>
                    </li>
                </ul>
                <div class="dt-actions">
                    <button type="button" class="btn btn-primary" id="retryBtn">Coba Foto Lain</button>
                    <a href="{{ route('beranda') }}" class="btn btn-light">Ke Beranda</a>
                </div>
            </section>
        @endif

            <form id="detectForm" class="dt-card dt-upload" method="POST" action="{{ route('deteksi.store') }}" enctype="multipart/form-data" @if ($why) hidden @endif>
                @csrf

                @if ($errors->any())
                    <div class="alert alert-error dt-alert" role="alert">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <input type="file" id="foto" name="foto" class="visually-hidden" accept="image/jpeg,image/png,image/webp" required>

                <label for="foto" class="dt-drop" id="dropzone">
                    <span class="dt-dropicon">
                        <svg width="38" height="38" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    </span>
                    <strong>Ketuk untuk memilih foto</strong>
                    <span class="dt-dropsub">atau seret dan lepas foto ke sini</span>
                </label>

                <div class="dt-preview" id="preview" hidden>
                    <figure class="dt-frame" id="previewFrame"><img id="previewImg" alt="Pratinjau foto yang dipilih"></figure>
                    <p class="dt-name" id="previewName"></p>
                </div>

                <div class="dt-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><line x1="7" y1="12" x2="17" y2="12"/></svg>
                        Analisis Sekarang
                    </button>
                    <button type="button" class="btn btn-light" id="resetBtn" hidden>Ganti Foto</button>
                </div>
            </form>

        <aside class="dt-side">
            <article class="dt-card dt-tips">
                <h3>Tips foto yang baik</h3>
                <ul class="dt-tiplist">
                    <li class="dt-tip">
                        <span class="dt-tipicon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path class="c-bump" d="M8.3 6.6 9.5 4.5a1 1 0 0 1 .9-.5h3.2a1 1 0 0 1 .9.5l1.2 2.1z"/><rect class="c-body" x="2" y="6.5" width="20" height="14" rx="3.2"/><circle class="c-ring" cx="12" cy="13.5" r="5"/><circle class="c-lens" cx="12" cy="13.5" r="2.6"/><circle class="c-glint" cx="10.9" cy="12.4" r=".75"/><circle class="c-flash" cx="18.6" cy="9.8" r="1"/><path class="c-rays" d="M19.5 1.8v2M22.3 3.2l-1.4 1.4M16.7 3.2l1.4 1.4"/></svg></span>
                        <div><b>Dekat dan jelas</b><span>Foto satu helai daun dari dekat agar bercak terlihat jelas.</span></div>
                    </li>
                    <li class="dt-tip">
                        <span class="dt-tipicon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/></svg></span>
                        <div><b>Cahaya cukup</b><span>Pakai cahaya alami, hindari bayangan dan blitz terlalu terang.</span></div>
                    </li>
                    <li class="dt-tip">
                        <span class="dt-tipicon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect width="18" height="18" x="3" y="3" rx="2"/></svg></span>
                        <div><b>Latar polos</b><span>Latar polos membuat warna daun terbaca lebih akurat.</span></div>
                    </li>
                    <li class="dt-tip">
                        <span class="dt-tipicon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect class="f-frame" x="3" y="3" width="18" height="14" rx="2.5"/><circle class="f-sun" cx="8" cy="7.4" r="1.7"/><path class="f-hill" d="M4 17v-2.6l4.2-4.6 3.3 3.2 2.6-2.4L20 14.4V17z"/><path class="f-scan" d="M4.5 4.6h15"/><circle class="f-badge" cx="18" cy="18" r="4.6"/><path class="f-check" d="m15.8 18.1 1.6 1.6 3-3.3"/></svg></span>
                        <div><b>Foto asli</b><span>Ilustrasi, animasi, dan logo ditolak. Daun tanpa gejala tampil sebagai padi sehat.</span></div>
                    </li>
                </ul>
            </article>

            <article class="dt-card dt-known">
                <h3>Penyakit yang dikenali</h3>
                <div class="dt-dislist">
                    @foreach (config('padi.diseases') as $key => $d)
                        @php $img = $diseaseImg($key); @endphp
                        <article class="dt-dis acc-{{ $d['tone'] }}">
                            <div class="dt-disimg">
                                @if ($img)
                                    <img src="{{ $img }}" alt="Contoh {{ $d['name'] }}" loading="lazy">
                                @else
                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                                @endif
                                <span class="dt-disrisk risk-{{ \Illuminate\Support\Str::slug($d['risk']) }}">Risiko {{ $d['risk'] }}</span>
                            </div>
                            <div class="dt-disbody">
                                <h4>{{ $cardName[$key] ?? $d['name'] }}</h4>
                                <p>{{ $d['summary'] }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </article>
        </aside>
    </div>
</section>

{{-- Pop up: ukuran/format foto tidak sesuai --}}
<div class="mdl" id="fileModal" hidden>
    <div class="mdl-backdrop" data-close></div>
    <div class="mdl-box" role="alertdialog" aria-modal="true" aria-labelledby="fileModalTitle" aria-describedby="fileModalText">
        <div class="mdl-icon is-warn">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
        </div>
        <h3 id="fileModalTitle">Ukuran foto terlalu besar</h3>
        <p id="fileModalText"></p>
        <div class="mdl-actions">
            <button type="button" class="btn btn-primary" id="fileModalOk" data-close>Mengerti</button>
        </div>
    </div>
</div>

<div class="loading" id="loading" hidden aria-live="polite" role="status">
    <div class="loading-box">
        <div class="ld-orb" aria-hidden="true">
            <span class="ld-ring"></span>
            <span class="ld-ring ld-ring2"></span>
            <span class="ld-core"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg></span>
        </div>
        <p class="ld-title"><strong>Menganalisis foto…</strong></p>
        <p class="ld-sub">Mohon tunggu sebentar.</p>
        <ul class="ld-steps" aria-hidden="true">
            <li style="--d:0s">Membaca foto</li>
            <li style="--d:1.6s">Mengukur warna daun</li>
            <li style="--d:3.2s">Mencocokkan penyakit</li>
        </ul>
        <div class="ld-bar" aria-hidden="true"><span></span></div>
    </div>
</div>
@endsection