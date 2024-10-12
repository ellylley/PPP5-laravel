<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\M_projek2; // gunakan "Models" dengan huruf kapital sesuai PSR-4
use Illuminate\Http\Request; // Pastikan untuk mengimport class Request
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function home() {
        // Check if user level is greater than 0
        if (session()->get('level') > 0) {
            // Instantiate the model
            $model = new M_projek2();
            
            // Define the where clause
            $where = [
                'id_setting' => 1
            ];
            
            // Retrieve data from the model
            $data['setting'] = $model->getWhere('setting', $where);
            $data['currentMenu'] = 'dashboard'; // Set the current menu
            
            // Return views with the data
            return view('header', $data) .
                   view('menu', $data) .
                   view('dashboard', $data) .
                   view('footer');
        } else {
            // Redirect to the login page
            return redirect()->route('home.login');
        }
    }
    public function logout()
    {
        // Destroy the session
        session()->flush(); // Alternatively, you can use auth()->logout() if using Laravel's authentication
    
        // Redirect to the login page
        return redirect()->route('home.login'); // Assuming you have named your route 'home.login'
    }
        
    public function profile($id)
    {
        // Cek level user dari session
        if (session()->get('level') >= 0 && session()->get('level') <= 5) {
            $model = new M_projek2();
            $id_user = session('id'); // Ambil ID user dari session
            
            // Cek apakah ID yang diminta sesuai dengan ID user yang ada di session
            if ($id == $id_user) {
                $activity = 'Mengakses halaman profile'; // Deskripsi aktivitas
                $this->addLog($id_user, $activity);
    
                // Ambil data user
                $where = ['id_user' => $id]; // Query user berdasarkan ID
                $data['user'] = $model->getWhere('user', $where);
    
                // Ambil setting
                $where = ['id_setting' => 1]; // Query setting
                $data['setting'] = $model->getWhere('setting', $where);
                $data['currentMenu'] = 'profile'; // Sesuaikan dengan menu yang aktif
    
                // Return view dengan data
                echo view('header', $data);
                echo view('menu', $data);
                echo view('profile', $data);
                echo view('footer', $data);
            } else {
                // Redirect ke halaman error jika ID tidak sesuai
                return redirect()->to('home/error');
            }
        } else {
            // Redirect ke halaman error jika level user tidak sesuai
            return redirect()->to('home/error');
        }
    }
    

public function aksieprofile(Request $request)
{
    $model = new M_projek2();

    $id_user = session('id'); // Ambil ID user dari session
    $activity = 'Mengubah data profile'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);

    $a = $request->input('nama');
    $id = $request->input('id');
    $fotoName = $request->input('old_foto'); // Mengambil nama foto lama
    $foto = $request->file('foto');

    if ($foto && $foto->isValid()) {
        // Generate a new name for the uploaded file
        $newName = $foto->getClientOriginalName(); // Mengambil nama asli
        // Simpan file ke direktori public/images
        $foto->storeAs('images', $newName, 'public');
        // Set the new file name to be saved in the database
        $fotoName = $newName;
    }

    $existingBackup = $model->getWhere('backup_user', ['id_user' => $id]);

    if ($existingBackup) {
        // Hapus data lama di user_backup jika ada
        $model->hapus('backup_user', ['id_user' => $id]);
    }

    // Ambil data user lama berdasarkan id_user
    $userLama = $model->getUserById($id);

    // Simpan data user lama ke tabel user_backup
    $backupData = (array) $userLama; // Ubah objek menjadi array
    $model->tambah('backup_user', $backupData);

    $isi = [
        'nama_user' => $a,
        'foto' => $fotoName,
        'updated_at' => now(), // Waktu saat produk dibuat
        'updated_by' => $id_user // ID user yang login
    ];

    $model->edit('user', $isi, ['id_user' => $id]);

    return redirect()->route('home.profile', ['id' => $id]);
}


public function aksi_changepass(Request $request)
{
    $model = new M_projek2();
    $id_user = session('id'); // Ambil ID user dari session
    $activity = 'mengubah password profile'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);
    
    $oldPassword = $request->input('old');
    $newPassword = $request->input('new');

    // Dapatkan password lama dari database
    $currentPassword = $model->getPassword($id_user);

    // Verifikasi apakah password lama cocok
    if (md5($oldPassword) !== $currentPassword) {
        // Set pesan error jika password lama salah
        return redirect()->back()->withInput()->with('error', 'Password lama tidak valid.');
    }

    // Update password baru
    $data = [
        'password' => md5($newPassword),
        'updated_by' => $id_user,
        'updated_at' => now() // Gunakan helper now() untuk waktu saat ini
    ];
    $where = ['id_user' => $id_user];
    
    $model->edit('user', $data, $where);
    
    // Set pesan sukses
    return redirect()->route('home.profile', ['id' => $id_user])->with('success', 'Password berhasil diperbarui.');


}


//login

public function login()
{
    $model = new M_projek2();
    $where = [
        'id_setting' => 1
    ];
    
    $data['setting'] = $model->getWhere('setting', $where);

    // Mengembalikan tampilan 'login' dengan data setting
    return view('header', $data) . view('login', $data);
}


public function aksilogin(Request $request)
    {
        $name = $request->input('nama');
        $pw = $request->input('password');
        $captchaResponse = $request->input('g-recaptcha-response');
        $backupCaptcha = $request->input('backup_captcha');
        
        $secretKey = '6LdLhiAqAAAAAPxNXDyusM1UOxZZkC_BLCgfyoQf'; // Ganti dengan secret key Anda yang sebenarnya
        $recaptchaSuccess = false;

        $captchaModel = new M_projek2();

        // Cek koneksi internet
        if ($this->isInternetAvailable()) {
            // Verifikasi reCAPTCHA
            $response = Http::get("https://www.google.com/recaptcha/api/siteverify", [
                'secret' => $secretKey,
                'response' => $captchaResponse
            ]);

            $responseKeys = $response->json();
            $recaptchaSuccess = $responseKeys['success'];
        }

        if ($recaptchaSuccess) {
            // reCAPTCHA berhasil
            $where = [
                'nama_user' => $name,
                'password' => md5($pw),
            ];

            $model = new M_projek2();
            $check = $model->getWhere('user', $where);

            if ($check) {
                session()->put('id', $check->id_user);
                session()->put('nama', $check->nama_user);
                session()->put('level', $check->level);
                session()->put('foto', $check->foto);
                session()->put('id_kelas', $check->id_kelas);
              

                return redirect()->to('home');
            } else {
                return redirect()->route('home.login')->with('error', 'Invalid username or password.');
            }
        } else {
            // Validasi CAPTCHA offline
            $storedCaptcha = session()->get('captcha_code'); // Retrieve stored CAPTCHA from session

            if ($storedCaptcha !== null) {
                if ($storedCaptcha === $backupCaptcha) {
                    // CAPTCHA valid
                    $where = [
                        'nama_user' => $name,
                        'password' => md5($pw),
                    ];

                    $model = new M_projek2();
                    $check = $model->getWhere('user', $where);

                    if ($check) {
                        session()->put('id', $check->id_user);
                        session()->put('nama', $check->nama_user);
                        session()->put('level', $check->level);
                        session()->put('foto', $check->foto);
                        session()->put('id_kelas', $check->id_kelas);
                        

                        return redirect()->to('home');
                    } else {
                        return redirect()->route('home.login')->with('error', 'Invalid username or password.');
                    }
                } else {
                    // CAPTCHA tidak valid
                    return redirect()->route('home.login')->with('error', 'Invalid CAPTCHA.');
                }
            } else {
                return redirect()->route('home.login')->with('error', 'CAPTCHA session is not set.');
            }
        }
    }

    private function isInternetAvailable()
    {
        $connected = @fsockopen("www.google.com", 80);
        if ($connected) {
            fclose($connected);
            return true;
        }
        return false;
    }

    public function generateCaptcha()
    {
        $code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);

        // Store the CAPTCHA code in the session
        session()->put('captcha_code', $code);

        // Generate the image
        $image = imagecreatetruecolor(120, 40);
        $bgColor = imagecolorallocate($image, 255, 255, 255); // White background
        $textColor = imagecolorallocate($image, 0, 0, 0);     // Black text

        imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);
        imagestring($image, 5, 10, 10, $code, $textColor);

        // Set the content type header - in this case image/png
        header('Content-Type: image/png');

        // Output the image
        imagepng($image);

        // Free up memory
        imagedestroy($image);
    }

