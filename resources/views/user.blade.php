<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .disabled-field {
    pointer-events: none;
    background-color: #e9ecef; /* Optional: change the background color to indicate it's disabled */
}
.img-circle {
    border-radius: 50%;
    width: 150px; /* Sesuaikan ukuran yang diinginkan */
    height: 150px; /* Sesuaikan ukuran yang diinginkan */
    object-fit: cover;
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
                    <h4 class="card-title" align="center">Manajemen User</h4>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">Tambah</button>
                    
                </div>

                <div class="card-content">
                    <!-- table bordered -->
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Level</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
    @php $no = 1; @endphp
    @foreach ($elly as $gou)
        @if ($gou->isdelete == 0)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $gou->nama_user }}</td>
                <td>
                    @switch($gou->level)
                        @case(1)
                            Admin
                            @break
                        @case(2)
                            Kepala Sekolah
                            @break
                        @case(3)
                            Wakil Kepala Sekolah
                            @break
                        @case(4)
                            Guru
                            @break
                        @case(5)
                            Murid
                            @break
                    @endswitch
                </td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="actionMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            Aksi
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="actionMenu">
                            <li>
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                    data-id="{{ $gou->id_user }}"
                                    data-nama="{{ $gou->nama_user }}"
                                    data-jk="{{ $gou->jk }}"
                                    data-lahir="{{ $gou->tgl_lhr }}"
                                    data-level="{{ $gou->level }}"
                                    data-nis="{{ $gou->nis }}"
                                    data-nisn="{{ $gou->nisn }}"
                                    data-kelas="{{ $gou->id_kelas }}"
                                    data-foto="{{ $gou->foto }}"
                                    data-password="{{ $gou->password }}">
                                    Edit
                                </button>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('home.hapususer', $gou->id_user) }}">Hapus</a></li>
                            <li><a class="dropdown-item" href="{{ route('home.aksi_reset', $gou->id_user) }}">Reset</a></li>
                            @if (isset($backup_users[$gou->id_user]))
                                <li>
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#undoEditModal"
                                        data-id="{{ $backup_users[$gou->id_user]->id_user }}"
                                        data-nama="{{ $backup_users[$gou->id_user]->nama_user }}"
                                        data-jk="{{ $backup_users[$gou->id_user]->jk }}"
                                        data-lahir="{{ $backup_users[$gou->id_user]->tgl_lhr }}"
                                        data-level="{{ $backup_users[$gou->id_user]->level }}"
                                        data-nis="{{ $backup_users[$gou->id_user]->nis }}"
                                        data-nisn="{{ $backup_users[$gou->id_user]->nisn }}"
                                        data-kelas="{{ $backup_users[$gou->id_user]->id_kelas }}"
                                        data-foto="{{ $backup_users[$gou->id_user]->foto }}">
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('home.aksi_tambah_user') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label>Foto</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="file" id="foto" class="form-control" name="foto">
                            </div>
                            <div class="col-md-4">
                                <label>Nama User</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="first-name" class="form-control" name="nama" placeholder="Nama User">
                            </div>
                           

                            <div class="col-md-4">
                                <label>Password</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="password" id="password" class="form-control" name="password" placeholder="Password">
                            </div>

                            <div class="col-md-4">
    <label>Jenis Kelamin</label>
</div>
<div class="col-md-8 form-group">
    <select id="jk" class="form-control" name="jk">
        <option value="">Pilih</option>
        <option value="Perempuan">Perempuan</option>
        <option value="Laki-laki">Laki-laki</option>
    </select>
