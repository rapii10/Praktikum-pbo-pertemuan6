<?php
class MahasiswaDasar {
    public function __construct(private string $nim, private string $nama) {}
    public function getNama(): string { return $this->nama; }
}
class MahasiswaBeasiswaContoh extends MahasiswaDasar {
    public function __construct(string $nim, string $nama, private string $jenisBeasiswa) {
        parent::__construct($nim, $nama);
    }
    public function info(): string { return $this->getNama() . ' / ' . $this->jenisBeasiswa; }
}
echo (new MahasiswaBeasiswaContoh('23001', 'Siti', 'Prestasi'))->info();
