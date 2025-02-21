<?php
class PetShop {
    private $id;
    private $nama_produk;
    private $kategori;
    private $harga;
    private $foto;

    public function __construct($id = "", $nama_produk = "", $kategori = "", $harga = 0, $foto = "") {
        $this->id = $id;
        $this->nama_produk = $nama_produk;
        $this->kategori = $kategori;
        $this->harga = $harga;
        $this->foto = $foto;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'nama_produk' => $this->nama_produk,
            'kategori' => $this->kategori,
            'harga' => $this->harga,
            'foto' => $this->foto
        ];
    }

    public static function fromArray($data) {
        return new self(
            $data['id'],
            $data['nama_produk'],
            $data['kategori'],
            $data['harga'],
            $data['foto']
        );
    }

    // Getters dan setters tetap sama
    public function getId() { return $this->id; }
    public function getNamaProduk() { return $this->nama_produk; }
    public function getKategori() { return $this->kategori; }
    public function getHarga() { return $this->harga; }
    public function getFoto() { return $this->foto; }
}
?>