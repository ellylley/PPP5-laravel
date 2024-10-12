<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Penilaian</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        /* CSS untuk styling daftar abjad */
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
    </style>
</head>
<body>

<div class="main-content container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Penilaian</h3>
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
                                <div class="list-group">
                                    @if (isset($tugas_by_kelas[$kelas_item->id_kelas]))
                                        @foreach($tugas_by_kelas[$kelas_item->id_kelas] as $row)
                                            <button type="button" class="list-group-item list-group-item-action"
                                                data-id-kelas="{{ $kelas_item->id_kelas }}" 
                                                data-id-tugas="{{ $row->id_tugas }}">
                                                {{ $row->nama_tugas }} - <small><i>{{ date('d M Y', strtotime($row->tanggal)) }}</i></small>
                                            </button>
                                        @endforeach
                                    @else
                                        <p>No tasks available for this class.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="usersModal" tabindex="-1" role="dialog" aria-labelledby="usersModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="usersModalLabel">Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body users-list">
                    <!-- Tabel pengguna akan ditambahkan di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS & Popper.js -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>

<script>
    $(document).ready(function() {
        $('.list-group-item').on('click', function() {
            $('.list-group-item').removeClass('active'); // Hapus active dari item lain
            $(this).addClass('active'); // Tambahkan active ke item yang diklik

            var idKelas = $(this).data('id-kelas');
            var idTugas = $(this).data('id-tugas');

            $.ajax({
                url: '{{ route('home.getUsersByClass', '') }}/' + idKelas,
                type: 'GET',
                data: { id_tugas: idTugas }, // Kirim id_tugas
                dataType: 'json',
                success: function(data) {
                    var users = data.users;
                    
                    // Urutkan pengguna berdasarkan nama secara abjad
                    users.sort(function(a, b) {
                        return a.nama_user.localeCompare(b.nama_user);
                    });

                    var usersTable = '<table class="table table-bordered">';
                    usersTable += '<thead><tr><th>Nama Siswa</th><th>Nilai</th></tr></thead>';
                    usersTable += '<tbody>';
                    $.each(users, function(index, user) {
                        var nilai = user.nilai !== undefined ? user.nilai : ''; // Set nilai default ke 0 jika tidak ada
                        usersTable += '<tr><td>' + user.nama_user + '</td><td><input type="number" class="form-control nilai-input" data-user-id="' + user.id_user + '" value="' + nilai + '" data-id-kelas="' + idKelas + '" data-id-tugas="' + idTugas + '"></td></tr>';
                    });
                    usersTable += '</tbody></table>';
                    $('.users-list').html(usersTable);
                    
                    // Tampilkan modal
                    $('#usersModal').modal('show');
                }
            });
        });

        $('#saveBtn').on('click', function() {
            var values = [];
            
            $('.nilai-input').each(function() {
                var userId = $(this).data('user-id');
                var idKelas = $(this).data('id-kelas'); // Ambil id_kelas dari input
                var idTugas = $(this).data('id-tugas'); // Ambil id_tugas dari input
                var nilai = $(this).val();
                values.push({ id_user: userId, id_kelas: idKelas, id_tugas: idTugas, nilai: nilai });
            });

            $.ajax({
                url: '{{ route('home.savenilai') }}',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ nilai: values }),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    // alert('Nilai berhasil disimpan!');
                    console.log(response.data);
                    $('#usersModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    // alert('Terjadi kesalahan saat menyimpan nilai.');
                }
            });
        });
    });
</script>

</body>
</html>
