
#include <iostream>
#include <string>
#include <vector>
#include <iomanip>
#include <algorithm> 

using namespace std;

// kelas untuk menyimpan data produk petshop
class Petshop {
private:
    string kode;
    string namaItem;
    string jenis;
    double biaya;

public:
    // konstruktor dengan nilai default
    Petshop(string kode = "", string nama = "", string jenis = "", double biaya = 0.0)
        : kode(kode), namaItem(nama), jenis(jenis), biaya(biaya) {}

    // getter dan setter untuk setiap atribut
    string getKode() const { return kode; }
    void setKode(string newKode) { kode = newKode; }

    string getNamaItem() const { return namaItem; }
    void setNamaItem(string newNama) { namaItem = newNama; }

    string getJenis() const { return jenis; }
    void setJenis(string newJenis) { jenis = newJenis; }

    double getBiaya() const { return biaya; }
    void setBiaya(double newBiaya) { biaya = newBiaya; }

    //  untuk menampilkan data produk
    void tampilkan() const {
        cout << "| " << setw(10) << left << kode 
             << " | " << setw(20) << left << namaItem 
             << " | " << setw(15) << left << jenis 
             << " | " << setw(10) << right << fixed << setprecision(2) << biaya << " |" << endl;
    }
};

//  untuk menyimpan data produk
vector<Petshop> daftarProduk;

//  fungsi untuk menambahkan data produk
void tambahProduk() {
    string kode, nama, jenis;
    double biaya;

    cout << "Masukkan Kode: ";
    cin >> kode;
    cout << "Masukkan Nama Item: ";
    cin.ignore();
    getline(cin, nama);
    cout << "Masukkan Jenis: ";
    getline(cin, jenis);
    cout << "Masukkan Biaya: ";
    cin >> biaya;

    daftarProduk.emplace_back(kode, nama, jenis, biaya);
    cout << "Data berhasil ditambahkan!" << endl;
}

//  fungsi untuk menampilkan semua data produk
void tampilkanProduk() {
    if (daftarProduk.empty()) {
        cout << "Tidak ada data tersedia." << endl;
        return;
    }

    cout << "+------------+----------------------+-----------------+------------+" << endl;
    cout << "| Kode       | Nama Item            | Jenis           | Biaya      |" << endl;
    cout << "+------------+----------------------+-----------------+------------+" << endl;

    for (const auto& item : daftarProduk) {
        item.tampilkan();
    }

    cout << "+------------+----------------------+-----------------+------------+" << endl;
}

//  fungsi untuk mengubah data produk berdasarkan kode
void ubahProduk() {
    string kode;
    cout << "Masukkan Kode yang ingin diubah: ";
    cin >> kode;

    for (auto& item : daftarProduk) {
        if (item.getKode() == kode) {
            string nama, jenis;
            double biaya;

            cout << "Masukkan Nama Baru: ";
            cin.ignore();
            getline(cin, nama);
            cout << "Masukkan Jenis Baru: ";
            getline(cin, jenis);
            cout << "Masukkan Biaya Baru: ";
            cin >> biaya;

            item.setNamaItem(nama);
            item.setJenis(jenis);
            item.setBiaya(biaya);
            cout << "Data berhasil diubah!" << endl;
            return;
        }
    }
    cout << "Data tidak ditemukan." << endl;
}

//  fungsi untuk menghapus data produk berdasarkan kode
void hapusProduk() {
    string kode;
    cout << "Masukkan Kode yang ingin dihapus: ";
    cin >> kode;

    daftarProduk.erase(remove_if(daftarProduk.begin(), daftarProduk.end(), 
        [&kode](const Petshop& p) { return p.getKode() == kode; }), daftarProduk.end());
    
    cout << "Data berhasil dihapus!" << endl;
}

//  fungsi untuk mencari produk berdasarkan nama item
void cariProduk() {
    string keyword;
    cout << "Masukkan Nama Item: ";
    cin.ignore();
    getline(cin, keyword);

    bool ditemukan = false;
    for (const auto& item : daftarProduk) {
        if (item.getNamaItem().find(keyword) != string::npos) {
            item.tampilkan();
            ditemukan = true;
        }
    }

    if (!ditemukan) {
        cout << "Data tidak ditemukan." << endl;
    }
}