<?php
declare(strict_types=1);
require_once __DIR__ . '/DataMahasiswa.php';
session_start();

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function rupiah(int $value): string
{
    return 'Rp ' . number_format($value, 0, ',', '.');
}

$repo = new DataMahasiswa($_SESSION['mahasiswa'] ?? []);
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $aksi = (string) ($_POST['aksi'] ?? '');
    try {
        if ($aksi === 'hapus') {
            $nimHapus = trim((string) ($_POST['nim'] ?? ''));
            if (!$repo->hapus($nimHapus)) {
                throw new InvalidArgumentException('Data mahasiswa tidak ditemukan.');
            }
            $_SESSION['mahasiswa'] = $repo->semua();
            $_SESSION['flash'] = 'Data mahasiswa berhasil dihapus.';
            header('Location: index.php'); exit;
        }

        $nim = trim((string) ($_POST['nim'] ?? ''));
        $nama = trim((string) ($_POST['nama'] ?? ''));
        $prodi = trim((string) ($_POST['prodi'] ?? ''));
        $semester = filter_var($_POST['semester'] ?? null, FILTER_VALIDATE_INT);
        $ukt = filter_var($_POST['ukt'] ?? null, FILTER_VALIDATE_INT);
        if ($semester === false || $semester === null || $ukt === false || $ukt === null) {
            throw new InvalidArgumentException('Semester dan UKT harus diisi dengan bilangan bulat.');
        }

        $jenis = (string) ($_POST['jenis'] ?? '');
        if ($jenis === 'reguler') {
            $mahasiswa = new MahasiswaReguler($nim, $nama, $prodi, $semester, $ukt);
        } elseif ($jenis === 'beasiswa') {
            $potongan = filter_var($_POST['potongan'] ?? null, FILTER_VALIDATE_INT);
            if ($potongan === false || $potongan === null) {
                throw new InvalidArgumentException('Potongan beasiswa harus berupa bilangan bulat.');
            }
            $mahasiswa = new MahasiswaBeasiswa($nim, $nama, $prodi, $semester, $ukt, $potongan);
        } else {
            throw new InvalidArgumentException('Jenis mahasiswa tidak valid.');
        }

        if ($aksi === 'ubah') {
            $nimLama = trim((string) ($_POST['nim_lama'] ?? ''));
            if (!$repo->ubah($nimLama, $mahasiswa)) {
                throw new InvalidArgumentException('Data edit tidak cocok. NIM tidak boleh diubah.');
            }
            $pesan = 'Data mahasiswa berhasil diperbarui.';
        } else {
            if (!$repo->tambah($mahasiswa)) {
                throw new InvalidArgumentException('NIM sudah terdaftar. Gunakan NIM lain.');
            }
            $pesan = 'Data mahasiswa berhasil ditambahkan.';
        }

        $_SESSION['mahasiswa'] = $repo->semua();
        $_SESSION['flash'] = $pesan;
        header('Location: index.php'); exit;
    } catch (InvalidArgumentException $exception) {
        $error = $exception->getMessage();
    }
}

if (isset($_SESSION['flash'])) {
    $success = (string) $_SESSION['flash'];
    unset($_SESSION['flash']);
}

