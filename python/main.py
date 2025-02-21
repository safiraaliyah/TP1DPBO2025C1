# mengimpor kelas petshop dari file petshop.py
from PetShop import PetShop

# fungsi untuk mencetak tabel dari daftar produk petshop
def printTable(pet_shop_list):
    # jika daftar produk kosong, tampilkan pesan dan keluar dari fungsi
    if not pet_shop_list:
        print("Tidak ada data untuk ditampilkan.")
        return
    
    # mencari panjang maksimum untuk setiap kolom
    maxLens = findMaxLen(pet_shop_list)
    idMax, namaMax, kategoriMax, hargaMax, total = maxLens
    # menghitung total panjang tabel
    total_length = total + 3 * 3 + 5

    # mencetak garis pembatas tabel
    print("-" * total_length)
    # membuat header tabel dengan format yang sesuai
    header = (
        f"| ID{' ' * (idMax - 2)} "
        f"| Nama Produk{' ' * (namaMax - 11)} "
        f"| Kategori{' ' * (kategoriMax - 8)} "
        f"| Harga{' ' * (hargaMax - 5)} |"
    )
    # mencetak header tabel
    print(header)
    # mencetak garis pembatas tabel
    print("-" * total_length)

    # mencetak setiap baris data produk
    for item in pet_shop_list:
        id_str = str(item.get_id()).ljust(idMax)
        nama = item.get_nama_produk().ljust(namaMax)
        kategori = item.get_kategori().ljust(kategoriMax)
        harga = str(item.get_harga()).ljust(hargaMax)
        row = f"| {id_str} | {nama} | {kategori} | {harga} |"
        print(row)
        # mencetak garis pembatas setelah setiap baris
        print("-" * total_length)

# fungsi untuk mencari panjang maksimum setiap kolom
def findMaxLen(pet_shop_list):
    # inisialisasi panjang maksimum untuk setiap kolom
    idMax = 2
    namaMax = 11
    kategoriMax = 8
    hargaMax = 5

    # mencari panjang maksimum untuk setiap kolom berdasarkan data produk
    for item in pet_shop_list:
        id_str_len = len(str(item.get_id()))
        nama_len = len(item.get_nama_produk())
        kategori_len = len(item.get_kategori())
        harga_str_len = len(str(item.get_harga()))
        idMax = max(idMax, id_str_len)
        namaMax = max(namaMax, nama_len)
        kategoriMax = max(kategoriMax, kategori_len)
        hargaMax = max(hargaMax, harga_str_len)

    # menghitung total panjang semua kolom
    total = idMax + namaMax + kategoriMax + hargaMax
    return (idMax, namaMax, kategoriMax, hargaMax, total)

# fungsi untuk mencari produk berdasarkan nama
def cari_data(pet_shop_list, nama):
    return [item for item in pet_shop_list if item.get_nama_produk().lower() == nama.lower()]

# inisialisasi variabel stop untuk mengontrol loop
stop = False
# inisialisasi daftar produk petshop
pet_shop_list = []

# loop utama program
while not stop:
    # menampilkan menu utama
    print("\nDaftar Peralatan PetShop\n")
    printTable(pet_shop_list)
    print("\n=====================")
    print("1. Tambah data")
    print("2. Tampilkan produk")
    print("3. Ubah data")
    print("4. Hapus data")
    print("5. Cari produk")
    print("6. Keluar")
    print("=====================")
    try:
        # meminta input menu dari pengguna
        menu = int(input("Pilih menu: "))
    except ValueError:
        # menangani input yang tidak valid
        print("Input tidak valid. Harap masukkan angka.")
        continue

    # menambahkan data produk baru
    if menu == 1:
        print("\n--- Tambah Data ---")
        try:
            id = int(input("ID: "))
            nama = input("Nama Produk: ")
            kategori = input("Kategori: ")
            harga = int(input("Harga: "))
            pet_shop_list.append(PetShop(id, nama, kategori, harga))
            print("Produk berhasil ditambahkan!")
        except ValueError:
            print("Input harga/ID harus angka!")

    # menampilkan semua produk
    elif menu == 2:
        print("\n--- Tampilkan Produk ---")
        printTable(pet_shop_list)
        input("\nTekan Enter untuk kembali ke menu utama...")

    # mengubah data produk berdasarkan ID
    elif menu == 3:
        print("\n--- Ubah Data ---")
        try:
            id = int(input("Masukkan ID produk yang ingin diubah: "))
            found = False
            for item in pet_shop_list:
                if item.get_id() == id:
                    item.set_nama_produk(input("Nama baru: "))
                    item.set_kategori(input("Kategori baru: "))
                    item.set_harga(int(input("Harga baru: ")))
                    print("Data diperbarui!")
                    found = True
                    break
            if not found:
                print("ID tidak ditemukan.")
        except ValueError:
            print("Input harga harus angka!")

    # menghapus data produk berdasarkan ID
    elif menu == 4:
        print("\n--- Hapus Data ---")
        try:
            id = int(input("Masukkan ID produk yang ingin dihapus: "))
            for item in pet_shop_list:
                if item.get_id() == id:
                    pet_shop_list.remove(item)
                    print("Data dihapus!")
                    break
            else:
                print("ID tidak ditemukan.")
        except ValueError:
            print("Input ID harus angka!")

    # mencari produk berdasarkan nama
    elif menu == 5:
        print("\n--- Cari Data ---")
        nama = input("Masukkan nama produk yang ingin dicari: ")
        hasil = cari_data(pet_shop_list, nama)
        print("\nHasil Pencarian:")
        printTable(hasil)

    # keluar dari program
    elif menu == 6:
        stop = True
        print("Keluar dari program.")

    # menangani pilihan menu yang tidak valid
    else:
        print("Menu tidak tersedia. Silakan pilih menu 1-6.")