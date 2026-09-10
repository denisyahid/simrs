export function informasi(): any {
  return [
    {
      label: 'Bahasa',
      children: [
        {
          label: 'Indonesia',
          model: 'bahasa',
          value: 'indonesia',
          type: 'checkbox',
        },
        {
          label: 'Inggris',
          model: 'bahasa',
          value: 'inggris',
          type: 'checkbox',
        },
        {
          label: 'Daerah',
          model: 'bahasa',
          value: 'daerah',
          type: 'checkbox',
        },
        {
          model: 'bahasaDaerah',
          type: 'input',
          placeholder: 'Bahasa daerah..',
        },
        {
          label: 'Lain-lain',
          model: 'bahasa',
          value: 'lain-lain',
          type: 'checkbox',
        },
        {
          model: 'bahasaLain',
          type: 'input',
          placeholder: 'Bahasa lain..',
        },
      ],
    },
    {
      label: 'Kebutuhan Penerjemah',
      children: [
        {
          label: 'Ya',
          model: 'kebutuhanPenerjemah',
          value: 'ya',
          type: 'checkbox',
        },
        {
          label: 'Tidak',
          model: 'kebutuhanPenerjemah',
          value: 'tidak',
          type: 'checkbox',
        },
      ],
    },
    {
      label: 'Pendidikan pasien',
      children: [
        {
          label: 'SD',
          model: 'pendidikanPasien',
          value: 'sd',
          type: 'checkbox',
        },
        {
          label: 'SLTP',
          model: 'pendidikanPasien',
          value: 'sltp',
          type: 'checkbox',
        },
        {
          label: 'SLTA',
          model: 'pendidikanPasien',
          value: 'slta',
          type: 'checkbox',
        },
        {
          label: 'S-1',
          model: 'pendidikanPasien',
          value: 's1',
          type: 'checkbox',
        },
        {
          label: 'Lain-lain',
          model: 'pendidikanPasien',
          value: 'lain-lain',
          type: 'checkbox',
        },
        {
          model: 'pendidikanLainnya',
          type: 'input',
          placeholder: 'Pendidiakan lain..',
        },
      ],
    },
    {
      label: 'Baca dan tulis',
      children: [
        {
          label: 'Baik',
          model: 'bacaDanTulis',
          value: 'baik',
          type: 'checkbox',
        },
        {
          label: 'Kurang',
          model: 'bacaDanTulis',
          value: 'kurang',
          type: 'checkbox',
        },
      ],
    },
    {
      label: 'Pilih tipe pembelajaran',
      children: [
        {
          label: 'Tidak ada',
          model: 'tipePemebelajaran',
          value: 'tidakAda',
          type: 'checkbox',
        },
        {
          label: 'Bahasa',
          model: 'tipePemebelajaran',
          value: 'bahasa',
          type: 'checkbox',
        },
        {
          label: 'Kognitif terbatas',
          model: 'tipePemebelajaran',
          value: 'kognitifTerbatas',
          type: 'checkbox',
        },
        {
          label: 'Motivasi Kurang',
          model: 'tipePemebelajaran',
          value: 'motivasiKurang',
          type: 'checkbox',
        },
        {
          label: 'Budaya/Agama/Spiritual',
          model: 'tipePemebelajaran',
          value: 'budaya/agama/spititual',
          type: 'checkbox',
        },
        {
          label: 'Emosional',
          model: 'tipePemebelajaran',
          value: 'emosional',
          type: 'checkbox',
        },
        {
          label: 'Pendengaran terganggu',
          model: 'tipePemebelajaran',
          value: 'pendengaranTerganggu',
          type: 'checkbox',
        },
        {
          label: 'Gangguan bicara',
          model: 'tipePemebelajaran',
          value: 'gangguanBicara',
          type: 'checkbox',
        },
        {
          label: 'Pengelihatan terganggu',
          model: 'tipePemebelajaran',
          value: 'pengelihatanTerganggu',
          type: 'checkbox',
        },
        {
          label: 'Fisik lemah',
          model: 'tipePemebelajaran',
          value: 'fisikLemah',
          type: 'checkbox',
        },
        {
          label: 'Lain-lain',
          model: 'tipePemebelajaran',
          value: 'lain-lain',
          type: 'checkbox',
        },
      ],
    },
  ]
}
export function detailEdukasi(): any {
  return [
    {
      label: ' 1. Penyakit yang diderita pasien',
      model: 'penyakitPasien',
    },
    {
      label: ' 2. Rencana tindakan / terapi',
      model: 'rencanaTindakanTerapi',
    },
    {
      label: ' 3. Pengobatan dan prosedur yang diberikan atau diperlukan',
      model: 'pengobatanDanProsedurYangDiberikan',
    },
    {
      label:
        ' 4. Hasil pelayanan,termasuk terjadinya kejadian yang diharapkan dan tidak diharapkan',
      model: 'hasilPelayanan',
    },
    {
      label: ' 5. Tatalaksana covid',
      model: 'tatalaksanaCovid',
    },
    {
      label: ' a. Hasil pemeriksaan',
      model: 'hasilPemeriksaan',
    },
    {
      label: ' b. Rencanakan tindakan dan pengobatan',
      model: 'hasilPemeriksaan',
      children: [
        {
          label: 'isolasi',
          value: 'isolasi',
          type: 'checkbox',
        },
        {
          label: 'mandiri/isolasi',
          value: 'mandiri/isolasi',
          type: 'checkbox',
        },
        {
          label: 'di RS/karantina',
          value: 'di RS/karantina',
          type: 'checkbox',
        },
      ],
    },
  ]
}

export function metodeEdukasi(): any{
  return [
    {
      label: 'Diskusi (discussion)',
      value: 'Diskusi'
    },
    {
      label: 'Demonstrasi (demo)',
      value: 'Demonstrasi'
    },
    {
      label: 'Ceramah (lecture)',
      value: 'Ceramah'
    },
    {
      label: 'Praktek langsung (direct pratice)',
      value: 'Praktek langsung'
    },
    {
      label: 'Audio visual',
      value: 'Audio visual'
    },
    {
      label: 'Lembar balik',
      value: 'Lembar balik'
    },

    {
      label: 'Booklet',
      value: 'Booklet'
    },
    {
      label: 'Leaflet',
      value: 'Leaflet'
    },
    {
      label: 'Lainnya',
      value: 'Lainnya'
    },
  ]
}
