<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/database.php'; // Koneksi database

$page_title = "Jadwal Kuliah";
include '../../includes/header_admin.php';

// Ambil data jadwal dari database
$sql = "SELECT * FROM jadwal_kuliah";
$result = $conn->query($sql);
$jadwal = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jadwal[] = $row;
    }
}

// Ambil data mata kuliah dan dosen dari database
$sql_mk = "SELECT * FROM mata_kuliah";
$result_mk = $conn->query($sql_mk);
$mata_kuliah_list = [];
if ($result_mk->num_rows > 0) {
    while ($row_mk = $result_mk->fetch_assoc()) {
        $mata_kuliah_list[] = $row_mk;
    }
}

$sql_dosen = "SELECT * FROM dosen";
$result_dosen = $conn->query($sql_dosen);
$dosen_list = [];
if ($result_dosen->num_rows > 0) {
    while ($row_dosen = $result_dosen->fetch_assoc()) {
        $dosen_list[] = $row_dosen;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="../../css/style_jadwal.css">
</head>
<body>
<main class="main-content">
    <h2 class="page-title">Pengaturan Jadwal Kuliah</h2>
    <form action="simpan_jadwal.php" method="POST">
        <input type="hidden" name="id" id="id">
        <label for="mata_kuliah">Mata Kuliah:</label>
        <select name="mata_kuliah" id="mata_kuliah" required>
            <?php foreach ($mata_kuliah_list as $mk): ?>
                <option value="<?php echo $mk['nama']; ?>"><?php echo $mk['nama']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="dosen">Dosen:</label>
        <select name="dosen" id="dosen" required>
            <?php foreach ($dosen_list as $dosen): ?>
                <option value="<?php echo $dosen['nama']; ?>"><?php echo $dosen['nama']; ?></option>
            <?php endforeach; ?>
        </select>
        <label for="hari">Hari:</label>
        <select name="hari" id="hari" required>
            <option value="Senin">Senin</option>
            <option value="Selasa">Selasa</option>
            <option value="Rabu">Rabu</option>
            <option value="Kamis">Kamis</option>
            <option value="Jumat">Jumat</option>
        </select>
        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" required>
        <label for="waktu_mulai">Waktu Mulai:</label>
        <input type="time" name="waktu_mulai" id="waktu_mulai" required>
        <label for="waktu_selesai">Waktu Selesai:</label>
        <input type="time" name="waktu_selesai" id="waktu_selesai" required>
        <button type="submit">Simpan</button>
    </form>
    <table class="data-table">
        <thead>
            <tr>
                <th>Mata Kuliah</th>
                <th>Dosen</th>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jadwal as $jdwl): ?>
                <tr>
                    <td><?php echo $jdwl['mata_kuliah']; ?></td>
                    <td><?php echo $jdwl['dosen']; ?></td>
                    <td><?php echo $jdwl['hari']; ?></td>
                    <td><?php echo $jdwl['tanggal']; ?></td>
                    <td><?php echo $jdwl['waktu_mulai']; ?></td>
                    <td><?php echo $jdwl['waktu_selesai']; ?></td>
                    <td>
                        <button onclick="editJadwal(<?php echo htmlspecialchars(json_encode($jdwl)); ?>)">Edit</button>
                        <a href="hapus_jadwal.php?id=<?php echo $jdwl['id']; ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<script>
function editJadwal(jadwal) {
    document.getElementById('id').value = jadwal.id;
    document.getElementById('mata_kuliah').value = jadwal.mata_kuliah;
    document.getElementById('dosen').value = jadwal.dosen;
    document.getElementById('hari').value = jadwal.hari;
    document.getElementById('tanggal').value = jadwal.tanggal;
    document.getElementById('waktu_mulai').value = jadwal.waktu_mulai;
    document.getElementById('waktu_selesai').value = jadwal.waktu_selesai;
}

// Set minimum date to today
document.addEventListener('DOMContentLoaded', function() {
    var today = new Date().toISOString().split('T')[0];
    document.getElementById('tanggal').setAttribute('min', today);
});
</script>

<?php include '../../includes/footer.php'; ?>
</body>
</html>