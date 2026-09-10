export function antisipasiKondisi(): any {
  return [
    {
      label: 'Dokter Pelaksana tindakan',
      model: 'dokterPelaksanaTindakan',
    },
    {
      label: 'Pemberi Informasi',
      model: 'permberiInformasi',
    },
    {
      label: 'Penerima  / Pemberi Informasi',
      model: 'PenerimaAtauPemberiInformasi',
    },
  ]
}

export function daftarInformasi(): any {
  return [
    {
      no: 1,
      Kriteria: 'Diagnosa (WD & DD)',
      keterangan: [
        {
          model: 'isiDiagnosa',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'AdaDiagnosa',
        },
      ],
    },
    {
      no: 2,
      Kriteria: 'Dasar Diagnosa',
      keterangan: [
        {
          model: 'isiDasarDiagnosis',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaDasarDiagnosa',
        },
      ],
    },
    {
      no: 3,
      Kriteria: 'Tindakan Kedokteran',
      keterangan: [
        {
          model: 'isiKedokteran',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaKedokteran',
        },
      ],
    },
    {
      no: 4,
      Kriteria: 'Indikasi Tindakan',
      keterangan: [
        {
          model: 'isiIndikasi',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaIndikasi',
        },
      ],
    },
    {
      no: 5,
      Kriteria: 'Tata Cara',
      keterangan: [
        {
          model: 'isiTatacara',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaTataCara',
        },
      ],
    },
    {
      no: 6,
      Kriteria: 'Tujuan',
      keterangan: [
        {
          model: 'isiTujuan',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaTujuan',
        },
      ],
    },
    {
      no: 7,
      Kriteria: 'Risiko',
      keterangan: [
        {
          model: 'isiRisiko',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaRisiko',
        },
      ],
    },
    {
      no: 8,
      Kriteria: 'Komplikasi',
      keterangan: [
        {
          model: 'isiKomplikasi',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaKomplikasi',
        },
      ],
    },
    {
      no: 9,
      Kriteria: 'Prognosis',
      keterangan: [
        {
          model: 'isiPrognosis',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaPrognosis',
        },
      ],
    },
    {
      no: 10,
      Kriteria: 'Alternatif & Risiko',
      keterangan: [
        {
          model: 'isiAlternatif',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaAlternatif',
        },
      ],
    },
    {
      no: 11,
      Kriteria: 'Management Nyeri Pada Anestesi / Sedasi',
      keterangan: [
        {
          model: 'isiNyeri',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaNyeri',
        },
      ],
    },
    {
      no: 12,
      Kriteria: 'Hal Yang dilakukan untuk menyelamatkan pasien',
      keterangan: [
        {
          model: 'isiMenyelamatkanPasien',
          type: 'textArea',
        },
      ],
      pilihan: [
        {
          label: 'Ada',
          model: 'adaMenyelamatkanPasien',
        },
      ],
    },
  ]
}
