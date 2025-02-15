
#include <iostream>
using namespace std;

//  fungsi-fungsi yang didefinisikan 
void tambahProduk();
void tampilkanProduk();
void ubahProduk();
void hapusProduk();
void cariProduk();

int main() {
    int pilihan;
    do {
        //  pilihan menu yang tersedia
        cout << "\nMenu:" << endl;
        cout << "1. Tambah Produk" << endl;
        cout << "2. Tampilkan Produk" << endl;
        cout << "3. Ubah Produk" << endl;
        cout << "4. Hapus Produk" << endl;
        cout << "5. Cari Produk" << endl;
        cout << "0. Keluar" << endl;
        cout << "Pilih: ";
        cin >> pilihan;

        // eksekusi berdasarkan pilihan
        switch (pilihan) {
            case 1: tambahProduk(); break;
            case 2: tampilkanProduk(); break;
            case 3: ubahProduk(); break;
            case 4: hapusProduk(); break;
            case 5: cariProduk(); break;
            case 0: cout << "Keluar..." << endl; break;
            default: cout << "Pilihan tidak valid!" << endl;
        }
    } while (pilihan != 0);

    return 0;
}