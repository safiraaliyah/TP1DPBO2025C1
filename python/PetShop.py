# definisi kelas petshop
class PetShop:
    # inisialisasi atribut privat
    __id = -1
    __nama_produk = ""
    __kategori = ""
    __harga = 0

    # konstruktor untuk inisialisasi objek petshop
    def __init__(self, id=-1, nama_produk="", kategori="", harga=0):
        self.__id = id
        self.__nama_produk = nama_produk
        self.__kategori = kategori
        self.__harga = harga

    # metode untuk mendapatkan id produk
    def get_id(self):
        return self.__id

    # metode untuk mengatur id produk
    def set_id(self, id):
        self.__id = id

    # metode untuk mendapatkan nama produk
    def get_nama_produk(self):
        return self.__nama_produk

    # metode untuk mengatur nama produk
    def set_nama_produk(self, nama_produk):
        self.__nama_produk = nama_produk

    # metode untuk mendapatkan kategori produk
    def get_kategori(self):
        return self.__kategori

    # metode untuk mengatur kategori produk
    def set_kategori(self, kategori):
        self.__kategori = kategori

    # metode untuk mendapatkan harga produk
    def get_harga(self):
        return self.__harga

    # metode untuk mengatur harga produk
    def set_harga(self, harga):
        self.__harga = harga