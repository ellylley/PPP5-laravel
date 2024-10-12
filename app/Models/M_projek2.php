<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class M_projek2 extends Model
{
    public function tampil($tabel, $id)
    {
        return DB::table($tabel)
                    ->orderBy($id, 'desc')
                    ->get();
    }

    public function join($tabel, $tabel2, $on, $id)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->orderBy($id, 'desc')
                    ->get();
    }

    public function tampiltugas($tabel, $id)
    {
        return DB::table($tabel)
                    ->where('isdelete', 0)
                    ->orderBy($id, 'desc')
                    ->get()
                    ->toArray(); // Mengembalikan array asosiatif
    }

    public function getBackupUser($id_user)
    {
        return DB::table('backup_user')
                    ->where('id_user', $id_user)
                    ->first();
    }

    public function getBackupKelas($id_kelas)
    {
        return DB::table('backup_kelas')
                    ->where('id_kelas', $id_kelas)
                    ->first();
    }

    public function getBackupTugas($id_tugas)
    {
        return DB::table('backup_tugas')
                    ->where('id_tugas', $id_tugas)
                    ->first();
    }

    public function joinkondisi($tabel, $tabel2, $on, $id, $where = [])
{
    $query = \DB::table($tabel)
                ->join($tabel2, function ($join) use ($on) {
                    // Split the on condition to get the column names
                    list($left, $right) = explode('=', $on);
                    $join->on(trim($left), '=', trim($right));
                })
                ->orderBy($id, 'desc');

    // If there are where conditions, add them to the query
    if (!empty($where)) {
        $query->where($where);
    }

    return $query->get();
}


public function joinkondisibaru($tabel, $tabel2, $on, $id, $where = [])
{
    $query = \DB::table($tabel)
                ->leftJoin($tabel2, function ($join) use ($on) {
                    // Split the on condition to get the column names
                    list($left, $right) = explode('=', $on);
                    $join->on(trim($left), '=', trim($right));
                })
                ->orderBy($id, 'desc');

    // If there are where conditions, add them to the query
    if (!empty($where)) {
        $query->where($where);
    }

    return $query->get();
}



    public function joinkondisi3($tabel, $tabel2, $tabel3, $on, $on2, $id, $where = [])
    {
        $query = DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->leftJoin($tabel3, $on2)
                    ->orderBy($id, 'desc');

        if (!empty($where)) {
            $query->where($where);
        }

        return $query->get();
    }

    public function joinWhereresult($tabel, $tabel2, $on, $where)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->where($where)
                    ->get(); 
    }

    public function getModulById($id_modul)
    {
        return DB::table('modul')
                    ->leftJoin('fase', 'fase.id_modul', '=', 'modul.id_modul')
                    ->leftJoin('kelas', 'modul.id_kelas', '=', 'kelas.id_kelas')
                    ->where('modul.id_modul', $id_modul)
                    ->orderBy('modul.id_modul', 'asc')
                    ->get();
    }

    public function getModulByIdrow($id_modul)
    {
        return DB::table('modul')
                    ->leftJoin('fase', 'fase.id_modul', '=', 'modul.id_modul')
                    ->leftJoin('kelas', 'modul.id_kelas', '=', 'kelas.id_kelas')
                    ->where('modul.id_modul', $id_modul)
                    ->orderBy('modul.id_modul', 'asc')
                    ->first();
    }

    public function getUserById2($id_user)
    {
        return DB::table('user')
                    ->where('id_user', $id_user)
                    ->first();
    }

    public function joinempat($tabel, $tabel2, $tabel3, $tabel4, $on, $on2, $on3, $id)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->leftJoin($tabel3, $on2)
                    ->leftJoin($tabel4, $on3)
                    ->orderBy($id, 'desc')
                    ->get();
    }

//     public function jointiga($tabel, $tabel2, $tabel3, $on, $on2, $id)
// {
//     return DB::table($tabel)
//             ->leftJoin($tabel2, DB::raw($on))  // Menggunakan DB::raw untuk on condition
//             ->leftJoin($tabel3, DB::raw($on2)) // Menggunakan DB::raw untuk on condition
//             ->orderBy($id, 'desc')
//             ->get(); // Laravel akan langsung mengembalikan koleksi hasil query
// }

