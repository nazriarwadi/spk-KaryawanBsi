<!DOCTYPE html>
<html>

<head>
    <title>Laporan Penilaian Kinerja</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #00A39E;
        }

        /* Warna BSI */
        .header p {
            margin: 2px 0;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .score-box {
            margin-top: 20px;
            padding: 15px;
            border: 2px solid #00A39E;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>BANK SYARIAH INDONESIA</h2>
        <p>Cabang Bengkalis</p>
        <hr>
        <h3>HASIL PENILAIAN KINERJA KARYAWAN (SMART)</h3>
    </div>

    <table>
        <tr>
            <td width="30%"><strong>Nama Karyawan</strong></td>
            <td>{{ $record->employee->name }}</td>
        </tr>
        <tr>
            <td><strong>NIP</strong></td>
            <td>{{ $record->employee->nip }}</td>
        </tr>
        <tr>
            <td><strong>Periode Penilaian</strong></td>
            <td>{{ $record->schedule->name }}</td>
        </tr>
        <tr>
            <td><strong>Tanggal Cetak</strong></td>
            <td>{{ now()->format('d F Y') }}</td>
        </tr>
    </table>

    <h4>Rincian Nilai Kriteria</h4>
    <table>
        <thead>
            <tr>
                <th>Kriteria</th>
                <th>Nilai Input</th>
                <th>Bobot (%)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Capacity Plan (C1)</td>
                <td>{{ $record->c1_capacity_plan }}</td>
                <td>70%</td>
            </tr>
            <tr>
                <td>Kedisiplinan (C2)</td>
                <td>{{ $record->c2_kedisiplinan }}</td>
                <td>10%</td>
            </tr>
            <tr>
                <td>Pengetahuan (C3)</td>
                <td>{{ $record->c3_pengetahuan }}</td>
                <td>10%</td>
            </tr>
            <tr>
                <td>Loyalitas (C4)</td>
                <td>{{ $record->c4_loyalitas }}</td>
                <td>5%</td>
            </tr>
            <tr>
                <td>Team Work (C5)</td>
                <td>{{ $record->c5_team_work }}</td>
                <td>5%</td>
            </tr>
        </tbody>
    </table>

    <div class="score-box">
        SKOR AKHIR SMART: {{ number_format($record->final_score, 3) }}
    </div>

    <br><br>
    <table style="border: none;">
        <tr style="border: none;">
            <td style="border: none; text-align: center;" width="60%"></td>
            <td style="border: none; text-align: center;">
                Mengetahui,<br>
                HRD / Pimpinan<br><br><br><br>
                ( ................................. )
            </td>
        </tr>
    </table>
</body>

</html>
