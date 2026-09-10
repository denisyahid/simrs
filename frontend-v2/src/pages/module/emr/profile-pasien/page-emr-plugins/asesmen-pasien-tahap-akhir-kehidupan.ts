export function gejala():any{
  return [
    {
      "title" : "a. Kegawatan Pernapasan",
      "detail" : [
        {
          "label" : "Mukosa oral Kering",
          "model" : "gejalaPernapasan",
          "code" : "MOK",
        },
        {
          "label" : "Ada sekret",
          "model" : "gejalaPernapasan",
          "code" : "AS",
        },
        {
          "label" : "Dypsneu",
          "model" : "gejalaPernapasan",
          "code" : "DU",
        },
        {
          "label" : "Tidak ada kelainan",
          "model" : "gejalaPernapasan",
          "code" : "TAK",
        },
        {
          "label" : "Napas melalui mulut",
          "model" : "gejalaPernapasan",
          "code" : "NMM",
        },
        {
          "label" : "Napas lambat",
          "model" : "gejalaPernapasan",
          "code" : "NL",
        },
        {
          "label" : "SPO2 < normal",
          "model" : "gejalaPernapasan",
          "code" : "SPO2",
        },
        {
          "label" : "Napas tidak teratur",
          "model" : "gejalaPernapasan",
          "code" : "NTT",
        },
        {
          "label" : "Napas cepat dan dangkal",
          "model" : "gejalaPernapasan",
          "code" : "NCD",
        },
      ]
    },
    {
      "title" : "b. Kehilangan Tanos Otot",
      "detail" : [
        {
          "label" : "Inkontinensia feses",
          "model" : "kehilanganTenosOtot",
          "code" : "IF",
        },
        {
          "label" : "Inkontinensia urine",
          "model" : "kehilanganTenosOtot",
          "code" : "IU",
        },
        {
          "label" : "Sulit menelan",
          "model" : "kehilanganTenosOtot",
          "code" : "SM",
        },
        {
          "label" : "Mual",
          "model" : "kehilanganTenosOtot",
          "code" : "MUAL",
        },
        {
          "label" : "Penurunan pergerakan tubuh",
          "model" : "kehilanganTenosOtot",
          "code" : "PPT",
        },
        {
          "label" : "Didtensi abdomen",
          "model" : "kehilanganTenosOtot",
          "code" : "DA",
        },
        {
          "label" : "Sulit berbicara",
          "model" : "kehilanganTenosOtot",
          "code" : "SB",
        },
        {
          "label" : "Tidak ada kelainan",
          "model" : "kehilanganTenosOtot",
          "code" : "TAK",
        },
      ]
    },
    {
      "title" : "C. Nyeri",
      "detail" : [
        {
          "label" : "Tidak",
          "model" : "nyeri",
          "code" : "tidak",
        },
        {
          "label" : "Ya",
          "model" : "nyeri",
          "code" : "ya",
        },
      ],
      "optional": {
        "label" : "Lokasi",
        "model" : "lokasi"
      }
    },
    {
      "title" : "d. Perlambatan Sirkulasi",
      "detail" : [
        {
          "label" : "Bercak dan sianosis pada ekstremitas",
          "model" : "perlambatanSirkulasi",
          "code" : "BSE",
        },
        {
          "label" : "Gelisah",
          "model" : "perlambatanSirkulasi",
          "code" : "GH",
        },
        {
          "label" : "Lemas",
          "model" : "perlambatanSirkulasi",
          "code" : "LS",
        },
      ],
    },
    {
      "title" : "e. Pencernaan",
      "detail" : [
        {
          "label" : "Mual",
          "model" : "penceranaan",
          "code" : "BSE",
        },
        {
          "label" : "Muntah",
          "model" : "penceranaan",
          "code" : "GH",
        },
      ],
      "optional": {
        "model" : "ketPencernaan"
      }
    },
  ]
}

