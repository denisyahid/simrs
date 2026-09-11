# Laporan Konversi `sql.txt` → `sql_mysql.sql`

## Hasil

| Item | Nilai |
|---|---|
| File hasil | **`sql_mysql.sql`** (42,2 MB) — siap import ke MySQL / MariaDB |
| Jumlah tabel | **537** (368 berisi data + 169 tanpa data) |
| Jumlah baris data | **15.874** baris |
| Baris yang hilang/dibuang | **0** |
| Script konversi | `tools/sql_txt_to_mysql.py` |
| Catatan detail | `tools/konversi_catatan.txt` |

File `sql.txt` asli tidak diubah sama sekali.

## Cara import

```bash
# 1) buat database (lewati kalau sudah ada)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS rsud_malangbong \
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2) import
mysql -u root -p rsud_malangbong < sql_mysql.sql
```

Lewat **phpMyAdmin**: pilih database → tab **Import** → pilih `sql_mysql.sql` → **Go**.
Lewat **Adminer/DBeaver**: sama, gunakan menu Import.

Bila server memakai `max_allowed_packet` kecil, naikkan dulu — statement terbesar
di file ini ± 1,2 MB (satu baris `bundleklaim_t` berisi blob base64 ± 1,19 MB).

## Isi file

1. Header komentar (asal data + catatan penting).
2. `SET NAMES utf8mb4`, penyimpanan `SQL_MODE`, dan `FOREIGN_KEY_CHECKS=0`.
3. **368 tabel berisi data** — tiap tabel: `DROP TABLE IF EXISTS` → `CREATE TABLE`
   → `INSERT INTO` (maksimal 100 baris / ± 512 KB per statement).
4. **169 tabel tanpa data** — `DROP TABLE IF EXISTS` → `CREATE TABLE` placeholder.
5. Penutup: mengembalikan `SQL_MODE` dan `FOREIGN_KEY_CHECKS`.

`CREATE DATABASE` + `USE` sengaja ditulis sebagai **komentar** supaya file bisa
di-import ke database apa pun tanpa butuh hak `CREATE DATABASE`.

## Aturan konversi

* `sql.txt` bukan dump SQL, melainkan hasil salin-tempel tabel HTML Adminer 6
  (PostgreSQL `192.168.22.81:5792` → db `rsud_malangbong` → schema `public`),
  isinya hanya hasil query `SELECT 'public.<tabel>' AS nama_tabel, t.* FROM
  public.<tabel> t LIMIT 100`. Semua teks UI Adminer (`SQL`, `QUERY PLAN`,
  `Tidak ada baris.`, dsb.) dibuang.
* **Nama kolom** diambil dari baris header hasil query.
* **Tipe kolom** diperkirakan dari isi data:

  | Bentuk data | Tipe MySQL |
  |---|---|
  | bilangan bulat (maks ≤ 2³¹−1) | `INT` |
  | bilangan bulat besar | `BIGINT` |
  | angka berkode dengan nol di depan (`0001`) | `VARCHAR` |
  | bilangan pecahan | `DECIMAL(p,s)` |
  | `2025-07-30` | `DATE` |
  | `2025-07-30 08:15:00` / dengan mikrodetik | `DATETIME` / `DATETIME(6)` |
  | `08:00:00` | `TIME` |
  | teks pendek s/d panjang | `VARCHAR(64…8192)` / `TEXT` / `MEDIUMTEXT` |
  | kolom yang seluruhnya `NULL` | tipe diwarisi dari kolom bernama sama di tabel lain, jika tidak ada → `VARCHAR(255)` |

* Nilai `NULL` (tampilan Adminer) → `NULL`; sel kosong → string kosong `''`.
* Teks yang memuat `'`, `"`, atau `\` di-escape; semua nama tabel/kolom memakai
  backtick (aman untuk nama seperti `#_jaspel_obat` dan kolom camelCase).
* Semua tabel: `ENGINE=InnoDB`, `DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci`.
* Tidak ada `PRIMARY KEY` / `FOREIGN KEY` / `UNIQUE` / `INDEX` / `NOT NULL` /
  `DEFAULT` — informasinya tidak ada di `sql.txt`; semua kolom dibuat NULL-able
  supaya `INSERT` tidak pernah gagal.
* Batas ukuran baris MySQL (65.535 byte) dijaga otomatis; baris terlebar di file
  ini 59.948 byte (`pasiendaftar_t`).
