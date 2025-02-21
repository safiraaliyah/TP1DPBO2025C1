class PetShop {
    private String id; // variabel untuk menyimpan ID produk
    private String namaProduk; // variabel untuk menyimpan nama produk
    private String kategoriProduk; // variabel untuk menyimpan kategori produk
    private double hargaProduk; // variabel untuk menyimpan harga produk

    // konstruktor untuk inisialisasi objek PetShop
    public PetShop(String id, String namaProduk, String kategoriProduk, double hargaProduk) {
        this.id = id; // menginisialisasi ID produk
        this.namaProduk = namaProduk; // menginisialisasi nama produk
        this.kategoriProduk = kategoriProduk; // menginisialisasi kategori produk
        this.hargaProduk = hargaProduk; // menginisialisasi harga produk
    }

    // getter untuk mendapatkan ID produk
    public String getId() {
        return id;
    }

    // setter untuk mengubah ID produk
    public void setId(String id) {
        this.id = id;
    }

    // getter untuk mendapatkan nama produk
    public String getNamaProduk() {
        return namaProduk;
    }

    // setter untuk mengubah nama produk
    public void setNamaProduk(String namaProduk) {
        this.namaProduk = namaProduk;
    }

    // getter untuk mendapatkan kategori produk
    public String getKategoriProduk() {
        return kategoriProduk;
    }

    // setter untuk mengubah kategori produk
    public void setKategoriProduk(String kategoriProduk) {
        this.kategoriProduk = kategoriProduk;
    }

    // getter untuk mendapatkan harga produk
    public double getHargaProduk() {
        return hargaProduk;
    }

    // setter untuk mengubah harga produk
    public void setHargaProduk(double hargaProduk) {
        this.hargaProduk = hargaProduk;
    }
}