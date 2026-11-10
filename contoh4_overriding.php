<?php
class MahasiswaBiaya {
    public function hitungBiaya(): int { return 5000000; }
}
class MahasiswaBeasiswaBiaya extends MahasiswaBiaya {
    public function hitungBiaya(): int { return 2000000; }
}
$a = new MahasiswaBiaya();
$b = new MahasiswaBeasiswaBiaya();
echo $a->hitungBiaya() . ' | ' . $b->hitungBiaya();