* 2 nama kolom kembar di `sql.txt` (tidak mungkin ada di PostgreSQL, kemungkinan
  artefak tampilan Adminer) diganti nama:
  * `pasien_m.objectgolongandarahfk` → `objectgolongandarahfk_2`
  * `pegawai_m.objectdetailkategorypegawaifk` → `objectdetailkategorypegawaifk_2`

## Validasi yang dilakukan

* Seluruh 1.529 statement di-parse ulang memakai `sqlglot` dialek `mysql` →
  **0 error sintaks**, 537 `CREATE TABLE`, 447 `INSERT`, 15.874 baris.
* Isi dump dibandingkan ulang dengan `sql.txt` nilai per nilai →
  **0 perbedaan** (537/537 tabel; 169 tabel kosong semuanya tetap dibuat).
* Setiap nilai dicek apakah muat di tipe kolomnya (rentang angka, presisi
  `DECIMAL`, panjang `VARCHAR`) → **0 masalah** (tidak ada yang akan terpotong).
* Nama tabel & kolom: tidak ada yang duplikat (case-insensitive), tidak ada yang
  melebihi 64 karakter, semua tabel punya minimal 1 kolom.

Catatan: import ke server MySQL asli belum bisa diuji di sandbox ini (tidak ada
`mysqld`/MariaDB/Docker dan mirror paket tidak dapat diakses), sehingga validasi
dilakukan secara statis seperti di atas.

## Keterbatasan / hal yang perlu diketahui

1. **Data hanya contoh `LIMIT 100` per tabel.** 119 tabel mengembalikan tepat 100
   baris, jadi kemungkinan isi aslinya lebih banyak. File ini bukan backup penuh.
2. **Tipe kolom adalah perkiraan** — `sql.txt` tidak memuat DDL PostgreSQL,
   sehingga tipe (dan panjang) bisa berbeda dari skema aslinya.
3. **Tidak ada primary/foreign key & index**, jadi aplikasi yang mengandalkan PK
   perlu menambahkannya sendiri (mis. `ALTER TABLE ... ADD PRIMARY KEY (...)`).
4. **169 tabel tanpa data** tidak menyisakan info kolom apa pun; tabel tetap
   dibuat berisi kolom placeholder `id BIGINT AUTO_INCREMENT PRIMARY KEY`
   supaya tidak ada tabel yang terlewat. Silakan `ALTER TABLE` sesuai kebutuhan.
5. **2.057 kolom** seluruhnya `NULL` pada data contoh; tipenya hasil warisan nama
   kolom dari tabel lain (975 kolom) atau `VARCHAR(255)`.

## Daftar 169 tabel tanpa data (placeholder)

Tabel-tabel berikut ada di `sql.txt` tetapi query-nya mengembalikan 0 baris,
sehingga definisi kolomnya tidak diketahui. Semuanya tetap dibuat di
`sql_mysql.sql` (bagian akhir file) berisi kolom placeholder `id`:

| | | | |
|---|---|---|---|
| `#_jaspel_harga_komponen_m` | `#_jaspel_ibsa` | `#_jaspel_layanan` | `#_jaspel_noreg` |
| `#_jaspel_obat` | `#_jaspel_susulan` | `#_jaspel_temp` | `#_noreg` |
| `#_tmp_noreg` | `#_tmp_noreg_aja` | `FormulirSkemaPenyinaranRadioterapi` | `ICD_10` |
| `apgar_t` | `batalregistrasi_t` | `bpdcheckout_t` | `bpdlog_t` |
| `chartofaccountmapjurnal_t` | `chartofaccountmapjurnal_t_copy1` | `closingborlostoi_t` | `closinged_t` |
| `closingpersediaan_t` | `detaildiagnosamorfologipasien_t` | `detailpegawaipagu_t` | `diagnosakanker_m` |
| `diagnosamorfologipasien_t` | `diagnosasdki_m` | `dializer_t` | `eecg_t` |
| `emrd_t` | `emrpasiend_t` | `emrpasienform_t` | `evaluasi_pasien_t` |
| `farmaka_m` | `hargaobat_t` | `hasilradiologilistgambar_t` | `historikemoterapi_t` |
| `icd_10_sisa` | `informasikiosk_m` | `jaspel_ibsa_t` | `jaspel_layanan_t` |
| `jaspel_noreg_t` | `jaspel_obat_t` | `jenisakuisisi_m` | `jenisaset_m` |
| `jenisdokumen_m` | `jenislimbahb3masuk_m` | `jenispelayananprofile_m` | `jenispengantarpasien_m` |
| `jenistempat_m` | `jenisterapi_m` | `kamusindikator_m` | `kasuspenyakit_m` |
| `kategoripagu_t` | `kelompokaset_m` | `kelompokpagu_t` | `kelompokpegawai_m` |
| `keluargapegawai_m` | `keluhanpelanggan_m` | `kendalidokumenrekammedis_t` | `kirimprodukaset_t` |
| `kondisibarang_m` | `lab_hasil` | `labbukti_t` | `list_surkon_bpjs_t` |
| `log_json` | `maphasillabdetail_m` | `mapkelompokusertoruangan_m` | `mappegawaijabatantounitkerja_m` |
| `mappegawaitoruangan_m` | `mappelayanangizi_m` | `mapping_jaspel_t` | `master_map_rad_sanata` |
| `mataanggaran_t` | `metodedelivery_m` | `metodepenyusutan_m` | `mkko_detail_t` |
| `monitoringklaim_t` | `nonbpjsklaimtxt_t` | `olah_hnpd_part2` | `olahhnpd` |
| `order_lab` | `pasienbantu` | `pasienperjanjian_t` | `pelayananpasien_temp_t` |
| `pelayananpasiendetail_temp_t` | `pelayananpasienobatkronis_t` | `pelayananpasientidakterklaim_t` | `pelayananprofile_m` |
| `penanganankeluhanpelanggan_t` | `penanganankeluhanpelanggand_t` | `penanggungjawabpasien_t` | `penjadwalan_t` |
| `penyusutanasset_t` | `periodeaccount_t` | `permohonanalat_t` | `perubahanjadwal_t` |
| `petugasdiklat_m` | `plafonbpjs_m` | `postingjurnal_t` | `postingjurnald_t` |
| `postingjurnaltransaksi_t` | `postingsaldoawal_t` | `ppra_antibiotik` | `ppra_divisi` |
| `ppra_generik` | `ppra_generikdetail` | `ppra_jenisoperasi` | `ppra_jenisoperasidetail` |
| `ppra_tindakan` | `ppra_tindakandetail` | `ppra_transaksi` | `produkformulaproduksi_m` |
| `produksinonsteril_t` | `radionuklida_m` | `registrasiaset_t` | `registrasipelayananpasien_t` |
| `remundetailpegawai_t` | `remunerasidokter_t` | `rencana_t` | `result_bridge` |
| `resumemedis_t` | `resumemedisdetail_t` | `ris_order` | `riskregister_t` |
| `riwayatpelatihan_t` | `riwayatpendidikan_t` | `riwayatrealisasi_t` | `rm_jenisobat_m` |
| `rm_lokasi_darah_m` | `rm_status_darah_m` | `rm_supplier_darah_m` | `saldoprodukdetail_t` |
| `sasaranmutu_t` | `satuanwaktukesling_m` | `settingkiosk_t` | `siki_m` |
| `slottinglibur_m` | `spm_t` | `status_barang_m` | `statuspiutang_m` |
| `stokprodukdetailpemakaian_t` | `stokprodukkadaluarsa_t` | `strukbuktipenerimaancarabayar_t_BAK` | `strukbuktipenerimaancarabayar_t_bk` |
| `strukbuktipengeluaran_t` | `strukbuktipengeluarancarabayar_t` | `strukdetailpagu_t` | `strukkonfirmasi_t` |
| `strukpelayanandnobatch_t` | `strukposting_t` | `strukpraorderdetail_t` | `strukrealisasi_t` |
| `strukreseppesanan_t` | `strukreseppesanandetail_t` | `strukreturdetail_t` | `strukreturperawatdetail_t` |
| `strukverifikasi_t` | `subkategory` | `subunitkerja_m` | `suhu_m` |
| `suratketerangan_t` | `suratpermohonanjenazah_t` | `surveilansantibiotik_t` | `surveilansfrd_t` |
| `surveilansoperasi_t` | `temp_tindakan_t` | `ulasanklaim_t` | `unitlaporan_m` |
| `waktulogin_m` | | | |