public function kelas()
{
    // Check session level
    if (session()->get('level') == 0||session()->get('level') == 1 ) {
        $model = new M_projek2();

        // Get user ID from session
        $id_user = session()->get('id'); // Ambil ID user dari session

        // Log activity
        $this->addLog($id_user, 'Mengakses halaman manajemen kelas');

        // Retrieve specific class details by ID using the `getwhere` method
        $id = Session::get('id_kelas'); // assuming ID is stored in session
        $data['satu'] = $model->getwhere('kelas', ['id_kelas' => $id]);

        // Retrieve all classes using the `tampil` method
        $data['elly'] = $model->tampil('kelas', 'id_kelas');

        // Backup classes logic
        $data['backup_kelas'] = [];
        foreach ($data['elly'] as $kelas) {
            $data['backup_kelas'][$kelas->id_kelas] = $model->getBackupKelas($kelas->id_kelas);
        }

        // Get setting for id_setting = 1 using the `getwhere` method
        $data['setting'] = $model->getwhere('setting', ['id_setting' => 1]);

        //Current active menu
        $data['currentMenu'] = 'kelas';

        // Render views
        echo view('header', $data);
        echo view('menu', $data);
        echo view('kelas', $data);
        echo view('footer', $data);

        // Return view with data (optional, as echoing views already renders them)
        // return view('kelas', $data); // You may remove this if views are already echoed above
    } else {
        return redirect()->route('home.error');
    }
}
public function hapusKelas($id)
{
    $model = new M_projek2();
    $id_user = session('id'); // Get user ID from session
    $activity = 'Menghapus data kelas'; // Activity description
    $this->addLog($id_user, $activity);

    $data = [
        'isdelete' => 1,
        'deleted_by' => $id_user,
        'deleted_at' => now() // Get the current date and time
    ];

    // Update the 'kelas' table
    $model->edit('kelas', $data, ['id_kelas' => $id]);

    // Delete data from the 'backup_kelas' table
    $where = ['id_kelas' => $id];
    $model->hapus('backup_kelas', $where);

    return redirect()->route('home.kelas'); // Redirect to the specified route
}
public function aksi_tambah_kelas(Request $request)
    {
        $model = new M_projek2();
        $id_user = session()->get('id'); // Ambil ID user dari session
        $activity = 'Menambah data kelas'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
       
        $a = $request->input('kelas'); // Get the input for 'kelas'

    $isi = [
        'nama_kelas' => $a,
        'created_at' => now(), // Current timestamp
        'created_by' => $id_user, // ID of the logged-in user
        'isdelete' => 0,
    ];
        $model ->tambah('kelas', $isi);
        
        return redirect()->route('home.kelas'); // Redirect to the 'home/kelas' route
    }

    public function aksi_edit_kelas(Request $request)
{
    $model = new M_projek2();
    $id_user = session()->get('id'); // Ambil ID user dari session
    $activity = 'Mengubah data kelas'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity); // Pastikan untuk mendefinisikan method addLog jika ingin menggunakannya

    $a = $request->input('kelas'); // Ambil input 'kelas'
    $id = $request->input('id'); // Ambil input 'id'

    // Cek apakah backup untuk kelas yang diedit sudah ada
    $existingBackup = $model->getwhere('backup_kelas', ['id_kelas' => $id]);
    
    if ($existingBackup) {
        // Hapus data lama di backup_kelas jika ada
        $model->hapus('backup_kelas', ['id_kelas' => $id]);
    }

    // Ambil data kelas lama berdasarkan id
    $userLama = $model->getwhere('kelas', ['id_kelas' => $id]);
    
    // Simpan data kelas lama ke tabel backup_kelas
    $backupData = (array) $userLama; // Ubah objek menjadi array
    $model->tambah('backup_kelas', $backupData);
    
    // Data yang akan diupdate
    $isi = [
        'nama_kelas' => $a,
        'updated_at' => now(), // Waktu saat kelas diupdate
        'updated_by' => $id_user // ID user yang login
    ];
    
    // Update data di tabel kelas
    $model->edit('kelas', $isi, ['id_kelas' => $id]);
    
    return redirect()->route('home.kelas'); // Redirect ke rute yang sesuai
}

public function aksi_unedit_kelas(Request $request)
{
    $model = new M_projek2();
    $id = $request->input('id'); // Ambil ID dari POST data
    
    if (!$id) {
        return redirect()->route('home.kelas')->with('error', 'ID kelas tidak ditemukan.');
    }
    
    $id_user = session()->get('id'); // Ambil ID user dari session
    $activity = 'Merestore kelas yang diedit'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);
    
    // Ambil data dari tabel backup_kelas berdasarkan id_kelas
    $backupData = $model->getWhere('backup_kelas', ['id_kelas' => $id]);

    if ($backupData) {
        // Konversi data backup menjadi array
        $restoreData = (array) $backupData;

        // Hapus id_kelas dari array karena tidak perlu di-update
        unset($restoreData['id_kelas']);

        // Update data di tabel kelas dengan data dari backup_kelas
        $model->edit('kelas', $restoreData, ['id_kelas' => $id]);

        // Hapus data dari tabel backup_kelas setelah di-restore
        $model->hapus('backup_kelas', ['id_kelas' => $id]);
    }

    return redirect()->route('home.kelas');
}
//nilai

