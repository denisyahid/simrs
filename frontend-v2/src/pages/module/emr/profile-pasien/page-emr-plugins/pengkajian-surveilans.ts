export function pengkajianSurveilans(): any {
  return [
    {
      title: 'Status Gizi',
      value: [
        {
          subTitle: 'Baik',
          type: 'checkBox',
          model: 'statusGizi',
          value: 1,
        },
        {
          subTitle: 'Sedang',
          type: 'checkBox',
          model: 'statusGizi',
          value: 2,
        },
        {
          subTitle: 'Buruk',
          type: 'checkBox',
          model: 'statusGizi',
          value: 3,
        },
      ],
    },
    {
      title: 'DM',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'dm',
          value: 1,
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'dm',
          value: 2,
        },
      ],
    },
    {
      title: 'Gula Darah',
      value: [
        {
          subTitle: 'Normal',
          type: 'checkBox',
          model: 'gulaDarah',
          value: 1,
        },
        {
          subTitle: 'Tinggi',
          type: 'checkBox',
          model: 'gulaDarah',
          value: 2,
        },
      ],
    },
    {
      title: 'Merokok',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'merokok',
          value: 1,
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'merokok',
          value: 2,
        },
      ],
    },
    {
      title: 'Obesitas',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'obesitas',
          value: 1,
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'obesitas',
          value: 2,
        },
      ],
    },
    {
      title: 'Pemeriksaan Kultur',
      value: [
        {
          subTitle: 'Pas Luka',
          type: 'checkBox',
          model: 'pemeriksaanKultrur',
          value: 1,
        },
      ],
    },
  ]
}
