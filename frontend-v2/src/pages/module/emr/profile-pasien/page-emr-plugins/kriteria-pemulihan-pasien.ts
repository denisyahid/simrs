export function bromage():any{
  return [
    {
      "label" : "Gerakan Penuh Tungkal",
      "model" : "aktivitasMotorik_1",
      "value" : {
        "code" : "GPT",
        "skor" : 1,
      }
    },
    {
      "label" : "Tak mampu ekstensi Tungkai",
      "model" : "aktivitasMotorik_2",
      "value" : {
        "code" : "TMT",
        "skor" : 2,
      }
    },
    {
      "label" : "Tak mampu fleksi lutut",
      "model" : "aktivitasMotorik_3",
      "value" : {
        "code" : "TML",
        "skor" : 3,
      }
    },
    {
      "label" : "Tak mampu fleksi pergelangan kaki",
      "model" : "aktivitasMotorik_4",
      "value" : {
        "code" : "TMF",
        "skor" : 4,
      }
    },
  ]
}

export function ramsay():any{
  return [
    {
      "label" : "Pasien cemas atau agitasi atau keduanya",
      "model" : "poinRamsay_1",
      "value" : {
        "code" : "PCA",
        "skor" : 1,
      }
    },
    {
      "label" : " Pasien Koopera?, terorientasi dan tenang",
      "model" : "poinRamsay_2",
      "value" : {
        "code" : "PKT",
        "skor" : 2,
      }
    },
    {
      "label" : " Pasien hanya berespon terhadap perintah",
      "model" : "poinRamsay_3",
      "value" : {
        "code" : "PHBP",
        "skor" : 3,
      }
    },
    {
      "label" : "Respon penuh terhadap sentuhan glabela ringan",
      "model" : "poinRamsay_4",
      "value" : {
        "code" : "RPTS",
        "skor" : 4,
      }
    },
    {
      "label" : "Respon lambat terhadap sentuhan glabela ringan",
      "model" : "poinRamsay_4",
      "value" : {
        "code" : "RLTs",
        "skor" : 5,
      }
    },
    {
      "label" : "Tidak ada respon",
      "model" : "poinRamsay_4",
      "value" : {
        "code" : "TAR",
        "skor" : 6,
      }
    },
  ]
}

export function steward():any{
  return [
    {
      "title" : "Kesadaran",
      "detail" : [
        {
          "label" : "Sadar Penuh",
          "model" : "poinKesadaran_1",
          "value" : {
            "code" : "SP",
            "skor" : 2,
          }
        },
        {
          "label" : "Bangun jika dipanggil",
          "model" : "poinKesadaran_2",
          "value" : {
            "code" : "BJD",
            "skor" : 1,
          }
        },
        {
          "label" : "Belum merespon",
          "model" : "poinKesadaran_3",
          "value" : {
            "code" : "BM",
            "skor" : 0,
          }
        },
      ]
    },
    {
      "title" : "Respirasi",
      "detail" : [
        {
          "label" : "Batuk/Menangis",
          "model" : "poinRespirasi_1",
          "value" : {
            "code" : "BM",
            "skor" : 2,
          }
        },
        {
          "label" : "Berusaha Bernafas",
          "model" : "poinRespirasi_2",
          "value" : {
            "code" : "BB",
            "skor" : 1,
          }
        },
        {
          "label" : "Perlu Bantuan Bernafas",
          "model" : "poinRespirasi_3",
          "value" : {
            "code" : "PBB",
            "skor" : 0,
          }
        },
      ]
    },
    {
      "title" : "Aktifitas Motorik",
      "detail" : [
        {
          "label" : "Gerak beraturan",
          "model" : "poinAktifasiMotorikST_1",
          "value" : {
            "code" : "GB",
            "skor" : 2,
          }
        },
        {
          "label" : "Gerak tanpa tujuan",
          "model" : "poinAktifasiMotorikST_1",
          "value" : {
            "code" : "GTT",
            "skor" : 1,
          }
        },
        {
          "label" : "Tidak Bergerak",
          "model" : "poinAktifasiMotorikST_1",
          "value" : {
            "code" : "TB",
            "skor" : 0,
          }
        },
      ]
    },
  ]
}

export function vitalSign():any{
  return [
    {
      "label" : "Tekanan Darah",
      "model" : "tekananDarah",
      "satuan" : "mmHg"
    },
    {
      "label" : "Nadi",
      "model" : "nadi",
      "satuan" : "x/menit"
    },
    {
      "label" : "Pernapasan",
      "model" : "pernapasan",
      "satuan" : "x/menit"
    },
    {
      "label" : "SpO2",
      "model" : "spo2",
      "satuan" : "%"
    },
  ]
}