public function jointiga($tabel, $tabel2, $tabel3, $on, $on2, $id)
{
    return DB::table($tabel)
            ->leftJoin($tabel2, function($join) use ($on) {
                list($first, $second) = explode('=', $on);  // Memisahkan kolom join
                $join->on(trim($first), '=', trim($second)); // Menggunakan join biasa tanpa DB::raw
            })
            ->leftJoin($tabel3, function($join) use ($on2) {
                list($first, $second) = explode('=', $on2);  // Memisahkan kolom join
                $join->on(trim($first), '=', trim($second)); // Menggunakan join biasa tanpa DB::raw
            })
            ->orderBy($id, 'desc')
            ->get();
}



    public function joinWhere($tabel, $tabel2, $on, $where)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->where($where)
                    ->first();
    }

    public function joinWherebaru($tabel, $tabel2, $on, $where)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->where($where)
                    ->get();
    }

    public function getWhere($tabel, $where)
    {
        return DB::table($tabel)
                    ->where($where)
                    ->first();
    }

    public function getWhereres($tabel, $where)
{
    $query = DB::table($tabel)->where($where)->get();
    
    // Cek jika hasil query tidak kosong
    if ($query->isNotEmpty()) {
        return $query;  // Mengembalikan hasil jika ada
    }
    
    return collect();  // Kembalikan koleksi kosong jika tidak ada hasil
}


    public function tambahBatch($table, $data)
    {
        return DB::table($table)->insert($data);
    }

    public function cari($tabel, $tabel2, $on, $awal, $akhir, $field)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->whereBetween('tgl_pesanan', [$awal, $akhir])
                    ->get();
    }

    public function carik($tabel, $tabel2, $on, $awal, $akhir, $field)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->whereBetween('tanggal_k', [$awal, $akhir])
                    ->get();
    }

    public function caritiga($tabel, $tabel2, $tabel3, $on, $on2, $awal, $akhir, $field)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->leftJoin($tabel3, $on2)
                    ->whereBetween('tgl_pesanan', [$awal, $akhir])
                    ->get();
    }

    public function upload($photo)
    {
        $imageName = $photo->getClientOriginalName();
        $photo->move(public_path('images'), $imageName);
    }

    public function joinn($tabel, $tabel2, $tabel3, $tabel4, $on, $on2, $on3, $id, $where)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->leftJoin($tabel3, $on2)
                    ->leftJoin($tabel4, $on3)
                    ->where($where)
                    ->orderBy($id, 'desc')
                    ->get();
    }

    public function jointigawhere($tabel, $tabel2, $tabel3, $on, $on2, $id, $where)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->leftJoin($tabel3, $on2)
                    ->where($where)
                    ->orderBy($id, 'desc')
                    ->get();
    }

    public function joinempatwhere($tabel, $tabel2, $tabel3, $tabel4, $on, $on2, $on3, $id, $where)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->leftJoin($tabel3, $on2)
                    ->leftJoin($tabel4, $on3)
                    ->where($where)
                    ->orderBy($id, 'desc')
                    ->get();
    }

    public function joinduawhere($tabel, $tabel2, $on, $id, $where)
    {
        return DB::table($tabel)
                    ->leftJoin($tabel2, $on)
                    ->where($where)
                    ->orderBy($id, 'desc')
                    ->get();
    }

    public function getWherecon($table, $conditions)
    {
        return DB::table($table)
                    ->where($conditions)
                    ->get();
    }

    public function getPassword($userId)
    {
        return DB::table('user')
            ->where('id_user', $userId)
            ->value('password'); // Menggunakan value untuk mendapatkan nilai kolom 'password' secara langsung
    }

    public function tambah($tabel, $isi)
    {
        return DB::table($tabel)
                    ->insert($isi);
    }

    public function edit($tabel, $isi, $where)
    {
        return DB::table($tabel)
                    ->where($where)
                    ->update($isi);
    }

    public function hapus($tabel, $where)
    {
        return DB::table($tabel)
                    ->where($where)
                    ->delete();
    }

    public function getMax($table, $column)
    {
        return DB::table($table)
                    ->max($column);
    }

    public function getUserById($id)
{
    return DB::table('user')->where('id_user', $id)->first();
}

public function getTugasById($id)
{
    return DB::table('tugas')->where('id_tugas', $id)->first();
}

public function getActivityLogs()
{
    return DB::table('activity_log')
        ->leftJoin('user', 'activity_log.id_user', '=', 'user.id_user')
        ->select('activity_log.*', 'user.nama_user')
        ->orderBy('activity_log.timestamp', 'DESC')
        ->get();
}

}
