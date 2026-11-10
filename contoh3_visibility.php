<?php
class MahasiswaAkses {
    private string $nim = '23001';
    protected string $kategori = 'Mahasiswa';
    public function getNim(): string { return $this->nim; }
}
class MahasiswaRegulerAkses extends MahasiswaAkses {
    public function info(): string { return $this->getNim() . ' / ' . $this->kategori; }
}
echo (new MahasiswaRegulerAkses())->info();
