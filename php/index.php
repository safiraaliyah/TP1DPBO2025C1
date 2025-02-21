<?php
session_start(); // memulai sesi untuk menyimpan data produk
require 'PetShop.php'; // mengimpor file yang berisi class petshop

// mengaktifkan mode debug untuk menampilkan semua error
error_reporting(E_ALL);
ini_set('display_errors', 1);

// menentukan direktori untuk menyimpan file yang diunggah
$upload_dir = "uploads/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true); // membuat folder uploads jika belum ada
}

// inisialisasi array produk di sesi jika belum ada
if (!isset($_SESSION['products_data'])) {
    $_SESSION['products_data'] = [];
}

// menangani pengiriman formulir
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add': // menambahkan produk baru
                $foto_path = "";
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                    // menyimpan file yang diunggah dengan nama unik
                    $file_name = time() . '_' . basename($_FILES["foto"]["name"]);
                    $target_file = $upload_dir . $file_name;
                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                        $foto_path = $target_file;
                    }
                }
                
                // membuat id baru untuk produk
                $newId = count($_SESSION['products_data']) + 1;
                $newProduct = new PetShop(
                    $newId,
                    $_POST['nama_produk'],
                    $_POST['kategori'],
                    $_POST['harga'],
                    $foto_path
                );
                
                // menyimpan produk dalam sesi sebagai array
                $_SESSION['products_data'][] = $newProduct->toArray();
                break;
                
            case 'edit': // mengedit produk
                $id = $_POST['id'];
                foreach ($_SESSION['products_data'] as $key => $product_data) {
                    if ($product_data['id'] == $id) {
                        $foto_path = $product_data['foto'];
                        
                        // jika ada file baru yang diunggah, hapus file lama dan simpan yang baru
                        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
                            if (!empty($foto_path) && file_exists($foto_path)) {
                                unlink($foto_path);
                            }
                            
                            $file_name = time() . '_' . basename($_FILES["foto"]["name"]);
                            $target_file = $upload_dir . $file_name;
                            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                                $foto_path = $target_file;
                            }
                        }
                        
                        // memperbarui data produk
                        $updatedProduct = new PetShop(
                            $id,
                            $_POST['nama_produk'],
                            $_POST['kategori'],
                            $_POST['harga'],
                            $foto_path
                        );
                        
                        $_SESSION['products_data'][$key] = $updatedProduct->toArray();
                        break;
                    }
                }
                break;
                
            case 'delete': // menghapus produk
                $id = $_POST['id'];
                foreach ($_SESSION['products_data'] as $key => $product_data) {
                    if ($product_data['id'] == $id) {
                        // hapus file foto jika ada
                        if (!empty($product_data['foto']) && file_exists($product_data['foto'])) {
                            unlink($product_data['foto']);
                        }
                        unset($_SESSION['products_data'][$key]); // hapus produk dari sesi
                        break;
                    }
                }
                // reset indeks array setelah penghapusan
                $_SESSION['products_data'] = array_values($_SESSION['products_data']);
                break;
        }
        
        // redirect ke halaman utama setelah operasi selesai
        header('Location: index.php');
        exit();
    }
}

// mengonversi data dalam sesi kembali ke objek produk
$products = [];
foreach ($_SESSION['products_data'] as $product_data) {
    $products[] = PetShop::fromArray($product_data);
}

// pencarian produk berdasarkan nama
$search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
if ($search_query !== '') {
    $products = array_filter($products, function($product) use ($search_query) {
        return strpos(strtolower($product->getNamaProduk()), $search_query) !== false;
    });
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetShop - Milik Sapira</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    :root {
        --primary-color: #FF9BB3;    /* Pink muda */
        --secondary-color: #FFB5C7;  /* Pink muda lebih terang */
        --danger-color: #FF7B98;     /* Pink yang lebih tua untuk button delete */
        --warning-color: #FFE5EB;    /* Pink sangat muda untuk button edit */
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #FFF0F3;   /* Background pink sangat muda */
        color: #4A4A4A;
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .header {
        background-color: white;
        padding: 20px;
        box-shadow: 0 2px 15px rgba(255, 155, 179, 0.2);  /* Shadow dengan warna pink */
        margin-bottom: 30px;
    }

    .header h1 {
        color: var(--primary-color);
        text-align: center;
        font-size: 2.5em;
    }

    .add-product-btn {
        background-color: var(--primary-color);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1em;
        margin-bottom: 20px;
        transition: background-color 0.3s;
    }

    .add-product-btn:hover {
        background-color: var(--secondary-color);
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 20px;
        padding: 20px 0;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(255, 155, 179, 0.15);
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(255, 155, 179, 0.3);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-info {
        padding: 20px;
    }

    .product-title {
        font-size: 1.2em;
        margin-bottom: 10px;
        color: #4A4A4A;
    }

    .product-category {
        color: #888;
        font-size: 0.9em;
        margin-bottom: 10px;
    }

    .product-price {
        font-weight: bold;
        color: var(--primary-color);
        font-size: 1.1em;
        margin-bottom: 15px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9em;
        transition: all 0.3s;
        flex: 1;
    }

    .btn-edit {
        background-color: var(--warning-color);
        color: var(--primary-color);
    }

    .btn-edit:hover {
        background-color: #FFD6E0;
    }

    .btn-delete {
        background-color: var(--danger-color);
        color: white;
    }

    .btn-delete:hover {
        background-color: #FF6B8B;
    }

    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        z-index: 1000;
    }

    .modal-content {
        background-color: white;
        padding: 30px;
        border-radius: 15px;
        width: 90%;
        max-width: 500px;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        box-shadow: 0 5px 25px rgba(255, 155, 179, 0.25);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #4A4A4A;
        font-weight: 500;
    }

    .form-group input[type="text"],
    .form-group input[type="number"],
    .form-group input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #FFD6E0;
        border-radius: 5px;
        font-size: 1em;
        transition: border-color 0.3s;
    }

    .form-group input[type="text"]:focus,
    .form-group input[type="number"]:focus,
    .form-group input[type="file"]:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .close {
        position: absolute;
        right: 20px;
        top: 20px;
        font-size: 1.5em;
        cursor: pointer;
        color: var(--primary-color);
        transition: color 0.3s;
    }

    .close:hover {
        color: var(--danger-color);
    }

    .top-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        gap: 20px;
    }

    .search-container {
        flex-grow: 1;
        max-width: 500px;
        position: relative;
    }

    .search-container input {
        width: 100%;
        padding: 12px 20px;
        padding-left: 45px;
        border: 2px solid var(--primary-color);
        border-radius: 25px;
        font-size: 1em;
        transition: all 0.3s ease;
        background-color: white;
    }

    .search-container input:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 10px rgba(255, 155, 179, 0.2);
    }

    .search-container i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--primary-color);
        font-size: 1.2em;
    }

    .button-container {
        display: flex;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .top-actions {
            flex-direction: column;
        }
        
        .search-container {
            width: 100%;
            max-width: none;
        }
    }
    </style>