public function nilai()
{
    if (session()->get('level') == 0 || session()->get('level') == 1 || session()->get('level') == 4) {
        $model = new M_projek2();
        $id_user = session('id'); // Ambil user ID dari session
    
        // Logging activity
        $activity = 'Mengakses halaman penilaian'; // Deskripsi aktivitas
       $this->addLog($id_user, $activity);
    
        if (session('level') == 4) {
            // Ambil tugas hanya untuk user yang sedang login (id_user)
            $tasks = $model->getWhereres('tugas', ['id_user' => $id_user, 'isdelete' => 0]);  // Filter tugas sesuai id_user
    
            // Ambil ID kelas yang memiliki tugas yang ditugaskan ke user yang sedang login
            $kelas_ids = array_unique(array_column($tasks->toArray(), 'id_kelas'));
    
            // Ambil kelas di mana id_kelas ada dalam daftar kelas_ids
            $data['kelas'] = $model->getWhereres('kelas', [['id_kelas', $kelas_ids], ['isdelete', 0]]);
    
        } else {
            // Ambil semua kelas dengan isdelete = 0
            $data['kelas'] = $model->getWhereres('kelas', ['isdelete' => 0]);
        }
    
        // Ambil tugas
        if (session('level') == 4) {
            $tasks = $model->getWhereres('tugas', ['id_user' => $id_user, 'isdelete' => 0]);  // Filter tugas sesuai id_user
        } else {
            $tasks = $model->getWhereres('tugas', ['isdelete' => 0]);  // Tambahkan filter isdelete = 0
        }
    
        // Ambil semua nilai dengan join
        $data['nilai'] = $model->jointiga('nilai', 'tugas', 'user', 
            'nilai.id_tugas=tugas.id_tugas', 'nilai.id_user=user.id_user', 'nilai.id_nilai');
    
        // Grupkan tugas berdasarkan kelas
        $data['tugas_by_kelas'] = [];
        foreach ($data['kelas'] as $kelas) {
            // Buat daftar untuk kelas ini
            $data['tugas_by_kelas'][$kelas->id_kelas] = array_filter($tasks->toArray(), function($task) use ($kelas) {
                // Pastikan id_kelas dalam tugas adalah nilai tunggal dan bandingkan
                return $task->id_kelas == $kelas->id_kelas;
            });
        }
    
        // Ambil pengaturan
        $where = ['id_setting' => 1];
        $data['setting'] = $model->getWhere('setting', $where);
        $data['currentMenu'] = 'nilai'; // Sesuaikan dengan menu yang aktif

        echo view('header', $data);
        echo view('menu', $data);
        echo view('nilai', $data);
        echo view('footer');
    } else {
        return redirect()->route('home.error');
    }
}
public function getUsersByClass($id_kelas)
{
    $model = new M_projek2();

    // Filter users by class ID and isdelete = 0
    $where = [
        'id_kelas' => $id_kelas,
        'isdelete' => 0  // Tambahkan kondisi isdelete = 0
    ];
    
    // Menggunakan model dengan function getWhereres yang sudah ada
    $users = $model->getWhereres('user', $where);
    
    // Prepare array for users with their grades
    $users_with_grades = [];

    foreach ($users as $user) {
        // Filter nilai by id_user, id_tugas, and isdelete = 0
        $nilai = $model->getWhereres('nilai', [
            'id_user' => $user->id_user,
            'id_tugas' => request()->get('id_tugas')
            // 'isdelete' => 0  // Tambahkan kondisi isdelete = 0
        ]);

        // Include the grade in the user data
        $user->nilai = isset($nilai[0]->nilai) ? $nilai[0]->nilai : ''; // if no grade, set it to empty string
        
        $users_with_grades[] = $user;
    }

    // Return users with grades in JSON format
    return response()->json(['users' => $users_with_grades]);
}

public function savenilai(Request $request)
{
    $model = new M_projek2();
    $id_user = session('id'); // Get user ID from session
    $current_time = now(); // Current timestamp

    // Ambil data JSON yang dikirim
    $input = $request->json()->all();
    $nilai = $input['nilai'] ?? []; // Data nilai dari client

    

    // Periksa apakah data nilai ada
    if (!is_array($nilai) || empty($nilai)) {
        return response()->json(['status' => 'error', 'message' => 'No data provided']);
    }
    
    // Loop melalui setiap nilai dan simpan ke database
    foreach ($nilai as $item) {
        $data = [
            'id_user' => $item['id_user'],
            'id_tugas' => $item['id_tugas'],
            'id_kelas' => $item['id_kelas'],
            'nilai' => $item['nilai'],
            'updated_by' => $id_user,
            'updated_at' => $current_time
        ];
        
        // Periksa apakah nilai sudah ada, jika ada, update, jika tidak, insert baru
        $existing = $model->getWhere('nilai', [
            'id_user' => $data['id_user'],
            'id_tugas' => $data['id_tugas']
        ]);

        if ($existing) {
            // Update nilai
            $model->edit('nilai', $data, [
                'id_user' => $data['id_user'],
                'id_tugas' => $data['id_tugas']
            ]);
        } else {
            // Insert nilai baru
            $data['created_by'] = $id_user;
            $data['created_at'] = $current_time;
            $model->tambah('nilai', $data);
        }
    }

    return response()->json(['status' => 'success', 'data' => $nilai, 'message' => 'Nilai berhasil disimpan!']);
}


//setting

public function setting()
{
    // Memeriksa level akses user
    if (session()->get('level') == 0||session()->get('level') == 1 ) {
      
        $model = new M_projek2();
        
        $id_user = session()->get('id'); // Ambil ID user dari session
        $activity = 'Mengakses halaman setting'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
        
       

    
        $id = 1; // id_toko yang diinginkan

        // Menyusun kondisi untuk query
        $where = array('id_setting' => $id);

        // Mengambil data dari tabel 'toko' berdasarkan kondisi
        $data['user'] = $model->getWhere('setting', $where);
 
        // Memuat view
        $where=array(
          'id_setting'=> 1
        );
        $data['setting'] = $model->getWhere('setting',$where);
        $data['currentMenu'] = 'setting'; 
        echo view('header', $data);
        echo view('menu', $data);
        echo view('setting', $data);
        echo view('footer', $data);
    } else {
        return redirect()->route('home.error');
    } 
}

public function aksisetting(Request $request)
{
    $model = new M_projek2();
    $id_user = session()->get('id'); // Ambil ID user dari session

        $activity = 'Mengubah data setting'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
        
      
    
       
    $nama = $request->input('nama');
    $alamat = $request->input('alamat');
    $nohp = $request->input('nohp');
    $sekolah = $request->input('sekolah');
    $id =  $request->input('id');
    $uploadedFile = $request->input('foto');

    $where = array('id_setting' => $id);

    $isi = array(
        'nama_setting' => $nama,
        'alamat' => $alamat,
        'nohp' => $nohp,
        'nama_sekolah'=> $sekolah,
        'updated_at' => now(), // Waktu saat produk dibuat
        'updated_by' => $id_user // ID user yang login
    );

    // Cek apakah ada file yang diupload
    if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
        $foto = $request->file('foto')->getClientOriginalName();
        $request->file('foto')->move(public_path('images'), $foto);
        $isi['logo'] = $foto;
    }
    

    $model->edit('setting', $isi, $where);

    return redirect()->route('home.setting', ['id' => $id]);

}

