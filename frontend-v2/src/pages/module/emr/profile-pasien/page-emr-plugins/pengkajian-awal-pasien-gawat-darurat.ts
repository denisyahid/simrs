export function detailKeperawatan(): any {
  return [
    {
      label: 'Keluhan Utama',
      placeholder: 'Keluhan Utama...',
      model: 'keluahanUtama',
      type: 'text',
    },
    {
      label: 'Mekanisme Cedera',
      placeholder: 'Mekanisme Cedera...',
      model: 'melanismeCedera',
      type: 'text',
    },
    {
      label: 'Medikasi',
      placeholder: 'Medikasi...',
      model: 'medikasi',
      type: 'text',
    },
    {
      label: 'Riwayat Penyakit Saat Ini',
      placeholder: 'Riwayat Penyakit Saat Ini...',
      model: 'riwayatPenyakitSaatIni',
      type: 'text',
    },
    {
      label: 'Riwayat Penyakit Sebelumnya',
      placeholder: 'Riwayat Penyakit Sebelumnya...',
      model: 'riwayatPenyakitSebelumnya',
      type: 'text',
    },
    {
      label: 'Alergi',
      placeholder: 'Alergi...',
      model: 'alergi',
      type: 'text',
    },
    {
      label: 'Berat Badan',
      placeholder: 'Berat Badan...',
      model: 'beratBadan',
      type: 'input',
    },
    {
      label: 'Tinggi Badan',
      placeholder: 'Tinggi Badan...',
      model: 'tinggiBadan',
      type: 'input',
    },
  ]
}
export function imgNyeri(): any {
  return {
    nama: 'Hurts',
    detail: [
      {
        nama: 'No Hurt',
        descNilai: 0,
        img: '/images/skalanyeri/1.png',
      },
      {
        nama: 'Hurts Little Bit',
        descNilai: 2,
        img: '/images/skalanyeri/2.png',
      },
      {
        nama: 'Hurts Little More',
        descNilai: 4,
        img: '/images/skalanyeri/3.png',
      },
      {
        nama: 'Hurts Even More',
        descNilai: 6,
        img: '/images/skalanyeri/4.png',
      },
      {
        nama: 'Hurts Whole Lot',
        descNilai: 8,
        img: '/images/skalanyeri/5.png',
      },
      {
        nama: 'Hurts whorts',
        descNilai: 10,
        img: '/images/skalanyeri/6.png',
      },
    ],
  }
}
export function skoringNyeri(): any {
  return {
    nama: 'Score ',
    detail: [
      { nama: '0 - 1 = Tidak Ada Nyeri', descNilai: 0 },
      { nama: '2 - 3 = Sedikit Nyeri', descNilai: 2 },
      { nama: '4 - 5 = Cukup Nyeri', descNilai: 4 },
      { nama: '6 - 7 = Lumayan Nyeri', descNilai: 6 },
      { nama: '8 - 9 = Sangat Nyeri', descNilai: 8 },
      { nama: '10 = Amat Sangat Nyeri', descNilai: 10 },
    ],
  }
}
export function skoringJatuh(): any {
  return [
    {
      label: 'NRS',
      model: 'skalaJatuh',
      type: 'checkbox',
      value: 0,
    },
    {
      label: 'WB',
      model: 'skalaJatuh',
      type: 'checkbox',
      value: 0,
    },
    {
      label: 'FLACE',
      model: 'skalaJatuh',
      type: 'checkbox',
      value: 0,
    },
    {
      label: 'NIPS',
      model: 'skalaJatuh',
      type: 'checkbox',
      value: 0,
    },
    {
      label: 'BPS',
      model: 'skalaJatuh',
      type: 'checkbox',
      value: 0,
    },
  ]
}

