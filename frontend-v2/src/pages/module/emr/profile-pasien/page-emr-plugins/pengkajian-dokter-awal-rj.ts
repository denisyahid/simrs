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
export function pemeriksaanFisik(): any {
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
