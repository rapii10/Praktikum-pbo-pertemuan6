<?php
declare(strict_types=1);

class Pegawai
{
    protected string $id;
    protected string $nama;

    public function __construct(string $id, string $nama)
    {
        if (trim($id) === '' || trim($nama) === '') {
            throw new InvalidArgumentException('ID dan nama pegawai wajib diisi.');
        }
        $this->id = trim($id);
        $this->nama = trim($nama);
    }

    public function getNama(): string { return $this->nama; }
}

class PegawaiTetap extends Pegawai
{
    private int $gajiPokok;

    public function __construct(string $id, string $nama, int $gajiPokok)
    {
        parent::__construct($id, $nama);
        if ($gajiPokok < 0) throw new InvalidArgumentException('Gaji pokok tidak boleh negatif.');
        $this->gajiPokok = $gajiPokok;
    }

    public function hitungGaji(): int { return $this->gajiPokok; }
}

class PegawaiKontrak extends Pegawai
{
    private int $upahPerJam;
    private int $jamKerja;

    public function __construct(string $id, string $nama, int $upahPerJam, int $jamKerja)
    {
        parent::__construct($id, $nama);
        if ($upahPerJam < 0 || $jamKerja < 0) {
            throw new InvalidArgumentException('Upah per jam dan jam kerja tidak boleh negatif.');
        }
        $this->upahPerJam = $upahPerJam;
        $this->jamKerja = $jamKerja;
    }

    public function hitungGaji(): int { return $this->upahPerJam * $this->jamKerja; }
}

try {
    $pegawaiTetap = new PegawaiTetap('P001', 'Andi', 5000000);
    $pegawaiKontrak = new PegawaiKontrak('P002', 'Budi', 25000, 160);

    echo '<h2>Data Pegawai</h2>';
    echo 'Nama Pegawai Tetap: ' . htmlspecialchars($pegawaiTetap->getNama()) . '<br>';
    echo 'Gaji Pegawai Tetap: Rp ' . number_format($pegawaiTetap->hitungGaji(), 0, ',', '.') . '<br><br>';
    echo 'Nama Pegawai Kontrak: ' . htmlspecialchars($pegawaiKontrak->getNama()) . '<br>';
    echo 'Gaji Pegawai Kontrak: Rp ' . number_format($pegawaiKontrak->hitungGaji(), 0, ',', '.');
} catch (InvalidArgumentException $e) {
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}