export function reaksi():any{
  return [
    {
      "title" : "4. Reaksi Pasien atas penyakitnya",
      "detail" : [
        {
          "label" : "Menyangkal",
          "model" : "reaksiPasien",
          "code" : "ML",
        },
        {
          "label" : "Marah",
          "model" : "reaksiPasien",
          "code" : "MH",
        },
        {
          "label" : "Sedih/Menangis",
          "model" : "reaksiPasien",
          "code" : "SM",
        },
        {
          "label" : "Takut",
          "model" : "reaksiPasien",
          "code" : "TT",
        },
      ]
    },
    {
      "title" : "5. Reaksi keluarga atas penyakit pasien",
      "detail" : [
        {
          "label" : "Marah",
          "model" : "reaksiKeluarga",
          "code" : "MH",
        },
        {
          "label" : "Ganguan tidur",
          "model" : "reaksiKeluarga",
          "code" : "GT",
        },
        {
          "label" : "Penurunan Konsentrasi",
          "model" : "reaksiKeluarga",
          "code" : "PK",
        },
        {
          "label" : "Rasa Bersalah",
          "model" : "reaksiKeluarga",
          "code" : "RB",
        },
        {
          "label" : "Keluarga tidak berkomunikasi dengan pasien",
          "model" : "reaksiKeluarga",
          "code" : "KTB",
        },
        {
          "label" : "Letih",
          "model" : "reaksiKeluarga",
          "code" : "LH",
        },
        {
          "label" : "Lelah",
          "model" : "reaksiKeluarga",
          "code" : "LH",
        },
        {
          "label" : "Ketidak mampuan memenuhi peran yang diharapkan",
          "model" : "reaksiKeluarga",
          "code" : "KMM",
        },
        {
          "label" : "Sedih",
          "model" : "reaksiKeluarga",
          "code" : "SH",
        },
        {
          "label" : "Menangis",
          "model" : "reaksiKeluarga",
          "code" : "MS",
        },
      ]
    },
    {
      "title" : "6. Kebutuhan dukungan atau pelonggaran pelayanan bagi pasien, keluarga dan pember pelayanan **",
      "detail" : [
        {
          "label" : "Pasien perlu didampingi keluarga",
          "model" : "kebutuhanDukungan",
          "code" : "PPDK",
        },
        {
          "label" : "Keluarga/sahabat dapat melihat pasien diluar waktu berkunjung",
          "model" : "kebutuhanDukungan",
          "code" : "KMPB",
        },
      ],
    },
    {
      "title" : "7. Apakah ada kebutuhan atau alternatif pelayanan lainnya",
      "detail" : [
        {
          "label" : "Tidak",
          "model" : "kebutuhanLain",
          "code" : "APL",
        },
      ],
      "optional" : {
        "label" : "Donasi Organ",
        "model" : "donasiOrgan"
      }
    },
    {
      "title" : "8. Faktor resiko bagi keluarga yang ditinggalkan",
      "subTitle" : "Asesmen informasi",
      "detail" : [
        {
          "label" : "Marah",
          "model" : "faktorResiko",
          "code" : "MH",
        },
        {
          "label" : "Depresi",
          "model" : "faktorResiko",
          "code" : "DI",
        },
        {
          "label" : "Rasa Bersalah",
          "model" : "faktorResiko",
          "code" : "RB",
        },
        {
          "label" : "Perubahan Kebiasaan pola komunikasi",
          "model" : "faktorResiko",
          "code" : "PKPK",
        },
        {
          "label" : "Ketidak mampuan memenuhi peran yang diharapkan",
          "model" : "faktorResiko",
          "code" : "KMMP",
        },
      ],
    },
    {
      "title" : "9. Masalah Keperawatan",
      "detail" : [
        {
          "label" : "Mual",
          "model" : "masalahKeperawatan",
          "code" : "ML",
        },
        {
          "label" : "Nyeri Kronis",
          "model" : "masalahKeperawatan",
          "code" : "NK",
        },
        {
          "label" : "Nyeri Akut",
          "model" : "masalahKeperawatan",
          "code" : "NA",
        },
        {
          "label" : "Ansietas",
          "model" : "masalahKeperawatan",
          "code" : "AS",
        },
        {
          "label" : "Kematian",
          "model" : "masalahKeperawatan",
          "code" : "KN",
        },
        {
          "label" : "Pola nafas tidak efektif",
          "model" : "masalahKeperawatan",
          "code" : "PNTE",
        },
        {
          "label" : "Konstipasi",
          "model" : "masalahKeperawatan",
          "code" : "KI",
        },
        {
          "label" : "Distres spiritual",
          "model" : "masalahKeperawatan",
          "code" : "DS",
        },
        {
          "label" : "Defisit perawatan diri",
          "model" : "masalahKeperawatan",
          "code" : "DPD",
        },
        {
          "label" : "Perubahan proses keluarga",
          "model" : "masalahKeperawatan",
          "code" : "PPK",
        },
        {
          "label" : "Kopping individu tidak efektif",
          "model" : "masalahKeperawatan",
          "code" : "KITE",
        },
        {
          "label" : "Perubahan persepsi",
          "model" : "masalahKeperawatan",
          "code" : "PP",
        },
        {
          "label" : "Bersihan jalan nafas tidak efektif",
          "model" : "masalahKeperawatan",
          "code" : "BJN",
        },
      ],
      "optional" : {
        "model" : "ketMasalahKeperawatan"
      }
    },
    {
      "title" : "10. Bagaimana rencana keperawatan selanjutnya",
      "detail" : [
        {
          "label" : "Tetap dirawat diruangan intensif/ruang perawatan",
          "model" : "rencanaKeperawatan",
          "code" : "ML",
        },
        {
          "label" : "Dirawat dirumah",
          "model" : "rencanaKeperawatan",
          "code" : "NK",
        },
        {
          "label" : "Apakah lingkungan rumah sudah disiapkan ?",
          "model" : "rencanaKeperawatan",
          "code" : "NA",
        },
      ],
    },
  ]
}