</div>
<div class="col-md-4">
                                <label>Tanggal Lahir</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="date" id="lahir" class="form-control" name="lahir" placeholder="Tanggal Lahir">
                            </div>
                            <div class="col-md-4">
                                <label>Level</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="level" name="level" onchange="toggleKelas()">
                                    <option value="1">Admin</option>
                                    <option value="2">Kepala Sekolah</option>
                                    <option value="3">Wakil Kepala Sekolah</option>
                                    <option value="4">Guru</option>
                                    <option value="5">Murid</option>
                                </select>
                            </div>

                            <!-- Kelas Selection -->
                            <div class="col-md-4">
                                <label id="kelasLabel" style="display:none;">Kelas</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="kelas" name="kelas" style="display:none;">
                                    <option>Pilih</option>
                                    <?php foreach($kelas as $gou){ ?>
                                    <option value="<?=$gou->id_kelas?>"><?=$gou->nama_kelas?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- NIS Field -->
                            <div class="col-md-4">
                                <label id="nisLabel" style="display:none;">NIS</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="nis" class="form-control" name="nis" placeholder="NIS" style="display:none;">
                            </div>

                            <!-- NISN Field -->
                            <div class="col-md-4">
                                <label id="nisnLabel" style="display:none;">NISN</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="nisn" class="form-control" name="nisn" placeholder="NISN" style="display:none;">
                            </div>

                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" action="{{ route('home.aksi_edit_user') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label>Profile</label>
                            </div>
                            <div class="col-md-8 form-group">
                            <img id="editProfileImg" src="{{ asset('images/' . $setting->logo) }}" class="profile-img img-circle mb-3" alt="Profile Picture">

                                <input type="file" id="foto" class="form-control" name="foto">
                                <input type="hidden" id="old_foto" name="old_foto">
                            </div>
                            <div class="col-md-4">
                                <label>Nama User</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="editNama" class="form-control" name="nama" placeholder="Nama User">
                            </div>
                            
                            <div class="col-md-4">
    <label>Jenis Kelamin</label>
</div>
<div class="col-md-8 form-group">
    <select id="editjk" class="form-control" name="jk">
        <option value="">Pilih</option>
        <option value="Perempuan">Perempuan</option>
        <option value="Laki-laki">Laki-laki</option>
    </select>
</div>
<div class="col-md-4">
                                <label>Tanggal Lahir</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="date" id="editlahir" class="form-control" name="lahir" placeholder="Tanggal Lahir">
                            </div>
                            
                            <div class="col-md-4">
                                <label>Level</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="editLevel" name="level" onchange="toggleEditKelas()">
                                    <option value="1">Admin</option>
                                    <option value="2">Kepala Sekolah</option>
                                    <option value="3">Wakil Kepala Sekolah</option>
                                    <option value="4">Guru</option>
                                    <option value="5">Murid</option>
                                </select>
                            </div>

                            <!-- Kelas Selection -->
                            <div class="col-md-4">
                                <label id="editKelasLabel" style="display:none;">Kelas</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <select class="form-select" id="editKelas" name="kelas" style="display:none;">
                                    <option>Pilih</option>
                                    <?php foreach($kelas as $gou){ ?>
                                    <option value="<?=$gou->id_kelas?>"><?=$gou->nama_kelas?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <!-- NIS Field -->
                            <div class="col-md-4">
                                <label id="editNisLabel" style="display:none;">NIS</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="editNis" class="form-control" name="nis" placeholder="NIS" style="display:none;">
                            </div>

                            <!-- NISN Field -->
                            <div class="col-md-4">
                                <label id="editNisnLabel" style="display:none;">NISN</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="text" id="editNisn" class="form-control" name="nisn" placeholder="NISN" style="display:none;">
                            </div>

                            <!-- Hidden Password Field -->
                            <input type="hidden" id="editPassword" name="password">
                            <input type="hidden" id="editId" name="id">

                            <div class="col-sm-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Undo Edit Modal -->
    <div class="modal fade" id="undoEditModal" tabindex="-1" aria-labelledby="undoEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="undoEditModalLabel">Undo Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="undoEditForm" action="{{ route('home.aksi_unedit_user') }}" method="POST" enctype="multipart/form-data">
        @csrf
            <input type="hidden" id="undoUserId" name="id">
            <div class="row">
                <div class="col-md-4">
                    <label>Profile</label>
                </div>
                <div class="col-md-8 form-group">
                    <img id="undoProfileImg" src="" class="profile-img" alt="Profile Picture">
                </div>
                <div class="col-md-4">
                    <label>Nama User</label>
                </div>
                <div class="col-md-8 form-group">
                    <input type="text" id="undoNama" class="form-control disabled-field" name="nama" placeholder="Nama User">
                </div>
                
                <div class="col-md-4">
    <label>Jenis Kelamin</label>
</div>
<div class="col-md-8 form-group">
    <select id="undojk" class="form-control disabled-field" name="jk">
        <option value="">Pilih</option>
        <option value="Perempuan">Perempuan</option>
        <option value="Laki-laki">Laki-laki</option>
    </select>
</div>
<div class="col-md-4">
                                <label>Tanggal Lahir</label>
                            </div>
                            <div class="col-md-8 form-group">
                                <input type="date" id="undolahir" class="form-control disabled-field" name="lahir" placeholder="Tanggal Lahir">
                            </div>
                
                <div class="col-md-4">
                    <label>Level</label>
                </div>
                <div class="col-md-8 form-group">
                    <select class="form-select disabled-field" id="undoLevel" name="level">
                        <option value="1">Admin</option>
                        <option value="2">Kepala Sekolah</option>
                        <option value="3">Wakil Kepala Sekolah</option>
                        <option value="4">Guru</option>
                        <option value="5">Murid</option>
                    </select>
                </div>

                <!-- Kelas Selection -->
                <div class="col-md-4">
                    <label id="undoKelasLabel" style="display:none;">Kelas</label>
                </div>
                <div class="col-md-8 form-group">
                    <select class="form-select disabled-field" id="undoKelas" name="kelas" style="display:none;">
                        <option>Pilih</option>
                        <?php foreach($kelas as $gou){ ?>
                        <option value="<?=$gou->id_kelas?>"><?=$gou->nama_kelas?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- NIS Field -->
                <div class="col-md-4">
                    <label id="undoNisLabel" style="display:none;">NIS</label>
                </div>
                <div class="col-md-8 form-group">
                    <input type="text" id="undoNis" class="form-control disabled-field" name="nis" placeholder="NIS" style="display:none;">
                </div>

                <!-- NISN Field -->
                <div class="col-md-4">
                    <label id="undoNisnLabel" style="display:none;">NISN</label>
                </div>
                <div class="col-md-8 form-group">
                    <input type="text" id="undoNisn" class="form-control disabled-field" name="nisn" placeholder="NISN" style="display:none;">
                </div>

                <div class="col-sm-12 d-flex justify-content-end">
                    
                    <button type="submit" class="btn btn-primary me-1 mb-1">Undo Edit</button>
                </div>
                
            </div>
        </form>
      </div>
    </div>
  </div>
</div>



</div>

<!-- Script to populate edit modal with existing data -->
<script>
    document.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');
        var jk = button.getAttribute('data-jk');
        var lahir = button.getAttribute('data-lahir');
        var level = button.getAttribute('data-level');
        var nis = button.getAttribute('data-nis');
        var nisn = button.getAttribute('data-nisn');
        var kelas = button.getAttribute('data-kelas');
        var foto = button.getAttribute('data-foto');
        var password = button.getAttribute('data-password');

        var modal = document.getElementById('editUserModal');
        modal.querySelector('#editId').value = id;
        modal.querySelector('#editNama').value = nama;
        modal.querySelector('#editjk').value = jk;
        modal.querySelector('#editlahir').value = lahir;
        modal.querySelector('#editLevel').value = level;
        modal.querySelector('#editNis').value = nis;
        modal.querySelector('#editNisn').value = nisn;
        modal.querySelector('#editKelas').value = kelas;
        modal.querySelector('#old_foto').value = foto;
        modal.querySelector('#editPassword').value = password;
      modal.querySelector('#editProfileImg').src = "{{ asset('images/') }}" + '/' + foto;


        var modal = document.getElementById('undoEditModal');
    modal.querySelector('#undoUserId').value = id;
    modal.querySelector('#undoNama').value = nama;
    modal.querySelector('#undojk').value = jk;
    modal.querySelector('#undolahir').value = lahir;
    modal.querySelector('#undoLevel').value = level;
    modal.querySelector('#undoNis').value = nis;
    modal.querySelector('#undoNisn').value = nisn;
    modal.querySelector('#undoKelas').value = kelas;
    modal.querySelector('#undoProfileImg').src = "{{ asset('images/') }}" + '/' + foto;


        // Toggle Kelas, NIS, and NISN visibility based on level
        toggleEditKelas();
        toggleUndoKelas();
    });

    function toggleEditKelas() {
    var level = document.getElementById('editLevel').value;
    var kelasField = document.getElementById('editKelas');
    var nisField = document.getElementById('editNis');
    var nisnField = document.getElementById('editNisn');
    var kelasLabel = document.getElementById('editKelasLabel');
    var nisLabel = document.getElementById('editNisLabel');
    var nisnLabel = document.getElementById('editNisnLabel');

    // Menampilkan kelas hanya untuk Murid (level 5)
    if (level == 5) {
        kelasField.style.display = 'block';
        kelasLabel.style.display = 'block';
    } else {
        kelasField.style.display = 'none';
        kelasLabel.style.display = 'none';
    }

    // Menampilkan NIS dan NISN untuk level 2, 3, 4, dan 5
    if (level >= 2 && level <= 5) {
        nisField.style.display = 'block';
        nisLabel.style.display = 'block';
        nisnField.style.display = 'block';
        nisnLabel.style.display = 'block';
    } else {
        nisField.style.display = 'none';
        nisLabel.style.display = 'none';
        nisnField.style.display = 'none';
        nisnLabel.style.display = 'none';
    }
}

function toggleUndoKelas() {
    var level = document.getElementById('undoLevel').value;
    var kelasField = document.getElementById('undoKelas');
    var nisField = document.getElementById('undoNis');
    var nisnField = document.getElementById('undoNisn');
    var kelasLabel = document.getElementById('undoKelasLabel');
    var nisLabel = document.getElementById('undoNisLabel');
    var nisnLabel = document.getElementById('undoNisnLabel');

    if (level == 5) {
        kelasField.style.display = 'block';
        kelasLabel.style.display = 'block';
    } else {
        kelasField.style.display = 'none';
        kelasLabel.style.display = 'none';
    }

    if (level >= 2 && level <= 5) {
        nisField.style.display = 'block';
        nisLabel.style.display = 'block';
        nisnField.style.display = 'block';
        nisnLabel.style.display = 'block';
    } else {
        nisField.style.display = 'none';
        nisLabel.style.display = 'none';
        nisnField.style.display = 'none';
        nisnLabel.style.display = 'none';
    }
}

    function toggleKelas() {
    var level = document.getElementById('level').value;
    var kelasField = document.getElementById('kelas');
    var nisField = document.getElementById('nis');
    var nisnField = document.getElementById('nisn');
    var kelasLabel = document.getElementById('kelasLabel');
    var nisLabel = document.getElementById('nisLabel');
    var nisnLabel = document.getElementById('nisnLabel');

    // Menampilkan kelas hanya untuk Murid (level 5)
    if (level == 5) {
        kelasField.style.display = 'block';
        kelasLabel.style.display = 'block';
    } else {
        kelasField.style.display = 'none';
        kelasLabel.style.display = 'none';
    }

    // Menampilkan NIS dan NISN untuk level 2, 3, 4, dan 5
    if (level >= 2 && level <= 5) {
        nisField.style.display = 'block';
        nisLabel.style.display = 'block';
        nisnField.style.display = 'block';
        nisnLabel.style.display = 'block';
    } else {
        nisField.style.display = 'none';
        nisLabel.style.display = 'none';
        nisnField.style.display = 'none';
        nisnLabel.style.display = 'none';
    }
}


</script>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
