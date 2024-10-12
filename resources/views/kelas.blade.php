<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include Bootstrap CSS and JS for modal functionality -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

    <style>
        .disabled-field {
            pointer-events: none;
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="main-content container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class='breadcrumb-header'></nav>
                </div>
            </div>
        </div>

        <div class="row" id="table-bordered">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" align="center">Manajemen Kelas</h4>
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahKelasModal">Tambah</button>
                    </div>
                    <div class="card-content">
                        <!-- table bordered -->
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kelas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($elly as $gou)
                                        @if ($gou->isdelete == 0)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $gou->nama_kelas }}</td>
                                                <td>
                                                    <!-- Dropdown button -->
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Aksi
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editKelasModal" data-id="{{ $gou->id_kelas }}" data-nama="{{ $gou->nama_kelas }}">Edit</a></li>
                                                            <li><a class="dropdown-item" href="{{ route('home.hapuskelas' , $gou->id_kelas) }}">Hapus</a></li>
                                                            @if (isset($backup_kelas[$gou->id_kelas]))
                                                                <li>
                                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#undoEditModal" data-id="{{ $backup_kelas[$gou->id_kelas]->id_kelas }}" data-nama="{{ $backup_kelas[$gou->id_kelas]->nama_kelas }}">
                                                                        Undo Edit
                                                                    </button>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Adding Kelas -->
    <div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-labelledby="tambahKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahKelasModalLabel">Tambah Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('home.aksi_tambah_kelas') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="kelas">Nama Kelas</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="kelas" class="form-control" name="kelas" placeholder="Nama Kelas" required>
                                </div>
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Editing Kelas -->
    <div class="modal fade" id="editKelasModal" tabindex="-1" aria-labelledby="editKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKelasModalLabel">Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('home.aksi_edit_kelas') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="edit-nama-kelas">Nama Kelas</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="edit-nama-kelas" class="form-control" name="kelas" placeholder="Nama Kelas" required>
                                    <input type="hidden" id="edit-id-kelas" name="id">
                                </div>
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                    <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Undo Edit Kelas -->
    <div class="modal fade" id="undoEditModal" tabindex="-1" aria-labelledby="undoEditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="undoEditModalLabel">Undo Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('home.aksi_unedit_kelas') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="undoKelasId" name="id">
                        <div class="form-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="undoNama">Nama Kelas</label>
                                </div>
                                <div class="col-md-8 form-group">
                                    <input type="text" id="undoNama" class="form-control disabled-field" name="kelas" placeholder="Nama Kelas" required>
                                </div>
                                <div class="col-sm-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Undo Edit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script to handle the editing of Kelas
        $('#editKelasModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var nama = button.data('nama');

            var modal = $(this);
            modal.find('#edit-id-kelas').val(id);
            modal.find('#edit-nama-kelas').val(nama);
        });

        // Script to handle the Undo Edit of Kelas
        $('#undoEditModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var id = button.data('id'); // Extract info from data-* attributes
            var nama = button.data('nama');

            var modal = $(this);
            modal.find('#undoKelasId').val(id);
            modal.find('#undoNama').val(nama);
        });
    </script>
</body>
</html>
