export function JenisKelamin(): any {
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
export function vitalSign(): any {
  return [
    {
      label: 'Berat Badan',
      model: 'beratBadan',
      addon: 'kg',
    },
    {
      label: 'Tinggi Badan',
      model: 'tinggiBadan',
      addon: 'cm',
    },
    {
      label: 'BSA',
      model: 'BSA',
      addon: 'BSA',
    },
    {
      label: 'Diagnosa Medis',
      model: 'diagnosaMedis',
      addon: 'DM',
    },
  ]
}

export function kajianSubTiga(): any {
  return [
    {
      label: 'Regimen Kemoterapi',
      model: 'regimenKemoterapi',
    },
    {
      label: 'Siklus ke',
      model: 'siklusKe',
    },
    {
      label: 'Hari Ke',
      model: 'hariKe',
    },
  ]
}

export function kajianSubKeluhan(): any {
  return [
    {
      label: 'Keluhan Utama',
      model: 'keluhanUtama',
    },
    {
      label: 'Keluhan Saat Pengkajian',
      model: 'keluhanSaatPengkajian',
    },
  ]
}
export function vitalSign2(): any {
  return [
    {
      label: 'Tekanan Darah',
      model: 'tekananDarah',
      addon: 'mmHG',
    },
    {
      label: 'Frekuensi Nadi',
      model: 'nadi',
      addon: 'x/mnt',
    },
    {
      label: 'Suhu',
      model: 'suhu',
      addon: '°C',
    },
    {
      label: 'Respirasi',
      model: 'respirasi',
      addon: 'x/mnt',
    },
  ]
}
export function imgNyeri(): any {
  return {
    nama: 'Hurts',
    detail: [
      {
        nama: 'No Hurt',
        descNilai: 0,
        img: '/images/skalanyeri/1.png',
      },
      {
        nama: 'Hurts Little Bit',
        descNilai: 2,
        img: '/images/skalanyeri/2.png',
      },
      {
        nama: 'Hurts Little More',
        descNilai: 4,
        img: '/images/skalanyeri/3.png',
      },
      {
        nama: 'Hurts Even More',
        descNilai: 6,
        img: '/images/skalanyeri/4.png',
      },
      {
        nama: 'Hurts Whole Lot',
        descNilai: 8,
        img: '/images/skalanyeri/5.png',
      },
      {
        nama: 'Hurts whorts',
        descNilai: 10,
        img: '/images/skalanyeri/6.png',
      },
    ],
  }
}
export function skoringNyeri(): any {
  return {
    nama: 'Score ',
    detail: [
      { nama: '0 - 1 = Tidak Ada Nyeri', descNilai: 0 },
      { nama: '2 - 3 = Sedikit Nyeri', descNilai: 2 },
      { nama: '4 - 5 = Cukup Nyeri', descNilai: 4 },
      { nama: '6 - 7 = Lumayan Nyeri', descNilai: 6 },
      { nama: '8 - 9 = Sangat Nyeri', descNilai: 8 },
      { nama: '10 = Amat Sangat Nyeri', descNilai: 10 },
    ],
  }
}

export function ekstimitasFields(): any {
  return {
    nama: 'Ekstremitas',
    detail: [
      {
        nama: 'Akral',
        options: [
          { label: 'Hangat', value: 'Hangat' },
          { label: 'Dingin', value: 'Dingin' },
        ],
      },
      {
        nama: 'Pergerakan',
        options: [
          { label: 'Aktif', value: 'Aktif' },
          { label: 'Pasif', value: 'Pasif' },
        ],
      },
      {
        nama: 'Kekuatan Otot',
        options: [
          { label: 'Kuat', value: 'Kuat' },
          { label: 'Lemah', value: 'Lemah' },
        ],
      },
      {
        nama: 'Kelainan',
        options: [
          { label: 'Tidak', value: 'Tidak' },
          { label: 'Ya, Jelaskan:', value: 'Ya' },
        ],
      },
    ],
  }
}

export function kualitasNyeri(): any {
  return [
    {
      label: 'Tumpul',
      model: 'Tumpul',
    },
    {
      label: 'Tajam',
      model: 'Tajam',
    },
    {
      label: 'Panas/Terbakar',
      model: 'Panas',
    },
    {
      label: 'Lainnya',
      model: 'Lainnya',
    },
  ]
}

export function keadaanFisik(): any {
  return [
    {
      label: 'Faktor pemicu/yang memperberat:',
      model: 'faktorPemicu',
    },
    {
      label: 'Faktor yang mengurangi/menghilangkan nyeri:',
      model: 'faktorMengurangi',
    },
  ]
}

export function oralMucositis(): any {
  return [
    {
      label: 'Ya',
      model: 'benarOralMucositis',
    },
    {
      label: 'Tidak',
      model: 'salahOralMucositis',
    },
  ]
}

export function bentukLeher(): any {
  return [
    {
      label: 'Normal',
      model: 'NormalLeher',
    },
    {
      label: 'Kelainan',
      model: 'KelainanLeher',
    },
    {
      label: 'Tidak',
      model: 'TidakLeher',
    },
    {
      label: 'Ya, jelaskan',
      model: 'yaLeher',
    },
  ]
}
export function bentukDada(): any {
  return [
    {
      label: 'Simetris',
      model: 'SimetrisDada',
    },
    {
      label: 'Kelainan',
      model: 'KelainanDada',
    },
    {
      label: 'Tidak',
      model: 'TidakDada',
    },
    {
      label: 'Ya, jelaskan',
      model: 'yaDada',
    },
  ]
}

export function PeripheralNeropathy(): any {
  return [
    {
      label: 'Kesemutan ringan di tangan dan ujung kaki',
      model: 'kesemutanRingan',
    },
    {
      label: 'Kesemutan sampai merasa tidak nyaman',
      model: 'kesemutanSampaiTidakNyaman',
    },
    {
      label: 'Nyeri sampai mempengaruhi ADL',
      model: 'nyeriSampaiPengaruhADL',
    },
    {
      label: 'Sangat nyeri saat jalan dan aktivitas',
      model: 'sangatNyeriJalanAktivitas',
    },
  ]
}

export function kulitFields(): any {
  return {
    nama: 'Kulit',
    detail: [
      {
        nama: 'Warna',
        options: [
          { label: 'Normal', value: 'Normal' },
          { label: 'Ikterus', value: 'Ikterus' },
          { label: 'Sianosis', value: 'Sianosis' },
        ],
      },
      {
        nama: 'Membran mukosa',
        options: [
          { label: 'Lembab', value: 'Lembab' },
          { label: 'Kering', value: 'Kering' },
          { label: 'Stomatitis', value: 'Stomatitis' },
        ],
      },
      {
        nama: 'Luka',
        options: [
          { label: 'Tidak', value: 'Tidak' },
          { label: 'Ya, jelaskan', value: 'Ya' },
        ],
      },
      {
        nama: 'Perdarahan/lebam di kulit',
        options: [
          { label: 'Ada petique di kulit', value: 'adaPetique' },
          { label: 'Ada perdarahan ringan', value: 'adaPerdarahanRingan' },
        ],
      },
      {
        nama: 'Masalah integritas kulit',
        options: [
          { label: 'Tidak', value: 'Tidak' },
          { label: 'Ya, jelaskan', value: 'Ya' },
        ],
      },
    ],
  }
}

export function ecogScore(): any {
  return [
    { label: '0 Masih sepenuhnya aktif', descNilai: 0 },
    { label: '1 Hanya mampu melakukan pekerjaan ringan', descNilai: 1 },
    {
      label:'2 Hanya mampu melakukan perawatan diri sendiri dan 50% aktivitas di atas tempat tidur',
      descNilai: 2,
    },
    {
      label:'3 Hanya mampu melakukan perawatan diri yang terbatas, 50% aktivitas dilakukan diatas tempat tidur/kursi roda',
      descNilai: 3,
    },
    {
      label:'4 Tidak mampu melakukan perawatan diri dan ambulasi secara total di tempat tidur/kursi roda',
      descNilai: 4,
    },
    { label: '5 Meninggal', descNilai: 5 },
  ]
}

export function skalaPenilaian(): any {
  return {
    skala: [
      {
        nama: 'Nyeri',
        deskripsi: 'Tidak nyeri',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Nyeri Hebat'
      },
      {
        nama: 'Kelelahan',
        deskripsi: 'Tidak lelah',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Perasaan Lelah Hebat'
      },
      {
        nama: 'Mual',
        deskripsi: 'Tidak mual',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Mual Hebat'
      },
      {
        nama: 'Depresi',
        deskripsi: 'Tidak Depresi',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Depresi Berat'
      },
      {
        nama: 'Kecemasan',
        deskripsi: 'Tidak cemas',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Cemas Berat'
      },
      {
        nama: 'Mengantuk',
        deskripsi: 'Tidak mengantuk',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Mengantuk Berat'
      },
      {
        nama: 'Nafsu Makan',
        deskripsi: 'Nafsu makan',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Tidak Nafsu Makan'
      },
      {
        nama: 'Kesehatan',
        deskripsi: 'Merasa sehat & segar bugar',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Perasaan Tidak Berdaya'
      },
      {
        nama: 'Sesak Nafas',
        deskripsi: 'Tidak sesak nafas',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Sesak Nafas Hebat'
      },
      {
        nama: 'Masalah',
        deskripsi: 'Tidak ada masalah',
        detail: [
          { nama: '0', descNilai: 0 },
          { nama: '1', descNilai: 1 },
          { nama: '2', descNilai: 2 },
          { nama: '3', descNilai: 3 },
          { nama: '4', descNilai: 4 },
          { nama: '5', descNilai: 5 },
          { nama: '6', descNilai: 6 },
          { nama: '7', descNilai: 7 },
          { nama: '8', descNilai: 8 },
          { nama: '9', descNilai: 9 },
          { nama: '10', descNilai: 10 },
        ],
        deskripsiAkhir: 'Masalah Berat'
      },
    ],
  };
}
export function hasilResikoJatuh(): any {
  return [
      {
          "title": "Tidak berisiko (tidak ditemukan a dan b)",
          "model": "hasilResikoJatuh"
      },
      {
          "title": "Resiko Tinggi (ditemukan a dan b)",
          "model": "hasilResikoJatuh"
      },
      {
          "title": "Risiko rendah sedang (ditemukan a atau b)",
          "model": "hasilResikoJatuh"
      },
  ]
}

export function Pilihan(): any {
  return [
      {
          "title": "Ya",
          "model": "pilihan"
      },
      {
          "title": "Tidak",
          "model": "pilihan"
      },
  ]
}

export function pegangKursisaatDuduk(): any {
  return [
      {
          "title": "Ya",
          "model": "pegangKursisaatDuduk"
      },
      {
          "title": "Tidak",
          "model": "pegangKursisaatDuduk"
      },
  ]
}

export function tindakanResikoJatuh(): any {
  return [
      {
          "title": "Tidak ada tindakan",
          "model": "tindakanResikoJatuh"
      },
      {
          "title": "Edukasi",
          "model": "tindakanResikoJatuh"
      },
      {
          "title": "Pasang penanda risiko jatuh",
          "model": "tindakanResikoJatuh"
      },
  ]
}

export function aksesKemoterapi(): any {
  return [
      {
          title: "Perifer",
          model: "aksesKemoterapi"
      },
      {
          title: "Chemoport",
          model: "aksesKemoterapi"
      },
      {
          title: "Lain-lainnya",
          model: "aksesKemoterapi"
      },
  ]
}

export function aksesKemoterapiKeterangan(): any {
  return [
      {
          title: "Bermasalah",
          model: "aksesKemoterapiKeterangan"
      },
      {
          title: "Baik",
          model: "aksesKemoterapiKeterangan"
      },
  ]
}

export function diagnosaKeperawatan(): any {
  return [
      {
          deskripsi: "Bersihan jalan nafas b.d. spasme jalan nafas, hipersekresi jalan nafas, disfungsi neuromuskuler, adanya jalan nafas buatan, sekresi yang tertahan, hiperflasia dinding jalan nafas, proses infeksi, respon alergi",
          model: "bersihanJalanNafas"
      },
      {
        deskripsi:"Nyeri akut b.d agen pencedera fisiologis, agen pencedera kimiawi, agen pencedera fisik",
        model:"nyeriAgenPencedera"
      },
      {
        deskripsi:"Nyeri kronis b.d kondisi muskuloskeletal kronis, kerusakan sistem saraf, penekanan saraf, infiltrasi tumor",
        model:"nyeriKronis"
      },
      {
        deskripsi:"Nausea b.d gangguan biokimiawi, gangguan pada esofagus, iritasi lambung, distensi lambung, tumor terlokalisasi, efek agen farmakologis, efek toksin",
        model:"nyeriNausea"
      },
      {
        deskripsi: "Ansietas b.d krisis situasional, ancaman terhadap kematian, kekawatiran mengalami kegagalan, disfungsi fungsi keluarga, kurang terpapar informasi",
        model:"nyeriAnsietas"
      },
      {
        deskripsi: "Risiko gangguan integritas kulit/jaringan b.d perubahan sirkulasi, perubahan status nutrisi, kekurangan/kelebihan volume cairan, penurunan mobilitas, bahan kimia iritatif, suhu lingkungan yang ekstrem, faktor mekanis (penekanan pada tonjolan tulang, gesekan), efek samping terapi radiasi, kelembaban",
        model:"nyeriRisikoGangguanIntegritasKulitJaringan"
      },
      {
        deskripsi:"Gangguan citra tubuh b.d perubahan struktur/bentuk tubuh, perubahan fungsi tubuh, perubahan fungsi kognitif, efek tindakan/pengobatan (mis. pembedahan, kemoterapi, terapi radiasi)",
        model:"nyeriGangguanCitraTubuh"
      },
      {
        deskripsi:"Keletihan b.d gangguan tidur, kondisi fisiologis (mis. penyakit kronis, penyakit terminal, anemia,malnutrisi), program perawatan/pengobatan jangka panjang, stres berlebihan, depresi",
        model:"keletihan"
      },
      {
        deskripsi:"Risiko infeksi b.d penyakit kronis, efek prosedur invasif, malnutrisi, peningkatan paparan organisme patogen, ketidakadekuatan pertahan tubuh primer, ketidakkuatan pertahanan tubuh sekunder",
        model:"resikoInfeksi"
      },
      {
        deskripsi:"Risiko alergi b.d terpapar zat alergen (mis. zat kimia, agen farmakologis)",
        model:"risikoAlergi"
      }
  ]
}

export function rencanaTindakanKeperawatan():any{
  return [
    {
      model: "monitor",
      deskripsi: "1. Monitor tanda-tanda vital"
    },
    {
      model: "aturPosisi",
      deskripsi: "2. Atur posisi semifowler/fowler"
    },
    {
      model: "ajarkanTeknik",
      deskripsi: "3. Ajarkan teknik nonfarmakologi (distraksi, relaksasi,guided imagery) "
    },
    {
      model: "kendalikanFaktor",
      deskripsi: "4. Kendalikan faktor lingkungan penyebab mual"
    },
    {
      model: "berikanOksigen",
      deskripsi: "5. Berikan oksigen"
    },
    {
      model: "kolaborasiDalamPemberianTerapi",
      deskripsi: "6. Kolaborasi dalam pemberian terapi"
    },
    {
      model: "lakukanProsedurPemberianObat",
      deskripsi: "7. Lakukan prosedur pemberian obat yang aman dan tepat"
    },
    {
      model: "lakukanPemantauanAksesVaskuler",
      deskripsi: "8. Lakukan pemantauan akses vaskuler"
    },
    {
      model: "awasiTanda",
      deskripsi: "9. Awasi tanda dan gejala ekstravasasi"
    },
    {
      model: "berikanKompres",
      deskripsi: "10. Berikan kompres dingin atau hangat"
    },
    {
      model: "rencanaTindakanKeperawatan",
      deskripsi: "11. Berikan edukasi sesuai kebutuhan pasien"
    },
    {
      model: "latihanKegiatan",
      deskripsi: "12. Latih kegiatan pengalihan untuk mengurangi ansietas"
    },
    {
      model: "monitorTanda",
      deskripsi: "13. Monitor tanda dan gejala alergi"
    },
    {
      model: "motivasiUntukMenguatkan",
      deskripsi: "14. Motivasi untuk menguatkan dukungan keluarga atau orang terdekat"
    },
    {
      model: "anjurkanTirahBaring",
      deskripsi: "15. Anjurkan tirah baring"
    },
    {
      model: "anjurkanAktivitas",
      deskripsi: "16. Anjurkan melakukan aktivitas secara bertahap"
    },
    {
      model: 'lainnya',
      deskripsi: "17. "
    }
  ]
}

export function timeOutKemoterapi():any{
  return {
    kriteria: [
      {
        nama: 'Benar Nama obat',
        detail: [
          {
            deskripsi: 'Ya',
            model: 'CB_nama_obat',
            type: 'checkBox',
          },
          {
            deskripsi: 'Tidak',
            model: 'CB_nama_obat',
            type: 'checkBox',
          }
        ],
      },
      {
        nama: 'Benar dosis obat',
        detail: [
          {
            deskripsi: 'Ya',
            model: 'CB_benar_dosis_obat',
            type: 'checkBox',
          },
          {
            deskripsi: 'Tidak',
            model: 'CB_benar_dosis_obat',
            type: 'checkBox',
          }
        ],
      },
      {
        nama: 'Benar cara / rute pemberian',
        detail: [
          {
            deskripsi: 'Ya',
            model: 'CB_cara_pemberian_obat',
            type: 'checkBox',
          },
          {
            deskripsi: 'Tidak',
            model: 'CB_cara_pemberian_obat',
            type: 'checkBox',
          }
        ],
      },
      {
        nama: 'Benar tanggal dan jam kadaluwarsa obat',
        detail: [
          {
            deskripsi: 'Ya',
            model: 'CB_kadaluwarsa',
            type: 'checkBox',
          },
          {
            deskripsi: 'Tidak',
            model: 'CB_kadaluwarsa',
            type: 'checkBox',
          }
        ],
      },
      {
        nama: 'Identitas pada etiket obat sama dengan identitas pada gelang pasien',
        detail: [
          {
            deskripsi: 'Ya',
            model: 'CB_identitas_obat',
            type: 'checkBox',
          },
          {
            deskripsi: 'Tidak',
            model: 'CB_identitas_obat',
            type: 'checkBox',
          }
        ],
      },
      {
        nama: 'Kecepatan tetesan infus',
        detail: [
          {
            deskripsi: 'tts/Mnt',
            model: 'kecepatanTetesan',
            type: 'input',
          },
        ],
      },
      {
        nama: 'Nama dan tanda tangan perawat',
        detail: [
          {
            deskripsi: 'TandaTangan',
            model: 'tandaTanganPerawat',
            type: 'TandaTangan',
          },
        ],
      },
      {
        nama: 'Nama dan tanda tangan pasien/keluarga',
        detail: [
          {
            deskripsi: 'TandaTangan',
            model: 'tandaTanganPasienKeluarga',
            type: 'TandaTangan',
          },
        ],
      }
    ]
  }
}

export function vitalSign3(): any {
  return [
    {
      label: 'Tekanan Darah',
      model: 'tekananDarahEvaluasi',
      addon: 'mmHG',
    },
    {
      label: 'Frekuensi Nadi',
      model: 'nadiEvaluasi',
      addon: 'x/mnt',
    },
    {
      label: 'Suhu',
      model: 'suhuEvaluasi',
      addon: '°C',
    },
    {
      label: 'Respirasi',
      model: 'respirasiEvaluasi',
      addon: 'x/mnt',
    },
    {
      label: 'Skala Nyeri',
      model: 'skalaNyeriEvaluasi',
      addon: 'nyeri',
    },
  ]
}
