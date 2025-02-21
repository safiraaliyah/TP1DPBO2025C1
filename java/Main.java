import java.util.ArrayList; // mengimpor kelas ArrayList untuk menyimpan daftar produk
import java.util.Scanner; // mengimpor kelas Scanner untuk menerima input dari pengguna

public class Main {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in); // membuat objek Scanner untuk membaca input
        ArrayList<PetShop> listProduk = new ArrayList<>(); // membuat ArrayList untuk menyimpan objek PetShop
        int pilihan; // variabel untuk menyimpan pilihan menu dari pengguna

        do {
            // menampilkan menu utama
            System.out.println("\nDaftar Peralatan PetShop");
            System.out.println("=====================");
            System.out.println("1. Tambah Produk");
            System.out.println("2. Tampilkan Produk");
            System.out.println("3. Ubah Produk");
            System.out.println("4. Hapus Produk");
            System.out.println("5. Cari Produk");
            System.out.println("6. Keluar");
            System.out.println("\n=====================");
            System.out.print("Pilih Menu: ");
            
            // validasi input agar hanya menerima angka
            while (!scanner.hasNextInt()) { 
                System.out.println("Harap masukkan angka antara 1-6.");
                System.out.print("Pilih Menu: ");
                scanner.next(); // membuang input yang tidak valid
            }
            
            pilihan = scanner.nextInt(); // membaca pilihan menu dari pengguna
            scanner.nextLine(); // membersihkan newline setelah nextInt()

            // switch case untuk menangani pilihan menu
            switch (pilihan) {
                case 1:
                    // menambahkan produk baru
                    System.out.print("ID: ");
                    String id = scanner.nextLine(); // membaca ID produk
                    System.out.print("Nama Produk: ");
                    String namaProduk = scanner.nextLine(); // membaca nama produk
                    System.out.print("Kategori: ");
                    String kategoriProduk = scanner.nextLine(); // membaca kategori produk
                    System.out.print("Harga: ");
                    double hargaProduk = scanner.nextDouble(); // membaca harga produk
                    scanner.nextLine(); // membersihkan newline setelah nextDouble()

                    listProduk.add(new PetShop(id, namaProduk, kategoriProduk, hargaProduk)); // menambahkan produk ke ArrayList
                    System.out.println("Produk berhasil ditambahkan!");
                    break;
                
                case 2:
                    // menampilkan semua produk yang ada di ArrayList
                    System.out.println("\nDaftar Produk:");
                    for (PetShop p : listProduk) {
                        System.out.println("ID: " + p.getId() + ", Nama: " + p.getNamaProduk() + 
                                           ", Kategori: " + p.getKategoriProduk() + ", Harga: " + p.getHargaProduk());
                    }
                    break;
                
                case 3:
                    // mengubah data produk berdasarkan ID
                    System.out.print("Masukkan ID produk yang ingin diubah: ");
                    String idUpdate = scanner.nextLine(); // membaca ID produk yang ingin diubah
                    boolean ditemukan = false; // flag untuk menandai apakah produk ditemukan
                    for (PetShop p : listProduk) {
                        if (p.getId().equals(idUpdate)) { // mencari produk dengan ID yang sesuai
                            System.out.print("Nama Baru: ");
                            p.setNamaProduk(scanner.nextLine()); // mengubah nama produk
                            System.out.print("Kategori Baru: ");
                            p.setKategoriProduk(scanner.nextLine()); // mengubah kategori produk
                            System.out.print("Harga Baru: ");
                            p.setHargaProduk(scanner.nextDouble()); // mengubah harga produk
                            scanner.nextLine(); // membersihkan newline setelah nextDouble()
                            System.out.println("Produk berhasil diperbarui!");
                            ditemukan = true; // menandai produk ditemukan
                            break;
                        }
                    }
                    if (!ditemukan) {
                        System.out.println("Produk dengan ID tersebut tidak ditemukan.");
                    }
                    break;
                
                case 4:
                    // menghapus produk berdasarkan ID
                    System.out.print("Masukkan ID produk yang ingin dihapus: ");
                    String idHapus = scanner.nextLine(); // membaca ID produk yang ingin dihapus
                    boolean dihapus = listProduk.removeIf(p -> p.getId().equals(idHapus)); // menghapus produk jika ID cocok
                    if (dihapus) {
                        System.out.println("Produk berhasil dihapus!");
                    } else {
                        System.out.println("Produk tidak ditemukan.");
                    }
                    break;
                
                case 5:
                    // mencari produk berdasarkan nama
                    System.out.print("Masukkan nama produk yang ingin dicari: ");
                    String namaCari = scanner.nextLine(); // membaca nama produk yang ingin dicari
                    boolean ada = false; // flag untuk menandai apakah produk ditemukan
                    for (PetShop p : listProduk) {
                        if (p.getNamaProduk().equalsIgnoreCase(namaCari)) { // mencari produk dengan nama yang sesuai (case-insensitive)
                            System.out.println("Hasil Pencarian: ID: " + p.getId() + ", Nama: " + p.getNamaProduk() + 
                                               ", Kategori: " + p.getKategoriProduk() + ", Harga: " + p.getHargaProduk());
                            ada = true; // menandai produk ditemukan
                        }
                    }
                    if (!ada) {
                        System.out.println("Produk tidak ditemukan.");
                    }
                    break;
                
                case 6:
                    // keluar dari program
                    System.out.println("Keluar dari program.");
                    break;
                
                default:
                    // menangani pilihan menu yang tidak valid
                    System.out.println("Menu tidak tersedia. Silakan pilih menu 1-6.");
                    break;
            }
        } while (pilihan != 6); // loop akan berjalan selama pengguna tidak memilih menu keluar

        scanner.close(); // menutup Scanner untuk mencegah kebocoran memori
    }
}