export function listNutrisional(): any {
  return {
    pertama: [
      {
        no: 1,
        parameter:
          'Apakah ada penurunan berat badan yang tidak diinginkan selama 6 bulan terakhir ?',
        pengkajian: [
          {
            model: 'penurunanBB',
            title: 'Tidak',
            keterangan: '',
            poin: 0,
            value: {
              poin: 0,
              keterangan: 'Tidak',
            },
          },
          {
            model: 'penurunanBB',
            title: 'Tidak Yakin',
            poin: 2,
            value: {
              poin: 2,
              keterangan: 'Tidak Yakin',
            },
          },
          {
            model: 'penurunanBB',
            title: 'Ya,1-5 Kg',
            keterangan: '',
            poin: 1,
            value: {
              poin: 1,
              keterangan: 'Ya,1-5 Kg',
            },
          },
          {
            model: 'penurunanBB',
            title: '6-10 Kg',
            keterangan: '',
            poin: 2,
            value: {
              poin: 2,
              keterangan: '6-10 Kg',
            },
          },
          {
            model: 'penurunanBB',
            title: '11-15 Kg',
            keterangan: '',
            poin: 3,
            value: {
              poin: 3,
              keterangan: '11-15 Kg',
            },
          },
          {
            model: 'penurunanBB',
            title: '> 15 Kg',
            keterangan: '',
            poin: 4,
            value: {
              poin: 4,
              keterangan: '> 15 Kg',
            },
          },
        ],
      },
    ],
    kedua: [
      {
        no: 2,
        parameter:
          'Apakah asupan makan menurun yang dikarenakan adanya penurunan nafsu makan/kesulitan menerima makan ?',
        pengkajian: [
          {
            model: 'penurunanNafsuMakan',
            title: 'Tidak',
            keterangan: '',
            poin: 0,
            value: {
              poin: 0,
              keterangan: 'Tidak',
            },
          },
          {
            model: 'penurunanNafsuMakan',
            title: 'Ya',
            keterangan: '',
            poin: 1,
            value: {
              poin: 1,
              keterangan: 'Ya',
            },
          },
        ],
      },
    ],
    ketiga: [
      {
        no: 3,
        parameter: 'Pasien dengan diagnosis khusus',
        pengkajian: [
          {
            model: 'diagnosaKhusus',
            title: 'Tidak',
            value: 'Tidak',
          },
          {
            model: 'diagnosaKhusus',
            title: 'Ya',
            keterangan: '',
            poin: 1,
            value: 'Ya',
          },
        ],
        children: [
          {
            label: 'DM',
            model: 'valueDiagnosaKhusus',
            type: 'checkbox',
            value: 'dm',
            column: '4',
          },
          {
            label: 'Kemoterapi',
            model: 'valueDiagnosaKhusus',
            type: 'checkbox',
            value: 'Kemoterapi',
            column: '4',
          },
          {
            label: 'Hemodialisa',
            model: 'valueDiagnosaKhusus',
            type: 'checkbox',
            value: 'Hemodialisa',
            column: '4',
          },
          {
            label: 'Geriatri',
            model: 'valueDiagnosaKhusus',
            type: 'checkbox',
            value: 'Geriatri',
            column: '4',
          },
          {
            label: 'Immunitas menurun',
            model: 'valueDiagnosaKhusus',
            type: 'checkbox',
            value: 'immunitasmMnurun',
            column: '4',
          },
          {
            label: 'Lain-lain',
            model: 'valueDiagnosaKhusus',
            type: 'checkbox',
            value: 'lain_lain',
            column: '4',
          },
          {
            label: 'Sebutkan',
            model: 'valueDiagnosaKhususLain',
            type: 'input',
            placeholder: 'sebutkan...',
            column: '12',
          },
        ],
      },
    ],
  }
}
export function statusPasien(): any {
  return [
    {
      label: 'STATUS FUNGSIONAL',
      model: 'statusFungsional',
      type: 'text',
      column: '4',
      placeholder: 'status fungsional...',
    },
    {
      label: 'STATUS BIOLOGIS',
      model: 'statusBiologis',
      type: 'text',
      column: '4',
      placeholder: 'status fungsional...',
    },
    {
      label: 'STATUS PSIKOLOGIS',
      model: 'statusPsikologis',
      type: 'text',
      column: '4',
      placeholder: 'status fungsional...',
    },
  ]
}

export function pemeriksaanFisik(): any {
  return [
    {
      label: '1. Deformitas',
      model: 'deformitas',
      type: 'checkbox',
    },
    {
      label: '2. Contusio',
      model: 'contusio',
      type: 'checkbox',
    },
    {
      label: '3. Abrasi',
      model: 'abrasi',
      type: 'checkbox',
    },
    {
      label: '4. Penetrasi',
      model: 'penetrasi',
      type: 'checkbox',
    },
    {
      label: '5. Laserasi',
      model: 'leserasi',
      type: 'checkbox',
    },
    {
      label: '6. Edema',
      model: 'edema',
      type: 'checkbox',
    },
    {
      label: '7. Keluhan Lain:',
      model: 'keluhanLainya',
      type: 'input',
    },
  ]
}

export function keperawatan(): any {
  return [
    {
      label: 'Rencana Asuhan Keperawatan',
      model: 'rencanaKeperawatan',
      palceholder: 'Rencana Asuhan Keperawatan...',
      type: 'text',
    },
    {
      label: 'Masalah Keperawatan',
      model: 'masalahKeperawatn',
      palceholder: 'Masalah Keperawatn...',
      type: 'text',
    },
    {
      label: 'Intruksi Perawat',
      model: 'intruksiPerawat',
      palceholder: 'Intruksi Perawat...',
      type: 'text',
    },
    {
      label: 'Saran Perawat',
      model: 'saranPerawat',
      palceholder: 'Saran Perawat...',
      type: 'text',
    },
  ]
}