//log

public function log() 
{
    if (session()->get('level') == 0||session()->get('level') == 1 ) {

      
        $model = new M_projek2();


        // Menambahkan log aktivitas ketika user mengakses halaman log
        $id_user = session()->get('id'); // Ambil ID user dari session
        $activity = 'Mengakses halaman log aktivitas'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
        
        // Mengambil data log aktivitas dari model
        $data['logs'] = $model->getActivityLogs();
        $where=array(
          'id_setting'=> 1
        );
        $data['setting'] = $model->getWhere('setting',$where);
        
        $data['currentMenu'] = 'log'; // Sesuaikan dengan menu yang aktif
        echo view('header', $data);
        echo view('menu', $data);
        echo view('log', $data);
        echo view('footer');
    }else{
        return redirect()->route('home.error');
        }
        
}

public function addLog($id_user, $activity)
{
    $model = new M_projek2(); // Gunakan model M_kedaikopi
    $id_user = session()->get('id');
    $data = [
        'id_user' => $id_user,
        'activity' => $activity,
        'timestamp' => now(),
    ];
    $model->tambah('activity_log', $data); // Pastikan 'activity_log' adalah nama tabel yang benar
}

//user

public function user()
{
    if (session()->get('level') == 0||session()->get('level') == 1 ) {
        
        $model = new M_projek2();
        $id_user = Session::get('id'); // Ambil ID user dari session
        $activity = 'Mengakses halaman manajemen user'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);

        $data['kelas'] = $model->getWhereres('kelas', ['isdelete' => 0]);
        $data['elly'] = $model->tampil('user', 'id_user');
        $data['backup_users'] = []; // Inisialisasi array untuk backup user

        foreach ($data['elly'] as $user) {
            $data['backup_users'][$user->id_user] = $model->getBackupUser($user->id_user);
        }

        $data['satu'] = $model->getWhere('user', ['id_user' => $id_user]);

        $where = ['id_setting' => 1];
        $data['setting'] = $model->getWhere('setting', $where);
        $data['currentMenu'] = 'user'; // Sesuaikan dengan menu yang aktif

        echo view('header', $data);
        echo view('menu', $data);
        echo view('user', $data);
        echo view('footer', $data);
    } else {
        return redirect()->route('home.error');
    }
}

public function aksi_tambah_user(Request $request)
{
    $model = new M_projek2();
    $id_user = session()->get('id'); // Ambil ID user dari session
    $activity = 'Menambah user'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);

    $a = $request->input('nama');
    $b = $request->input('level');
    $c = md5($request->input('password'));
    $d = $request->input('nis');
    $e = $request->input('nisn');
    $f = $request->input('kelas');
    $g = $request->input('jk');
    $h = $request->input('lahir');
    $uploadedFile = $request->file('foto');

    // Cek apakah file foto di-upload atau tidak
    if ($uploadedFile && $uploadedFile->isValid()) {
        $foto = $uploadedFile->getClientOriginalName();
        $model->upload($uploadedFile); // Assuming this method handles the file upload
    } else {
        // Set foto default jika tidak ada file yang di-upload
        $foto = 'default.jpg';
    }

    // Set default values based on the selected level
    switch ($b) {
        case 1: // Admin
            $f = 0; // Set id_kelas to 0 for Admin
            $d = null; // Set NIS to null
            $e = null; // Set NISN to null
            break;
        case 2: // Kepala Sekolah
        case 3: // Wakil Kepala Sekolah
        case 4: // Guru
            $f = 0; // Set id_kelas to 0 for these roles
            break;
        case 5: // Murid
            // No changes needed for Murid; use submitted values
            break;
        default:
            // Handle other cases if necessary
            break;
    }
    $isi = [
        'nama_user' => $a,
        'level' => $b,
        'password' => $c,
        'nis' => $d,
        'nisn' => $e,
        'id_kelas' => $f,
        'jk' => $g,
        'tgl_lhr' => $h,
        'foto' => $foto,
        'created_at' => now(), // Current timestamp
        'created_by' => $id_user, // ID user yang login
        'isdelete'=> 0
    ];

    $model->tambah('user', $isi);

    return redirect()->route('home.user'); // Redirect to the 'home/user' route
}

public function aksi_edit_user(Request $request)
{
    $model = new M_projek2();
    $id_user = session()->get('id'); // Ambil ID user dari session
    $activity = 'Mengubah data user'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity); // Pastikan method ini sudah didefinisikan

    // Mengambil input dari form
    $a = $request->input('nama');
    $b = $request->input('level');
    $c = $request->input('password'); // Tidak di-hash, sebaiknya di-hash jika perlu
    $d = $request->input('nis');
    $e = $request->input('nisn');
    $f = $request->input('kelas');
    $g = $request->input('jk');
    $h = $request->input('lahir');
    $id = $request->input('id');
    $fotoName = $request->input('old_foto'); // Mengambil nama foto lama

    $foto = $request->file('foto'); // Mengambil file foto baru jika ada

    // Backup data lama ke tabel backup_user
    $backupWhere = ['id_user' => $id];
    $existingBackup = $model->getwhere('backup_user', $backupWhere);

    if ($existingBackup) {
        // Hapus data lama di backup_user jika ada
        $model->hapus('backup_user', $backupWhere);
    }

    // Ambil data user lama berdasarkan id_user
    $userLama = $model->getUserById($id);

    // Simpan data user lama ke tabel backup_user
    $backupData = (array) $userLama; // Ubah objek menjadi array
    $model->tambah('backup_user', $backupData);

    // Proses upload file jika ada
    if ($foto && $foto->isValid()) {
        // Generate nama file baru
        $newName = time() . '_' . $foto->getClientOriginalName();
        // Pindahkan file ke direktori tujuan
        $foto->move(public_path('images'), $newName);
        // Set nama file baru untuk disimpan ke database
        $fotoName = $newName;
    }

    // Mengatur nilai untuk nis dan nisn berdasarkan level
    if ($b == 1) {
        $d = null; // NIS di-set ke null
        $e = null; // NISN di-set ke null
    }

    // Mengatur id_kelas jadi null jika level adalah 2, 3, atau 4
    if (in_array($b, [1,2, 3, 4])) {
        $f = 0;
    } else {
        $f = $f ?? 0; // Set id_kelas menjadi 0 jika null
    }

    // Data yang akan di-update
    $isi = [
        'nama_user' => $a,
        'level' => $b,
        'password' => $c, // Sebaiknya di-hash jika diperlukan
        'nis' => $d,
        'nisn' => $e,
        'id_kelas' => $f,
        'jk' => $g,
        'tgl_lhr' => $h,
        'foto' => $fotoName,
        'updated_at' => now(), // Waktu saat user di-update
        'updated_by' => $id_user // ID user yang melakukan update
    ];

    // Update data di tabel user
    $model->edit('user', $isi, ['id_user' => $id]);

    // Redirect ke halaman user
    return redirect()->route('home.user');
}

