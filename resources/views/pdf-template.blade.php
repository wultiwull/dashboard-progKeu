<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>PROGRES KEGIATAN BBWS SERAYU OPAK TA.{{ $tahun ?? date('Y') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSS ringan, gunakan inline agar render di Dompdf lebih stabil --}}
    <style>
        @page {
            margin: 20mm 12mm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #111;
            margin: 5m;
            padding: 0;
        }


        h4.section-title {
            font-size: 11p;
            margin: 2mm 0;
        }

        .logo {
            width: 72px;
            height: 72px;
            object-fit: contain;
        }

        .title {
            font-weight: 700;
            font-size: 14px;
        }

        .subtitle {
            font-size: 10px;
            color: #333;
            margin-top: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .table-header {
            background: #f2f2f2;
            font-weight: bold;
        }

        th,
        td {
            border: 0.5px solid #aaa;
            padding: 3px;
            vertical-align: middle;
            text-align: center;
        }

        thead th {
            background: #1f77b4;
            color: #fff;
            font-size: 10px;
        }

        thead .sub {
            background: #cfe6fb;
            color: #111;
            font-weight: 600;
        }

        .text-left {
            text-align: left;
        }

        .fw-bold {
            font-weight: 700;
        }

        tfoot td {
            background: #f4f6f8;
            font-weight: 700;
            text-align: center;
        }

        .small {
            font-size: 9px;
            color: #444;
        }

        .muted {
            color: #666;
            font-size: 9px;
        }

        /* khusus agar angka rata kanan */
        .num {
            text-align: right;
        }

        /* ketika ada teks panjang */
        .wrap {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        /* header group colors for tender to emulate the excel-like look */
        .grp-1 {
            background: #4ea3d8;
            color: #fff;
        }

        .grp-2 {
            background: #2f80b5;
            color: #fff;
        }

        .bg-warning {
            background: #f7b267;
            color: #000;
        }

        @page {
            size: A4 landscape;
            margin: 5mm;
        }

        @media print {

            html,
            body {
                width: 297mm;
                height: 210mm;
                overflow: hidden;
            }
        }

        /* Kalau masih lebih dari 1 halaman, bisa dikompres pakai scale */
        .page-scale {
            transform: scale(0.85);
            transform-origin: top left;
        }
    </style>
</head>

<body>

    {{-- Header --}}
    <div class="header" style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
        {{-- Logo: letakkan logo di public/images/logo.png --}}
        <div><img src="{{ public_path('images/logo.png') }}" alt="logo" class="logo"></div>

        <div>
            <div class="title">PROGRES KEGIATAN BBWS SERAYU OPAK TA.{{ $tahun ?? date('Y') }}</div>
            <div class="subtitle small">
                Sumber : Ie-Monitoring &nbsp;|&nbsp; Data : {{ now()->format('d F Y') }} Pukul 16.00 WIB
            </div>
        </div>
    </div>

    {{-- Section: Alokasi Anggaran Per Satker (Ringkasan) --}}
    <h4 class="small fw-bold">Alokasi Anggaran Per Satker</h4>
    <div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Satuan Kerja</th>
                    <th>PAGU (Rp)</th>
                    <th>Blokir (Rp)</th>
                    <th>Pagu Setelah Efisiensi (Rp)</th>
                    <th>Progres Keu (%)</th>
                    <th>Progres Fis (%)</th>
                    <th>Prognosis (Rp)</th>
                    <th>Prognosis (%)</th>
                </tr>
            </thead>

            {{-- totals before body --}}
            @php
            $total_pagu_sa = $total_blokir_sa = $total_pagu_ef_sa = $total_prog_rp_sa = 0;
            foreach($ppk as $row){
            $total_pagu_sa += is_numeric($row->pagu) ? floatval($row->pagu) : 0;
            $total_blokir_sa += is_numeric($row->blokir) ? floatval($row->blokir) : 0;
            $total_pagu_ef_sa += is_numeric($row->pagu_efisiensi) ? floatval($row->pagu_efisiensi) : 0;
            $total_prog_rp_sa += is_numeric($row->prognosis_rp) ? floatval($row->prognosis_rp) : 0;
            }
            @endphp

            <tfoot>
                <tr>
                    <td colspan="2">Total</td>
                    <td class="num">{{ number_format($total_pagu_sa,0,',','.') }}</td>
                    <td class="num">{{ number_format($total_blokir_sa,0,',','.') }}</td>
                    <td class="num">{{ number_format($total_pagu_ef_sa,0,',','.') }}</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="num">{{ number_format($total_prog_rp_sa,0,',','.') }}</td>
                    <td>-</td>
                </tr>
            </tfoot>

            <tbody>
                @foreach($ppk as $i => $r)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td class="text-left wrap">{{ $r->nama_ppk }}</td>
                    <td class="num">{{ is_numeric($r->pagu) ? number_format($r->pagu,0,',','.') : $r->pagu }}</td>
                    <td class="num">{{ is_numeric($r->blokir) ? number_format($r->blokir,0,',','.') : $r->blokir }}</td>
                    <td class="num">
                        {{ is_numeric($r->pagu_efisiensi) ? number_format($r->pagu_efisiensi,0,',','.') : $r->pagu_efisiensi }}
                    </td>
                    <td>{{ is_numeric($r->progress_keu_pagu_total) ? $r->progress_keu_pagu_total.'%' : $r->progress_keu_pagu_total }}
                    </td>
                    <td>{{ is_numeric($r->progress_fis_pagu_total) ? $r->progress_fis_pagu_total.'%' : $r->progress_fis_pagu_total }}
                    </td>
                    <td class="num">
                        {{ is_numeric($r->prognosis_rp) ? number_format($r->prognosis_rp,0,',','.') : $r->prognosis_rp }}
                    </td>
                    <td>{{ is_numeric($r->prognosis_persen) ? $r->prognosis_persen.'%' : $r->prognosis_persen }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Section: Progress Keuangan per PPK (header bertumpuk contoh) --}}
    <h4 class="small fw-bold">Progress Keuangan per PPK Berdasarkan PAGU Efektif</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Satuan Kerja</th>
                <th rowspan="2">PAGU (Rp.000)</th>
                <th rowspan="2">Blokir (Rp.000)</th>
                <th rowspan="2">Pagu Setelah Efisiensi (Rp.000)</th>
                <th colspan="2">Progres Terhadap PAGU Total (%)</th>
                <th colspan="2">Prognosis</th>
            </tr>
            <tr>
                <th class="sub">Keu</th>
                <th class="sub">Fis</th>
                <th class="sub">(Rp)</th>
                <th class="sub">%</th>
            </tr>
        </thead>

        @php
        $tot_pagu = $tot_blokir = $tot_pagu_ef = $tot_progn_rp = 0;
        foreach($ppk as $row){
        $tot_pagu += is_numeric($row->pagu) ? floatval($row->pagu) : 0;
        $tot_blokir += is_numeric($row->blokir) ? floatval($row->blokir) : 0;
        $tot_pagu_ef += is_numeric($row->pagu_efisiensi) ? floatval($row->pagu_efisiensi) : 0;
        $tot_progn_rp += is_numeric($row->prognosis_rp) ? floatval($row->prognosis_rp) : 0;
        }
        @endphp

        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td class="num">{{ number_format($tot_pagu,0,',','.') }}</td>
                <td class="num">{{ number_format($tot_blokir,0,',','.') }}</td>
                <td class="num">{{ number_format($tot_pagu_ef,0,',','.') }}</td>
                <td>-</td>
                <td>-</td>
                <td class="num">{{ number_format($tot_progn_rp,0,',','.') }}</td>
                <td>-</td>
            </tr>
        </tfoot>

        <tbody>
            @foreach($ppk as $i => $r)
            <tr>
                <td>{{ $i+1 }}</td>
                <td class="text-left wrap">{{ $r->nama_ppk }}</td>
                <td class="num">{{ is_numeric($r->pagu) ? number_format($r->pagu,0,',','.') : $r->pagu }}</td>
                <td class="num">{{ is_numeric($r->blokir) ? number_format($r->blokir,0,',','.') : $r->blokir }}</td>
                <td class="num">
                    {{ is_numeric($r->pagu_efisiensi) ? number_format($r->pagu_efisiensi,0,',','.') : $r->pagu_efisiensi }}
                </td>
                <td>{{ is_numeric($r->progress_keu_pagu_total) ? $r->progress_keu_pagu_total.'%' : $r->progress_keu_pagu_total }}
                </td>
                <td>{{ is_numeric($r->progress_fis_pagu_total) ? $r->progress_fis_pagu_total.'%' : $r->progress_fis_pagu_total }}
                </td>
                <td class="num">
                    {{ is_numeric($r->prognosis_rp) ? number_format($r->prognosis_rp,0,',','.') : $r->prognosis_rp }}
                </td>
                <td>{{ is_numeric($r->prognosis_persen) ? $r->prognosis_persen.'%' : $r->prognosis_persen }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Section: Tender / Pengadaan (header multi-level) --}}
    <h4 class="small fw-bold">Pelaksanaan Tender Paket Pekerjaan TA. {{ $tahun ?? date('Y') }}</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Satker</th>

                {{-- group columns; setiap group 2 kolom PKT + PAGU --}}
                <th colspan="2" class="grp-1">SYC & MYC Baru</th>
                <th colspan="2" class="grp-1">MYC Lanjutan</th>
                <th colspan="2" class="grp-2">e-Purchasing</th>
                <th colspan="2" class="grp-2">Pengadaan/PL</th>
                <th colspan="2" class="grp-2">Seleksi/Tender</th>
                <th colspan="2" class="grp-2">Repeat Order</th>
                <th colspan="2" class="grp-2">Tender Cepat</th>
                <th colspan="2" class="bg-warning">Belum Lelang</th>
                <th colspan="2" class="grp-1">Proses Lelang</th>
                <th colspan="2" class="grp-1">Gagal Lelang</th>
                <th colspan="2" class="grp-1">Terkontrak</th>
            </tr>
            <tr>
                {{-- sub headers --}}
                @for ($i = 0; $i < 11; $i++) <th class="sub">PKT</th>
                    <th class="sub">PAGU DIPA (Rp)</th>
                    @endfor
            </tr>
        </thead>

        @php
        // kalkulasi total tiap pagu group
        $t_pagu = array_fill(0, 11, 0); // 11 grup pagu sesuai loop
        foreach ($tenders as $it) {
        $cols = [
        'pagu_syc','pagu_myc','pagu_purchasing','pagu_pengadaan',
        'pagu_tender','pagu_order','pagu_tender_cepat','pagu_belum_lelang',
        'pagu_proses_lelang','pagu_gagal_lelang','pagu_terkontrak'
        ];
        foreach($cols as $k => $col){
        $t_pagu[$k] += is_numeric($it->$col) ? floatval($it->$col) : 0;
        }
        }
        @endphp

        <tfoot>
            <tr>
                <td>Total</td>
                @foreach($t_pagu as $val)
                <td>-</td>
                <td class="num">{{ number_format($val,0,',','.') }}</td>
                @endforeach
            </tr>
        </tfoot>

        <tbody>
            @foreach($tenders as $it)
            <tr>
                <td class="text-left wrap">{{ $it->satker }}</td>

                <td class="num">{{ $it->pkt_syc }}</td>
                <td class="num">{{ is_numeric($it->pagu_syc) ? number_format($it->pagu_syc,0,',','.') : $it->pagu_syc }}
                </td>

                <td class="num">{{ $it->pkt_myc }}</td>
                <td class="num">{{ is_numeric($it->pagu_myc) ? number_format($it->pagu_myc,0,',','.') : $it->pagu_myc }}
                </td>

                <td class="num">{{ $it->pkt_purchasing }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_purchasing) ? number_format($it->pagu_purchasing,0,',','.') : $it->pagu_purchasing }}
                </td>

                <td class="num">{{ $it->pkt_pengadaan }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_pengadaan) ? number_format($it->pagu_pengadaan,0,',','.') : $it->pagu_pengadaan }}
                </td>

                <td class="num">{{ $it->pkt_tender }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_tender) ? number_format($it->pagu_tender,0,',','.') : $it->pagu_tender }}
                </td>

                <td class="num">{{ $it->pkt_order }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_order) ? number_format($it->pagu_order,0,',','.') : $it->pagu_order }}
                </td>

                <td class="num">{{ $it->pkt_tender_cepat }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_tender_cepat) ? number_format($it->pagu_tender_cepat,0,',','.') : $it->pagu_tender_cepat }}
                </td>

                <td class="num">{{ $it->pkt_belum_lelang }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_belum_lelang) ? number_format($it->pagu_belum_lelang,0,',','.') : $it->pagu_belum_lelang }}
                </td>

                <td class="num">{{ $it->pkt_proses_lelang }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_proses_lelang) ? number_format($it->pagu_proses_lelang,0,',','.') : $it->pagu_proses_lelang }}
                </td>

                <td class="num">{{ $it->pkt_gagal_lelang }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_gagal_lelang) ? number_format($it->pagu_gagal_lelang,0,',','.') : $it->pagu_gagal_lelang }}
                </td>

                <td class="num">{{ $it->pkt_terkontrak }}</td>
                <td class="num">
                    {{ is_numeric($it->pagu_terkontrak) ? number_format($it->pagu_terkontrak,0,',','.') : $it->pagu_terkontrak }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Section: P3-TGAI (DIY & Jateng) --}}
    <h4 class="small fw-bold">Kegiatan P3-TGAI di BBWS Serayu Opak TA.{{ $tahun ?? date('Y') }}</h4>
    <div style="display:flex; gap:12px;">
        @php
        $p3tgaiDIY = $p3tgai->where('wilayah','DIY');
        $p3tgaiJateng = $p3tgai->where('wilayah','Jawa Tengah');
        @endphp

        <div style="flex:1;">
            <table>
                <thead>
                    <tr>
                        <th colspan="4" style="background:#f6c36a;color:#000;">WILAYAH DIY</th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th class="text-left">Kabupaten</th>
                        <th>Jumlah</th>
                        <th>Keu (%)</th>
                    </tr>
                </thead>

                @php
                $t_j= $t_k = $t_f = 0;
                foreach($p3tgaiDIY as $p){
                $t_j += is_numeric($p->jumlah_lokasi)? floatval($p->jumlah_lokasi):0;
                $t_k += is_numeric($p->prog_keu)? floatval($p->prog_keu):0;
                $t_f += is_numeric($p->prog_fis)? floatval($p->prog_fis):0;
                }
                @endphp

                <tfoot>
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="num">{{ $t_j }}</td>
                        <td class="num">{{ $t_k }}%</td>
                    </tr>
                </tfoot>

                <tbody>
                    @foreach($p3tgaiDIY as $i => $p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td class="text-left">{{ $p->kabupaten }}</td>
                        <td class="num">{{ $p->jumlah_lokasi }}</td>
                        <td>{{ is_numeric($p->prog_keu) ? $p->prog_keu.'%': $p->prog_keu }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="flex:1;">
            <table>
                <thead>
                    <tr>
                        <th colspan="4" style="background:#70c1b3;color:#000;">WILAYAH JAWA TENGAH</th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th class="text-left">Kabupaten</th>
                        <th>Jumlah</th>
                        <th>Keu (%)</th>
                    </tr>
                </thead>

                @php
                $t_j2 = $t_k2 = $t_f2 = 0;
                foreach($p3tgaiJateng as $p){
                $t_j2 += is_numeric($p->jumlah_lokasi)? floatval($p->jumlah_lokasi):0;
                $t_k2 += is_numeric($p->prog_keu)? floatval($p->prog_keu):0;
                $t_f2 += is_numeric($p->prog_fis)? floatval($p->prog_fis):0;
                }
                @endphp

                <tfoot>
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="num">{{ $t_j2 }}</td>
                        <td class="num">{{ $t_k2 }}%</td>
                    </tr>
                </tfoot>

                <tbody>
                    @foreach($p3tgaiJateng as $i => $p)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td class="text-left">{{ $p->kabupaten }}</td>
                        <td class="num">{{ $p->jumlah_lokasi }}</td>
                        <td>{{ is_numeric($p->prog_keu) ? $p->prog_keu.'%': $p->prog_keu }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Section: INPRES --}}
    @if($inpres->count() > 0)
    <h4 class="small fw-bold mt-2">Progres Pelaksanaan INPRES No. 2 Tahun {{ $tahun ?? date('Y') }}</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">PPK</th>
                <th rowspan="2">Nama Paket</th>
                <th rowspan="2">Lokasi</th>
                <th colspan="2">Nilai (Rp)</th>
                <th colspan="2">Progress (%)</th>
            </tr>
            <tr>
                <th>Pagu</th>
                <th>Realisasi</th>
                <th>Keu</th>
                <th>Fis</th>
            </tr>
        </thead>

        @php
        $tot_pagu_in = $tot_rea_in = 0;
        foreach($inpres as $it){
        $tot_pagu_in += is_numeric($it->pagu_rp) ? floatval($it->pagu_rp) : 0;
        $tot_rea_in += is_numeric($it->realisasi_rp) ? floatval($it->realisasi_rp) : 0;
        }
        @endphp

        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td class="num">{{ number_format($tot_pagu_in,0,',','.') }}</td>
                <td class="num">{{ number_format($tot_rea_in,0,',','.') }}</td>
                <td>-</td>
                <td>-</td>
            </tr>
        </tfoot>

        <tbody>
            @foreach($inpres as $it)
            <tr>
                <td class="text-left">{{ $it->ppk }}</td>
                <td class="wrap">{{ \Illuminate\Support\Str::limit($it->nama_paket,70) }}</td>
                <td class="text-left">{{ $it->lokasi }}</td>
                <td class="num">{{ is_numeric($it->pagu_rp) ? number_format($it->pagu_rp,0,',','.') : $it->pagu_rp }}
                </td>
                <td class="num">
                    {{ is_numeric($it->realisasi_rp) ? number_format($it->realisasi_rp,0,',','.') : $it->realisasi_rp }}
                </td>
                <td>{{ is_numeric($it->progres_keu) ? $it->progres_keu.'%' : $it->progres_keu }}</td>
                <td>{{ is_numeric($it->progres_fisik) ? $it->progres_fisik.'%' : $it->progres_fisik }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    {{-- Section: Sumber Dana --}}
    <h4 class="small fw-bold mt-2">Sumber Dana TA. {{ $tahun ?? date('Y') }}</h4>
    <table>
        <thead>
            <tr>
                <th>Sumber Dana</th>
                <th>Jumlah (Rp)</th>
                <th>Persentase (%)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sumber_dana as $sd)
            <tr>
                <td class="text-left">{{ strtoupper($sd->sumber_dana) }}</td>
                <td class="num">{{ is_numeric($sd->jumlah) ? number_format($sd->jumlah,0,',','.') : $sd->jumlah }}</td>
                <td class="num">
                    {{ is_numeric($sd->persentase) ? number_format($sd->persentase,2,',','.') : $sd->persentase }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="margin-top: 20px;">Status Blokir Anggaran</h4>
    <table style="width:100%; border-collapse: collapse; font-size:12px;" border="1">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>Status</th>
                <th>Nilai (Rp)</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalBlokir = $status_blokir->sum('jumlah');
            $totalPersenBlokir = $status_blokir->sum('persentase');
            @endphp
            @foreach($status_blokir as $blokir)
            <tr>
                <td>{{ $blokir->status }}</td>
                <td>Rp {{ number_format($blokir->jumlah, 0, ',', '.') }}</td>
                <td>{{ number_format($blokir->persentase, 2) }}%</td>
            </tr>
            @endforeach
            <tr style="font-weight:bold; background:#e6e6e6;">
                <td>Total</td>
                <td>Rp {{ number_format($totalBlokir, 0, ',', '.') }}</td>
                <td>{{ number_format($totalPersenBlokir, 2) }}%</td>
            </tr>
        </tbody>
    </table>

    <h4 style="margin-top: 20px;">Alokasi Anggaran Per Satker</h4>
    <table style="width:100%; border-collapse: collapse; font-size:12px;" border="1">
        <thead style="background:#f2f2f2;">
            <tr>
                <th>Satker</th>
                <th>Nilai (Rp)</th>
                <th>%</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalPaguSatker = $alokasiSatker->sum('pagu');
            $totalPersenSatker = $alokasiSatker->sum('persentase');
            @endphp
            @foreach($alokasiSatker as $alok)
            <tr>
                <td>{{ $alok->satker->nama_satker ?? '-' }}</td>
                <td>Rp {{ number_format($alok->pagu, 0, ',', '.') }}</td>
                <td>{{ number_format($alok->persentase, 2) }}%</td>
            </tr>
            @endforeach
            <tr style="font-weight:bold; background:#e6e6e6;">
                <td>Total</td>
                <td>Rp {{ number_format($totalPaguSatker, 0, ',', '.') }}</td>
                <td>{{ number_format($totalPersenSatker, 2) }}%</td>
            </tr>
        </tbody>
    </table>




    {{-- Footer small note --}}
    <div class="muted small" style="margin-top:8px;">
        Generated by BBWS Serayu Opak Monitoring System — Terakhir update: {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</body>

</html>
