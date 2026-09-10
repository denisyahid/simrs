export function pengkajianSebelum ():any{
  return [
    {
      "detail" : [
        {
          "label" : "Rencana Prosedur",
          "model" : "rencanaPros",
          "type" : "textarea"
        },
        {
          "label" : "Diagnosa sebelum prosedur",
          "model" : "diagSebelumPros",
          "type" : "textarea"
        }
      ]
    },
    {
      "title" : "Jenis Prosedur",
      "detail" : [
        {
          "label" : "Cito",
          "model" : "jenisPro",
          "type" : "checkBox",
        },
        {
          "label" : "Efektif",
          "model" : "jenisPro",
          "type" : "checkBox",
        },
      ]
    },
    {
      "detail" : [
        {
          "label" : "Berat Badan",
          "model" : "beratBadan",
          "placeholder" : "KG",
          "type" : "textBox"
        },
        {
          "label" : "Tinggi Badan",
          "model" : "tinggiBadan",
          "placeholder" : "CM",
          "type" : "textBox"
        },
        {
          "label" : "Riwayat Penyakit Lain",
          "model" : "diagSebelumPros",
          "type" : "textarea"
        },
      ]
    },
    {
      "title" : "Riwayat Merokok",
      "detail" : [
        {
          "label" : "Ya",
          "model" : "merokok",
          "type" : "checkBox",
        },
        {
          "model" : "ketMerokok",
          "type" : "textBox",
        },
        {
          "label" : "Tidak",
          "model" : "merokok",
          "type" : "checkBox",
        },
      ]
    },
    {
      "title" : "Riwayat Alkohol",
      "detail" : [
        {
          "label" : "Ya",
          "model" : "alkohol",
          "type" : "checkBox",
        },
        {
          "model" : "ketAlkohol",
          "type" : "textBox",
        },
        {
          "label" : "Tidak",
          "model" : "alkohol",
          "type" : "checkBox",
        },
      ]
    },
    {
      "title" : "Riwayat Narkoba",
      "detail" : [
        {
          "label" : "Ya",
          "model" : "narkoba",
          "type" : "checkBox",
        },
        {
          "model" : "ketNarkoba",
          "type" : "textBox",
        },
        {
          "label" : "Tidak",
          "model" : "narkoba",
          "type" : "checkBox",
        },
      ]
    },
    {
      "detail" : [
        {
          "label" : "Riwayat pemakaian obat lain ",
          "model" : "pemakaianObatLain",
          "type" : "textarea",
        },
      ]
    },
    {
      "title" : "Riwayat efek samping dengan sedasi, analgesia, anastesia regional atau umum",
      "detail" : [
        {
          "label" : "Ya",
          "model" : "efekSamping",
          "type" : "checkBox",
        },
        {
          "model" : "ketefekSamping",
          "type" : "textBox",
        },
        {
          "label" : "Tidak",
          "model" : "efekSamping",
          "type" : "checkBox",
        },
      ]
    },
  ]
}

export function riwayatSistem():any{
  return [
    {
      "title" : "NORMAL",
      "detail" : {
        "label" : "Ya",
        "model" : "normal",
        "value" : [
          {
            "label" : "Nyeri",
            "code" : "NI",
            "model" : "normalNyeri",
          },
          {
            "label" : "Neurologi",
            "code" : "NI",
            "model" : "normalNeurologi",
          },
          {
            "label" : "Kardiovaskular",
            "code" : "KR",
            "model" : "normalKardiovaskular",
          },
          {
            "label" : "Respirasi",
            "code" : "RI",
            "model" : "normalRespirasi",
          },
          {
            "label" : "Saluran pencernaan",
            "code" : "SP",
            "model" : "normalSaluranPen",
          },
          {
            "label" : "Ginjal dan saluran kemih",
            "code" : "GSK",
            "model" : "normalSaluranGK",
          },
          {
            "label" : "Endokrin/metabolik",
            "code" : "EM",
            "model" : "normalEndokrin",
          },
          {
            "label" : "Kemungkinan hamil",
            "code" : "KH",
            "model" : "normalHamil",
          },
        ]
      }
    },
    {
      "title" : "ABNORMAL",
      "detail" : {
        "label" : "Tidak",
        "model" : "abnormal",
        "value" : [
          {
            "label" : "Nyeri",
            "code" : "NI",
            "model" : "abnormalNyeri",
          },
          {
            "label" : "Neurologi",
            "code" : "NI",
            "model" : "abnormalNeurologi",
          },
          {
            "label" : "Kardiovaskular",
            "code" : "KR",
            "model" : "abnormalKardiovaskular",
          },
          {
            "label" : "Respirasi",
            "code" : "RI",
            "model" : "abnormalRespirasi",
          },
          {
            "label" : "Saluran pencernaan",
            "code" : "SP",
            "model" : "abnormalSaluranPen",
          },
          {
            "label" : "Ginjal dan saluran kemih",
            "code" : "GSK",
            "model" : "abnormalSaluranGK",
          },
          {
            "label" : "Endokrin/metabolik",
            "code" : "EM",
            "model" : "abnormalEndokrin",
          },
          {
            "label" : "Kemungkinan hamil",
            "code" : "KH",
            "model" : "abnormalHamil",
          },
        ]
      }
    }
  ]
}

export function pemeriksaanFisik():any{
  return [
    {
      "title" : "NORMAL",
      "detail" : {
        "label" : "Ya",
        "model" : "pemrksFisik_N",
        "value" : [
          {
            "label" : "Jantung",
            "code" : "NI",
            "model" : "pmrksFisikNyeri_N",
          },
          {
            "label" : "Paru",
            "code" : "NI",
            "model" : "pmrksFisikNeurologi_N",
          },
          {
            "label" : "Abdomen",
            "code" : "KR",
            "model" : "pmrksFisikKardiovaskular_N",
          },
          {
            "label" : "Kesadaran",
            "code" : "RI",
            "model" : "pmrksFisikRespirasi_N",
          },
          {
            "label" : "Lain-lain",
            "code" : "SP",
            "model" : "pmrksFisikSaluranPen_N",
          },
        ]
      },
      "optional" : "ketPemeriksaanFisik_N"
    },
    {
      "title" : "ABNORMAL",
      "detail" : {
        "label" : "Ya",
        "model" : "pemrksFisik_A",
        "value" : [
          {
            "label" : "Jantung",
            "code" : "NI",
            "model" : "pmrksFisikNyeri_A",
          },
          {
            "label" : "Paru",
            "code" : "NI",
            "model" : "pmrksFisikNeurologi_A",
          },
          {
            "label" : "Abdomen",
            "code" : "KR",
            "model" : "pmrksFisikKardiovaskular_A",
          },
          {
            "label" : "Kesadaran",
            "code" : "RI",
            "model" : "pmrksFisikRespirasi_A",
          },
          {
            "label" : "Lain-lain",
            "code" : "SP",
            "model" : "pmrksFisikSaluranPen_A",
          },
        ],
      },
      "optional" : "ketPemeriksaanFisik_A"
    },
  ]
}
