<?php
class MahasiswaContoh {
    public function __construct(private string $nama) {}
    public function getNama(): string { return $this->nama; }
}
class MahasiswaRegulerContoh extends MahasiswaContoh {
    public function jenis(): string { return 'Reguler'; }
}
$m = new MahasiswaRegulerContoh('Andi');
echo $m->getNama() . ' - ' . $m->jenis();
