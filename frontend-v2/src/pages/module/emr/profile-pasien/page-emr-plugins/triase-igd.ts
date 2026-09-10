export function PasienDewasa() {
  return [
    {
      label: 'Jalan nafas',
      children: [
        [
          {
            label: 'Sumbatan',
            model: 'jalanNafas',
            value: 'Sumbatan',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Bebas',
            model: 'jalanNafas',
            value: 'BebasUrgent',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Bebas',
            model: 'jalanNafas',
            value: 'BebasNonUrgent',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Tekanan Darah',
            model: 'tandaVitalPernafasa',
            value: 'Tekanan Darah',
            type: 'input',
          },
        ],
      ],
    },
    {
      label: 'Pernafasan',
      children: [
        [
          {
            label: 'Henti nafas',
            model: 'hentiNafas',
            value: 'Henti Nafas',
            type: 'checkbox',
          },
          {
            label: 'Frekuensi nafas< 10',
            model: 'frekuensiNafas10',
            value: 'Frekuensi nafas< 10',
            type: 'checkbox',
          },
          {
            label: 'sianosis',
            model: 'sianosis',
            value: 'sianosis',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frek. nafas >22-32',
            model: 'freknafas22',
            value: 'Frek. nafas >22-32',
            type: 'checkbox',
          },
          {
            label: 'Mengi',
            model: 'Mengi',
            value: 'Mengi',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frek. nafas >20-24',
            model: 'Pernafasan',
            value: 'frekNafasLebih20',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frekuensi nadi',
            model: 'frekuensiNadi',
            value: 'Frekuensi nadi',
            type: 'input',
          },
          {
            label: 'Frekuensi nafas',
            model: 'frekuensiNafas',
            value: 'frekuensiNadi',
            type: 'input',
          },
        ],
      ],
    },
    {
      label: 'Sirkulasi',
      children: [
        [
          {
            label: 'Henti jantung',
            model: 'hentiJantung',
            value: 'Henti jantung',
            type: 'checkbox',
          },
          {
            label: 'Nadi tidak teraba',
            model: 'nadiTidakTeraba',
            value: 'Nadi tidak teraba',
            type: 'checkbox',
          },
          {
            label: 'Pucat',
            model: 'pucar',
            value: 'Pucat',
            type: 'checkbox',
          },
          {
            label: 'Akral dingin',
            model: 'akralDingin',
            value: 'Akral dingin',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frek. nadi 120-150',
            model: 'frekNadi120',
            value: 'Frek. nadi 120-150',
            type: 'checkbox',
          },
          {
            label: 'TD sistol >160',
            model: 'tdSitol',
            value: 'TD sistol >160',
            type: 'checkbox',
          },
          {
            label: 'TD Diastol >100',
            model: 'tdDiastol',
            value: 'TD Diastol >100',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Frek. nadi 100-120',
            model: 'frekNadi',
            value: 'Frek. nadi 100-120',
            type: 'checkbox',
          },
          {
            label: 'TD sistol ≥120-140',
            model: 'tdSistol120',
            value: 'TD sistol ≥120-140',
            type: 'checkbox',
          },
          {
            label: 'TD Diastol ≥80-100',
            model: 'sikulasi',
            value: 'TD Diastol ≥80-100',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'Suhu',
            model: 'sikulasiSuhu',
            value: 'Suhu',
            type: 'input',
          },
          {
            label: 'Riwayat alergi',
            model: 'sikulasiRiwayatAlergi',
            value: 'Riwayat alergi',
            type: 'input',
          },
          {
            label: 'Makanan',
            model: 'sikulasiMakanan',
            value: 'Makanan',
            type: 'input',
          },
          {
            label: 'Lain-lain',
            model: 'sikulasiLainLain',
            value: 'Lain-lain',
            type: 'input',
          },
        ],
      ],
    },
    {
      label: 'Kesadaran',
      children: [
        [
          {
            label: 'GCS < 9',
            model: 'Kesadaran',
            value: 'GCS < 9',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'GCS >12',
            model: 'Kesadaran',
            value: 'GCS >12',
            type: 'checkbox',
          },
        ],
        [
          {
            label: 'GCS 15',
            model: 'Kesadaran',
            value: 'GCS 15',
            type: 'checkbox',
          },
        ],
      ],
    },
  ]
}

export function PasienBayi() {
  return [
    {
      label: 'Koma',
      checkbox: true,
      model: 'koma',
      children: [
        [
          {
            label: 'Terdapat tanda prioritas:',
            model: 'sikulasi',
            value: 'Henti jantung',
            type: 'input',
          },
        ],
        [
          {
            label: 'Tidak ada tanda gawat darurat',
            model: 'sikulasi',
            value: 'Henti jantung',
            type: 'checkbox',
          },
        ],
      ],
    },
    {
      label: 'Kejang',
      checkbox: false,
    },
  ]
}
