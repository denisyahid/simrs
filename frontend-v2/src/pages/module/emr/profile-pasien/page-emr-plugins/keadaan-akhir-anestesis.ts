export function keadaanAkhirAnestesis(): any {
  return [
    {
      judul: 'Keadaan Akhir Anestesis',
      desc: [
        {
          label: 'Tekanan Darah',
          modal: 'tekananDarah',
        },
        {
          label: 'Nadi',
          modal: 'nadi',
        },
        {
          label: 'Respirasi',
          modal: 'respirasi',
        },
        {
          label: 'Suhu',
          modal: 'suhu',
        },
        {
          label: 'SpO2',
          modal: 'spO2',
        },
        {
          label: 'Refleks Menelan',
          modal: 'refleksMenelan',
        },
        {
          label: 'Kesadaran',
          modal: 'kesadaran',
        },
        {
          label: 'Lain lain',
          modal: 'lainLain',
        },
      ],
    },
  ]
}

export function rangkumanPenggunaan(): any {
  return [
    {
      title: 'Rangkuman Penggunaan Obat, Input dan Output Cairan',
      desc: [
        {
          sub_title: 'Obat',
          value: [
            {
              label: 'Fetanyl',
              model: 'fetanyl',
              satuan: 'mcg',
            },
            {
              label: 'Ketamine',
              model: 'ketamine',
              satuan: 'mg',
            },
            {
              label: 'Dexketoprofen',
              model: 'dexketoprofen',
              satuan: 'mg',
            },
            {
              label: 'Propofol',
              model: 'propofol',
              satuan: 'mg',
            },
            {
              label: 'Lidacaine',
              model: 'lidacaine',
              satuan: 'mg',
            },
            {
              label: 'Ephedrine',
              model: 'ephedrine',
              satuan: 'mg',
            },
            {
              label: 'Midazalam',
              model: 'midazalam',
              satuan: 'mg',
            },
            {
              label: 'Meperdine',
              model: 'meperdine',
              satuan: 'mg',
            },
            {
              head_label: 'Regional Anastesia :',
              label: 'Bupivacaine',
              model: 'bupivacaine',
              satuan: 'mg',
            },
            {
              label: 'Recuronium',
              model: 'recuronium',
              satuan: 'mg',
            },
            {
              label: 'Dexametasone',
              model: 'dexametasone',
              satuan: 'mg',
            },
            {
              label: 'Morphine',
              model: 'morphine',
              satuan: 'mg',
            },
            {
              label: 'Atracurlum',
              model: 'atracurlum',
              satuan: 'mg',
            },
            {
              label: 'Diphenhydramine',
              model: 'diphenhydramine',
              satuan: 'mg',
            },
            {
              label: 'Udocaine',
              model: 'udocaine',
              satuan: 'mg',
            },
            {
              label: 'Odansetron',
              model: 'odansetron',
              satuan: 'mg',
            },
            {
              label: 'Ketorolac',
              model: 'ketorolac',
              satuan: 'mg',
            },
            {
              head_label: 'Obat-obat lain :',
              label: 'Oxytocin',
              model: 'oxytocin',
              satuan: 'IU',
            },
            {
              label: 'Pantoprazole',
              model: 'pantoprazole',
              satuan: 'mg',
            },
            {
              label: 'Paracetamol',
              model: 'paracetamol',
              satuan: 'mg',
            },
            {
              label: 'Methylengometrine',
              model: 'methylengometrine',
              satuan: 'mg',
            },
            {
              label: 'Ranitidin',
              model: 'ranitidin',
              satuan: 'mg',
            },
            {
              label: 'Ketoprofen',
              model: 'ketoprofen',
              satuan: 'mg',
            },
            {
              label: 'Metamizole',
              model: 'metamizole',
              satuan: 'mg',
            },
          ],
        },
      ],
    },
  ]
}

export function obatInput(): any {
  return [
    {
      title: 'Input :',
      desc: [
        {
          label: 'Kristaloid',
          model: 'kristaloid',
        },
        {
          label: 'Koloid',
          model: 'koloid',
        },
        {
          label: 'Komponen darah',
          model: 'komponenDarah',
        },
      ],
    },
  ]
}

export function obatOutput(): any {
  return [
    {
      title: 'Output :',
      desc: [
        {
          label: 'Darah',
          model: 'darah',
        },
        {
          label: 'Urine',
          model: 'urine',
        },
        {
          label: 'Lain-lain',
          model: 'lainLain',
        },
      ],
    },
  ]
}

export function intruksiPascaBedah(): any {
  return [
    {
      title: 'Intruksi Pasca Bedah',
      desc: [
        {
          label: 'Rawat Ruangan',
          model: 'rawatRuangan',
        },
        {
          label: 'ICU',
          model: 'icu',
        },
        {
          label: 'HCU',
          model: 'hcu',
        },
        {
          label: 'ODS',
          model: 'ods',
        },
        {
          label: 'RR',
          model: 'rr',
        },
        {
          label: 'Oxygen',
          model: 'oxygen',
        },
        {
          label: 'Infus',
          model: 'infus',
        },
        {
          head_label: 'Diet',
          label: 'Boleh makan seperti biasa setelah sadar penuh',
          model: 'bolehMakan',
        },
        {
          label: 'Sesuai instruksi dari dr.',
          model_box: 'sesuaiInstruksiDr',
          model: 'sesuaiIntruksiDrDesc',
        },
        {
          label:
            'Puasa sementara sampai ada intruksi selanjutnya, dan untuk penetral nutrisi dapat diberikan',
          model_box: 'puasaSementara',
          model: 'puasaSementaraDesc',
        },
        {
          label: 'Analgesik',
          model: 'analgesik',
        },
        {
          label: 'Obat-obat lain',
          model: 'obatObatLain',
        },
        {
          head_label: 'Posisi',
          label: 'Mobilitas bebas setelah sadar penuh',
          model: 'mobilitasSadar',
        },
        {
          label: 'Supine (flat on bed) dengan 1 bantal sampai jam ',
          model_box: 'supineJamBox',
          model: 'supineJam',
        },
        {
          label: 'Supine dengan posisi head up 30 sampai ada intruksi selanjutnya',
          model: 'supineHeadUp',
        },
        {
          label: 'Posisi head up 30 + leteral decubitus (posisi lonsillectomy)',
          model: 'leteralDecubitus',
        },
        {
          label: 'Posisi lainnya',
          model_box: 'posisiLainnyaBox',
          model: 'posisiLainnya',
        },
        {
          head_label: 'Khusus',
          label: 'Boleh pulang setelah sadar penuh dan didampingi orang dewasa',
          model: 'bolehPulangSadar',
        },
        {
          label:
            'Perhatikan tanda - tanda vital nyeri, mual dan muntah, gatal apabila ada keluhan seperti yang disebutkan lapor',
          model: 'tanda2Vital',
        },
        {
          label: 'Pemeriksaan laboratorium pasca operasi',
          model_box: 'pemeriksaanLabBox',
          model: 'pemeriksaanLab',
        },
        {
          label: 'Lain - lain',
          model: 'lainLain',
        },
        {
          label: 'Bandung,',
          model: 'jam',
        },
      ],
    },
  ]
}