public function aksi_unedit_user(Request $request)
{
    $model = new M_projek2();
    $id = $request->input('id'); // Ambil ID dari POST data
    
    if (!$id) {
        return redirect()->route('home.user')->with('error', 'ID user tidak ditemukan.');
    }
    
    $id_user = session()->get('id'); // Ambil ID user dari session
    $activity = 'Merestore user yang diedit'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);
    
    // Ambil data dari tabel user_backup berdasarkan id_user
    $backupData = $model->getWhere('backup_user', ['id_user' => $id]);

    if ($backupData) {
        // Konversi data backup menjadi array
        $restoreData = (array) $backupData;

        // Hapus id_user dari array karena id_user tidak perlu di-update
        unset($restoreData['id_user']);

        // Update data di tabel user dengan data dari user_backup
        $model->edit('user', $restoreData, ['id_user' => $id]);

        // Hapus data dari tabel user_backup setelah di-restore
        $model->hapus('backup_user', ['id_user' => $id]);
    }

    return redirect()->route('home.user');
}

public function aksi_reset($id)
{
    // Load the M_projek2 model
    $model = new M_projek2();

    // Get the user ID from the session
    $id_user = session('id'); 

    // Log the activity
    $activity = 'Mereset password user';
    $this->addLog($id_user, $activity);

    
    // Set the conditions
    $where = ['id_user' => $id];

    // Prepare the data to be updated
    $isi = [
        'password' => md5('12345'), // Use bcrypt for password hashing
        'updated_at' => now(),          // Laravel helper for current timestamp
        'updated_by' => $id_user
    ];

    // Update the user data
    $model->edit('user', $isi, $where); // Assuming you're using the 'users' table

    // Redirect to the user listing page
    return redirect()->route('home.user');
}




public function hapususer($id)
{
    // Create an instance of the M_projek2 model
    $model = new M_projek2();

    // Get the user ID from the session
    $id_user = session('id'); // Use the session helper to get the ID

    // Log the activity
    $activity = 'Menghapus data user'; // Activity description
    $this->addLog($id_user, $activity);

    // Prepare the data for the update
    $data = [
        'isdelete' => 1,
        'deleted_by' => $id_user,
        'deleted_at' => now() // Use the now() helper for the current datetime
    ];

    // Update the user record as "deleted"
    $model->edit('user', $data, ['id_user' => $id]);

    // Delete data from the backup_kelas table
    $where = ['id_user' => $id];
    $model->hapus('backup_user', $where);

    // Redirect to the user page
    return redirect()->route('home.user'); // Use named route for better practice
}


//tugas

public function tugas()
{
   if (session()->get('level') == 0||session()->get('level') == 1 ||session()->get('level') == 4||session()->get('level') == 5 ) {
        $model = new M_projek2();

        $id_user = session('id'); // Get user ID from session
        $id_kelas = session('id_kelas'); // Get user class ID from session
        $activity = 'Mengakses halaman manajemen tugas'; // Activity description
        $this->addLog($id_user, $activity);

        $data['kelas'] = $model->getWhereres('kelas', ['isdelete' => 0]);

        // Default condition for all levels
        $where = [
            'tugas.isdelete' => 0,
        ];

        // Add specific condition for level 4
        if (session('level') == 4) {
            $where['tugas.id_user'] = $id_user; // Only show tasks for this user
        }

        // Add specific condition for level 5
        if (session('level') == 5) {
            $where['tugas.id_kelas'] = $id_kelas; // Only show tasks for this class
        }

        $data['tugas'] = $model->joinkondisi('tugas', 'kelas', 'tugas.id_kelas=kelas.id_kelas', 'tugas.id_tugas', $where);
        $data['backup_tugas'] = []; // Initialize backup array for user

        foreach ($data['tugas'] as $tugas) {
            $data['backup_tugas'][$tugas->id_tugas] = $model->getBackupTugas($tugas->id_tugas);
        }

        $where = ['id_setting' => 1];
        $data['setting'] = $model->getWhere('setting', $where);
        $data['currentMenu'] = 'tugas'; // Set the active menu

        echo view('header', $data);
        echo view('menu', $data);
        echo view('tugas', $data);
        echo view('footer', $data); 

    } else {
        return redirect()->route('home.error'); // Redirect to error page
    }
}
public function aksi_tambah_tugas(Request $request)

    {
        $model = new M_projek2();
        $id_user = session()->get('id'); // Ambil ID user dari session
        $activity = 'Menambah data tugas'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
       
        $a = $request->input('nama_tugas');
        $b = $request->input('kelas');
        $c = $request->input('tanggal');
       
        
        $isi = array(
            'nama_tugas' => $a,
            'id_kelas' => $b,
            'tanggal' => $c,
            'id_user' => $id_user,
            'created_at' => now(), // Waktu saat produk dibuat
            'created_by' => $id_user, // ID user yang login
            'isdelete'=> 0
            
            

        );
        $model ->tambah('tugas', $isi);
        
        return redirect()->route('home.tugas');
    }

    public function aksi_edit_tugas(Request $request)
    {
        $model = new M_projek2();
        $id_user = session()->get('id'); // Ambil ID user dari session
        $activity = 'Mengubah data tugas'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
       
        $a = $request->input('nama_tugas');
        $b = $request->input('kelas');
        $c = $request->input('tanggal');
        $id = $request->input('id');
       
        $backupWhere = ['id_tugas' => $id];
        $existingBackup = $model->getWhere('backup_tugas', $backupWhere);
    
        if ($existingBackup) {
            // Hapus data lama di user_backup jika ada
            $model->hapus('backup_tugas', $backupWhere);
        }
    
        // Ambil data user lama berdasarkan id_user
        $userLama = $model->getTugasById($id);
    
        // Simpan data user lama ke tabel user_backup
        $backupData = (array) $userLama;  // Ubah objek menjadi array
        $model->tambah('backup_tugas', $backupData);

        $isi = array(
            'nama_tugas' => $a,
            'id_kelas' => $b,
            'tanggal' => $c,
            'id_user' => $id_user,
            'updated_at' => now(), // Waktu saat produk dibuat
            'updated_by' => $id_user // ID user yang login
            
            

        );
         $model->edit('tugas', $isi, ['id_tugas' => $id]);
        
        return redirect()->route('home.tugas');
    }

    public function aksi_unedit_tugas(Request $request)

