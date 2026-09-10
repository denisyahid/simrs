export function airway(): any {
  return [
    {
      "title": "",
      "value": [
        {
          "model": "airway",
          "label": "Paten",
        },
        {
          "model": "airway",
          "label": "Tidak Paten",
        },
        {
          "model": "airway",
          "label": "Snoring",
        },
        {
          "model": "airway",
          "label": "Gargling",
        },
        {
          "model": "airway",
          "label": "Stridor",
        },
        {
          "model": "airway",
          "label": "Benda Asing",
        },
      ],
      "optional": {
        "label": "Lain-lain",
        "model": "airwayLain"
      }
    },
    {
      "title": "Diagnosa Keperawatan",
      "value": [
        {
          "model": "diagnosaKeper",
          "label": "Bersihkan jalan nafas tidak efektif"
        },
        {
          "model": "diagnosaKeper",
          "label": "Resiko aspirasi"
        },
      ]
    }
  ]
}

export function breathingCirculation(): any {
  return [
    {
      "title": "B. Breathing",
      "detail": [
        {
          "subTitle": "Pola Nafas",
          "value": [
            {
              "label": "Teratur",
              "model": "polaNafas",
              "type": "checkBox",
            },
            {
              "label": "Tidak teratur",
              "model": "polaNafas",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Suara Nafas",
          "value": [
            {
              "label": "Vesikuler",
              "model": "suaraNafas",
              "type": "checkBox",
            },
            {
              "label": "Bronchovesikuler",
              "model": "suaraNafas",
              "type": "checkBox",
            },
            {
              "label": "Whezing",
              "model": "suaraNafas",
              "type": "checkBox",
            },
            {
              "label": "Ronchi",
              "model": "suaraNafas",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Penggunaan Otot Bantu Nafas",
          "value": [
            {
              "label": "Retraksi Dada",
              "model": "ototBantuNafas",
              "type": "checkBox",
            },
            {
              "label": "Cuping Hidung",
              "model": "ototBantuNafas",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Frekuensi Nafas",
          "value": [
            {
              "model": "frekuensiNafas",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Diagnosa Keperawatan",
          "value": [
            {
              "label": "Ketidak efektifan pola nafas",
              "model": "diagnosaKeper",
              "type": "checkBox",
            },
            {
              "label": "Bersihan Jalan nafas tidak efektif",
              "model": "diagnosaKeper",
              "type": "checkBox",
            },
          ]
        },
      ]
    },
    {
      "title": "C. Circulation",
      "detail": [
        {
          "subTitle": "Akral",
          "value": [
            {
              "label": "Hangat",
              "model": "akral",
              "type": "checkBox",
            },
            {
              "label": "Dingin",
              "model": "akral",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Pucat",
          "value": [
            {
              "label": "Ya",
              "model": "pucat",
              "type": "checkBox",
            },
            {
              "label": "Tidak",
              "model": "pucat",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Tekanan Darah",
          "value": [
            {
              "model": "tekananDarah",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Nadi",
          "value": [
            {
              "label": "Tidak Teraba",
              "model": "nadi",
              "type": "checkBox",
            },
            {
              "label": "Teraba",
              "model": "nadi",
              "type": "checkBox",
            },
            {
              "model": "tekananNadi",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Adanya riwayat kehilangan cairan dalam jumlah besar",
          "value": [
            {
              "label": "Diare",
              "model": "riwayatKhlCairan",
              "type": "checkBox",
            },
            {
              "label": "Muntah",
              "model": "riwayatKhlCairan",
              "type": "checkBox",
            },
            {
              "label": "Pendarahan",
              "model": "riwayatKhlCairan",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Kelembaban Kulit",
          "value": [
            {
              "label": "Lembab",
              "model": "kelembabanKulit",
              "type": "checkBox",
            },
            {
              "label": "Kering",
              "model": "kelembabanKulit",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Turgar",
          "value": [
            {
              "label": "Normal",
              "model": "turgar",
              "type": "checkBox",
            },
            {
              "label": "Kurang",
              "model": "turgar",
              "type": "checkBox",
            },
          ]
        },
        {
          "subTitle": "Diagnosa Keperawatan",
          "value": [
            {
              "label": "Ketidak efektifan perfusi jaringan perifer",
              "model": "diagnosaKeperCircul",
              "type": "checkBox",
            },
            {
              "label": "Defisit volume cairan",
              "model": "diagnosaKeperCircul",
              "type": "checkBox",
            },
            {
              "label": "Penurunan curah jantung",
              "model": "diagnosaKeperCircul",
              "type": "checkBox",
            },
          ]
        },
      ]
    }
  ]
}

export function disability(): any {
  return [
    {
      "title": "Tingkat Kesadaran",
      "type": "checkBox",
      "value": [
        {
          "label": "Compas metis",
          "code": "CM",
          "model": "tingkatKesadaran",
        },
        {
          "label": "Apatis",
          "code": "AS",
          "model": "tingkatKesadaran",
        },
        {
          "label": "Somnolen",
          "code": "SN",
          "model": "tingkatKesadaran",
        },
        {
          "label": "Sopor",
          "code": "SR",
          "model": "tingkatKesadaran",
        },
        {
          "label": "Coma",
          "code": "CA",
          "model": "tingkatKesadaran",
        },
      ]
    },
    {
      "title": "Nilai GCS Dewasa",
      "type": "textBox",
      "value": [
        {
          "label": "E",
          "model": "gcsDewas_E",
        },
        {
          "label": "V",
          "model": "gcsDewas_V",
        },
        {
          "label": "M",
          "model": "gcsDewas_M",
        },
      ]
    },
    {
      "title": "Nilai GCS Anak",
      "type": "textBox",
      "value": [
        {
          "label": "A",
          "model": "gcsAnak_A",
        },
        {
          "label": "V",
          "model": "gcsAnak_V",
        },
        {
          "label": "P",
          "model": "gcsAnak_P",
        },
        {
          "label": "U",
          "model": "gcsAnak_U",
        },
      ]
    },
    {
      "title": "Pupil",
      "type": "checkBox",
      "value": [
        {
          "label": "Miosis",
          "code": "MS",
          "model": "pupil",
        },
        {
          "label": "Midriasis",
          "code": "MD",
          "model": "pupil",
        },
        {
          "label": "Diameter",
          "code": "DR",
          "model": "pupil",
        },
        {
          "label": "O 1mm",
          "code": "O1",
          "model": "pupil",
        },
        {
          "label": "O 2mm",
          "code": "O2",
          "model": "pupil",
        },
        {
          "label": "O 3mm",
          "code": "O3",
          "model": "pupil",
        },
        {
          "label": "O 4mm",
          "code": "O4",
          "model": "pupil",
        },
      ]
    },
    {
      "title": "Respon Cahaya",
      "type": "checkBox",
      "value": [
        {
          "label": "+",
          "model": "responCahaya",
        },
        {
          "label": "-",
          "model": "responCahaya",
        },
      ]
    },
    {
      "title": "Diagnosa Keperawatan",
      "type": "checkBox",
      "value": [
        {
          "label": "Gangguan perfusi jaringan serebral",
          "code": "GPJS",
          "model": "diagnosaKeperDSB",
        },
        {
          "label": "Komunikasi Verbal",
          "code": "KV",
          "model": "diagnosaKeperDSB",
        },
        {
          "label": "Penurunan Kesadaran",
          "code": "PK",
          "model": "diagnosaKeperDSB",
        },
      ]
    },
    {
      "title": "Resiko Jatuh",
      "type": "checkBox",
      "value": [
        {
          "label": "Tidak Beresiko/Resiko Jatuh Rendah",
          "code": "TB",
          "model": "diagnosaKeperDSB",
        },
        {
          "label": "Resiko Jatuh Sedang",
          "code": "RJS",
          "model": "diagnosaKeperDSB",
        },
        {
          "label": "Resiko Jatuh Tinggi",
          "code": "RJT",
          "model": "diagnosaKeperDSB",
        },
      ]
    },
  ]
}

export function exposure(): any {
  return [
    {
      "title": "E.exposure",
      "detail": [
        {
          "subTitle": "Pengkajian Nyeri",
          "type": "textBox",
          "value": [
            {
              "model": "pengkajianNyeri"
            }
          ]
        },
        {
          "subTitle": "Apakah ada nyeri",
          "type": "checkBox",
          "value": [
            {
              "label": "Ya",
              "code": "YA",
              "model": "adaNyeri",
            },
            {
              "label": "Tidak",
              "code": "TK",
              "model": "adaNyeri",
            },
            {
              "label": "Skor Nyeri WB",
              "code": "SNW",
              "model": "adaNyeri",
            },
            {
              "label": "VAS",
              "code": "VAS",
              "model": "adaNyeri",
            },
            {
              "label": "CCPOT",
              "code": "CCPOT",
              "model": "adaNyeri",
            },
          ]
        }
      ]
    }
  ]
}

export function tahapanBerduka(): any {
  return [
    {
      "title": "Denial",
      "value": [
        {
          "label": "Tidak",
          "model": "denial",
          "type": "checkBox",
        },
        {
          "label": "Ya",
          "model": "denial",
          "type": "checkBox",
        },
        {
          "label": "Jelaskan",
          "model": "ketDenial",
          "type": "textBox",
        },
      ]
    },
    {
      "title": "Merah",
      "value": [
        {
          "label": "Tidak",
          "model": "merah",
          "type": "checkBox",
        },
        {
          "label": "Ya",
          "model": "merah",
          "type": "checkBox",
        },
        {
          "label": "Jelaskan",
          "model": "ketMerah",
          "type": "textBox"
        },
      ]
    },
    {
      "title": "Tawar Menawar",
      "value": [
        {
          "label": "Tidak",
          "model": "tawarMenawar",
          "type": "checkBox",
        },
        {
          "label": "Ya",
          "model": "tawarMenawar",
          "type": "checkBox",
        },
        {
          "label": "Jelaskan",
          "model": "ketTawarMenawar",
          "type": "textBox"
        },
      ]
    },
    {
      "title": "Depresi",
      "value": [
        {
          "label": "Tidak",
          "model": "depresi",
          "type": "checkBox",
        },
        {
          "label": "Ya",
          "model": "depresi",
          "type": "checkBox",
        },
        {
          "label": "Jelaskan",
          "model": "ketDepresi",
          "type": "textBox",
        },
      ]
    },
    {
      "title": "Penerimaan",
      "value": [
        {
          "label": "Tidak",
          "model": "penerimaan",
          "type": "checkBox",
        },
        {
          "label": "Ya",
          "model": "penerimaan",
          "type": "checkBox",
        },
        {
          "label": "Jelaskan",
          "model": "ketPenerimaan",
          "type": "textBox",
        },
      ]
    },
  ]
}

export function konsepDiri(): any {
  return [
    {
      "title": "H. Pola Konsep Diri",
      "detail": [
        {
          "subTitle": "Gambaran Diri",
          "value": [
            {
              "label": "Tidak Masalah",
              "model": "gambaranDiri",
              "type": "checkBox",
            },
            {
              "label": "Masalah",
              "model": "gambaranDiri",
              "type": "checkBox",
            },
            {
              "label": "Jelaskan",
              "model": "ketMasalah",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Peran",
          "value": [
            {
              "label": "Orang Tua",
              "model": "peran",
              "type": "checkBox",
            },
            {
              "label": "Ibu",
              "model": "peran",
              "type": "checkBox",
            },
            {
              "label": "Bapak",
              "model": "peran",
              "type": "checkBox",
            },
            {
              "label": "Suami",
              "model": "peran",
              "type": "checkBox",
            },
            {
              "label": "Istri",
              "model": "peran",
              "type": "checkBox",
            },
          ],
          "optional": {
            "label": "Jelaskan apakah ada masalah tentang perannnya selama ini",
            "model": "masalahPadaPeran"
          }
        },
        {
          "subTitle": "Ideal Diri",
          "value": [
            {
              "label": "Tidak Masalah",
              "model": "idealDiri",
              "type": "checkBox",
            },
            {
              "label": "Masalah",
              "model": "idealDiri",
              "type": "checkBox",
            },
            {
              "label": "Jelaskan",
              "model": "ketIdealDiri",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Harga Diri",
          "value": [
            {
              "label": "Tidak Masalah",
              "model": "hargaDiri",
              "type": "checkBox",
            },
            {
              "label": "Masalah",
              "model": "hargaDiri",
              "type": "checkBox",
            },
            {
              "label": "Jelaskan",
              "model": "ketHargaDiri",
              "type": "textBox",
            },
          ]
        },
      ]
    },
    {
      "title": "I. Psikososial",
      "detail": [
        {
          "subTitle": "Hubungan dengan orang terdekat",
          "value": [
            {
              "label": "Tidak Masalah",
              "model": "psikologi",
              "type": "checkBox",
            },
            {
              "label": "Masalah",
              "model": "psikologi",
              "type": "checkBox",
            },
            {
              "label": "Sebutkan",
              "model": "ketPsikologi",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Peran serta dalam kegiatan kelompok atau masyarakat",
          "value": [
            {
              "label": "Tidak Masalah",
              "model": "kegiatanKelompok",
              "type": "checkBox",
            },
            {
              "label": "Masalah",
              "model": "kegiatanKelompok",
              "type": "checkBox",
            },
            {
              "label": "Sebutkan",
              "model": "ketKegiatanKelompok",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Hambatan dalam berhubungan dengan orang lain",
          "value": [
            {
              "label": "Tidak Masalah",
              "model": "hambatanBerhubungan",
              "type": "checkBox",
            },
            {
              "label": "Masalah",
              "model": "hambatanBerhubungan",
              "type": "checkBox",
            },
            {
              "label": "Sebutkan",
              "model": "ketHambatanBerhubungan",
              "type": "textBox",
            },
          ]
        },
        {
          "subTitle": "Komunikasi",
          "value": [
            {
              "label": "Tidak Bermasalah",
              "model": "komunikasi",
              "type": "checkBox",
            },
            {
              "label": "Bermasalah",
              "model": "komunikasi",
              "type": "checkBox",
            },
          ],
          "optional": {
            "label": "Jika ya tuliskan yang terjadi pada pasien",
            "model": "ketKomunikasi"
          }
        },
      ]
    }
  ]
}

export function spiritual(): any {
  return [
    {
      "title": "Apakah membutuhkan ritual khusus ?",
      "value": [
        {
          "label": "Tidak",
          "model": "butuhRitual",
        },
        {
          "label": "Ya",
          "model": "butuhRitual",
        },
      ],
      "optional":
      {
        "title": "Jika ya tuliskan ritual yang dikehendaki pasien dan keluarga",
        "model" : "ketRitualKhusus",
      }
    },
    {
      "value" : [
        {
          "label" : "Islam",
          "model" : "spiritual",
        },
        {
          "label" : "Hindu",
          "model" : "spiritual",
        },
        {
          "label" : "Khatolik",
          "model" : "spiritual",
        },
        {
          "label" : "Budha",
          "model" : "spiritual",
        },
        {
          "label" : "Protestan",
          "model" : "spiritual",
        },
        {
          "label" : "Lain-lain",
          "model" : "spiritual",
        },
      ]
    },
    {
      "title" : "Apakah membutuhkan rohaniawan pendamping ?",
      "value" : [
        {
          "label" : "Tidak",
          "model" : "butuhRohaniawan",
        },
        {
          "label" : "Ya",
          "model" : "butuhRohaniawan",
        },
      ],
    },
    {
      "title" : "Jika Ya tuliskan didatangkan oleh",
      "value" : [
        {
          "label" : "RS",
          "model" : "diDatangkan",
        },
        {
          "label" : "Keluarga",
          "model" : "diDatangkan",
        },
      ],
    },
  ]
}


export function again():any {
  return [
    {
      "title" : "K. Donor Organ",
      "detail" : [
        {
          "subTitle" : "Apakah pasien atau keluarga menghendaki untuk melakukan donor organ ?",
          "value" : [
            {
              "label" : "Ya",
              "model" : "lakukanDonor",
            },
            {
              "label" : "Tidak",
              "model" : "lakukanDonor",
            },
          ]
        }
      ]
    },
    {
      "title" : "L. Do Not Resucitation",
      "detail" : [
        {
          "subTitle" : "Apakah pasien atau keluarga menghendaki untuk dilakukan tindakan DNR ?",
          "value" : [
            {
              "label" : "Ya",
              "model" : "lakukanDNR",
            },
            {
              "label" : "Tidak",
              "model" : "lakukanDNR",
            },
          ]
        }
      ]
    },
  ]
}