$edit = null;
if (isset($_GET['edit'])) {
    $edit = $repo->cari((string) $_GET['edit']);
}
$kataKunci = trim((string) ($_GET['cari'] ?? ''));
$daftar = array_filter($repo->semua(), static function (Mahasiswa $m) use ($kataKunci): bool {
    if ($kataKunci === '') return true;
    return stripos($m->getNim(), $kataKunci) !== false
        || stripos($m->getNama(), $kataKunci) !== false
        || stripos($m->getProdi(), $kataKunci) !== false;
});
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Mahasiswa - Inheritance</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">
    <header>
        <p class="eyebrow">PRAKTIKUM PBO • PERTEMUAN 06</p>
        <h1>Data Mahasiswa</h1>
        <p class="subtitle">Implementasi inheritance menggunakan PHP 8 dan session.</p>
    </header>

    <?php if ($success !== ''): ?><div class="alert success"><?= e($success) ?></div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>

    <section class="panel">
        <h2><?= $edit ? 'Edit Mahasiswa' : 'Tambah Mahasiswa' ?></h2>
        <form method="post" action="index.php">
            <input type="hidden" name="aksi" value="<?= $edit ? 'ubah' : 'tambah' ?>">
            <?php if ($edit): ?><input type="hidden" name="nim_lama" value="<?= e($edit->getNim()) ?>"><?php endif; ?>
            <div class="form-grid">
                <label>NIM
                    <input name="nim" required value="<?= e($edit?->getNim() ?? '') ?>" <?= $edit ? 'readonly' : '' ?>>
                </label>
                <label>Nama
                    <input name="nama" required value="<?= e($edit?->getNama() ?? '') ?>">
                </label>
                <label>Program Studi
                    <input name="prodi" required value="<?= e($edit?->getProdi() ?? '') ?>">
                </label>
                <label>Semester
                    <input type="number" name="semester" min="1" max="14" required value="<?= e($edit?->getSemester() ?? '') ?>">
                </label>
                <label>Jenis Mahasiswa
                    <select name="jenis" id="jenis" required>
                        <option value="reguler" <?= $edit instanceof MahasiswaReguler ? 'selected' : '' ?>>Reguler</option>
                        <option value="beasiswa" <?= $edit instanceof MahasiswaBeasiswa ? 'selected' : '' ?>>Beasiswa</option>
                    </select>
                </label>
                <label>UKT (Rp)
                    <input type="number" name="ukt" min="0" required value="<?= e($edit?->getUkt() ?? '') ?>">
                </label>
                <label id="potongan-wrap">Potongan Beasiswa (%)
                    <input type="number" name="potongan" id="potongan" min="0" max="100"
                           value="<?= e($edit instanceof MahasiswaBeasiswa ? $edit->getPotonganPersen() : 0) ?>">
                </label>
            </div>
            <div class="actions">
                <button type="submit"><?= $edit ? 'Simpan Perubahan' : 'Tambah Data' ?></button>
                <?php if ($edit): ?><a class="button secondary" href="index.php">Batal</a><?php endif; ?>
            </div>
        </form>
    </section>

    <section class="panel">
        <div class="table-heading">
            <h2>Daftar Mahasiswa</h2>
            <form method="get" class="search">
                <input name="cari" placeholder="Cari NIM, nama, atau prodi" value="<?= e($kataKunci) ?>">
                <button type="submit">Cari</button>
                <?php if ($kataKunci !== ''): ?><a href="index.php">Reset</a><?php endif; ?>
            </form>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr>
                    <th>NIM</th><th>Nama</th><th>Prodi</th><th>Semester</th>
                    <th>Jenis</th><th>UKT</th><th>Potongan</th><th>Biaya Akhir</th><th>Aksi</th>
                </tr></thead>
                <tbody>
                <?php if (!$daftar): ?>
                    <tr><td colspan="9" class="empty">Belum ada data yang cocok.</td></tr>
                <?php else: foreach ($daftar as $m): ?>
                    <tr>
                        <td><?= e($m->getNim()) ?></td>
                        <td><?= e($m->getNama()) ?></td>
                        <td><?= e($m->getProdi()) ?></td>
                        <td><?= e($m->getSemester()) ?></td>
                        <td><span class="tag"><?= e($m->getJenis()) ?></span></td>
                        <td><?= rupiah($m->getUkt()) ?></td>
                        <td><?= $m instanceof MahasiswaBeasiswa ? e($m->getPotonganPersen()) . '%' : '-' ?></td>
                        <td><strong><?= rupiah($m->hitungBiaya()) ?></strong></td>
                        <td class="row-actions">
                            <a href="index.php?edit=<?= rawurlencode($m->getNim()) ?>">Edit</a>
                            <form method="post" onsubmit="return confirm('Hapus data ini?')">
                                <input type="hidden" name="aksi" value="hapus">
                                <input type="hidden" name="nim" value="<?= e($m->getNim()) ?>">
                                <button class="link danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <p class="note">Data disimpan menggunakan PHP Session. Data akan tersedia selama session browser masih aktif.</p>
    </section>
</div>
<script>
const jenis = document.getElementById('jenis');
const potonganWrap = document.getElementById('potongan-wrap');
const potongan = document.getElementById('potongan');
function aturPotongan() {
    const aktif = jenis.value === 'beasiswa';
    potonganWrap.style.display = aktif ? 'flex' : 'none';
    potongan.required = aktif;
}
jenis.addEventListener('change', aturPotongan);
aturPotongan();
</script>
</body>
</html>