{
    $model = new M_projek2();
    $id = $request->input('id'); // Ambil ID dari POST data

    
    if (!$id) {
        return redirect()->route('home.tugas')->with('error', 'ID tugas tidak ditemukan.');
    }
    
    $id_user = session()->get('id'); // Ambil ID user dari session
    $activity = 'Merestore tugas yang diedit'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);
    
    // Ambil data dari tabel user_backup berdasarkan id_user
    $backupData = $model->getWhere('backup_tugas', ['id_tugas' => $id]);

    if ($backupData) {
        // Konversi data backup menjadi array
        $restoreData = (array) $backupData;

        // Hapus id_user dari array karena id_user tidak perlu di-update
        unset($restoreData['id_tugas']);

        // Update data di tabel user dengan data dari user_backup
        $model->edit('tugas', $restoreData, ['id_tugas' => $id]);

        // Hapus data dari tabel user_backup setelah di-restore
        $model->hapus('backup_tugas', ['id_tugas' => $id]);
    }

    return redirect()->route('home.tugas');
}

public function hapustugas($id){
    $model = new M_projek2();
    $id_user = session('id'); // Use the session helper to get the ID

    $activity = 'Menghapus data tugas'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);
    $data = [
        'isdelete' => 1,
        'deleted_by' => $id_user,
        'deleted_at' => now() // Format datetime untuk deleted_at
    ];
   
    
      
    $model->edit('tugas', $data, ['id_tugas' => $id]);

    // Hapus data dari tabel backup_kelas
    $where = ['id_tugas' => $id];
$model->hapus('backup_tugas', $where);

    return redirect()->route('home.tugas');
}

public function restore_user()
    {   
        if (session()->get('level') == 0 || session()->get('level') == 1) {
    	$model= new M_projek2();
        $id_user = session('id'); // Ambil ID user dari session
        $activity = 'Mengakses halaman restore user'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
        
        $where = [
            'user.isdelete' => 1,
            
        ];
        $data['elly'] = $model->joinkondisibaru('user', 'kelas', 'user.id_kelas=kelas.id_kelas', 'user.id_user', $where);

        $setting = ['id_setting' => 1];
        $data['setting'] = $model->getWhere('setting', $setting);

          $data['currentMenu'] = 'restore_user'; // Sesuaikan dengan menu yang aktif
        echo view('header', $data);
        echo view ('menu',$data);
        echo view('restore_user',$data);
        echo view ('footer');
         }else{
        return redirect()->route('home.error');
 
    } 
    }

    public function aksi_restore_user($id) {
        $model = new M_projek2();
         $id_user = session()->get('id'); // Ambil ID user dari session
            $activity = 'Merestore user'; // Deskripsi aktivitas
            $this->addLog($id_user, $activity);
        
        // Data yang akan diupdate untuk mengembalikan produk
        $data = [
            'isdelete' => 0,
            'deleted_by' => null,
            'deleted_at' => null
        ];
    
        // Update data produk dengan kondisi id_produk sesuai
        $model->edit('user', $data, ['id_user' => $id]);
    
        return redirect()->route('home.restore_user');
    }

    public function restore_kelas()
    {   
        if (session()->get('level') == 0 || session()->get('level') == 1) {

    	$model= new M_projek2();
        
        $id_user = session('id'); // Ambil ID user dari session
        $activity = 'Mengakses halaman restore kelas'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
        
      
        $data['elly'] = $model->tampil('kelas','id_kelas');

        $setting = ['id_setting' => 1];
        $data['setting'] = $model->getWhere('setting', $setting);

          $data['currentMenu'] = 'restore_kelas'; // Sesuaikan dengan menu yang aktif
        echo view('header', $data);
        echo view ('menu',$data);
        echo view('restore_kelas',$data);
        echo view ('footer');
         }else{
        return redirect()->route('home.error');
 
    } 
    }

    public function aksi_restore_kelas($id) {
        $model = new M_projek2();
         $id_user = session()->get('id'); // Ambil ID user dari session
            $activity = 'Merestore kelas'; // Deskripsi aktivitas
            $this->addLog($id_user, $activity);
        
        // Data yang akan diupdate untuk mengembalikan produk
        $data = [
            'isdelete' => 0,
            'deleted_by' => null,
            'deleted_at' => null
        ];
    
        // Update data produk dengan kondisi id_produk sesuai
        $model->edit('kelas', $data, ['id_kelas' => $id]);
    
        return redirect()->route('home.restore_kelas');
    }


    public function restore_tugas()
    {   
        if (session()->get('level') == 0 || session()->get('level') == 1) {
    	$model= new M_projek2();
        
        $id_user = session('id'); // Ambil ID user dari session
        $activity = 'Mengakses halaman restore tugas'; // Deskripsi aktivitas
        $this->addLog($id_user, $activity);
        // $where= array('id_kelas'=>$id);
        // $data['satu']=$model->getwhere('tugas',$where);
        $data['kelas'] = $model->tampil('kelas', 'id_kelas');
        $where = [
            'tugas.isdelete' => 1,
            
        ];
        $data['tugas'] = $model->joinkondisi('tugas','kelas', 'tugas.id_kelas=kelas.id_kelas', 'tugas.id_tugas', $where);
        $where=array(
            'id_setting'=> 1
          );
          $data['setting'] = $model->getWhere('setting',$where);
          $data['currentMenu'] = 'restore_tugas'; // Sesuaikan dengan menu yang aktif
        echo view('header', $data);
        echo view ('menu',$data);
        echo view('restore_tugas',$data);
        echo view ('footer');
         }else{
        return redirect()->route('home.error');
 
    } 
    }

    public function aksi_restore_tugas($id) {
        $model = new M_projek2();
         $id_user = session()->get('id'); // Ambil ID user dari session
            $activity = 'Merestore tugas'; // Deskripsi aktivitas
            $this->addLog($id_user, $activity);
        
        // Data yang akan diupdate untuk mengembalikan produk
        $data = [
            'isdelete' => 0,
            'deleted_by' => null,
            'deleted_at' => null
        ];
    
        // Update data produk dengan kondisi id_produk sesuai
        $model->edit('tugas', $data, ['id_tugas' => $id]);
    
        return redirect()->route('home.restore_tugas');
    }

    public function nilaisiswa()
{
    if (session()->get('level') == 0 || session()->get('level') == 1 || session()->get('level') == 2 || session()->get('level') == 3 || session()->get('level') == 4 || session()->get('level') == 5) {
        $model = new M_projek2();

        $id_user = session()->get('id'); // Get user ID from session
        $level = session()->get('level'); // Get user level
        $activity = 'Mengakses halaman laporan penilaian'; // Activity description
        $this->addLog($id_user, $activity);

        // Initialize data arrays
        $data['kelas'] = [];
        $data['users_by_class'] = [];
        $data['tugas'] = [];

        if ($level == 5) {
            // If level 5, get only the user's class
            $user = $model->getWhereres('user', ['id_user' => $id_user], 'id_kelas');
            if ($user) {
                $id_kelas_user = $user[0]->id_kelas; // Get the user's class ID
                
                // Get class information where the user belongs
                $data['kelas'] = $model->getWhereres('kelas', ['id_kelas' => $id_kelas_user, 'isdelete' => 0], 'id_kelas');
                
                // Get users in this class
                $data['users_by_class'][$id_kelas_user] = $model->getWhereres('user', [
                    'id_kelas' => $id_kelas_user,
                    'id_user' => $id_user, // Only get the logged-in user's data
                    'isdelete' => 0
                ]);
                
                // Get tasks for this class
                $data['tugas'][$id_kelas_user] = $model->getWhereres('tugas', [
                    'id_kelas' => $id_kelas_user,
                    'isdelete' => 0
                ]);

                // Process user data to get their grades for each task
                foreach ($data['users_by_class'][$id_kelas_user] as &$user_data) {
                    $nilai_per_tugas = [];

                    foreach ($data['tugas'][$id_kelas_user] as $task) {
                        $nilai = $model->getWhereres('nilai', [
                            'id_user' => $user_data->id_user,
                            'id_tugas' => $task->id_tugas
                        ]);

                        // Set nilai per tugas, if available
                        $nilai_per_tugas[$task->id_tugas] = isset($nilai[0]->nilai) ? $nilai[0]->nilai : '';
                    }
                    $user_data->nilai = $nilai_per_tugas; // Assign nilai to user
                }
            }
        } else {
            // For other levels, get all classes and users normally
            $data['kelas'] = $model->getWhereres('kelas', ['isdelete' => 0], 'id_kelas');

            foreach ($data['kelas'] as $kelas) {
                // Get users by class id
                $data['users_by_class'][$kelas->id_kelas] = $model->getWhereres('user', [
                    'id_kelas' => $kelas->id_kelas,
                    'isdelete' => 0
                ]);
                
                // Get tasks by class id
                $data['tugas'][$kelas->id_kelas] = $model->getWhereres('tugas', [
                    'id_kelas' => $kelas->id_kelas,
                    'isdelete' => 0
                ]);

                // Process each user to get their grades for each task
                foreach ($data['users_by_class'][$kelas->id_kelas] as &$user) {
                    $nilai_per_tugas = [];
                    
                    foreach ($data['tugas'][$kelas->id_kelas] as $task) {
                        $nilai = $model->getWhereres('nilai', [
                            'id_user' => $user->id_user,
                            'id_tugas' => $task->id_tugas
                        ]);

                        // Set nilai per tugas, if available
                        $nilai_per_tugas[$task->id_tugas] = isset($nilai[0]->nilai) ? $nilai[0]->nilai : '';
                    }
                    $user->nilai = $nilai_per_tugas; // Assign nilai to user
                }
            }
        }

        // Get settings
        $data['setting'] = $model->getWhere('setting', ['id_setting' => 1]);
        $data['currentMenu'] = 'nilaisiswa'; // Sesuaikan dengan menu yang aktif

        echo view('header', $data);
        echo view('menu', $data);
        echo view('nilaisiswa', $data);
        echo view('footer');
    } else {
        return redirect()->route('home.error');
    }
}

