export function jenisPersetujuan(): any {
  return [
    {
      label:
        'Pengurusan pengumpulan kartu BPJS Kesehatan dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      value: 'Pengurusan pengumpulan kartu BPJS Kesehatan dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      model: 'pengurusanPengumpulanKartuBpjsKesehatan',
    },
    { label: 'Pengurusan pemberitahuan dari Aplikasi SEP BPJS Kesehatan “Umur sudah melebihi 21 tahun” dengan melampirkan Surat Keterangan Masih Aktif dalam Perkuliahan/Pembelajaran ke Kantor BPJS Kesehatan terhitung dari tanggal mulai MRS',
      value: 'Pengurusan pemberitahuan dari Aplikasi SEP BPJS Kesehatan “Umur sudah melebihi 21 tahun” dengan melampirkan Surat Keterangan Masih Aktif dalam Perkuliahan/Pembelajaran ke Kantor BPJS Kesehatan terhitung dari tanggal mulai MRS',
      model: 'pengurusanPemberitahuanDariAplikasiSepBpjsKesehatanUmur'
    },
    { label: 'Pengurusan pemberitahuan dari Aplikasi SEP BPJS Kesehatan “Denda pelayanan 45 Hari” Kartu BPJS Kesehatan di Kantor BPJS Kesehatan dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      value: 'Pengurusan pemberitahuan dari Aplikasi SEP BPJS Kesehatan “Denda pelayanan 45 Hari” Kartu BPJS Kesehatan di Kantor BPJS Kesehatan dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      model: 'pengurusanPemberitahuanDariAplikasiSepBpjsKesehatanDenda'
    },
    { label: 'Pengurusan Kartu BPJS Kesehatan dengan pemberitahuan dari Aplikasi SEP BPJS Kesehatan “Non Aktif Karena Premi” Kartu BPJS Kesehatan, dengan melakukan pembayaran Premi di Bank/ATM/Kantor POS/Tempat Lainnya untuk pembayaran Premi BPJS Kesehatan dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      value: 'Pengurusan Kartu BPJS Kesehatan dengan pemberitahuan dari Aplikasi SEP BPJS Kesehatan “Non Aktif Karena Premi” Kartu BPJS Kesehatan, dengan melakukan pembayaran Premi di Bank/ATM/Kantor POS/Tempat Lainnya untuk pembayaran Premi BPJS Kesehatan dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      model: 'pengurusanKartuBPJSNonAktifPremi'
    },
    { label: 'Pengurusan “Laporan Kepolisian/Surat Jaminan Tidak Ditanggung Jasa Raharja” untuk Kasus Kecelakaan Tunggal dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      value: 'Pengurusan “Laporan Kepolisian/Surat Jaminan Tidak Ditanggung Jasa Raharja” untuk Kasus Kecelakaan Tunggal dengan batas waktu 3 x 24 Jam terhitung dari tanggal mulai MRS',
      model: 'kasusKecelakaanTunggal'
    },
    { label: 'Pengurusan “Laporan Kepolisian & Surat Jaminan Jasa Raharja” untuk Kasus Kecelakaan Ganda dengan batas waktu 3 x 24 Jam terhitung dari tanggal MRS',
      value: 'Pengurusan “Laporan Kepolisian & Surat Jaminan Jasa Raharja” untuk Kasus Kecelakaan Ganda dengan batas waktu 3 x 24 Jam terhitung dari tanggal MRS',
      model: 'kasusKecelakaanGanda'
    },
    { label: 'Pengurusan “Surat Jaminan ASABRI” untuk kasus kecelakaan dengan Kepesertaan Kartu BPJS Kesehatan TNI/POLRI dengan batas waktu 3 x 24 Jam terhitung dari tanggal MRS',
      value: 'Pengurusan “Surat Jaminan ASABRI” untuk kasus kecelakaan dengan Kepesertaan Kartu BPJS Kesehatan TNI/POLRI dengan batas waktu 3 x 24 Jam terhitung dari tanggal MRS',
      model: 'pengurusanSuratJaminanASABRI'
    },
    { label: 'Bersedia membayar “Biaya panjer total pembiayaan selama perawatan” jika pemenuhan kelengkapan persyaratan rawat inap belum terpenuhi dalam rentang waktu 3 x 24 jam; jika pulang rawat inap sebelum batas waktu 3 x 24 jam atau dalam waktu 3 x 24 jam tersebut merupakan hari libur atau diluar hari kerja.',
      value: 'Bersedia membayar “Biaya panjer total pembiayaan selama perawatan” jika pemenuhan kelengkapan persyaratan rawat inap belum terpenuhi dalam rentang waktu 3 x 24 jam; jika pulang rawat inap sebelum batas waktu 3 x 24 jam atau dalam waktu 3 x 24 jam tersebut merupakan hari libur atau diluar hari kerja.',
      model: 'bersediaMembayarBiayaPanjer'
    },
  ]
}
export function JenisKelamin(): any {
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