</head>
<body>
    <!-- Header toko -->
    <div class="header">
        <h1>Pet Shop Sigma Gurl</h1>
    </div>
    
    <!-- Container utama -->
    <div class="container">
        <!-- Bagian pencarian produk -->
        <div class="top-actions">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Cari produk..." 
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                    onkeyup="handleSearch(event)"
                >
            </div>  
        </div>

        <!-- Tombol untuk menambah produk baru -->
        <button class="add-product-btn" onclick="openModal('add')">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>

        <!-- Daftar produk yang ditampilkan dalam grid -->
        <div class="product-grid">
            <?php foreach ($products as $product) { ?>
                <div class="product-card">
                    <!-- Gambar produk -->
                    <img src="<?php echo $product->getFoto() ? $product->getFoto() : 'uploads/default.jpg'; ?>" 
                         alt="<?php echo $product->getNamaProduk(); ?>" 
                         class="product-image">
                    <div class="product-info">
                        <!-- Informasi produk -->
                        <h3 class="product-title"><?php echo $product->getNamaProduk(); ?></h3>
                        <p class="product-category"><?php echo $product->getKategori(); ?></p>
                        <p class="product-price">Rp <?php echo number_format($product->getHarga(), 0, ',', '.'); ?></p>
                        
                        <!-- Tombol aksi untuk edit dan hapus -->
                        <div class="action-buttons">
                            <button class="btn btn-edit" onclick="openModal('edit', <?php echo $product->getId(); ?>, '<?php echo $product->getNamaProduk(); ?>', '<?php echo $product->getKategori(); ?>', <?php echo $product->getHarga(); ?>)">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-delete" onclick="deleteProduct(<?php echo $product->getId(); ?>)">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Modal form untuk tambah/edit produk -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Tambah Produk</h2>
            <form id="productForm" action="index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="productId">
                
                <!-- Input nama produk -->
                <div class="form-group">
                    <label for="nama_produk">Nama Produk:</label>
                    <input type="text" id="nama_produk" name="nama_produk" required>
                </div>
                
                <!-- Input kategori produk -->
                <div class="form-group">
                    <label for="kategori">Kategori:</label>
                    <input type="text" id="kategori" name="kategori" required>
                </div>
                
                <!-- Input harga produk -->
                <div class="form-group">
                    <label for="harga">Harga:</label>
                    <input type="number" id="harga" name="harga" required>
                </div>
                
                <!-- Input gambar produk -->
                <div class="form-group">
                    <label for="foto">Foto Produk:</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>
                
                <button type="submit" class="add-product-btn">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </form>
        </div>
    </div>

    <script>
        // Fungsi untuk membuka modal
        function openModal(action, id = '', nama = '', kategori = '', harga = '') {
            document.getElementById('productModal').style.display = 'block';
            document.getElementById('formAction').value = action;
            document.getElementById('modalTitle').textContent = action === 'add' ? 'Tambah Produk' : 'Edit Produk';
            
            if (action === 'edit') {
                document.getElementById('productId').value = id;
                document.getElementById('nama_produk').value = nama;
                document.getElementById('kategori').value = kategori;
                document.getElementById('harga').value = harga;
            } else {
                document.getElementById('productForm').reset();
            }
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('productModal').style.display = 'none';
        }

        // Fungsi untuk menghapus produk
        function deleteProduct(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'index.php';
                
                const actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = 'delete';
                
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'id';
                idInput.value = id;
                
                form.appendChild(actionInput);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Menutup modal ketika klik di luar modal
        window.onclick = function(event) {
            const modal = document.getElementById('productModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        // Fungsi untuk menangani pencarian produk
        function handleSearch(event) {
            if (event.key === 'Enter') {
                const searchQuery = document.getElementById('searchInput').value;
                window.location.href = 'index.php' + (searchQuery ? '?search=' + encodeURIComponent(searchQuery) : '');
            }
        }

        // Fungsi untuk membersihkan pencarian ketika tombol x ditekan
        document.getElementById('searchInput').addEventListener('search', function() {
            if (this.value === '') {
                window.location.href = 'index.php';
            }
        });
    </script>
</body>

</html>