public function word(Request $request) // Tambahkan parameter Request
{
    $model = new M_projek2();
    
    $id_user = session()->get('id'); // Get user ID from session
    $activity = 'Mencetak nilai siswa'; // Activity description
    $this->addLog($id_user, $activity);

    // Get filter 'kelas' from GET request
    $selected_kelas = $request->input('kelas'); // Menggunakan input() dari Request

    // Get all classes (or specific class if filter is applied)
    if ($selected_kelas) {
        // Filter berdasarkan nama_kelas yang dipilih dan hanya ambil yang isdelete=0
        $data['kelas'] = $model->getWhereres('kelas', [
            'id_kelas' => $selected_kelas,
            'isdelete' => 0
        ]);
    } else {
        // Jika tidak ada filter, ambil semua kelas yang isdelete=0
        $data['kelas'] = $model->getWhereres('kelas', ['isdelete' => 0]);
    }

    // Get all users and tasks by class
    $data['users_by_class'] = [];
    $data['tugas'] = [];
    foreach ($data['kelas'] as $kelas) {
        // Get users by class id yang isdelete=0
        $data['users_by_class'][$kelas->id_kelas] = $model->getWhereres('user', [
            'id_kelas' => $kelas->id_kelas,
            'isdelete' => 0
        ]);

        // Get tasks by class id yang isdelete=0
        $data['tugas'][$kelas->id_kelas] = $model->getWhereres('tugas', [
            'id_kelas' => $kelas->id_kelas,
            'isdelete' => 0
        ]);

        // Process each user to get their grades for each task
        foreach ($data['users_by_class'][$kelas->id_kelas] as &$user) {
            $nilai_per_tugas = [];
            
            foreach ($data['tugas'][$kelas->id_kelas] as $task) {
                $nilai = $model->getWhereres('nilai', [
                    'id_user' => $user->id_user,
                    'id_tugas' => $task->id_tugas
                ]);

                // Set nilai per tugas, if available
                $nilai_per_tugas[$task->id_tugas] = isset($nilai[0]->nilai) ? $nilai[0]->nilai : '';
            }
            $user->nilai = $nilai_per_tugas; // Assign nilai to user
        }
    }

    // Get settings
    $data['setting'] = $model->getWhere('setting', ['id_setting' => 1]); // Menghilangkan array dan mengubah menjadi langsung

    // Pass data to view
    return view('word', $data); // Menggunakan return view()
}
public function pdf(Request $request)
{
    $model = new M_projek2();
    
    // Ambil ID user dari session
    $id_user = session()->get('id');
    $activity = 'Mencetak nilai siswa'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);
    
    // Membuat instance Dompdf
    $dompdf = new Dompdf();

    // Mengambil filter 'kelas' dari request
    $selected_kelas = $request->input('kelas');

    // Mengambil semua kelas (atau kelas tertentu jika filter diterapkan)
    if ($selected_kelas) {
        // Filter kelas berdasarkan isdelete = 0
        $data['kelas'] = $model->getWhereres('kelas', [
            'id_kelas' => $selected_kelas,
            'isdelete' => 0 // Menambahkan filter isdelete
        ]);
    } else {
        // Ambil semua kelas dengan isdelete = 0
        $data['kelas'] = $model->getWhereres('kelas', ['isdelete' => 0]);
    }

    // Mengambil semua pengguna dan tugas berdasarkan kelas
    $data['users_by_class'] = [];
    $data['tugas'] = [];
    foreach ($data['kelas'] as $kelas) {
        // Ambil pengguna berdasarkan kelas dan isdelete = 0
        $data['users_by_class'][$kelas->id_kelas] = $model->getWhereres('user', [
            'id_kelas' => $kelas->id_kelas,
            'isdelete' => 0
        ]);
        
        // Ambil tugas berdasarkan kelas dan isdelete = 0
        $data['tugas'][$kelas->id_kelas] = $model->getWhereres('tugas', [
            'id_kelas' => $kelas->id_kelas,
            'isdelete' => 0
        ]);

        foreach ($data['users_by_class'][$kelas->id_kelas] as &$user) {
            $nilai_per_tugas = [];
            
            foreach ($data['tugas'][$kelas->id_kelas] as $task) {
                $nilai = $model->getWhereres('nilai', [
                    'id_user' => $user->id_user,
                    'id_tugas' => $task->id_tugas
                ]);
                $nilai_per_tugas[$task->id_tugas] = isset($nilai[0]->nilai) ? $nilai[0]->nilai : '';
            }
            $user->nilai = $nilai_per_tugas;
        }
    }

    // Mengambil pengaturan
    $data['setting'] = $model->getWhere('setting', ['id_setting' => 1]);

    // Mengonversi logo menjadi base64
    $path = public_path('images/' . $data['setting']->logo);
    if (file_exists($path)) {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data['base64_logo'] = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($path));
    } else {
        $data['base64_logo'] = ''; // Menangani kasus di mana logo tidak ada
    }

    // Membuat HTML dari view
    $html = view('pdf', $data)->render();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Mengirimkan PDF ke browser
    return $dompdf->stream('LAPORAN NILAI TUGAS P5 SISWA.pdf', ['Attachment' => false]);
}



