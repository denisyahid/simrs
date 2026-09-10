export function tujuanTranfer():any{
  return [
    {
      "label" : "Alih Rawat",
      "model" : "tujuan",
    },
    {
      "label" : "Pemeriksaan Penunjang",
      "model" : "tujuan",
    },
    {
      "label" : "Tindakan/Operasi",
      "model" : "tujuan"
    },
    {
      "label" : "Lain-lain",
      "model" : "tujuan"
    },
  ]
}

export function pemeriksaanDiagnostik():any{
  return [
    {
      "label" : "Rontgen Thoraks",
      "model" : "pemeriksaanDiag",
    },
    {
      "label" : "CT Scan",
      "model" : "pemeriksaanDiag",
    },
    {
      "label" : "USG",
      "model" : "pemeriksaanDiag",
    },
    {
      "label" : "ECG",
      "model" : "pemeriksaanDiag",
    },
    {
      "label" : "Lainnya",
      "model" : "pemeriksaanDiag",
    },
  ]
}

export function dataTransfer():any{
  return [
    {
      "label" : "Diagnosis",
      "model" : "diagnosis"
    },
    {
      "label" : "Prosedur yang Dilakukan",
      "model" : "prosedur"
    },
    {
      "label" : "Obat yang diberikan",
      "model" : "obatDiberikan"
    },
  ]
}

export function kondisiSaatTransfer():any{
  return [
    {
      "label" : "Kesadaran",
      "model" : "kesadaran",
      "type" : "textarea",
    },
    {
      "label" : "Tensi",
      "model" : "tensi",
      "type" : "textBox"
    },
    {
      "label" : "Nadi",
      "model" : "nadi",
      "type" : "textBox",
    },
    {
      "label" : "Respirasi",
      "model" : "respirasi",
      "type" : "textBox"
    },
    {
      "label" : "Suhu",
      "model" : "suhu",
      "type" : "textBox"
    },
    {
      "label" : "SPO2",
      "model" : "spo2",
      "type" : "textBox"
    },
    {
      "label" : "Skala Nyeri",
      "model" : "skalaNyeri",
      "type" : "textarea",
    },
    {
      "label" : "Lain-lain",
      "model" : "kondisiLain",
      "type" : "textarea",
    },
    {
      "label" : "Rekomendasi",
      "model" : "rekomendasi",
      "type" : "textarea",
    },
  ]
}

export function peralatanTerpasang():any{
  return [
    {
      "title" : "Airways",
      "value" : [
        {
          "label" : "Tanpa alat bantu",
          "model" : "airways_1"
        },
        {
          "label" : "Orofaringeal airway",
          "model" : "airways_2",
        },
        {
          "label" : "Nasofaringeal airway",
          "model" : "airways_3"
        },
        {
          "label" : "Laryngeal mask airway",
          "model" : "airways_4",
        },
        {
          "label" : "Endotracheal tube",
          "model" : "airways_5"
        },
        {
          "label" : "Tracheostomi",
          "model" : "airways_6",
        },
        {
          "label" : "Lainnya",
          "model" : "airways_7",
        },
      ],
      "optional" : {
        "model" : "ketAirways"
      }
    },

    {
      "title" : "Breathing",
      "value" : [
        {
          "label" : "Nafas Spontan",
          "model" : "breathing_1"
        },
        {
          "label" : "Binasal kanul",
          "model" : "breathing_2",
        },
        {
          "label" : "Non rebreathing mask",
          "model" : "breathing_3"
        },
        {
          "label" : "Simple mask",
          "model" : "breathing_4",
        },
        {
          "label" : "Jackson Rees",
          "model" : "breathing_5",
        },
        {
          "label" : "Lainnya",
          "model" : "breathing_6",
        },
      ],
      "optional" : {
        "model" : "ketBreathing"
      }
    },

    {
      "title" : "Lain-lain",
      "value" : [
        {
          "label" : "Folley Kateter",
          "model" : "lainnya_1"
        },
        {
          "label" : "Thorakostomi tube",
          "model" : "lainnya_2",
        },
        {
          "label" : "Nasogatric tube/orogastric tube",
          "model" : "lainnya_3"
        },
        {
          "label" : "Lainnya",
          "model" : "lainnya_6",
        },
      ],
      "optional" : {
        "model" : "ketLain"
      }
    },
    {
      "title" : "Derajat Keparahan Pasien",
      "value" : [
        {
          "label" : "Derajat 0",
          "model" : "derajat"
        },
        {
          "label" : "Derajat 1",
          "model" : "derajat",
        },
        {
          "label" : "Derajat 2",
          "model" : "derajat"
        },
        {
          "label" : "Derajat 3",
          "model" : "derajat",
        },
      ]
    },
  ]
}
