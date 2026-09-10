export function formulir(): any {
  return [
    {
      "title": "",
      "type": "textBox",
      "detail": [
        {
          "label": "Pekerjaan",
          "model": "pekerjaan",
        },
        {
          "label": "Suku",
          "model": "suku",
        },
        {
          "label": "Berat Badan",
          "model": "beratBadan",
        },

      ]
    },
    {
      "title": "Ket",
      "type": "checkBox",
      "detail": [
        {
          "label": "Hamil",
          "model": "hamil",
        },
        {
          "label": "Tidak Hamil",
          "model": "hamil",
        },
        {
          "label": "Tidak tahu",
          "model": "hamil",
        },
      ]
    },
    {
      "title": "",
      "type": "select",
      "detail": [
        {
          "label": "Diagnosa Utama",
          "model": "diagnosa",
        },
      ]
    },
  ]
}

export function reaksiObat(): any {
  return [
    {
      "title": "REAKSI EFEK SAMPING OBAT",
      "detail": [
        {
          "subTitle": "Kesudahan",
          "type": "checkBox",
          "value": [
            {
              "label": "Sembuh",
              "model": "kesudahan",
              "code": "SH"
            },
            {
              "label": "Meninggal",
              "model": "kesudahan",
              "code": "ML"
            },
            {
              "label": "Belum Sembuh",
              "model": "kesudahan",
              "code": "BS"
            },
            {
              "label": "Tidak Tahu",
              "model": "kesudahan",
              "code": "TT"
            },
            {
              "label": "Sembuh dengan gajala sisa",
              "model": "kesudahan",
              "code": "SDG"
            },
          ]
        },
        {
          "subTitle": "Penyakit/kondisi lain yang menyertai",
          "type": "checkBox",
          "value": [
            {
              "label": "Gangguan ginjal",
              "model": "penyakit",
              "code": "GG"
            },
            {
              "label": "Kondisi medis lainnya",
              "model": "penyakit",
              "code": "KM"
            },
            {
              "label": "Gangguan hati",
              "model": "penyakit",
              "code": "GH"
            },
            {
              "label": "Alergi",
              "model": "penyakit",
              "code": "AI"
            },
            {
              "label": "Faktor industri,pertanian,kimia dan lain-lain",
              "model": "penyakit",
              "code": "GI"
            },
          ]
        },
        {
          "subTitle": "Saat/tgl mulai terjadi",
          "type": "date",
          "value": [
            {
              "model": "tglMulaiTerjadi"
            }
          ]
        },
        {
          "subTitle": "Bentuk/manifestasi efek samping obat yang terjadi",
          "type": "textArea",
          "value": [
            {
              "model": "efeksampingObat"
            }
          ]
        },
        {
          "subTitle": "Data laboratorium (jika ada)",
          "type": "textArea",
          "value": [
            {
              "model": "dataLaboratorium"
            }
          ]
        },
      ]
    },

    {
      "title": "Kesudahan efek samping obat",
      "detail": [
        {
          "subTitle": "Tanggal dan Jam",
          "type": "date",
          "value": [
            {
              "model": "tglSelesaiEfeksamping"
            }
          ]
        },
        {
          "type": "checkBox",
          "value": [
            {
              "label": "Sembuh",
              "model": "kesudahanEfek",
              "code": "SH"
            },
            {
              "label": "Meninggal",
              "model": "kesudahanEfek",
              "code": "ML"
            },
            {
              "label": "Belum Sembuh",
              "model": "kesudahanEfek",
              "code": "BS"
            },
            {
              "label": "Tidak Tahu",
              "model": "kesudahanEfek",
              "code": "TT"
            },
            {
              "label": "Sembuh dengan gajala sisa",
              "model": "kesudahanEfek",
              "code": "SDG"
            },
          ]

        },
        {
          "subTitle": "Reaksi efek samping obat yang pernah dialami",
          "type": "textArea",
          "value": [
            {
              "model": "reaksiSampingObata",
            }
          ]
        },
        {
          "subTitle": "Tindakan yang telah dilakukan untuk mengatasi reaksi efek samping obat",
          "type": "textArea",
          "value": [
            {
              "model": "tindakanMengatasiEfekSamping",
            }
          ]
        }
      ]
    },
  ]
}