public function excel(Request $request)
{
    $model = new M_projek2();

    $id_user = session('id'); // Ambil ID user dari session
    $activity = 'Mencetak nilai siswa'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);

    // Ambil filter 'kelas' dari request
    $selected_kelas = $request->input('kelas');

    // Ambil semua kelas (atau kelas tertentu jika filter diterapkan)
    if ($selected_kelas) {
        // Filter kelas berdasarkan isdelete = 0
        $data['kelas'] = $model->getWhereres('kelas', [
            'id_kelas' => $selected_kelas,
            'isdelete' => 0 // Menambahkan filter isdelete
        ]);
    } else {
        // Ambil semua kelas dengan isdelete = 0
        $data['kelas'] = $model->getWhereres('kelas', ['isdelete' => 0]);
    }

    // Mengambil semua pengguna dan tugas berdasarkan kelas
    $data['users_by_class'] = [];
    $data['tugas'] = [];
    foreach ($data['kelas'] as $kelas) {
        // Ambil pengguna berdasarkan kelas dan isdelete = 0
        $data['users_by_class'][$kelas->id_kelas] = $model->getWhereres('user', [
            'id_kelas' => $kelas->id_kelas,
            'isdelete' => 0
        ]);
        
        // Ambil tugas berdasarkan kelas dan isdelete = 0
        $data['tugas'][$kelas->id_kelas] = $model->getWhereres('tugas', [
            'id_kelas' => $kelas->id_kelas,
            'isdelete' => 0
        ]);

        foreach ($data['users_by_class'][$kelas->id_kelas] as &$user) {
            $nilai_per_tugas = [];
            
            foreach ($data['tugas'][$kelas->id_kelas] as $task) {
                $nilai = $model->getWhereres('nilai', [
                    'id_user' => $user->id_user,
                    'id_tugas' => $task->id_tugas
                ]);
                $nilai_per_tugas[$task->id_tugas] = isset($nilai[0]->nilai) ? $nilai[0]->nilai : '';
            }
            $user->nilai = $nilai_per_tugas;
        }
    }

    // Buat Spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Define border style
    $borderStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '000000'],
            ],
        ],
    ];

    // Define font style untuk header kelas
    $classHeaderStyle = [
        'font' => [
            'bold' => true,
            'size' => 14, // Atur ukuran font di sini
        ],
    ];

    // Posisi baris awal
    $row = 1;

    foreach ($data['kelas'] as $kelas_item) {
        // Header Kelas
        $sheet->setCellValue('A' . $row, 'Kelas: ' . $kelas_item->nama_kelas);

        // Terapkan style untuk header kelas
        $sheet->getStyle('A' . $row)->applyFromArray($classHeaderStyle);
        $row++;

        // Set header untuk info siswa
        $sheet->setCellValue('A' . $row, 'Nama');
        $sheet->setCellValue('B' . $row, 'NIS');

        // Header dinamis untuk setiap tugas
        $col = 'C'; // Mulai dari kolom C
        foreach ($data['tugas'][$kelas_item->id_kelas] as $task) {
            $sheet->setCellValue($col . $row, $task->nama_tugas);
            $col++;
        }

        // Header untuk Nilai Akhir
        $sheet->setCellValue($col . $row, 'Nilai Akhir');

        // Tambahkan border untuk header
        $sheet->getStyle('A' . $row . ':' . $col . $row)->applyFromArray($borderStyle);
        $row++;

        // Set lebar kolom untuk header
        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Mengisi data siswa
        if (isset($data['users_by_class'][$kelas_item->id_kelas])) {
            foreach ($data['users_by_class'][$kelas_item->id_kelas] as $user) {
                // Isi data siswa
                $sheet->setCellValue('A' . $row, $user->nama_user);
                $sheet->setCellValue('B' . $row, $user->nis);

                // Isi nilai tugas
                $col = 'C';
                $total_nilai = 0;
                $jumlah_tugas = count($data['tugas'][$kelas_item->id_kelas]);

                foreach ($data['tugas'][$kelas_item->id_kelas] as $task) {
                    $nilai = isset($user->nilai[$task->id_tugas]) && $user->nilai[$task->id_tugas] !== '' ? $user->nilai[$task->id_tugas] : 0;

                    $sheet->setCellValue($col . $row, $nilai);

                    if (is_numeric($nilai)) {
                        $total_nilai += $nilai;
                    }
                    $col++;
                }

                // Hitung dan isi Nilai Akhir
                $nilai_akhir = $jumlah_tugas > 0 ? $total_nilai / $jumlah_tugas : 0;
                $sheet->setCellValue($col . $row, number_format($nilai_akhir, 2, '.', ''));

                // Tambahkan border untuk baris data siswa
                $sheet->getStyle('A' . $row . ':' . $col . $row)->applyFromArray($borderStyle);
                $row++;
            }
        } else {
            $sheet->setCellValue('A' . $row, 'Tidak ada siswa pada kelas ini.');
            $row++;

            // Tambahkan border untuk pesan tidak ada siswa
            $sheet->getStyle('A' . $row . ':' . $col . $row)->applyFromArray($borderStyle);
        }

        // Tambahkan spasi sebelum kelas berikutnya
        $row++;
    }

    // Set headers untuk download Excel
    return response()->stream(function () use ($spreadsheet) {
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => 'attachment; filename="LAPORAN NILAI TUGAS P5 SISWA.xlsx"',
        'Cache-Control' => 'max-age=0',
    ]);
}


}
 