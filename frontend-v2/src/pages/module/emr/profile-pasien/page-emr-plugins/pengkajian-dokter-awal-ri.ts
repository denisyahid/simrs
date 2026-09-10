export function anamnesis(): any {
  return [
    {
      model: 'KeluhanUtama',
      placeholder: 'Keluhan utama....',
      label: 'Keluhan utama',
      type: 'textarea',
      column: 10,
    },
    {
      model: 'riwayatPenyakitSekarang',
      placeholder: 'Riwayat penyakit sekarang....',
      label: 'Riwayat penyakit sekarang',
      type: 'textarea',
      column: 10,
    },
    {
      model: 'riwayatAlergi',
      placeholder: 'Riwayat Alergi....',
      label: 'Riwayat Alergi',
      type: 'textarea',
      column: 10,
    },
  ]
}

export function pengkajianUmum(): any {
  return [
    {
      model: 'KeluhanUtama',
      placeholder: 'Keluhan utama....',
      label: 'Keluhan utama',
      type: 'textarea',
      column: 10,
    },
    {
      model: 'riwayatPenyakitDahulu',
      placeholder: 'Riwayat penyakit dahulu....',
      label: 'Riwayat penyakit dahulu',
      type: 'textarea',
      column: 10,
    },
    {
      model: 'riwayatKesehatanSekarang',
      placeholder: 'Riwayat kesehatan sekarang....',
      label: 'Riwayat kesehatan sekarang',
      type: 'textarea',
      column: 10,
    },
    {
      model: 'riwayatAlergi',
      placeholder: 'Riwayat alergi....',
      label: 'Riwayat alergi',
      type: 'textarea',
      column: 10,
    },
    {
      model: 'riwayatObatYangDiminum',
      placeholder: 'Riwayat obat yang diminum....',
      label: 'Riwayat obat yang diminum',
      type: 'textarea',
      column: 10,
    },
    {
      label: 'Status gizi',
      type: 'checkbox',
      children: [
        {
          label: 'Gizi Kurang / Buruk',
          value: 'Gizi Kurang / Buruk',
          model: 'statusGizi',
        },
        {
          label: 'Gizi cukup',
          value: 'Gizi cukup',
          model: 'statusGizi',
        },
        {
          label: 'Gizi Lebih',
          value: 'Gizi Lebih',
          model: 'statusGizi',
        },
      ],
    },
  ]
}

export function KeadaanUmum(): any {
  return [
    {
      label: 'Sehat',
      value: 'Sehat',
      model: 'KeadaanUmum',
    },
    {
      label: 'Sakit Ringan',
      value: 'Sakit Ringan',
      model: 'KeadaanUmum',
    },
    {
      label: 'Sakit Sedang',
      value: 'Sakit Sedang',
      model: 'KeadaanUmum',
    },
  ]
}

export function Kesadaran(): any {
  return [
    {
      label: 'CM',
      value: 'CM',
      model: 'Kesadaran',
    },
    {
      label: 'Apatis',
      value: 'Apatis',
      model: 'Kesadaran',
    },
    {
      label: 'Somnolen',
      value: 'Somnolen',
      model: 'Kesadaran',
    },
    {
      label: 'Sopor',
      value: 'Sopor',
      model: 'Kesadaran',
    },
    {
      label: 'Koma',
      value: 'Koma',
      model: 'Kesadaran',
    },
  ]
}

export function kepala(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'kepala',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'kepala',
    },
  ]
}

export function mata(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'mata',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'mata',
    },
  ]
}

export function hidung(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'hidung',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'hidung',
    },
  ]
}

export function gigiMulut(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'gigiMulut',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'gigiMulut',
    },
  ]
}

export function tenggorokan(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'tenggorokan',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'tenggorokan',
    },
  ]
}

export function telinga(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'telinga',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'telinga',
    },
  ]
}

export function thoraks(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'thoraks',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'thoraks',
    },
  ]
}

export function paru(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'paru',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'paru',
    },
  ]
}

export function abdomen(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'abdomen',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'abdomen',
    },
  ]
}

export function genitaliaAnus(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'genitaliaAnus',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'genitaliaAnus',
    },
  ]
}

export function ekstremitas(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'ekstremitas',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'ekstremitas',
    },
  ]
}

export function kulit(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'kulit',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'kulit',
    },
  ]
}

export function jantung(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'jantung',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'jantung',
    },
  ]
}

export function leher(): any {
  return [
    {
      label: 'Normal',
      value: 'Normal',
      model: 'leher',
    },
    {
      label: 'Abnormal',
      value: 'Abnormal',
      model: 'leher',
    },
  ]
}

export function pemeriksaanFisik1(): any {
  return [
    {
      model: 'pemeriksaanFisik',
      placeholder: 'Pemeriksaan Fisik (O)....',
      label: 'Pemeriksaan Fisik (O)',
      type: 'textarea',
      column: 4,
    },
    {
      model: 'diagnosis',
      placeholder: 'Diagnosis ( A )....',
      label: 'Diagnosis ( A )',
      type: 'textarea',
      column: 4,
    },
    {
      model: 'recanaTerapi',
      placeholder: 'Rencana Terapi ( P )....',
      label: 'Rencana Terapi ( P )',
      type: 'textarea',
      column: 4,
    },
    {
      model: 'rencanaPemeriksaanPenunjang',
      placeholder: 'Rencana pemeriksaan penunjang....',
      label: 'Rencana pemeriksaan penunjang',
      type: 'textarea',
      column: 8,
    },
  ]
}