export function reaksi(): any {
  return [
    {
      "title": "Apakah reaksi E.S.O hilang setelah obat dihentikan ?",
      "value": [
        {
          "label": "Ya",
          "model": "reaksiESOHilang",
        },
        {
          "label": "Tidak",
          "model": "reaksiESOHilang",
        },
        {
          "label": "Tidak Tahu",
          "model": "reaksiESOHilang",
        },
      ]
    },
    {
      "title": "Apakah reaksi E.S.O yang sama timbul sewaktu obat yang dicurigai digunakan kembali",
      "value": [
        {
          "label": "Ya",
          "model": "reaksiESOTimbul",
        },
        {
          "label": "Tidak",
          "model": "reaksiESOTimbul",
        },
        {
          "label": "Tidak Tahu",
          "model": "reaksiESOTimbul",
        },
      ]
    },
  ]
}

export function skorAlgoritma(): any {
  return [
    {
      "no": 1,
      "value": "Jawablah setiap pertanyaan dibawah ini",
    },
    {
      "no": 2,
      "value": "Tuliskan skor dari setiap jawaban",
    },
    {
      "no": 3,
      "value": "Jumlahkan skor jawaban atas 10 pertanyaan tersebut",
    },
    {
      "no": 4,
      "value": "Jika total skor nilai adalah :",
    },
  ]
}

export function skor(): any {
  return [
    {
      "title": "Skor 9 atau Lebih",
      "value": {
        "keterangan": "Sangat mungkin ESO (High Probable)",
        "poin": 9,
      },
    },
    {
      "title": "Skor 5-8",
      "value": {
        "keterangan": "Mungkin ESO(Probable)",
        "poin": 8,
      },
    },
    {
      "title": "Skor 1-4",
      "value": {
        "keterangan": "Dicurigai ESO(Possible)",
        "poin": 4,
      },
    },
    {
      "title": "Skor 0 atau kurang",
      "value": {
        "keterangan": "Diragukan ESO(Doubtful)",
        "poin": 0,
      },
    },
  ]
}

