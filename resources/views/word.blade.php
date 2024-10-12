<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAPORAN NILAI TUGAS P5 SISWA</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .users-list table {
            width: 100%;
            border-collapse: collapse;
        }
        .users-list th, .users-list td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .users-list th {
            background-color: #f2f2f2;
            text-align: left;
        }

        /* CSS untuk cetak */
        @media print {
            .main-content {
                margin: 0;
                padding: 0;
                font-size: 12px;
            }
            .card {
                page-break-inside: avoid;
                margin: 0;
            }
            .card-header, .card-content {
                display: block;
                padding: 0;
                border: none;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            .table td, .table th {
                text-align: center;
            }
            th, td {
                border: 1px solid #000;
                padding: 4px;
            }
            .btn, .breadcrumb-header {
                display: none;
            }
            .center-text {
                text-align: center;
            }
        }

        /* CSS untuk memperbesar font data dan kelas */
        .list-group-button-badge .card-header h4 {
            font-size: 18px; /* Atur sesuai kebutuhan */
        }
        .list-group-button-badge .user-info h5 {
            font-size: 16px; /* Atur sesuai kebutuhan */
        }
        .list-group-button-badge .table th, .list-group-button-badge .table td {
            font-size: 14px; /* Atur sesuai kebutuhan */
        }
    </style>

</head>
<body>

<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last center-text">
                <img src="{{ asset('images/' . $setting->logo) }}" style="width: 120px; height: auto;">
                <h1>Penilaian Tugas Projek Penguatan Profil Pelajar Pancasila</h1>
                <h1>{{ $setting->nama_sekolah }}</h1>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class='breadcrumb-header'>
                </nav>
            </div>
        </div>
    </div>

    <!-- List group button & badge start -->
    <section class="list-group-button-badge">
    <div class="row match-height">
        @foreach($kelas as $kelas_item)
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelas: {{ $kelas_item->nama_kelas }}</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if (isset($users_by_class[$kelas_item->id_kelas]))
                            @foreach($users_by_class[$kelas_item->id_kelas] as $user)
                                <div class="user-info">
                                    <h5>Nama: {{ $user->nama_user }}</h5>
                                    <h5>NIS: {{ $user->nis }}</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>Tugas</th>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total_nilai = 0;
                                            $jumlah_tugas = count($tugas[$kelas_item->id_kelas]);
                                            ?>
                                            @foreach($tugas[$kelas_item->id_kelas] as $task)
                                                <tr>
                                                    <td>{{ date('d-m-Y', strtotime($task->tanggal)) }}</td>
                                                    <td>{{ $task->nama_tugas }}</td>
                                                    <td>{{ isset($user->nilai[$task->id_tugas]) && is_numeric($user->nilai[$task->id_tugas]) ? $user->nilai[$task->id_tugas] : '0' }}</td>
                                                    <?php
                                                    if (isset($user->nilai[$task->id_tugas]) && is_numeric($user->nilai[$task->id_tugas])) {
                                                        $total_nilai += $user->nilai[$task->id_tugas];
                                                    }
                                                    ?>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td colspan="2"><strong>Nilai Akhir</strong></td>
                                                <td>
                                                <?php
                                                // Hitung nilai akhir rata-rata
                                                if ($jumlah_tugas > 0) {
                                                    $nilai_akhir = $total_nilai / $jumlah_tugas;
                                                    // Memeriksa apakah ada bagian desimal
                                                    if (floor($nilai_akhir) == $nilai_akhir) {
                                                        echo number_format($nilai_akhir, 0, '.', ''); // Tidak menampilkan desimal
                                                    } else {
                                                        echo number_format($nilai_akhir, 2, '.', ''); // Menampilkan dua desimal
                                                    }
                                                } else {
                                                    echo '0';
                                                }
                                                ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        @else
                            <p>No users available for this class.</p>
                        @endif
                    </div>
                </div>
            </div>
            <hr style="border: 2px solid #000; margin: 20px 0;"> <!-- Pemisah antar kelas -->
        </div>
        @endforeach
    </div>
</section>

</div>

<!-- Bootstrap JS & Popper.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        window.print();
    });
</script>

</body>
</html>
