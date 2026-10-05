-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 02 Jan 2026 pada 18.28
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store_sepatu`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `no_order` varchar(50) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `kota` varchar(100) NOT NULL,
  `kodepos` varchar(10) NOT NULL,
  `total` int(11) NOT NULL,
  `metode_bayar` varchar(50) NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `tgl_order` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `no_order`, `nama`, `email`, `telepon`, `alamat`, `kota`, `kodepos`, `total`, `metode_bayar`, `catatan`, `status`, `tgl_order`) VALUES
(1, 'ORD20251220191522436', 'Guest', 'guest@email.com', '', '', '', '', 2099000, 'transfer', NULL, 'pending', '0000-00-00 00:00:00'),
(2, 'ORD20251222024351', '', '', '', '', '', '', 2200000, '', NULL, 'pending', '0000-00-00 00:00:00'),
(3, 'ORD20251222064941', '', '', '', '', '', '', 2099000, '', NULL, 'pending', '0000-00-00 00:00:00'),
(4, 'ORD20251223151507', '', '', '', '', '', '', 159000, '', NULL, 'pending', '0000-00-00 00:00:00'),
(5, 'ORD20251223151846', '', '', '', '', '', '', 2099000, '', NULL, 'pending', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `idproduk` int(11) NOT NULL,
  `namaproduk` varchar(255) NOT NULL,
  `hargaproduk` decimal(10,2) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  `kategori` varchar(100) DEFAULT 'Sepatu',
  `stok` int(11) NOT NULL,
  `ukuran_sepatu` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`idproduk`, `namaproduk`, `hargaproduk`, `gambar`, `deskripsi`, `kategori`, `stok`, `ukuran_sepatu`) VALUES
(22, 'Puma Speedcat OG Red White', 2200000.00, '1766500288_maria-fernanda-pissioli-VOpBKo48lq0-unsplash.jpg', 'Kalau kamu penggemar motorsport atau suka sepatu dengan siluet ramping dan warna ngejreng, Puma Speedcat OG Red White ini wajib kamu masukin ke dalam rotasi harian kamu. Sepatu ini hadir dengan DNA balap yang kental, dibalut warna merah menyala yang instantly eye-catching.', 'Sepatu', 20, '38.5, 43'),
(23, 'Puma Speedcat Ballet Black White Womens', 1900000.00, '1766500244_BALET.jpeg', 'Kalau biasanya sepatu balet identik dengan gaya lembut dan feminin, Puma bikin gebrakan baru lewat Puma Speedcat Ballet Black White Womens —sebuah siluet hybrid yang memadukan keanggunan sepatu balerina dengan jiwa racing dari lini Speedcat. Hasilnya adalah sepatu perempuan dengan desain super sleek, edgy, dan tetap nyaman dipakai sehari-hari.', 'Sepatu', 5, '37, 38, 38.5, 38'),
(25, 'Adidas Adizero Evo SL', 2300000.00, '1766500175_adi0.jpeg', 'Rasakan sensasi kecepatan dalam Adizero Evo SL. Terinspirasi oleh inovasi sepatu pemecah rekor dalam keluarga lari Adizero - khususnya Pro Evo 1 - Evo SL dirancang untuk Anda gunakan berlari, atau tidak. Menggabungkan teknologi Adizero dengan estetika berani dan unik yang terinspirasi dari balap, sepatu ini adalah evolusi kecepatan dalam segala aspek kehidupan. Lapisan responsif busa LIGHTSTRIKE PRO di solnya memberikan kenyamanan dan bantalan untuk pengembalian energi yang optimal.', 'Sepatu Sport', 30, '37,38,39,40.5,41.5,42'),
(26, 'New Balance Rebel v5', 2490000.00, '1766500315_product_1762095834_690772daa5abc.jpg', 'Rebel dari namanya, rebel dari sifatnya, Rebel v5 akan mengubah cara pandang Anda terhadap sepatu olahraga sehari-hari. Detail tambahan seperti bagian atas mesh yang ramping menambahkan aksen yang terinspirasi dari balapan ke dalam sepatu olahraga yang dinamis dan ceria, yang dirancang untuk menghadirkan kembali unsur kesenangan dalam latihan Anda.', 'Sepatu Sport', 50, '39,40,41,42,43'),
(27, 'Asics Novablast 5', 2099000.00, 'product_27_1762087286.jpg', 'Geometri midsole dan outsole sepatu lari NOVABLAST™ 5 menghasilkan lari yang bertenaga.\r\n\r\nBantalan FF BLAST™ MAX membantu menciptakan pendaratan yang lebih lembut dan toe-off yang lebih bertenaga selama latihan Anda.\r\n\r\nKonstruksi tongue wing di bagian atas membantu meningkatkan kesesuaian sekaligus mengurangi pergerakan lidah. Dilengkapi dengan upper berbahan jala jacquard yang direkayasa untuk memberikan lebih banyak elastisitas dan ventilasi.', 'Sepatu Sport', 70, '37,38,39,40,41,42'),
(28, 'Adidas Samba OG', 2099000.00, '1766500155_product_28_1762088846.jpg', 'Born on the pitch, the Samba is a timeless icon of street style. These shoes stay true to their legacy with a soft leather upper and suede overlays.', 'Sepatu Casual', 15, '37,38,39.5,40.5,41.5,42'),
(29, 'Sepatu Ventela Public Low Black Natural Ventella Sepatu Sneakers Pria dan wanita', 159000.00, '1766500103_v.jpg', 'Tipe Penutup SepatuBertali, AcaraCocok Untuk Semua Tema, Material OlahragaKaret,Mesh,Canvas,Sintetis, Bahan KulitKaret, Modelsneakers.', 'Sepatu', 105, '37, 38, 39, 40, 41, 42'),
(30, 'Kaos Kaki Pendek Semata kaki Katun Ankle Socks Motif Murah ARGYLE 01 Hitam', 50000.00, '1766502210_images.jpg', 'Bahan: Cotton 85%, Nylon 12%, elastane 3%\r\nterdapat tempelan silicone pada bagian tumit.\r\ntidak terlihat ketika mengenakan sepatu', 'kaos kaki', 55, 'all size'),
(31, 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops', 10995.00, 'https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_t.png', 'Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday', 'Sepatu', 0, '-'),
(32, 'Mens Casual Premium Slim Fit T-Shirts ', 223.00, 'https://fakestoreapi.com/img/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_t.png', 'Slim-fitting style, contrast raglan long sleeve, three-button henley placket, light weight & soft fabric for breathable and comfortable wearing. And Solid stitched shirts with round neck made for durability and a great fit for casual fashion wear and diehard baseball fans. The Henley style round neckline includes a three-button placket.', 'Sepatu', 0, '-'),
(33, 'Mens Cotton Jacket', 5599.00, 'https://fakestoreapi.com/img/71li-ujtlUL._AC_UX679_t.png', 'great outerwear jackets for Spring/Autumn/Winter, suitable for many occasions, such as working, hiking, camping, mountain/rock climbing, cycling, traveling or other outdoors. Good gift choice for you or your family member. A warm hearted love to Father, husband or son in this thanksgiving or Christmas Day.', 'Sepatu', 0, '-'),
(34, 'Mens Casual Slim Fit', 1599.00, 'https://fakestoreapi.com/img/71YXzeOuslL._AC_UY879_t.png', 'The color could be slightly different between on the screen and in practice. / Please note that body builds vary by person, therefore, detailed size information should be reviewed below on the product description.', 'Sepatu', 0, '-'),
(35, 'John Hardy Women\'s Legends Naga Gold & Silver Dragon Station Chain Bracelet', 695.00, 'https://fakestoreapi.com/img/71pWzhdJNwL._AC_UL640_QL65_ML3_t.png', 'From our Legends Collection, the Naga was inspired by the mythical water dragon that protects the ocean\'s pearl. Wear facing inward to be bestowed with love and abundance, or outward for protection.', 'Sepatu', 0, '-'),
(36, 'Solid Gold Petite Micropave ', 168.00, 'https://fakestoreapi.com/img/61sbMiUnoGL._AC_UL640_QL65_ML3_t.png', 'Satisfaction Guaranteed. Return or exchange any order within 30 days.Designed and sold by Hafeez Center in the United States. Satisfaction Guaranteed. Return or exchange any order within 30 days.', 'Sepatu', 0, '-'),
(37, 'White Gold Plated Princess', 999.00, 'https://fakestoreapi.com/img/71YAIFU48IL._AC_UL640_QL65_ML3_t.png', 'Classic Created Wedding Engagement Solitaire Diamond Promise Ring for Her. Gifts to spoil your love more for Engagement, Wedding, Anniversary, Valentine\'s Day...', 'Sepatu', 0, '-'),
(38, 'Pierced Owl Rose Gold Plated Stainless Steel Double', 1099.00, 'https://fakestoreapi.com/img/51UDEzMJVpL._AC_UL640_QL65_ML3_t.png', 'Rose Gold Plated Double Flared Tunnel Plug Earrings. Made of 316L Stainless Steel', 'Sepatu', 0, '-'),
(39, 'WD 2TB Elements Portable External Hard Drive - USB 3.0 ', 64.00, 'https://fakestoreapi.com/img/61IBBVJvSDL._AC_SY879_t.png', 'USB 3.0 and USB 2.0 Compatibility Fast data transfers Improve PC Performance High Capacity; Compatibility Formatted NTFS for Windows 10, Windows 8.1, Windows 7; Reformatting may be required for other operating systems; Compatibility may vary depending on user’s hardware configuration and operating system', 'Sepatu', 0, '-'),
(40, 'SanDisk SSD PLUS 1TB Internal SSD - SATA III 6 Gb/s', 109.00, 'https://fakestoreapi.com/img/61U7T1koQqL._AC_SX679_t.png', 'Easy upgrade for faster boot up, shutdown, application load and response (As compared to 5400 RPM SATA 2.5” hard drive; Based on published specifications and internal benchmarking tests using PCMark vantage scores) Boosts burst write performance, making it ideal for typical PC workloads The perfect balance of performance and reliability Read/write speeds of up to 535MB/s/450MB/s (Based on internal testing; Performance may vary depending upon drive capacity, host device, OS and application.)', 'Sepatu', 0, '-'),
(41, 'Silicon Power 256GB SSD 3D NAND A55 SLC Cache Performance Boost SATA III 2.5', 109.00, 'https://fakestoreapi.com/img/71kWymZ+c+L._AC_SX679_t.png', '3D NAND flash are applied to deliver high transfer speeds Remarkable transfer speeds that enable faster bootup and improved overall system performance. The advanced SLC Cache Technology allows performance boost and longer lifespan 7mm slim design suitable for Ultrabooks and Ultra-slim notebooks. Supports TRIM command, Garbage Collection technology, RAID, and ECC (Error Checking & Correction) to provide the optimized performance and enhanced reliability.', 'Sepatu', 0, '-'),
(42, 'WD 4TB Gaming Drive Works with Playstation 4 Portable External Hard Drive', 114.00, 'https://fakestoreapi.com/img/61mtL65D4cL._AC_SX679_t.png', 'Expand your PS4 gaming experience, Play anywhere Fast and easy, setup Sleek design with high capacity, 3-year manufacturer\'s limited warranty', 'Sepatu', 0, '-'),
(43, 'Acer SB220Q bi 21.5 inches Full HD (1920 x 1080) IPS Ultra-Thin', 599.00, 'https://fakestoreapi.com/img/81QpkIctqPL._AC_SX679_t.png', '21. 5 inches Full HD (1920 x 1080) widescreen IPS display And Radeon free Sync technology. No compatibility for VESA Mount Refresh Rate: 75Hz - Using HDMI port Zero-frame design | ultra-thin | 4ms response time | IPS panel Aspect ratio - 16: 9. Color Supported - 16. 7 million colors. Brightness - 250 nit Tilt angle -5 degree to 15 degree. Horizontal viewing angle-178 degree. Vertical viewing angle-178 degree 75 hertz', 'Sepatu', 0, '-'),
(44, 'Samsung 49-Inch CHG90 144Hz Curved Gaming Monitor (LC49HG90DMNXZA) – Super Ultrawide Screen QLED ', 99999.00, 'https://fakestoreapi.com/img/81Zt42ioCgL._AC_SX679_t.png', '49 INCH SUPER ULTRAWIDE 32:9 CURVED GAMING MONITOR with dual 27 inch screen side by side QUANTUM DOT (QLED) TECHNOLOGY, HDR support and factory calibration provides stunningly realistic and accurate color and contrast 144HZ HIGH REFRESH RATE and 1ms ultra fast response time work to eliminate motion blur, ghosting, and reduce input lag', 'Sepatu', 0, '-'),
(45, 'BIYLACLESEN Women\'s 3-in-1 Snowboard Jacket Winter Coats', 5699.00, 'https://fakestoreapi.com/img/51Y5NI-I5jL._AC_UX679_t.png', 'Note:The Jackets is US standard size, Please choose size as your usual wear Material: 100% Polyester; Detachable Liner Fabric: Warm Fleece. Detachable Functional Liner: Skin Friendly, Lightweigt and Warm.Stand Collar Liner jacket, keep you warm in cold weather. Zippered Pockets: 2 Zippered Hand Pockets, 2 Zippered Pockets on Chest (enough to keep cards or keys)and 1 Hidden Pocket Inside.Zippered Hand Pockets and Hidden Pocket keep your things secure. Humanized Design: Adjustable and Detachable Hood and Adjustable cuff to prevent the wind and water,for a comfortable fit. 3 in 1 Detachable Design provide more convenience, you can separate the coat and inner as needed, or wear it together. It is suitable for different season and help you adapt to different climates', 'Sepatu', 0, '-'),
(46, 'Lock and Love Women\'s Removable Hooded Faux Leather Moto Biker Jacket', 2995.00, 'https://fakestoreapi.com/img/81XH0e8fefL._AC_UY879_t.png', '100% POLYURETHANE(shell) 100% POLYESTER(lining) 75% POLYESTER 25% COTTON (SWEATER), Faux leather material for style and comfort / 2 pockets of front, 2-For-One Hooded denim style faux leather jacket, Button detail on waist / Detail stitching at sides, HAND WASH ONLY / DO NOT BLEACH / LINE DRY / DO NOT IRON', 'Sepatu', 0, '-'),
(47, 'Rain Jacket Women Windbreaker Striped Climbing Raincoats', 3999.00, 'https://fakestoreapi.com/img/71HblAHs5xL._AC_UY879_-2t.png', 'Lightweight perfet for trip or casual wear---Long sleeve with hooded, adjustable drawstring waist design. Button and zipper front closure raincoat, fully stripes Lined and The Raincoat has 2 side pockets are a good size to hold all kinds of things, it covers the hips, and the hood is generous but doesn\'t overdo it.Attached Cotton Lined Hood with Adjustable Drawstrings give it a real styled look.', 'Sepatu', 0, '-'),
(48, 'MBJ Women\'s Solid Short Sleeve Boat Neck V ', 985.00, 'https://fakestoreapi.com/img/71z3kpMAYsL._AC_UY879_t.png', '95% RAYON 5% SPANDEX, Made in USA or Imported, Do Not Bleach, Lightweight fabric with great stretch for comfort, Ribbed on sleeves and neckline / Double stitching on bottom hem', 'Sepatu', 0, '-'),
(49, 'Opna Women\'s Short Sleeve Moisture', 795.00, 'https://fakestoreapi.com/img/51eg55uWmdL._AC_UX679_t.png', '100% Polyester, Machine wash, 100% cationic polyester interlock, Machine Wash & Pre Shrunk for a Great Fit, Lightweight, roomy and highly breathable with moisture wicking fabric which helps to keep moisture away, Soft Lightweight Fabric with comfortable V-neck collar and a slimmer fit, delivers a sleek, more feminine silhouette and Added Comfort', 'Sepatu', 0, '-'),
(50, 'DANVOUY Womens T Shirt Casual Cotton Short', 1299.00, 'https://fakestoreapi.com/img/61pHAEJ4NML._AC_UX679_t.png', '95%Cotton,5%Spandex, Features: Casual, Short Sleeve, Letter Print,V-Neck,Fashion Tees, The fabric is soft and has some stretch., Occasion: Casual/Office/Beach/School/Home/Street. Season: Spring,Summer,Autumn,Winter.', 'Sepatu', 100, '-');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_order` (`no_order`),
  ADD KEY `idx_no_order` (`no_order`),
  ADD KEY `idx_status` (`status`);

--
-- Indeks untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_order_id` (`order_id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`idproduk`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `idproduk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
