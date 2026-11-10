<?php
trait BisaCetak {
    public function cetak(): string { return 'Siap dicetak'; }
}
class MahasiswaLabel {
    public function label(): string { return 'Mahasiswa'; }
}
class MahasiswaRegulerLabel extends MahasiswaLabel {
    use BisaCetak;
    public function label(): string { return parent::label() . ' Reguler'; }
}
$m = new MahasiswaRegulerLabel();
echo $m->label() . ' - ' . $m->cetak();
