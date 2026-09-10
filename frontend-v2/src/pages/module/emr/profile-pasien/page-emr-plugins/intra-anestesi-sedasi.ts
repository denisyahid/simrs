export function teknikAnestesia(): any {
  return [
    {
      "model": "TeknikAnestesi_1",
      "label": "Anestesia umum (kolom A diisi)"
    },
    {
      "model": "TeknikAnestesi_2",
      "label": "Kombinasi umum dan regional (kolom A dan B diisi)"
    },
    {
      "model": "TeknikAnestesi_3",
      "label": "Anestesia regional (kolom B diisi)"
    },
  ]
}

export function tataLaksana(): any {
  return [
    {
      "title": "Bilah",
      "value": [
        {
          "model": "tataBilah",
          "type": "textBox"
        },
      ]
    },
    {
      "title": "Glottic",
      "value": [
        {
          "subTitle": "Cormack",
          "model": "glottic",
          "type": "textBox"
        }
      ]
    },
    {
      "title": "ETT/LMA/NTT No",
      "value": [
        {
          "model": "tataETT",
          "type": "textBox"
        }
      ]
    },
    {
      "title": "Cuff",
      "value": [
        {
          "label": "Ya",
          "model": "cuff",
          "type": "checkBox"
        },
        {
          "label": "Tidak",
          "model": "cuff",
          "type": "checkBox"
        },
        {
          "model": "ketCuff",
          "type": "textBox"
        }
      ]
    },
    {
      "title": "Suara Nafas",
      "value": [
        {
          "model": "tataSuaraNafas",
          "type": "textBox"
        }
      ]
    },
    {
      "title": "Sulit Intubasi",
      "value": [
        {
          "label": "Ya",
          "model": "sulitIntubasi",
          "type": "checkBox"
        },
        {
          "label": "Tidak",
          "model": "sulitIntubasi",
          "type": "checkBox"
        },
        {
          "model": "ketSulitIntubasi",
          "type": "textBox"
        }
      ]
    },
    {
      "title": "Sulit Ventilasi",
      "value": [
        {
          "label": "Ya",
          "model": "sulitVentilasi",
          "type": "checkBox"
        },
        {
          "label": "Tidak",
          "model": "sulitVentilasi",
          "type": "checkBox"
        },
        {
          "model": "ketSulitVentilasi",
          "type": "textBox"
        }
      ]
    },
  ]
}

export function teknikRegional(): any {
  return [
    {
      "title": "Jenis",
      "detail": [
        {
          "label": "Sulit",
          "model": "isSulit",
        }, {
          "label": "Tidak",
          "model": "isSulit",
        }
      ],
      "optional": [{
        "model": "jenisRegional",
        "type": "textBox",
      }]
    },
    {
      "title": "Lokasi",
      "optional": [{
        "label": "Vert",
        "model": "lokasiRegional"
      }]
    },
    {
      "title": "Jenis jarum / No",
      "optional": [{
        "model": "lokasiRegional"
      }]
    },
    {
      "title": "Kateter",
      "detail": [
        {
          "label": "Ya",
          "model": "isKateter"
        },
        {
          "label": "Tidak",
          "model": "isKateter"
        },
      ],
      "optional": [
        {
          "label" : "Kulit",
          "placeholder" : "Cm",
          "model": "kateterKulit"
        },
        {
          "label" : "Ruang epidural",
          "placeholder" : "Cm",
          "model": "kateterKulit"
        },
      ]
    },
    {
      "title" : "Blok level",
      "optional" : [
        {
          "model": "blokLevel"
        }
      ]
    }
  ]
}

export function ventilator():any{
  return [
    {
      "title" : "Ventilasi",
      "value" : [
        {
          "label" : "Spontan",
          "model" : "ventilasi_1",
        },
        {
          "label" : "Assisted",
          "model" : "ventilasi_2",
        },
        {
          "label" : "Controlled",
          "model" : "ventilasi_3",
        },
      ]
    },
    {
      "title" : "Ventilator",
      "value" : [
        {
          "label" : "Ya",
          "model" : "ventilator_1",
        },
        {
          "label" : "Tidak",
          "model" : "ventilator_2",
        },
      ]
    },
    {
      "title" : "Setting Ventilator",
      "value" : [
        {
          "label" : "PCV",
          "model" : "settingVentilator_1",
        },
        {
          "label" : "CMV",
          "model" : "settingVentilator_2",
        },
        {
          "label" : "PSV",
          "model" : "settingVentilator_3",
        },
      ]
    },
  ]
}

export function more():any{
  return [
    {
      "label" : "PR",
      "model" : "pr"
    },
    {
      "label" : "TV",
      "model" : "tv"
    },
    {
      "label" : "Pinsp",
      "model" : "pinsp"
    },
    {
      "label" : "Psupp",
      "model" : "psupp"
    },
    {
      "label" : "PEEP",
      "model" : "peep"
    },
    {
      "label" : "IE",
      "model" : "ie"
    },
    {
      "label" : "FiO2",
      "model" : "fiO2"
    },
  ]
}
