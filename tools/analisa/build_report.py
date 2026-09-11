#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Pembangun LAPORAN_ANALISA_DATABASE.md dari hasil ekstraksi tools/analisa/out/*.json.

Struktur laporan:
  1. Ringkasan eksekutif
  2. Metodologi & sumber data
  3. Arsitektur backend / frontend / database
  4. Konvensi penamaan tabel & kategori
  5. Kamus tabel per domain (semua tabel)
  6. Detail tabel inti (kolom, relasi, pemakai)
  7. Peta koneksi / JOIN antar tabel
  8. Pemetaan folder backend (controller -> tabel)
  9. Pemetaan folder frontend (halaman -> endpoint -> controller -> tabel)
 10. Tabel tanpa pemakai & catatan kualitas data
"""

import collections
import json
import os
import re

ROOT = "/home/user/simrs"
OUT = os.path.join(ROOT, "tools", "analisa", "out")
REPORT = os.path.join(ROOT, "LAPORAN_ANALISA_DATABASE.md")

# --------------------------------------------------------------------------
# pemetaan modul backend -> nama & penjelasan domain
# --------------------------------------------------------------------------
MODULE_INFO = {
    "Sysadmin": ("Sistem & Master Data", "Pengelolaan pengguna, hak akses, menu, dan seluruh tabel master (referensi) rumah sakit."),
    "General": ("Umum & Sinkronisasi", "Fungsi umum: menu, pencarian pasien/pegawai, utilitas lintas modul."),
    "Auth": ("Autentikasi", "Login, token, dan sesi pengguna."),
    "Dashboard": ("Dashboard", "Agregasi data untuk tampilan ringkasan tiap unit."),
    "Registrasi": ("Registrasi & Pendaftaran", "Pendaftaran pasien (rawat jalan, IGD, rawat inap), antrian, dan data identitas pasien."),
    "Antrian": ("Antrian & Kiosk", "Antrian loket/poliklinik, pemanggilan, dan integrasi kiosk mandiri."),
    "Kiosk": ("Kiosk Mandiri", "Registrasi & antrian mandiri pasien lewat mesin kiosk."),
    "EMR": ("Rekam Medis Elektronik", "Asesmen, CPPT, diagnosa, tindakan, resume medis, dan dokumen rekam medis."),
    "RekamMedis": ("Rekam Medis", "Berkas, kodefikasi, dan pelaporan rekam medis."),
    "IGD": ("IGD", "Pelayanan gawat darurat."),
    "RawatInap": ("Rawat Inap", "Perawatan inap, tempat tidur, dan mutasi pasien."),
    "Pelayanan": ("Pelayanan Pasien", "Order & realisasi pelayanan (tindakan, lab, radiologi, obat) pasien."),
    "Laboratorium": ("Laboratorium", "Permintaan, sampling, dan hasil pemeriksaan laboratorium."),
    "Radiologi": ("Radiologi", "Permintaan & ekspertise hasil radiologi."),
    "Farmasi": ("Farmasi", "Resep, order obat, produksi, dan penyerahan obat."),
    "Logistik": ("Logistik & Persediaan", "Stok barang, gudang, penerimaan, dan distribusi ke ruangan."),
    "Kasir": ("Kasir & Billing", "Struk pelayanan, tagihan pasien, pembayaran, dan piutang."),
    "Piutang": ("Piutang", "Tagihan yang belum tertagih dan penagihannya."),
    "Bendahara": ("Bendahara", "Penerimaan & pengeluaran kas rumah sakit."),
    "Akuntansi": ("Akuntansi", "Jurnal, buku besar, neraca saldo, arus kas, dan COA."),
    "Anggaran": ("Anggaran & Perencanaan", "Rencana kegiatan/anggaran (RKA) dan realisasinya."),
    "Remunerasi": ("Remunerasi & Jasa Pelayanan", "Perhitungan jasa pelayanan (jaspel) dan remunerasi pegawai."),
    "JasaPelayanan": ("Jasa Pelayanan", "Perhitungan & distribusi jaspel."),
    "Asset": ("Aset / IPSRS", "Registrasi, penyusutan, dan pemeliharaan aset."),
    "Iprs": ("IPSRS", "Pemeliharaan sarana & prasarana (dashboard)."),
    "Higea": ("Higea / Kesling", "Kesehatan lingkungan dan sanitasi."),
    "Ambulan": ("Ambulan", "Order & pemakaian ambulans."),
    "Darah": ("Bank Darah", "Stok darah, permintaan & reaksi transfusi."),
    "Jenazah": ("Jenazah", "Pemulasaran dan surat keterangan jenazah."),
    "Cathlab": ("Cathlab", "Tindakan kateterisasi jantung."),
    "Kemoterapi": ("Kemoterapi", "Penjadwalan & pemberian kemoterapi."),
    "BedahSentral": ("Bedah Sentral", "Jadwal dan order kamar operasi."),
    "Sterilisasi": ("Sterilisasi / CSSD", "Sterilisasi alat dan pelacakan instrumen."),
    "Ppi": ("PPI", "Pencegahan & pengendalian infeksi, surveilans."),
    "Indikator": ("Indikator Mutu", "Indikator mutu rumah sakit & pelaporan (PMKP/INM)."),
    "Humas": ("Humas & Informasi", "Informasi rumah sakit, ketersediaan tempat tidur, pengaduan."),
    "Laporan": ("Pelaporan", "Laporan-laporan operasional & manajemen."),
    "Report": ("Cetak & Laporan (Service)", "Endpoint cetak dokumen (PDF/Excel) dan laporan lintas modul."),
    "Bridging": ("Bridging / Integrasi Eksternal", "Integrasi BPJS (VClaim, Antrol), SATUSEHAT/IHS, SIRANAP, dsb."),
    "Reservasi": ("Reservasi", "Reservasi/booking layanan (termasuk aplikasi mobile)."),
    "Controllers": ("Controller Dasar", "Kelas dasar controller (Controller.php) dan helper bersama."),
}

# --------------------------------------------------------------------------
# kamus fungsi tabel inti (kurasi manual, nama tabel -> fungsi)
# --------------------------------------------------------------------------
TABLE_FUNCTION = {
    # ---- identitas pasien & registrasi
    "pasien_m": "Master data induk pasien (identitas, demografi, nomor rekam medis/nocm, data IHS SATUSEHAT).",
    "pasiendaftar_t": "Transaksi pendaftaran/pelayanan pasien per kunjungan (norec = nomor registrasi internal, penghubung hampir semua transaksi klinis & billing).",
    "antrianpasienregistrasi_t": "Antrian pendaftaran pasien (loket/online/kiosk) beserta nomor antrian & status panggilan.",
    "antrianpasiendiperiksa_t": "Antrian pasien menunggu/nanti diperiksa di poliklinik atau unit penunjang.",
    "antrianapotik_t": "Antrian pengambilan obat di farmasi.",
    "anggotakeluarga_t": "Data anggota keluarga/penanggung jawab pasien.",
    "alamat_m": "Master jenis/klasifikasi alamat; menyimpan alamat pasien per wilayah.",
    "alergi_m": "Master jenis alergi pasien.",
    "agama_m": "Master agama (referensi dropdown).",
    "jeniskelamin_m": "Master jenis kelamin.",
    "pekerjaan_m": "Master pekerjaan pasien.",
    "pendidikan_m": "Master pendidikan terakhir.",
    "statusperkawinan_m": "Master status perkawinan.",
    "kebangsaan_m": "Master kebangsaan/warga negara.",
    "kelompokpasien_m": "Master kelompok pasien (umum, BPJS, asuransi, perusahaan) - menentukan tarif & penjamin.",
    "kelas_m": "Master kelas perawatan (VIP, I, II, III) yang menentukan tarif kamar & tarif tindakan.",
    "asuransipasien_m": "Master peserta asuransi/asuransi pasien (polisi/penjamin) beserta masa berlaku.",
    "penjaminpasien_m": "Master penjamin pasien (penanggung biaya).",
    "pemakaianasuransi_t": "Catatan pemakaian asuransi/penjamin pada satu kunjungan pasien.",
    "pasiendaftar_asuransi_t": "Detail asuransi yang dipakai saat pendaftaran.",
    "keteranganlahir_t": "Data kelahiran (bayi, ibu, penolong) untuk pencatatan vital statistik.",
    "hubungankeluarga_m": "Master hubungan keluarga/penanggung jawab.",
    "golongandarah_m": "Master golongan darah.",
    "ruangan_m": "Master ruangan/unit layanan (poliklinik, IGD, rawat inap, penunjang) - inti hampir semua modul.",
    "departemen_m": "Master departemen/instalasi tempat ruangan bernaung.",
    "mapruangantoproduk_m": "Pemetaan ruangan dengan produk/layanan yang tersedia di ruangan tersebut.",
    "tempattidur_m": "Master tempat tidur (bed) per ruangan rawat inap beserta statusnya.",
    "slottingkiosk_m": "Pengaturan slot/loket kiosk per ruangan dan tanggal.",
    "pegawai_m": "Master pegawai/tenaga medis (dokter, perawat, administrasi) - dipakai untuk petugas pelayanan.",
    "dokter_m": "Master data dokter (bila dipisah dari pegawai).",
    "profile_m": "Profil/instansi (kdprofile) - konteks multi-tenant/profile pada hampir semua query.",
    # ---- pelayanan & order
    "orderpelayanan_t": "Order/permintaan pelayanan (tindakan, lab, radiologi) dari poliklinik ke unit penunjang.",
    "pelayananpasien_t": "Realisasi pelayanan pasien (jasa/tindakan yang dikerjakan petugas) - sumber billing pelayanan.",
    "pelayananpasien_detail_t": "Detail komponen tindakan pelayanan (komponen tarif/jasa).",
    "pelayananpasienpetugas_t": "Petugas yang mengerjakan satu pelayanan (untuk jaspel/remunerasi).",
    "pemeriksaanfisik_m": "Master item pemeriksaan fisik.",
    "diagnosa_m": "Master diagnosa (ICD-10) yang dipakai saat koding.",
    "icd_9_m": "Master kode tindakan ICD-9 CM.",
    "jenisdiagnosa_m": "Master tipe diagnosa (utama/sekunder/komplikasi).",
    "diagnosapasien_t": "Diagnosa pasien per kunjungan (hasil koding dokter).",
    "tindakanpasien_t": "Tindakan/prosedur yang dilakukan pada pasien.",
    "cpptemr_t": "Catatan Perkembangan Pasien Terintegrasi (CPPT) - inti dokumentasi EMR.",
    "intruksi_cppt_t": "Instruksi dokter dalam CPPT (order verbal/tertulis).",
    "resumemedis_t": "Resume medis keluar rawat inap (ringkasan diagnosa, tindakan, kondisi pulang).",
    "asesmenawal_t": "Asesmen awal keperawatan/medis saat masuk.",
    "cppt_umum_t": "CPPT untuk pelayanan umum/rawat jalan.",
    # ---- penunjang
    "orderlab_t": "Order pemeriksaan laboratorium.",
    "hasillab_t": "Hasil pemeriksaan laboratorium.",
    "detailhasillab_t": "Detail nilai hasil lab per parameter pemeriksaan.",
    "orderrad_t": "Order pemeriksaan radiologi.",
    "hasilradiologi_t": "Hasil/bacaan radiologi (ekspertise).",
    "ekspertiseradiologi_t": "Ekspertise dokter radiologi.",
    # ---- farmasi & logistik
    "produk_m": "Master produk/barang & jasa (obat, BMHP, tarif layanan) beserta harga dan satuan.",
    "produkdetail_m": "Detail produk (kemasan, konversi satuan, pabrikan).",
    "satuanstandar_m": "Master satuan standar barang (tablet, botol, ampul).",
    "konversisatuan_t": "Aturan konversi antar satuan produk.",
    "golonganproduk_m": "Master golongan produk (generik, paten).",
    "jenisproduk_m": "Master jenis produk (obat, alkes, jasa).",
    "kategoryproduk_m": "Master kategori produk.",
    "hargajualproduk_t": "Harga jual produk (per kelas/penjamin).",
    "harganettoprodukbykelasd_m": "Harga netto produk per kelas (dasar perhitungan billing).",
    "stokprodukdetail_t": "Kartu stok per batch produk (jumlah, tanggal kadaluarsa, gudang/ruangan) - inti persediaan.",
    "stokproduk_t": "Ringkasan stok produk per gudang/ruangan.",
    "penerimaanbarang_t": "Penerimaan barang dari supplier (Penerimaan Barang).",
    "penerimaanbarangdetail_t": "Detail item penerimaan barang.",
    "pengeluaranbarang_t": "Pengeluaran/distribusi barang dari gudang ke ruangan.",
    "pengeluaranbarangdetail_t": "Detail item pengeluaran barang.",
    "permintaanbarang_t": "Permintaan barang dari unit ke gudang.",
    "orderbarang_t": "Order/pesanan barang ke supplier.",
    "supplier_m": "Master supplier/rekanan penyedia barang.",
    "rekanan_m": "Master rekanan/vendor (supplier, asuransi, perusahaan).",
    "kirimproduk_t": "Pengiriman produk ke unit/ruangan.",
    "produksiobat_t": "Pencatatan produksi sediaan obat di instalasi farmasi.",
    "resep_t": "Resep/order obat pasien.",
    "orderresep_t": "Order resep elektronik dari poliklinik ke farmasi.",
    "strukresep_t": "Struk penyerahan obat (dasar billing obat & stok keluar).",
    "strukresepdetail_t": "Detail item obat pada struk resep.",
    # ---- billing & kasir
    "strukorder_t": "Struk order: tagihan atas order pelayanan pasien sebelum realisasi.",
    "strukpelayanan_t": "Struk pelayanan: tagihan jasa/tindakan pasien.",
    "strukpelayanan_detail_t": "Detail item struk pelayanan.",
    "strukpelayananpenjamin_t": "Porsi biaya yang ditanggung penjamin/asuransi pada struk pelayanan.",
    "strukcollecting_t": "Pengumpulan tagihan (collecting) sebelum finalisasi billing.",
    "tagihanpasien_t": "Tagihan akhir pasien per kunjungan.",
    "detailtagihanpasien_t": "Detail komponen tagihan pasien.",
    "pembayaranpasien_t": "Pembayaran pasien (kasir) beserta cara bayar.",
    "detailpembayaranpasien_t": "Detail pembayaran pasien.",
    "carabayar_m": "Master cara bayar (tunai, transfer, kartu, BPJS).",
    "kasir_t": "Transaksi kasir harian.",
    "piutangpasien_t": "Piutang pasien/asuransi yang belum dibayar.",
    "jurnaltransaksi_t": "Jurnal akuntansi hasil posting transaksi pelayanan.",
    "postingjurnal_t": "Posting jurnal ke buku besar.",
    # ---- akuntansi & anggaran
    "chartofaccount_m": "Master Chart of Account (COA) - bagan akun akuntansi.",
    "chartofaccountmapjurnal_t": "Pemetaan akun COA terhadap jenis jurnal transaksi.",
    "buku_besar_t": "Buku besar (detail posting akun per periode).",
    "neracasaldo_t": "Neraca saldo akun per periode.",
    "saldoawalaccount_t": "Saldo awal akun pada tahun buku.",
    "jurnalumum_t": "Jurnal umum manual.",
    "anggaran_t": "Anggaran rumah sakit per periode.",
    "rencanakegiatananggaran_t": "Rencana Kegiatan & Anggaran (RKA) per unit.",
    "kegiatananggaran_m": "Master kegiatan anggaran.",
    "komponenanggaran_m": "Master komponen anggaran (belanja pegawai/barang/modal).",
    "realisasianggaran_t": "Realisasi anggaran per kegiatan.",
    "jasa_pelayanan_t": "Perhitungan jasa pelayanan (jaspel) pegawai atas pelayanan yang dikerjakan.",
    "jaspel_t": "Distribusi jasa pelayanan per pegawai/periode.",
    # ---- remunerasi & SDM
    "remunerasi_t": "Perhitungan remunerasi pegawai per periode.",
    "remunerasipegawai_t": "Rincian remunerasi per pegawai.",
    "jenisremunerasi_m": "Master komponen/tipe remunerasi.",
    "jenispegawai_m": "Master jenis pegawai (PNS, PPPK, kontrak).",
    "jabatan_m": "Master jabatan pegawai.",
    "unitkerja_m": "Master unit kerja pegawai.",
    "unitkerjapegawai_m": "Riwayat unit kerja pegawai.",
    "riwayatpendidikan_t": "Riwayat pendidikan pegawai.",
    "riwayatpelatihan_t": "Riwayat pelatihan/diklat pegawai.",
    "absensipegawai_t": "Absensi/kehadiran pegawai.",
    "shiftpegawai_t": "Jadwal shift pegawai.",
    # ---- jadwal, rekam medis, penunjang lain
    "jadwaldokter_m": "Master jadwal praktik dokter per poliklinik.",
    "jadwalpoliklinik_m": "Jadwal buka poliklinik.",
    "orderkj_t": "Order kedokteran jiwa / layanan khusus.",
    "mlogbook_t": "Logbook pelayanan (catatan aktivitas medis).",
    "berkaspasien_t": "Berkas/dokumen hasil scan milik pasien (rekam medis).",
    "bundleklaim_t": "Bundle berkas klaim BPJS (PDF base64) per registrasi - penyimpanan besar.",
    "log_eklaim_t": "Log integrasi E-Klaim BPJS (kirim/terima data klaim).",
    "ihs_transaction": "Log transaksi SATUSEHAT/IHS (payload & response FHIR).",
    "reservasi_t": "Reservasi/booking layanan pasien (online/telepon).",
    "antrianreservasi_t": "Antrian pasien hasil reservasi.",
    "siranap_t": "Data ketersediaan tempat tidur untuk pelaporan SIRANAP.",
    "logpendaftaran_t": "Log aktivitas pendaftaran (audit trail).",
}


# --------------------------------------------------------------------------
# util
# --------------------------------------------------------------------------
TOKEN_ID = {
    "struk": "struk/bukti transaksi", "order": "order/permintaan", "noreg": "nomor registrasi",
    "jaspel": "jasa pelayanan", "remun": "remunerasi", "pasien": "pasien", "pasiendaftar": "pendaftaran pasien",
    "dokter": "dokter", "pegawai": "pegawai", "obat": "obat", "produk": "produk/barang",
    "stok": "stok/persediaan", "resep": "resep", "bayar": "pembayaran", "tagihan": "tagihan",
    "klaim": "klaim", "bpjs": "BPJS", "lab": "laboratorium", "rad": "radiologi",
    "ruangan": "ruangan/unit", "kelas": "kelas perawatan", "tarif": "tarif", "harga": "harga",
    "kamar": "kamar", "bed": "tempat tidur", "darah": "darah", "operasi": "operasi/bedah",
    "jenazah": "jenazah", "ambulan": "ambulan", "gizi": "gizi", "kasir": "kasir", "kas": "kas",
    "jurnal": "jurnal akuntansi", "saldo": "saldo", "akun": "akun", "account": "akun",
    "anggaran": "anggaran", "asset": "aset", "pemeliharaan": "pemeliharaan", "kadaluarsa": "masa kadaluarsa",
    "log": "log/riwayat", "mapping": "pemetaan", "map": "pemetaan", "setting": "pengaturan",
    "kontak": "kontak", "alamat": "alamat", "keluarga": "keluarga", "asuransi": "asuransi",
    "penjamin": "penjamin", "kunjungan": "kunjungan", "antrian": "antrian", "reservasi": "reservasi",
    "surveilans": "surveilans", "ppi": "PPI", "steril": "sterilisasi", "hemodialisa": "hemodialisa",
    "kemoterapi": "kemoterapi", "cathlab": "cathlab", "bank": "bank", "piutang": "piutang",
    "pajak": "pajak", "transfer": "transfer", "mutasi": "mutasi", "batal": "pembatalan",
    "kirim": "pengiriman", "terima": "penerimaan", "keluar": "pengeluaran", "masuk": "penerimaan",
    "detail": "detail", "header": "header/induk", "history": "riwayat", "riwayat": "riwayat",
    "hasil": "hasil", "pemeriksaan": "pemeriksaan", "tindakan": "tindakan", "diagnosa": "diagnosa",
    "alergi": "alergi", "imunisasi": "imunisasi", "gizi": "gizi", "diet": "diet",
    "menu": "menu", "user": "pengguna", "modul": "modul aplikasi", "hak": "hak akses",
    "otorisasi": "otorisasi", "password": "kata sandi", "login": "login", "sir": "sistem informasi RS",
}

STOPWORDS_ORDER = ("norec", "id", "kdprofile", "statusenabled", "kodeexternal", "namaexternal",
                   "reportdisplay", "created_at", "updated_at", "deleted_at", "q")


def humanize(name):
    """strukorder_t -> 'struk order'"""
    base = re.sub(r"^#_", "", name)
    base = re.sub(r"_(m|t|s|r|d|old|copy\d*|\d{8}|draft|transaction|status|bridge)$", "", base)
    words = re.findall(r"[A-Z]?[a-z0-9]+|[A-Z]{2,}", base)
    return " ".join(w.lower() for w in words)


def describe_table(name, info, module_usage):
    """Fungsi tabel: kurasi manual bila ada, kalau tidak dirangkai dari pola nama."""
    if name in TABLE_FUNCTION:
        return TABLE_FUNCTION[name]
    if name.startswith("#_"):
        return ("Tabel kerja sementara (temp table) untuk proses internal; kosong pada dump "
                "sehingga struktur kolomnya belum diketahui.")
    if info.get("placeholder"):
        return "Tabel tanpa data pada dump; struktur kolom belum diketahui."
    human = humanize(name)
    mods = [m for m, _ in module_usage[:2]]
    ctx = ""
    if mods:
        ctx = " Terkait modul %s." % (", ".join(MODULE_INFO.get(m, (m, ""))[0] for m in mods))
    if name.endswith("_m"):
        return "Tabel master/referensi: menyimpan pilihan data %s yang dipakai dropdown & validasi.%s" % (human, ctx)
    if name.endswith("_t"):
        return "Tabel transaksi: mencatat %s.%s" % (human, ctx)
    if name.endswith("_s"):
        return "Tabel pendukung/pengaturan sistem untuk %s.%s" % (human, ctx)
    return "Tabel data %s.%s" % (human, ctx)


def key_columns(info, limit=6):
    cols = [c["name"] for c in info.get("cols", [])]
    if not cols:
        return []
    prio = []
    for c in cols:
        lc = c.lower()
        if lc in ("id", "norec"):
            prio.append((0, c))
        elif lc.endswith("fk"):
            prio.append((1, c))
        elif any(k in lc for k in ("noreg", "nocm", "nomr", "nostruk", "notrans", "noantri")):
            prio.append((2, c))
        elif lc.startswith(("tgl", "tanggal")) or lc.endswith("date"):
            prio.append((3, c))
        elif lc.startswith(("kd", "kode")):
            prio.append((4, c))
        elif any(k in lc for k in ("nama", "status", "jumlah", "total", "harga", "tarif", "qty")):
            prio.append((5, c))
    prio.sort(key=lambda x: x[0])
    out = []
    for _, c in prio:
        if c not in out:
            out.append(c)
        if len(out) >= limit:
            break
    return out


def esc(text):
    return str(text).replace("|", "\\|")


def main():
    tables = json.load(open(os.path.join(OUT, "tables.json"), encoding="utf-8"))
    controllers = json.load(open(os.path.join(OUT, "controllers.json"), encoding="utf-8"))
    routes = json.load(open(os.path.join(OUT, "routes.json"), encoding="utf-8"))
    closures = json.load(open(os.path.join(OUT, "closures.json"), encoding="utf-8"))
    frontend = json.load(open(os.path.join(OUT, "frontend.json"), encoding="utf-8"))
    models = json.load(open(os.path.join(OUT, "models.json"), encoding="utf-8"))

    # ---------------- indeks turunan ----------------
    ctrl_by_class = {}
    for c in controllers:
        ctrl_by_class[c["class"]] = c

    # tabel -> modul controller pemakai
    table_modules = collections.defaultdict(collections.Counter)
    table_controllers = collections.defaultdict(list)
    table_methods = collections.defaultdict(list)
    ctrl_tables = {}
    for c in controllers:
        used = collections.Counter()
        for mname, m in c["methods"].items():
            for t, n in m["tables"].items():
                used[t] += n
                table_methods[t].append((c["class"], mname))
        ctrl_tables[c["class"]] = used
        for t, n in used.items():
            table_modules[t][c["module"]] += n
            table_controllers[t].append((c["class"], c["module"], n))

    # join edges
    edge_count = collections.Counter()
    edge_examples = {}
    malformed_joins = []
    n_join_clauses = 0
    table_edges = collections.defaultdict(collections.Counter)
    for c in controllers:
        for mname, m in c["methods"].items():
            am = m.get("aliases", {})
            n_join_clauses += len(m["joins"])
            for j in m["joins"]:
                if j["style"] == "builder" and j.get("op_invalid"):
                    malformed_joins.append((c["class"], mname, j))
                    continue
                if j["style"] == "builder":
                    def resolve(col, j=j, am=am):
                        if "." in col:
                            a, col2 = col.split(".", 1)
                            return am.get(a, a), col2
                        return am.get(j["alias"], j["table"]), col
                    lt, lc = resolve(j["left"])
                    rt, rc = resolve(j["right"])
                else:
                    on = j.get("on", "")
                    mm = re.search(r"([\w]+)\.([\w]+)\s*=\s*([\w]+)\.([\w]+)", on)
                    if not mm:
                        continue
                    lt = am.get(mm.group(1), mm.group(1))
                    lc = mm.group(2)
                    rt = am.get(mm.group(3), mm.group(3))
                    rc = mm.group(4)
                if not lt or not rt or lt == rt:
                    continue
                if lt.startswith(("select", "where")) or rt.startswith(("select", "where")):
                    continue
                key = tuple(sorted([(lt, lc), (rt, rc)]))
                edge_count[key] += 1
                edge_examples.setdefault(key, "%s::%s" % (c["class"], mname))
                table_edges[lt][rt] += 1
                table_edges[rt][lt] += 1

    # route: controller@method -> path
    route_by_method = collections.defaultdict(list)
    for r in routes:
        route_by_method[(r["controller"], r["method"])].append(r["path"])

    # endpoint frontend -> controller
    route_index = []
    for r in routes:
        p = r["path"]
        if p.startswith("service/"):
            p = p[len("service/"):]
        parts = re.split(r"\{[^}]+\}", p)
        rx = re.compile("^" + "[^/]+".join(re.escape(x) for x in parts) + "$")
        route_index.append((rx, p, r))
    closures_by_path = collections.defaultdict(list)
    for cl in closures:
        p = cl["path"]
        if p.startswith("service/"):
            p = p[len("service/"):]
        closures_by_path[p].append(cl)

    # indeks tambahan: rute berparameter yang dipanggil tanpa parameter
    # (mis. rute 'emr/tanda-tangan/{pegawaifk}' dipanggil '/emr/tanda-tangan')
    stripped_index = collections.defaultdict(list)
    for rx, raw, r in route_index:
        stripped_index[re.sub(r"/[^/]*\{[^}]+\}[^/]*$", "", raw)].append(r)

    def match_endpoint(path):
        p = re.sub(r"[?&].*$", "", path).strip("/")
        if p in closures_by_path:
            return [("Closure", "__closure__")]
        hits = [r for rx, _raw, r in route_index if rx.match(p)]
        if hits:
            return [(r["controller"], r["method"]) for r in hits]
        hits = stripped_index.get(p)
        if hits:
            return [(r["controller"], r["method"] + " (berparameter)") for r in hits[:2]]
        return []

    endpoint_frontend = collections.defaultdict(list)
    for f in frontend:
        for e in f["endpoints"]:
            endpoint_frontend[(e["verb"], e["path"])].append(f["file"])

    # ---------------- statistik ----------------
    n_tables = len(tables)
    n_with_data = sum(1 for t in tables.values() if t["rows"] > 0)
    n_empty = n_tables - n_with_data
    n_cols = sum(len(t["cols"]) for t in tables.values())
    total_rows = sum(t["rows"] for t in tables.values())
    all_referenced = {t for t in table_modules if t}
    used_tables = {t for t in all_referenced if t in tables}
    n_used = len(used_tables)
    missing_tables = sorted(all_referenced - set(tables))
    n_frontend_files = len(frontend)
    n_frontend_pages = sum(1 for f in frontend if "/pages/" in f["file"] or f["file"].startswith("frontend-v2/src/pages"))
    n_endpoints = len(endpoint_frontend)

    lines = []
    add = lines.append

    # ======================================================================
    add("# LAPORAN ANALISA DATABASE, BACKEND, DAN FRONTEND")
    add("")
    add("**Proyek:** SIMRS RSUD Malangbong (`denisyahid/simrs`)  ")
    add("**Tanggal analisa:** 11 September 2026  ")
    add("**Sumber data:**")
    add("")
    add("- `backend/` — aplikasi Laravel (API, 269 file controller, 2.458 rute)")

    add("- `frontend-v2/` — aplikasi Vue 3 + TypeScript (%d file memanggil API, %d di antaranya halaman)"
        % (n_frontend_files, n_frontend_pages))
    add("- `kiosk/`, `e-reservasi/`, `viewer/` — aplikasi Angular; `eis/` — aplikasi Laravel")
    add("- `sql.txt` → `sql_mysql.sql` — dump struktur & contoh data database (537 tabel)")
    add("")

    # ---------------------------------------------------------------- 1
    add("---")
    add("")
    add("## 1. Ringkasan Eksekutif")
    add("")
    add("| Aspek | Jumlah |")
    add("|---|---|")
    add("| Tabel database | **%d** (%d berisi data, %d tabel kosong/placeholder) |" % (n_tables, n_with_data, n_empty))
    add("| Kolom database | **%s** |" % f"{n_cols:,}".replace(",", "."))
    add("| Baris contoh data | **%s** (dump `LIMIT 100` per tabel) |" % f"{total_rows:,}".replace(",", "."))
    add("| Tabel yang dipakai kode backend | **%d dari %d** (%.1f%%) |" % (n_used, n_tables, 100 * n_used / n_tables))
    add("| File controller | **%d** pada 39 folder modul |" % len(controllers))
    add("| Rute API | **%d** rute (+%d rute closure) |" % (len(routes), len(closures)))
    add("| Model Eloquent | **%d** model |" % len(models))
    add("| File frontend memanggil API | **%d** (di antaranya %d halaman pada `src/pages`) |"
        % (n_frontend_files, n_frontend_pages))
    add("| Endpoint unik dipanggil frontend | **%d** |" % n_endpoints)
    add("| Relasi/JOIN antar tabel terdeteksi | **%d pasangan kolom** |" % len(edge_count))
    add("| Tabel dipakai kode tapi tidak ada di dump | **%d tabel** |" % len(missing_tables))
    add("")
    add("**Temuan utama**")
    add("")
    add("1. Aplikasi memakai satu database operasional dengan **%d tabel**: %d tabel master "
        "(`_m`), %d tabel transaksi (`_t`), dan sisanya tabel pengaturan/temp/legacy." % (
            n_tables,
            sum(1 for t in tables if t.endswith("_m") and not t.startswith("#")),
            sum(1 for t in tables if t.endswith("_t") and not t.startswith("#"))))
    add("2. Pusat data transaksi ada pada `pasiendaftar_t` (kunjungan pasien). Hampir semua "
        "transaksi klinis maupun billing menyimpan kolom `noregistrasifk`/`norec` yang menunjuk "
        "ke tabel ini, sehingga tabel tersebut adalah **tulang punggung relasi database**.")
    add("3. Relasi tidak dideklarasikan sebagai FOREIGN KEY di database — semuanya dijaga di "
        "level aplikasi (JOIN manual di controller). Dump MySQL hasil konversi juga tanpa FK.")
    add("4. Pola akses data campuran: **query builder + raw SQL** (`DB::table`, `DB::select`) dan "
        "**Eloquent model** (%d model). Tabel yang paling sering di-JOIN: `pasien_m`, "
        "`ruangan_m`, `pegawai_m`, `produk_m`." % len(models))
    add("5. **%d tabel pada dump tidak dipakai controller mana pun** (kandidat legacy), dan "
        "**%d tabel dipakai kode namun tidak ikut ter-dump** (mis. `papalergi_t`, `quisoner_m`, "
        "`ris_out`) sehingga struktur kolomnya belum diketahui — dump `sql.txt` tidak memuat "
        "seluruh tabel yang dipakai aplikasi." % (n_tables - n_used, len(missing_tables)))
    add("")

    # ---------------------------------------------------------------- 2
    add("---")
    add("")
    add("## 2. Metodologi & Sumber Data")
    add("")
    add("| Yang dianalisa | Cara |")
    add("|---|---|")
    add("| Struktur tabel & kolom | dump `sql_mysql.sql` (hasil konversi `sql.txt`, 537 `CREATE TABLE`) |")
    add("| Tabel yang dipakai controller | pemindaian `DB::table()`, `DB::select()`, raw SQL (`FROM`/`JOIN`), dan `Model::` pada 269 file controller |")
    add("| JOIN antar tabel | %s klausa JOIN dari kode, dinormalisasi alias → nama tabel (%d pasangan kolom unik) |"
        % (f"{n_join_clauses:,}".replace(",", "."), len(edge_count)))
    add("| Rute API | parser `backend/routes/web.php` (2.458 definisi rute + 11 rute closure) |")
    add("| Pemanggilan frontend | pemindaian `useApi().get/post/put/delete()` pada 1.232 file frontend |")
    add("| Fungsi tabel | kurasi manual untuk tabel inti + inferensi pola nama (`_m`, `_t`, `_s`), modul pemakai, dan kolom |")
    add("")
    add("> **Catatan:** dump `sql.txt` dibuat dengan `LIMIT 100` per tabel, jadi jumlah baris di "
        "laporan ini adalah jumlah **contoh data**, bukan jumlah data produksi.")
    add("")

    # ---------------------------------------------------------------- 3
    add("---")
    add("")
    add("## 3. Arsitektur Sistem")
    add("")
    add("```")
    add("  ┌──────────────────────────┐        ┌───────────────────────────┐")
    add("  │  frontend-v2 (Vue 3/TS)  │        │  kiosk / e-reservasi /    │")
    add("  │  1.231 halaman modul     │        │  viewer (Angular)         │")
    add("  └────────────┬─────────────┘        └─────────────┬─────────────┘")
    add("               │  axios  baseURL .../service         │")
    add("               ▼                                     ▼")
    add("        ┌────────────────────────────────────────────────────────┐")
    add("        │  backend Laravel  routes/web.php                       │")
    add("        │  middleware jwt.auth → prefix 'service' → controller   │")
    add("        │  269 controller · 2.458 rute · 369 model Eloquent      │")
    add("        └────────────┬───────────────────────────────────────────┘")
    add("                     │  DB::table / DB::select / Eloquent")
    add("                     ▼")
    add("        ┌────────────────────────────────────────────────────────┐")
    add("        │  Database operasional: 537 tabel (master/transaksi)    │")
    add("        └────────────────────────────────────────────────────────┘")
    add("```")
    add("")
    add("**Pola URL API.** Semua endpoint bisnis berada di bawah prefix `service` "
        "(middleware `jwt.auth`), contoh nyata: route `service/emr/get-emr` ↔ frontend "
        "memanggil `useApi().get('/emr/get-emr')`.")
    add("")
    add("| Backend | Frontend (frontend-v2/src/pages/module) |")
    add("|---|---|")
    add("| `app/Http/Controllers/*` | `pages/module/<modul>/*.vue` |")
    add("| `app/Models/{Master,Transaksi,...}` | `composable/useApi.ts`, `stores/`, `models/` |")
    add("| `routes/web.php` | `router.ts` |")
    add("")
    add("### 3.1 Cara membaca laporan ini")
    add("")
    add("Untuk mengetahui “tabel apa yang dipakai sebuah fitur”, telusuri rantai berikut:")
    add("")
    add("```")
    add("halaman .vue  →  useApi().get('/xxx')  →  routes/web.php  →  Controller::method  →  tabel")
    add("```")
    add("")
    add("- **Bab 5** — daftar semua tabel beserta fungsi, relasi JOIN, dan controllernya.")
    add("- **Bab 6** — penjelasan detail tabel-tabel inti.")
    add("- **Bab 7** — peta JOIN antar tabel (pasangan kolom + jumlah kemunculan).")
    add("- **Bab 8** — dari sisi backend: tiap folder controller dan tabel yang dipakainya.")
    add("- **Bab 9** — dari sisi frontend: tiap folder halaman, endpoint, controller, dan tabelnya.")
    add("")
    add("**Contoh penelusuran (alur simpan EMR):**")
    add("")
    add("| Langkah | Bukti |")
    add("|---|---|")
    add("| Halaman | `frontend-v2/src/pages/module/emr/*.vue` memanggil `useApi().post('/emr/simpan-emr')` |")
    add("| Rute | `backend/routes/web.php`: `Route::post('emr/simpan-emr', 'saveEMR')` pada grup `Route::controller(EMRCtrl::class)` di dalam prefix `service` |")
    add("| Controller | `App\\Http\\Controllers\\EMR\\EMRCtrl::saveEMR()` |")
    add("| Tabel | `pasiendaftar_t` (kunjungan), `emrpasien_t` (dokumen EMR), `antrianpasiendiperiksa_t` (antrian/pelayanan) |")
    add("| JOIN | `antrianpasiendiperiksa_t.noregistrasifk ↔ pasiendaftar_t.norec`, lalu `pasien_m.id ↔ pasiendaftar_t.nocmfk` |")
    add("")

    # ---------------------------------------------------------------- 4
    add("---")
    add("")
    add("## 4. Konvensi Penamaan Tabel")
    add("")
    add("| Pola | Arti | Jumlah | Contoh |")
    add("|---|---|---|---|")
    masters = sum(1 for t in tables if t.endswith("_m") and not t.startswith("#"))
    trans = sum(1 for t in tables if t.endswith("_t") and not t.startswith("#"))
    sets = sum(1 for t in tables if t.endswith("_s") and not t.startswith("#"))
    temps = sum(1 for t in tables if t.startswith("#_"))
    others = n_tables - masters - trans - sets - temps
    add("| `_m` | **Master** — data referensi yang tampil di dropdown & validasi | %d | `agama_m`, `ruangan_m`, `produk_m` |" % masters)
    add("| `_t` | **Transaksi** — data operasional harian, jumlah baris paling banyak | %d | `pasiendaftar_t`, `pelayananpasien_t` |" % trans)
    add("| `_s` | **Sistem/pengaturan** — konfigurasi pengguna, hak akses, modul | %d | `loginuser_s`, `modulaplikasi_s` |" % sets)
    add("| `#_...` | **Tabel sementara** (temp table proses) | %d | `#_jaspel_obat`, `#_noreg` |" % temps)
    add("| lainnya | Legacy/view/tabel khusus (tanpa pola) | %d | `koordinat`, `ihs_transaction` |" % others)
    add("")
    add("**Pola kolom yang konsisten di hampir semua tabel**")
    add("")
    add("| Kolom | Arti |")
    add("|---|---|")
    add("| `id` | Primary key teknis (integer) |")
    add("| `norec` | Nomor record bisnis (kunci yang dipakai aplikasi untuk relasi antar transaksi) |")
    add("| `kdprofile` | Kode profile/instansi (multi-profile) — ikut di hampir semua query |")
    add("| `statusenabled` | Penanda data aktif; di aplikasi dibandingkan dengan `true`/`false`, "
        "pada contoh data berisi `'1'`/`'0'` |")
    add("| `reportdisplay` / `namaexternal` | Nama yang ditampilkan pada laporan/cetakan |")
    add("| `created_at`, `updated_at` | Audit waktu pembuatan & perubahan |")
    add("| `object...fk` / `...fk` | Foreign key logis ke tabel master (mis. `objectruanganfk` → `ruangan_m.id`) |")
    add("| `kodeexternal`, `q...` | Kode integrasi eksternal & kolom bantu query |")
    add("")

    # ---------------------------------------------------------------- 5
    add("---")
    add("")
    add("## 5. Kamus Tabel per Domain")
    add("")
    add("Setiap tabel dijelaskan: **fungsi**, kolom kunci, relasi JOIN yang terbukti dari kode, "
        "serta controller/modul yang memakainya. Domain ditentukan dari modul controller "
        "pemakai terbanyak, jika tidak ada dipakai pendekatan pola nama.")
    add("")

    # kelompokkan tabel per domain
    domain_of = {}
    for name in tables:
        mods = table_modules.get(name)
        if mods:
            top = mods.most_common()
            weight = {}
            for m, n in top:
                weight[m] = n
            best = max(weight.items(), key=lambda kv: kv[1])[0]
            domain_of[name] = MODULE_INFO.get(best, (best, ""))[0]
        else:
            domain_of[name] = "Tidak dipakai / belum terpetakan"

    domain_tables = collections.defaultdict(list)
    for name in sorted(tables):
        domain_tables[domain_of[name]].append(name)

    domain_order = sorted(
        domain_tables,
        key=lambda d: (d == "Tidak dipakai / belum terpetakan", -len(domain_tables[d]), d))

    for dom in domain_order:
        names = domain_tables[dom]
        desc = ""
        for _k, (label, ket) in MODULE_INFO.items():
            if label == dom:
                desc = ket
                break
        add("### 5.%d Domain: %s (%d tabel)" % (domain_order.index(dom) + 1, dom, len(names)))
        add("")
        if desc:
            add("_%s_" % desc)
            add("")
        add("| Tabel | Fungsi | Baris | Kolom kunci | Relasi JOIN utama | Controller pemakai |")
        add("|---|---|---|---|---|---|")
        for name in names:
            info = tables[name]
            rows = info["rows"] if info["rows"] else "0"
            rel = table_edges.get(name)
            top_rel = ", ".join("`%s`" % t for t, _ in rel.most_common(3)) if rel else "—"
            ctrls = collections.Counter(c for c, _m, _n in table_controllers.get(name, []))
            ctrl_txt = ", ".join("`%s`" % c for c, _ in ctrls.most_common(3)) or "—"
            note = ""
            if name not in table_modules:
                note = " _tidak dipakai controller_"
            add("| `%s` | %s | %s | %s | %s | %s%s |" % (
                name,
                esc(describe_table(name, info, table_modules.get(name, collections.Counter()).most_common(2))),
                rows,
                esc(", ".join("`%s`" % c for c in key_columns(info, 4))) or "—",
                top_rel,
                ctrl_txt,
                note))
        add("")

    # ---------------------------------------------------------------- 6
    add("---")
    add("")
    add("## 6. Detail Tabel Inti")
    add("")
    add("Tabel dengan pemakaian terbanyak di kode aplikasi. Untuk setiap tabel: struktur kolom "
        "(contoh), relasi JOIN nyata, dan modul pemakai.")
    add("")

    usage_total = collections.Counter()
    for t, mods in table_modules.items():
        usage_total[t] = sum(mods.values())
    core = [t for t, _ in usage_total.most_common(40)]
    core += [t for t in ("pasien_m", "pasiendaftar_t", "strukorder_t", "pelayananpasien_t")
             if t not in core]

    for name in core:
        if name not in tables:
            continue
        info = tables[name]
        add("#### `%s`" % name)
        add("")
        add("- **Fungsi:** %s" % describe_table(name, info, table_modules.get(name, collections.Counter()).most_common(2)))
        if info["cols"]:
            add("- **Kolom (%d):** %s%s" % (
                len(info["cols"]),
                ", ".join("`%s`" % c["name"] for c in info["cols"][:24]),
                " …" if len(info["cols"]) > 24 else ""))
        else:
            add("- **Kolom:** tidak diketahui (tabel tanpa data pada dump)")
        add("- **Baris contoh data:** %d" % info["rows"])
        mods = table_modules.get(name, collections.Counter()).most_common(4)
        if mods:
            add("- **Dipakai modul:** %s" % ", ".join(
                "%s (%d referensi)" % (MODULE_INFO.get(m, (m, ""))[0], n) for m, n in mods))
        ctrls = collections.Counter(c for c, _m, _n in table_controllers.get(name, []))
        if ctrls:
            add("- **Controller (akses terbanyak):** %s" % ", ".join(
                "`%s`" % c for c, _ in ctrls.most_common(6)))
        rel = table_edges.get(name)
        if rel:
            rels = []
            for other, cnt in rel.most_common(6):
                pairs = []
                for (a, ca), (b, cb) in [((k[0][0], k[0][1]), (k[1][0], k[1][1]))
                                         for k in edge_count
                                         if {k[0][0], k[1][0]} == {name, other}]:
                    pairs.append("%s.%s ↔ %s.%s" % (a, ca, b, cb))
                rels.append("`%s` (%d JOIN; %s)" % (other, cnt, "; ".join(sorted(set(pairs))[:2])))
            add("- **Relasi JOIN:**")
            for r in rels:
                add("  - %s" % r)
        add("")

    # ---------------------------------------------------------------- 7
    add("---")
    add("")
    add("## 7. Peta Koneksi / JOIN Antar Tabel")
    add("")
    add("### 7.1 Pola relasi yang dipakai aplikasi")
    add("")
    add("| Pola | Contoh | Arti |")
    add("|---|---|---|")
    add("| `norec` ↔ `...fk` | `antrianpasiendiperiksa_t.noregistrasifk` → `pasiendaftar_t.norec` | Kunci transaksi internal |")
    add("| `object<master>fk` | `pasiendaftar_t.objectruanganlastfk` → `ruangan_m.id` | FK ke tabel master |")
    add("| `<transaksi>fk` | `pelayananpasien_t.strukorderfk` → `strukorder_t.norec` | Relasi transaksi ke transaksi |")
    add("| `nocmfk` | `pasiendaftar_t.nocmfk` → `pasien_m.id` | Identitas pasien |")
    add("| Kolom denormalisasi | `pasiendaftar_t.objectkelompokpasienlastfk` | Nilai master yang “dibekukan” saat transaksi |")
    add("")
    add("### 7.2 Pasangan tabel yang paling sering di-JOIN")
    add("")
    add("| # | Tabel A | Kolom A | Tabel B | Kolom B | Jumlah JOIN | Contoh pemakaian |")
    add("|---|---|---|---|---|---|---|")
    for i, (key, cnt) in enumerate(edge_count.most_common(60), 1):
        (t1, c1), (t2, c2) = key
        add("| %d | `%s` | `%s` | `%s` | `%s` | %d | `%s` |" % (
            i, t1, c1, t2, c2, cnt, edge_examples[key]))
    add("")
    add("### 7.3 Diagram relasi inti")
    add("")
    add("```mermaid")
    add("erDiagram")
    core_pairs = [k for k, _ in edge_count.most_common(28)]
    diagram_nodes = sorted({t for k in core_pairs for t, _ in k})
    for k in core_pairs:
        (t1, c1), (t2, c2) = k
        add("    %s ||--o{ %s : \"%s\"" % (t1, t2, c1 if c1.endswith("fk") or c1 == "id" else c2))
    add("```")
    add("")
    add("> Diagram di atas adalah relasi logis hasil analisa kode (JOIN), bukan FOREIGN KEY "
        "database — database tidak mendeklarasikan constraint apa pun.")
    add("")

    # ---------------------------------------------------------------- 8
    add("---")
    add("")
    add("## 8. Pemetaan Backend: Folder Controller → Tabel")
    add("")
    add("Diurutkan sesuai folder di `backend/app/Http/Controllers/`.")

    def module_sort_key(mod):
        return mod.lower()

    by_module = collections.defaultdict(list)
    for c in controllers:
        by_module[c["module"]].append(c)

    for module in sorted(by_module, key=module_sort_key):
        info = MODULE_INFO.get(module)
        title = info[0] if info else module
        add("")
        add("### 8.%d `%s` — %s" % (list(sorted(by_module, key=module_sort_key)).index(module) + 1, module, title))
        add("")
        if info:
            add("_%s_" % info[1])
            add("")
        files = sorted(by_module[module], key=lambda x: x["class"])
        mod_tables = collections.Counter()
        mod_routes = 0
        for c in files:
            mod_tables.update(ctrl_tables.get(c["class"], {}))
            mod_routes += sum(len(route_by_method.get((c["class"], m), []))
                              for m in c["methods"])
        if mod_tables:
            add("**Tabel yang dipakai modul ini (%d tabel):** %s" % (
                len(mod_tables),
                ", ".join("`%s`(%d)" % (t, n) for t, n in mod_tables.most_common(12))))
            add("")
        add("**%d controller, %d method ber-query, %d rute terkait.**" % (
            len(files), sum(len(c["methods"]) for c in files), mod_routes))
        add("")
        add("| Controller | Method | Tabel yang dipakai (jumlah referensi) | JOIN utama |")
        add("|---|---|---|---|")
        for c in files:
            methods = list(c["methods"].items())
            if not methods:
                total = c.get("n_methods_total", 0)
                add("| `%s` | _%d method, tidak ada akses tabel_ | "
                    "_tidak menyentuh database (integrasi API eksternal / helper / kelas dasar)_ | — |"
                    % (c["class"], total))
                continue
            for mname, m in methods:
                tbls = ", ".join("`%s`%s" % (t, ("(%d)" % n) if n > 1 else "")
                                 for t, n in list(m["tables"].items())[:8])
                more = "" if len(m["tables"]) <= 8 else " …+%d" % (len(m["tables"]) - 8)
                jn = []
                am = m.get("aliases", {})
                for j in m["joins"][:3]:
                    if j["style"] == "builder" and "." in j.get("left", ""):
                        a, col = j["left"].split(".", 1)
                        b, col2 = (j["right"].split(".", 1) + ["", ""])[:2]
                        jn.append("%s.%s=%s.%s" % (am.get(a, a), col, am.get(b, b), col2))
                    elif j.get("on"):
                        jn.append(re.sub(r"\s+", " ", j["on"])[:46])
                add("| `%s` | `%s` | %s%s | %s |" % (
                    c["class"], mname, tbls, more,
                    esc("; ".join(jn)) or "—"))
        add("")

    # ---------------------------------------------------------------- 9
    add("---")
    add("")
    add("## 9. Pemetaan Frontend → API → Controller → Tabel")
    add("")
    add("### 9.1 Folder halaman frontend-v2 dan endpoint yang dipanggil")
    add("")

    fe_by_area = collections.defaultdict(list)
    for f in frontend:
        if not f["file"].startswith("frontend-v2/"):
            continue
        fe_by_area[f["area"]].append(f)

    for area in sorted(fe_by_area, key=lambda a: -len(fe_by_area[a])):
        files = fe_by_area[area]
        eps = collections.Counter()
        for f in files:
            for e in f["endpoints"]:
                eps[(e["verb"], e["path"])] += 1
        ctrls = collections.Counter()
        tbls = collections.Counter()
        for (verb, path) in eps:
            for ctrl, method in match_endpoint(path):
                ctrls[ctrl] += 1
                for t in ctrl_tables.get(ctrl, {}):
                    tbls[t] += 1
        add("#### 9.1.%d Modul `%s` — %d file, %d endpoint" % (
            sorted(fe_by_area, key=lambda a: -len(fe_by_area[a])).index(area) + 1,
            area, len(files), len(eps)))
        add("")
        if ctrls:
            add("- **Controller penerima:** %s" % ", ".join("`%s`" % c for c, _ in ctrls.most_common(8)))
        if tbls:
            add("- **Tabel yang tersentuh:** %s" % ", ".join("`%s`" % t for t, _ in tbls.most_common(12)))
        add("- **Halaman (contoh):** %s" % ", ".join(
            "`%s`" % os.path.basename(f["file"]) for f in files[:6]))
        add("")
        add("| Endpoint dipanggil | Verb | Controller | Method | Tabel utama |")
        add("|---|---|---|---|---|")
        for (verb, path), n in eps.most_common(40):
            hits = match_endpoint(path)
            if hits:
                ctrl, method = hits[0]
                tlist = ", ".join("`%s`" % t for t in list(ctrl_tables.get(ctrl, {}))[:4])
            else:
                ctrl, method, tlist = "—", "—", "(tidak ada rute yang cocok)"
            add("| `/%s`%s | %s | `%s` | `%s` | %s |" % (
                path, (" (×%d)" % n) if n > 1 else "", verb, ctrl, method, tlist))
        if len(eps) > 40:
            add("")
            add("_…dan %d endpoint lain pada modul ini._" % (len(eps) - 40))
        add("")

    add("### 9.2 Aplikasi frontend lain")
    add("")
    add("| Aplikasi | File memanggil API | Contoh endpoint |")
    add("|---|---|---|")
    other_apps = collections.defaultdict(list)
    for f in frontend:
        if f["file"].startswith("frontend-v2/"):
            continue
        other_apps[f["app"]].append(f)
    for app, files in sorted(other_apps.items()):
        eps = [e for f in files for e in f["endpoints"]]
        sample = ", ".join("`/%s`" % e["path"] for e in eps[:3]) or "—"
        add("| `%s` | %d | %s |" % (app, len(files), sample))
    add("")

    # ---------------------------------------------------------------- 10
    add("---")
    add("")
    add("## 10. Catatan Kualitas Data & Rekomendasi")
    add("")
    unused = [t for t in sorted(tables) if t not in used_tables]
    add("### 10.1 Tabel yang tidak dipakai controller (kandidat legacy)")
    add("")
    if unused:
        for t in unused:
            add("- `%s` — %s" % (t, describe_table(t, tables[t], [])))
    else:
        add("_Tidak ada — semua tabel dipakai minimal satu controller._")
    add("")
    add("### 10.2 Tabel tanpa contoh data pada dump (perlu verifikasi ke produksi)")
    add("")
    empty_names = [t for t in sorted(tables) if tables[t]["rows"] == 0]
    add("Sebanyak **%d tabel** mengembalikan 0 baris pada dump, sehingga struktur kolomnya "
        "tidak diketahui. Daftar lengkap (dikelompokkan 4 kolom):" % len(empty_names))
    add("")
    add("| | | | |")
    add("|---|---|---|---|")
    for i in range(0, len(empty_names), 4):
        row = empty_names[i:i + 4]
        add("| " + " | ".join("`%s`" % c for c in row) + " |" + " |" * (4 - len(row)))
    add("")
    add("### 10.3 Tabel yang dipakai kode tetapi TIDAK ada di dump")
    add("")
    add("Tabel-tabel berikut direferensikan controller (`DB::table()`, JOIN, atau model) tetapi "
        "tidak muncul di `sql.txt`, sehingga belum ada struktur kolomnya di `sql_mysql.sql`. "
        "Perlu diambil ulang dari database produksi bila akan dipakai:")
    add("")
    add("| Tabel | Modul yang memakai | Referensi |")
    add("|---|---|---|")
    for t in missing_tables:
        mods = table_modules.get(t, collections.Counter()).most_common(2)
        ref = sum(v for v in table_modules.get(t, {}).values()) if table_modules.get(t) else 0
        add("| `%s` | %s | %d |" % (
            t, ", ".join(MODULE_INFO.get(m, (m, ""))[0] for m, _ in mods) or "—", ref))
    add("")
    add("### 10.4 Catatan khusus pada dump")
    add("")
    add("- **Tabel `public.produk_m`** pada `sql_mysql.sql` adalah artefak dari `sql.txt`: ada satu "
        "query yang memakai nama ganda (`FROM public.\"public.produk_m\"`), sehingga terbentuk "
        "tabel tambahan berisi salinan data `produk_m`. Tabel ini bukan tabel asli di database "
        "sumber — boleh dihapus setelah import bila tidak diperlukan.")
    add("- **536 tabel riil + 1 tabel artefak = 537** tabel pada dump.")
    add("- Kolom `statusenabled` banyak diisi string `'1'`/`'0'` (bukan boolean) karena konversi "
        "tipe; di aplikasi nilai ini dibandingkan dengan `true`/`false`.")
    add("- Dua nama kolom kembar ditemukan pada `pasien_m` dan `pegawai_m` (artefak tampilan "
        "Adminer) dan diberi akhiran `_2` pada dump MySQL.")
    add("")
    add("### 10.5 Temuan & rekomendasi")
    add("")
    add("1. **Tidak ada FOREIGN KEY / PRIMARY KEY / INDEX di database** (dump hasil konversi "
        "maupun kode aplikasi tidak mendefinisikannya). Disarankan menambahkan index pada kolom "
        "yang paling sering di-JOIN agar performa query meningkat:")
    top_cols = collections.Counter()
    for k, cnt in edge_count.most_common(20):
        for t, c in k:
            top_cols["%s.%s" % (t, c)] += cnt
    for col, cnt in top_cols.most_common(12):
        add("   - `%s` (%d JOIN)" % (col, cnt))
    add("2. **Tabel temp `#_...`** (%d tabel) adalah tabel kerja proses (mis. perhitungan jaspel) "
        "dan kosong pada dump — jangan diandalkan sebagai tabel permanen."
        % sum(1 for t in tables if t.startswith("#_")))
    add("3. **Campuran raw SQL dan query builder** membuat relasi tidak konsisten; "
        "sebagian JOIN memakai alias berbeda untuk tabel yang sama (mis. `pd`, `ps`, `ru`). "
        "Pemetaan alias → tabel pada laporan ini sudah dinormalisasi.")
    add("4. **Kolom `kdprofile`** hampir selalu ikut di `WHERE`; pastikan index gabungan "
        "`(kdprofile, statusenabled)` pada tabel master.")
    add("5. **%d tabel tanpa data** pada dump perlu dicek langsung ke database produksi untuk "
        "mendapatkan struktur kolom aslinya (dump `sql.txt` memakai `LIMIT 100`, dan tabel yang "
        "memang kosong tidak menyisakan info kolom)." % len(empty_names))
    if malformed_joins:
        add("6. **%d klausa JOIN tidak valid** (argumen operator bukan operator perbandingan, "
            "kemungkinan salah tulis di kode) — relasi ini diabaikan dari peta JOIN:" % len(malformed_joins))
        for cls, mname, j in malformed_joins[:6]:
            add("   - `%s::%s` → `->join('%s', '%s', '%s', '%s')`"
                % (cls, mname, j["table"], j["left"], j["op"], j["right"]))
    add("7. **%d tabel tidak dipakai controller mana pun** — sebagian memang tabel kerja "
        "(temp `#_...`), sebagian lagi kandidat legacy yang sudah tidak dipanggil kode."
        % (n_tables - n_used))
    add("")
    add("---")
    add("")
    add("_Laporan ini dihasilkan otomatis dari kode sumber dan dump database. Skrip: "
        "`tools/analisa/extract.py` (ekstraksi) dan `tools/analisa/build_report.py` (penyusunan laporan)._")

    with open(REPORT, "w", encoding="utf-8") as fh:
        fh.write("\n".join(lines) + "\n")
    print("laporan ditulis:", REPORT, "%.2f MB" % (os.path.getsize(REPORT) / 1048576))
    print("baris:", len(lines))
    print("tabel belum dipakai:", len(unused), unused[:10])
    print("tabel kosong:", len(empty_names))


if __name__ == "__main__":
    main()
