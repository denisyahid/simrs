export function kesadaran(): any {
  return [
    {
      title: 'Kesadaran',
      value: [
        {
          subTitle: 'Compos Mentis',
          model: 'kesadaran_',
          type: 'checkBox',
        },
        {
          subTitle: 'Sonmolen',
          model: 'kesadaran_',
          type: 'checkBox',
        },
        {
          subTitle: 'Soporkoma',
          model: 'kesadaran_',
          type: 'checkBox',
        },
        {
          subTitle: 'Koma',
          model: 'kesadaran_',
          type: 'checkBox',
        },
      ],
    },
  ]
}
export function tandaVital(): any {
  return [
    {
      title: '',
      value: [
        {
          subTitle: 'NIBP',
          model: 'tekananDarah',
          type: 'textfiled',
          satuan: 'mmHg',
        },
        {
          subTitle: 'HR',
          model: 'nadi',
          type: 'textfiled',
          satuan: 'x/menit',
        },

        {
          subTitle: 'RR',
          model: 'pernapasan',
          type: 'textfiled',
          satuan: 'x/menit',
        },
        {
          subTitle: 'Saturasi',
          model: 'saturasi',
          type: 'textfiled',
          satuan: '%',
        },
        {
          subTitle: 'Irama EKG',
          model: 'ekg',
          type: 'textbox',
          satuan: '',
        },
      ],
    },
  ]
}
export function terapiOksigen(): any {
  return [
    {
      title: 'Terapi Oksigen',
      value: [
        {
          subTitle: 'Binasal',
          model: 'terapiOksigen_',
          type: 'checkBox',
        },
        {
          subTitle: '',
          model: 'ketterapiOksigen_',
          type: 'textField',
          satuan: 'Lpm',
        },
        {
          subTitle: 'Simple Mask / NRM / RM *)',
          model: 'terapiOksigen_',
          type: 'checkBox',
        },
        {
          subTitle: '',
          model: 'ketterapiOksigen_',
          type: 'textField',
          satuan: 'Lpm',
        },
        {
          subTitle: 'Ventilator Mekanik',
          model: 'terapiOksigen_',
          type: 'checkBox',
        },
      ],
    },
    {
        title: 'Nyeri',
        value: [
          {
            subTitle: 'Tidak Ada',
            model: 'nyeri_',
            type: 'checkBox',
          },
          {
            subTitle: 'Ada, Skor',
            model: 'nyeri_',
            type: 'checkBox',
          },
          {
            subTitle: '',
            model: 'ketnyeri_',
            type: 'textBox',
          },
          {
            subTitle: 'NRS',
            model: 'nyeri_',
            type: 'checkBox',
          },
          {
            subTitle: 'BPSS',
            model: 'nyeri_',
            type: 'checkBox',
          },
          {
            subTitle: 'FLACC',
            model: 'nyeri_',
            type: 'checkBox',
          },
        ]
    },
    {
        title: 'Metode',
        value: [
          {
            subTitle: 'WBF',
            model: 'nyeri_',
            type: 'checkBox',
          },
        ]
    },
    {
        title: 'Lokasi Nyeri',
        value: [
          {
            subTitle: '',
            model: 'lokasiNyeri_',
            type: 'textBox',
          },
        ]
    }
  ]
}
export function risikoJatuh(): any {
    return [
        {
            title: 'Metoda',
            value: [
              {
                subTitle: 'Morse Scale',
                model: 'metoda_',
                type: 'checkBox',
              },
              {
                subTitle: 'Ontario Midified Stratify',
                model: 'metoda_',
                type: 'checkBox',
              },
              {
                subTitle: 'Sidney Scoring',
                model: 'metoda_',
                type: 'checkBox',
              },
            ],
          },
          {
            title: 'Tingkat Resiko',
            value: [
              {
                subTitle: 'Tidak Ada',
                model: 'tingkatResiko_',
                type: 'checkBox',
              },
              {
                subTitle: 'Sedang',
                model: 'tingkatResiko_',
                type: 'checkBox',
              },
              {
                subTitle: 'Ringan',
                model: 'tingkatResiko_',
                type: 'checkBox',
              },
              {
                subTitle: 'Berat',
                model: 'tingkatResiko_',
                type: 'checkBox',
              },
            ],
          },
    ]
}
export function keterangan(): any {
    return [
        {
            "title": "Angiografi",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Angiografi_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Angiografi_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganAngiografi_",
                    "type": "textBox",
                },
            ]
        },
        {
            "title": "Ekokardiografi",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Ekokardiografi_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Ekokardiografi_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganEkokardiografi_",
                    "type": "textBox",
                },
            ]
        },
        {
            "title": "CT Scan Cardiac/Kepala/Peripheral*)",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Cardiac_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Cardiac_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganCardiac_",
                    "type": "textBox",
                },
            ]
        },
        {
            "title": "Treadmill Test",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Treadmill_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Treadmill_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganTreadmill_",
                    "type": "textBox",
                },
            ]
        },
        {
            "title": "Sidik Perfusi Miokard",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Sidik_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Sidik_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganSidik_",
                    "type": "textBox",
                },
            ]
        },
        {
            "title": "Foto Thorax",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "Thorax_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "Thorax_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganThorax_",
                    "type": "textBox",
                },
            ]
        },
        {
            "title": "USG",
            "value": [
                {
                    "subTitle": "Ya",
                    "model": "USG_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "Tidak",
                    "model": "USG_",
                    "type": "checkBox",
                },
                {
                    "subTitle": "",
                    "model": "keteranganUSG_",
                    "type": "textBox",
                },
            ]
        },
    ]
}

