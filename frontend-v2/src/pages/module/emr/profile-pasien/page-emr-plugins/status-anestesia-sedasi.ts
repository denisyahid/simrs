export function sedasi(): any {
  return [
    {
      "title": "Penyakit yang menyertai",
      "model": "penyakitMenyertai",
      "opsi": {
        "label": "Pengobatan",
        "value": [
          {
            "model": "ObatPenTera",
            "label": "Teratur"
          },
          {
            "model": "ObatPenTera",
            "label": "Tidak teratur"
          },
        ]
      },
      "more": {
        "label": "Obat-obatan",
        "model": "obatPenyakit"
      }
    },
    {
      "title": "Riwayat Alergi",
      "model": "riwayatAlergi",
      "opsi": {
        "label": "Pengobatan",
        "value": [
          {
            "model": "ObatPenAlergi",
            "label": "Teratur"
          },
          {
            "model": "ObatPenAlergi",
            "label": "Tidak teratur"
          },
        ]
      },
      "more": {
        "label": "Obat-obatan",
        "model": "obatAlergi"
      }
    },
    {
      "title": "Riwayat anestesia",
      "model": "riwayatAnestesia",
      "opsi": {
        "label": "Pengobatan",
        "value": [
          {
            "model": "penyulit",
            "label": "Ada"
          },
          {
            "model": "penyulit",
            "label": "Tidak ada"
          },
        ]
      },
      "more": {
        "label": "Jenis Penyulit",
        "model": "jenisPenyulit"
      }
    },
  ]
}

export function pemeriksaanFisik(): any {
  return [
    {
      "title": "KU dan Kesadaran",
      "model": "kuKesadaran",
      "detail": [
        {
          "label": "T",
          "model": "t",
        },
        {
          "label": "N",
          "model": "nadi",
        },
        {
          "label": "R",
          "model": "pernapasan",
        },
        {
          "label": "SpO2",
          "model": "sp02",
        },
        {
          "label": "BB",
          "model": "beratBadan",
        },
        {
          "label": "TB",
          "model": "tinggiBadan",
        },
        {
          "label": "Gol. Darah",
          "model": "golDarah",
        },
        {
          "label": "Kepala",
          "model": "kepala",
        },
        {
          "label": "Thorax",
          "model": "thorax",
        },
        {
          "label": "Abdomen",
          "model": "abdomen",
        },
        {
          "label": "Extremitas",
          "model": "extremitas",
        },
      ],
    }
  ]
}

export function poinPemeriksaanFisik(): any {
  return [
    {
      "model": "poinFisik_",
      "label": ['ASA', 'PS', '1', '2', '3', '4', '5', 'E'],
    },
  ]
}

export function rencanaAnestesia(): any {
  return [
    {
      "title": "Anestesia Umum",
      "value": [
        {
          "label": "Inhalasia",
          "model": "anesUmum",
        },
        {
          "label": "TIVA",
          "model": "anesUmum",
        },
        {
          "label": "Kombinasi",
          "model": "anesUmum",
        },
      ],
      "detail": [
        {
          "label": "GA - ETT",
          "model": "subAnesUmum",
        },
        {
          "label": "GA - NTT",
          "model": "subAnesUmum",
        },
        {
          "label": "GA - LMA",
          "model": "subAnesUmum",
        },
        {
          "label": "GA - Mask",
          "model": "subAnesUmum",
        },
      ]
    },
    {
      "title": "Anestesia Regional",
      "model": "anesRegional",
      "detail": [
        {
          "label": "Spinal",
          "model": "subAnesRegional",
        },
        {
          "label": "Epidural : Lumbal / Torakal",
          "model": "subAnesRegional",
          "pilihan": [
            {
              "model": "epidural",
              "label": "Lunbal"
            },
            {
              "model": "epidural",
              "label": "Torakal"
            }
          ]
        },
        {
          "label": "Kaudal",
          "model": "subAnesRegional",
        },
        {
          "label": "Blok Perifer",
          "model": "subAnesRegional",
        },
      ],
      "optional": {
        "model": "blokPerifer"
      }
    },
    {
      "title": "Lain-lain",
      "model": "teknikAnestesia",
      "detail": [
        {
          "label": "MAC",
          "model": "lainnya_1",
        },
        {
          "label": "sedasi",
          "model": "lainnya_2",
          "pilihan": [
            {
              "model": "sedadi",
              "label": "Ya",
            },
            {
              "model": "sedadi",
              "label": "Tidak",
            },
          ]
        }
      ]
    }
  ]
}
