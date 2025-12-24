<!DOCTYPE html>
<html>

<head>
    <title>Laporan Rekapitulasi Penilaian</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            color: #00A39E;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #00A39E;
            color: white;
        }

        .rank-1 {
            background-color: #fff8c4;
        }

        /* Warna khusus Juara 1 */
        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>BANK SYARIAH INDONESIA</h2>
        <p>Cabang Bengkalis</p>
        <hr>
        <h3>LAPORAN PERANGKINGAN KINERJA KARYAWAN (SMART)</h3>
        <p>Dicetak pada: {{ now()->format('d F Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">Rank</th>
                <th>NIP</th>
                <th>Nama Karyawan</th>
                <th>Periode</th>
                <th>C1</th>
                <th>C2</th>
                <th>C3</th>
                <th>C4</th>
                <th>C5</th>
                <th width="15%">Nilai Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($assessments as $index => $data)
                <tr class="{{ $index == 0 ? 'rank-1' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->employee->nip }}</td>
                    <td class="text-left">{{ $data->employee->name }}</td>
                    <td>{{ $data->schedule->name }}</td>
                    <td>{{ $data->c1_capacity_plan }}</td>
                    <td>{{ $data->c2_kedisiplinan }}</td>
                    <td>{{ $data->c3_pengetahuan }}</td>
                    <td>{{ $data->c4_loyalitas }}</td>
                    <td>{{ $data->c5_team_work }}</td>
                    <td><strong>{{ number_format($data->final_score, 3) }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>
    <table style="border: none;">
        <tr style="border: none;">
            <td style="border: none;" width="70%"></td>
            <td style="border: none;">
                Mengetahui,<br>
                Pimpinan Cabang<br><br><br><br>
                ( ................................. )
            </td>
        </tr>
    </table>
</body>

</html>
