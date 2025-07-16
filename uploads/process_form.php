<?php
require_once('../includes/db.php');
require_once('../includes/functions.php');

// 1. Sanitasi semua input POST
$data = sanitize_array($_POST);

// 2. Upload semua file
$fotoPath = upload_file('foto_siswa');
$ttdAyahPath = upload_file('ttd_ayah');
$ttdIbuPath  = upload_file('ttd_ibu');
$pasFoto1    = upload_file('pas_foto_1');
$pasFoto2    = upload_file('pas_foto_2');
$pasFoto3    = upload_file('pas_foto_3');
$buktiBayar  = upload_file('bukti_pembayaran_pass');
$fotoSiswaPass = upload_file('foto_siswa_pass');
$fotoAyahPass  = upload_file('foto_ayah_pass');
$fotoIbuPass   = upload_file('foto_ibu_pass');

// 3. Siapkan SQL statement (pastikan kolom2 baru tersedia di DB Anda)
$sql = "INSERT INTO enrollment (
    nama_lengkap, nama_panggilan, jenis_kelamin, tempat_lahir, tanggal_lahir,
    agama, kewarganegaraan, anak_ke, jumlah_saudara, bahasa_rumah, alasan_sekolah,
    alamat, telepon, rt_rw, kelurahan, kecamatan, kab_kota,
    golongan_darah, penyakit, kelainan_jasmani, tinggi_badan, berat_badan,
    sekolah_asal, ijazah, skl, nisn, lama_belajar,
    nama_ayah, ttl_ayah, agama_ayah, wn_ayah, pendidikan_ayah, pekerjaan_ayah, gaji_ayah, alamat_ayah, telepon_ayah, status_ayah,
    nama_ibu, ttl_ibu, agama_ibu, wn_ibu, pendidikan_ibu, pekerjaan_ibu, gaji_ibu, alamat_ibu, telepon_ibu, status_ibu,
    nama_wali, ttl_wali, wn_wali, pendidikan_wali, pekerjaan_wali, gaji_wali, alamat_wali, telepon_wali, rt_rw_wali, kelurahan_wali, kecamatan_wali, kab_kota_wali,
    minat_kesenian, minat_olahraga, minat_lain,
    kondisi_medis, kondisi_medis_keterangan,
    pengobatan_rutin, pengobatan_rutin_keterangan,
    persetujuan_medis, tanggung_jawab_kecelakaan,
    ttd_ayah_path, ttd_ibu_path, pas_foto_1, pas_foto_2, pas_foto_3,
    tanggal_pass, nama_siswa_pass, nama_ayah_pass, nama_ibu_pass, kelas_pass,
    independent, pass_student, pass_parents, pass_pickup,
    foto_siswa_pass, foto_ayah_pass, foto_ibu_pass,
    bukti_pembayaran_pass, catatan_pass,
    foto_path
) VALUES (
    ?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?
)";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Kesalahan saat menyiapkan query: " . $conn->error);
}

$stmt->bind_param(
    "sssssssiiisssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",
    $data['nama_lengkap'], $data['nama_panggilan'], $data['jenis_kelamin'],
    $data['tempat_lahir'], $data['tanggal_lahir'], $data['agama'], $data['kewarganegaraan'],
    $data['anak_ke'], $data['jumlah_saudara'], $data['bahasa_rumah'], $data['alasan_sekolah'],
    $data['alamat'], $data['telepon'], $data['rt_rw'], $data['kelurahan'], $data['kecamatan'], $data['kab_kota'],
    $data['golongan_darah'], $data['penyakit'], $data['kelainan_jasmani'], $data['tinggi_badan'], $data['berat_badan'],
    $data['sekolah_asal'], $data['ijazah'], $data['skl'], $data['nisn'], $data['lama_belajar'],
    $data['nama_ayah'], $data['ttl_ayah'], $data['agama_ayah'], $data['wn_ayah'], $data['pendidikan_ayah'], $data['pekerjaan_ayah'], $data['gaji_ayah'], $data['alamat_ayah'], $data['telepon_ayah'], $data['status_ayah'],
    $data['nama_ibu'], $data['ttl_ibu'], $data['agama_ibu'], $data['wn_ibu'], $data['pendidikan_ibu'], $data['pekerjaan_ibu'], $data['gaji_ibu'], $data['alamat_ibu'], $data['telepon_ibu'], $data['status_ibu'],
    $data['nama_wali'], $data['ttl_wali'], $data['wn_wali'], $data['pendidikan_wali'], $data['pekerjaan_wali'], $data['gaji_wali'], $data['alamat_wali'], $data['telepon_wali'], $data['rt_rw_wali'], $data['kelurahan_wali'], $data['kecamatan_wali'], $data['kab_kota_wali'],
    $data['minat_kesenian'], $data['minat_olahraga'], $data['minat_lain'],
    $data['kondisi_medis'], $data['kondisi_medis_keterangan'],
    $data['pengobatan_rutin'], $data['pengobatan_rutin_keterangan'],
    $data['persetujuan_medis'], $data['tanggung_jawab_kecelakaan'],
    $ttdAyahPath, $ttdIbuPath, $pasFoto1, $pasFoto2, $pasFoto3,
    $data['tanggal_pass'], $data['nama_siswa_pass'], $data['nama_ayah_pass'], $data['nama_ibu_pass'], $data['kelas_pass'],
    $data['independent'], $data['pass_student'], $data['pass_parents'], $data['pass_pickup'],
    $fotoSiswaPass, $fotoAyahPass, $fotoIbuPass,
    $buktiBayar, $data['catatan_pass'],
    $fotoPath
);

if ($stmt->execute()) {
    redirect('../success.html');
} else {
    echo "Gagal menyimpan data: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
