<?php
declare(strict_types=1);
require_once __DIR__ . '/MahasiswaReguler.php';
require_once __DIR__ . '/MahasiswaBeasiswa.php';

class DataMahasiswa
{
    /** @var array<string, Mahasiswa> */
    private array $data = [];

    public function __construct(array $awal = [])
    {
        foreach ($awal as $mahasiswa) {
            if ($mahasiswa instanceof Mahasiswa) {
                $this->data[$mahasiswa->getNim()] = $mahasiswa;
            }
        }
    }

    public function tambah(Mahasiswa $mahasiswa): bool
    {
        $nim = $mahasiswa->getNim();
        if (isset($this->data[$nim])) return false;
        $this->data[$nim] = $mahasiswa;
        return true;
    }

    public function cari(string $nim): ?Mahasiswa
    {
        return $this->data[$nim] ?? null;
    }

    /** @return array<string, Mahasiswa> */
    public function semua(): array { return $this->data; }

    public function ubah(string $nimLama, Mahasiswa $mahasiswa): bool
    {
        if (!isset($this->data[$nimLama]) || $mahasiswa->getNim() !== $nimLama) {
            return false;
        }
        $this->data[$nimLama] = $mahasiswa;
        return true;
    }

    public function hapus(string $nim): bool
    {
        if (!isset($this->data[$nim])) return false;
        unset($this->data[$nim]);
        return true;
    }
}