export function pertanyaan(): any {
  return [
    {
      "no": "1",
      "pertanyaan": "Apakah ada laporan penelitian sebelumnya tentang reaksi ini ?",
      "skala": [
        {
          "model": "penelitianSebelumnya",
          "label": "Ya",
          "value": {
            "poin": 1,
            "ket": "Ya",
          }
        },
        {
          "model": "penelitianSebelumnya",
          "label": "Tidak",
          "value": {
            "poin": 0,
            "ket": "Tidak",
          }
        },
        {
          "model": "penelitianSebelumnya",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [1, 0, 0]
    },
    {
      "no": "2",
      "pertanyaan": "Apakah reaksi muncul setelah obat yang dicurigai diberikan ?",
      "skala": [
        {
          "model": "reaksiObatDicurigai",
          "label": "Ya",
          "value": {
            "poin": 2,
            "ket": "Ya"
          }
        },
        {
          "model": "reaksiObatDicurigai",
          "label": "Tidak",
          "value": {
            "poin": -1,
            "ket": "Tidak"
          }
        },
        {
          "model": "reaksiObatDicurigai",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [2, -1, 0]
    },
    {
      "no": "3",
      "pertanyaan": "Apakah reaksi ini berkurang saat obat dihentikan atau antagonis obat yang spesifik diberikan ?",
      "skala": [
        {
          "model": "berkurangSaatBerhenti",
          "label": "Ya",
          "value": {
            "poin": 1,
            "ket": "Ya"
          }
        },
        {
          "model": "berkurangSaatBerhenti",
          "label": "Tidak",
          "value": {
            "poin": 0,
            "ket": "Tidak"
          }
        },
        {
          "model": "berkurangSaatBerhenti",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [1,0,0]
    },
    {
      "no": "4",
      "pertanyaan": "Apakah reaksi muncul kembali saat obat digunakan kembali ?",
      "skala": [
        {
          "model": "munculKembali",
          "label": "Ya",
          "value": {
            "poin": 2,
            "ket": "Ya"
          }
        },
        {
          "model": "munculKembali",
          "label": "Tidak",
          "value": {
            "poin": -1,
            "ket": "Tidak"
          }
        },
        {
          "model": "munculKembali",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [2,-1,0]
    },
    {
      "no": "5",
      "pertanyaan": "Apakah ada penyebab alternatif (selain obat) yang dapat menyebabkan reaksi ini ?",
      "skala": [
        {
          "model": "penyebabAlternatif",
          "label": "Ya",
          "value": {
            "poin": -1,
            "ket": "Ya"
          }
        },
        {
          "model": "penyebabAlternatif",
          "label": "Tidak",
          "value": {
            "poin": 2,
            "ket": "Tidak"
          }
        },
        {
          "model": "penyebabAlternatif",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [-1,2,0]
    },
    {
      "no": "6",
      "pertanyaan": "Apakah reaksi muncul kembali saat diberikan placebo ?",
      "skala": [
        {
          "model": "munculKembaliSaatPlacebo",
          "label": "Ya",
          "value": {
            "poin": 1,
            "ket": "Ya"
          }
        },
        {
          "model": "munculKembaliSaatPlacebo",
          "label": "Tidak",
          "value": {
            "poin": 1,
            "ket": "Tidak"
          }
        },
        {
          "model": "munculKembaliSaatPlacebo",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [1,1,0]
    },
    {
      "no": "7",
      "pertanyaan": "Apakah obat terdeteksi dalam darah (atau cairan lain) dalam konsentrasi yang diketahui toksik ?",
      "skala": [
        {
          "model": "terdeteksiDalamDarah",
          "label": "Ya",
          "value": {
            "poin": 1,
            "ket": "Ya"
          }
        },
        {
          "model": "terdeteksiDalamDarah",
          "label": "Tidak",
          "value": {
            "poin": 0,
            "ket": "Tidak"
          }
        },
        {
          "model": "terdeteksiDalamDarah",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [1,0,0]
    },
    {
      "no": "8",
      "pertanyaan": "Apakah reaksi lebih berat saat dosis dinaikan, atau berkurang saat dosis diturunkan ?",
      "skala": [
        {
          "model": "reaksiLebihBerat",
          "label": "Ya",
          "value": {
            "poin": 1,
            "ket": "Ya"
          }
        },
        {
          "model": "reaksiLebihBerat",
          "label": "Tidak",
          "value": {
            "poin": 0,
            "ket": "Tidak"
          }
        },
        {
          "model": "reaksiLebihBerat",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [1,0,0]
    },
    {
      "no": "9",
      "pertanyaan": "Apakah pasien mempunyai reaksi yang mirip pada obat yang sama atau mirip pada pemaparan sebelumnya ",
      "skala": [
        {
          "model": "reaksiMirip",
          "label": "Ya",
          "value": {
            "poin": 1,
            "ket": "Ya"
          }
        },
        {
          "model": "reaksiMirip",
          "label": "Tidak",
          "value": {
            "poin": 0,
            "ket": "Tidak"
          }
        },
        {
          "model": "reaksiMirip",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [1,0,0]
    },
    {
      "no": "10",
      "pertanyaan": "Apakah reaksi dikonfirmasi dengan suatu bukti obyektif ?",
      "skala": [
        {
          "model": "reaksiDikonfirmasi",
          "label": "Ya",
          "value": {
            "poin": 1,
            "ket": "Ya"
          }
        },
        {
          "model": "reaksiDikonfirmasi",
          "label": "Tidak",
          "value": {
            "poin": 0,
            "ket": "Tidak"
          }
        },
        {
          "model": "reaksiDikonfirmasi",
          "label": "Tidak Tahu",
          "value": {
            "poin": 0,
            "ket": "Tidak Tahu"
          }
        },
      ],
      "skor": [1,0,0]
    },
  ]
}
