export function JenisKelamin(): any {
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
export function hipertensi(): any {
  return [
    {
      model: 'hipertensi',
      title: 'Ya',
    },
    {
      model: 'hiperKontrol',
      title: 'Terkontrol',
    },
    {
      model: 'hiperKontrol',
      title: 'Tidak Terkontrol',
    },
    {
      model: 'hipertensi',
      title: 'Tidak',
    },
  ]
}

export function statusGeneralis(): any {
  return [
    {
      model: 'Kepala',
      title: 'Kepala',
    },
    {
      model: 'Mata',
      title: 'Mata',
    },
    {
      model: 'THT',
      title: 'THT',
    },
    {
      model: 'Leher',
      title: 'Leher',
    },
    {
      model: 'Thoraks',
      title: 'Thoraks',
    },
    {
      model: 'Paru',
      title: 'Paru-paru',
    },
    {
      model: 'Perut',
      title: 'Perut',
    },
    {
      model: 'Lainnya',
      title: 'Lainnya',
    },
  ]
}

export function diabetes(): any {
  return [
    {
      model: 'diabetes',
      title: 'Ya',
    },
    {
      model: 'diabetesKontrol',
      title: 'Terkontrol',
    },
    {
      model: 'diabetesKontrol',
      title: 'Tidak Terkontrol',
    },
    {
      model: 'diabetes',
      title: 'Tidak',
    },
  ]
}
export function dyslipidemia(): any {
  return [
    {
      model: 'dyslipidemia',
      title: 'Ya',
    },
    {
      model: 'dyslipidemiaKontrol',
      title: 'Terkontrol',
    },
    {
      model: 'dyslipidemiaKontrol',
      title: 'Tidak Terkontrol',
    },
    {
      model: 'dyslipidemia',
      title: 'Tidak',
    },
  ]
}
export function duaPilihan(): any {
  return ['Ya', 'Tidak']
}
export function agama(): any {
  return ['Islam', 'Budha', 'Khatolik', 'Kristen', 'Hindu', 'Khonghucu']
}
export function status(): any {
  return ['Menikah', 'Belum Menikah', 'Duda/Janda']
}
export function keluarga(): any {
  return ['Tinggal Sendiri', 'Tinggal Serumah']
}
export function tempatTinggal(): any {
  return ['Rumah', 'Panti Asuhan', 'Lainnya']
}
export function psikologis(): any {
  return ['Depresi', 'Takut', 'Agresif', 'Melukai diri Sendiri', 'Tidak ada gejala']
}
export function more(): any {
  return [
    {
      title: 'Diagnosa Keperawatan',
      model: 'diagnosa',
    },
    {
      title: 'Intervensi Keperawatan',
      model: 'intervensiKeperawatan',
    },
    {
      title: 'Implementasi Keperawatan',
      model:
        'frontend-v2/src/pages/module/emr/profile-pasien/page-emr-plugins/asesmen-awal-keper-rj.ts',
    },
  ]
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

export function resikoNutrisional(): any {
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
  }
}

export function fungsionalPertama(): any {
  return [
    {
      title: 'Alat Bantu',
      model: 'alatBantu',
    },
    {
      title: 'ADL',
      model: 'adl',
    },
    {
      title: 'Pendidikan',
      model: 'pendidikan',
    },
    {
      title: 'Pekerjaan',
      model: 'pekerjaan',
    },
  ]
}
export function nilaiPoin(): any {
  return ['Skor 0 - 1', 'Skor 2 - 3', 'Skor > 4 ']
}
export function descNilai(): any {
  return ['Tidak Beresiko', 'Beresiko (Asuhan Gizi Oleh Dietizen)', 'Skor > 4 ']
}

export function pertanyaanA(): any {
  return [
    {
      pertanyaan:
        'A. Perhatikan cara berjalan pasien saat akan duduk di kursi, apakah pasien tampa seimbang (sempoyongan/limbung) ?',
      jawaban: [
        {
          deskripsi: 'Kondisi Pasien Akan Duduk Sempoyangan',
          jawab: 'Ya',
        },
        {
          deskripsi: 'Kondisi Pasien Akan Duduk Sempoyangan',
          jawab: 'Tidak',
        },
      ],
    },
  ]
}

export function pertanyaanB(): any {
  return [
    {
      pertanyaan:
        'B. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk ?',
      jawaban: [
        {
          deskripsi: 'Pegangan Pasien ketika Duduk',
          jawab: 'Ya',
        },
        {
          deskripsi: 'Pegangan Pasien ketika Duduk',
          jawab: 'Tidak',
        },
      ],
    },
  ]
}
export function pertanyaanC(): any {
  return [
    {
      pertanyaan: 'C. Hasil',
      detail: [
        {
          title: 'Tidak beresiko (tidak ditemukan a dan b)',
          value: 'Tidak beresiko',
        },
        {
          title: 'Risiko tinggi (ditemukan a dan b)',
          value: 'Risiko tinggi',
        },
        {
          title: 'Risiko Rendah Sedang (ditemukan a atau b)',
          value: 'Risiko Rendah Sedang',
        },
      ],
    },
  ]
}

export function detailSkriningNutrisi(): any {
  return [
    {
      child: [
        {
          type: 'text',
          colspan: '2',
          caption:
            'Apakah pasien mengalami penurunan berat badan yang tidak direncanakan selama 6 bulan terakhir?',
          style: 'background-color:lightgray',
        },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: 'Tidak' },
        { type: 'nilai', caption: '1' },
      ],
    },
    {
      child: [
        {
          type: 'checkbox',
          caption: 'Tidak yakin (ada tanda: baju menjadi lebih longgar)',
        },
        { type: 'nilai', caption: '2' },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: 'Ya, bila ya berapa penurunan berat badan' },
        { type: 'nilai', caption: '2' },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: '1-5Kg' },
        { type: 'nilai', caption: '1' },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: '6-10Kg' },
        { type: 'nilai', caption: '2' },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: '11-15Kg' },
        { type: 'nilai', caption: '3' },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: '>15Kg' },
        { type: 'nilai', caption: '4' },
      ],
    },
    {
      child: [
        {
          type: 'text',
          colspan: '2',
          caption: 'Apakah terjadi penurunan nafsu makan?',
          style: 'background-color:lightgray',
        },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: 'Ya' },
        { type: 'nilai', caption: '1' },
      ],
    },
    {
      child: [
        { type: 'checkbox', caption: 'Tidak' },
        { type: 'nilai', caption: '0' },
      ],
    },
    {
      child: [{ type: 'text', caption: 'Total Skor : ' }, { type: 'textbox' }],
    },
  ]
}

export function detailStatusFungsional(): any {
  return [
    {
      child: [
        { type: 'text', caption: 'Mengontrol BAB' },
        { type: 'text', caption: 'Inkontinen/ tidak teratur (perlu enema)' },
        { type: 'text', caption: 'Kadang inkontinen (1x seminggu)' },
        { type: 'text', caption: 'Kontinen teratur' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        { type: 'text', caption: 'Mengontrol BAK' },
        { type: 'text', caption: 'Inkontinen/ pakai kateter dan tidak terkontrol' },
        { type: 'text', caption: 'Kadang inkontinen (max 1x24 jam)' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        {
          type: 'text',
          caption: 'Membersihkan diri (lap muka, sisir rambut, sikat gigi)',
        },
        { type: 'text', caption: 'Butuh pertolongan orang lain' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        {
          type: 'text',
          caption:
            'Penggunaan toilet, pergi ke dalam dari WC (melepas, memakai, celana, menyeka, menyiram)',
        },
        { type: 'text', caption: 'Tergantung pertolongan orang lain' },
        {
          type: 'text',
          caption:
            'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain',
        },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        { type: 'text', caption: 'Makan' },
        { type: 'text', caption: 'Tidak Mampu' },
        { type: 'text', caption: 'Perlu seseorang menolong memotong makanan' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        { type: 'text', caption: 'Berpindah tempat dari tidur ke duduk' },
        { type: 'text', caption: 'Tidak Mampu' },
        { type: 'text', caption: 'Perlu banyak bantuan untuk bisa duduk (2 orang)' },
        { type: 'text', caption: 'Bantuan 1 orang' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        { type: 'text', caption: 'Mobilisasi/berjalan' },
        { type: 'text', caption: 'Tidak Mampu' },
        { type: 'text', caption: 'Dengan kursi roda' },
        { type: 'text', caption: 'Bantuan 1 orang' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        { type: 'text', caption: 'Berpakaian (memakai baju)' },
        { type: 'text', caption: 'Tergantung pertolongan orang lain' },
        { type: 'text', caption: 'Sebagian dibantu (misal mengancing baju)' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        { type: 'text', caption: 'Naik turun tangga' },
        { type: 'text', caption: 'Tidak mampu' },
        { type: 'text', caption: 'Butuh pertolongan' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
    {
      child: [
        { type: 'text', caption: 'Mandi' },
        { type: 'text', caption: 'Tergantung pertolongan orang lain' },
        { type: 'text', caption: 'Mandiri' },
        { type: 'textbox' },
        { type: 'textbox' },
        { type: 'textbox' },
      ],
    },
  ]
}

export function statusFungsional(): any {
  return {
    statusFungsional: [
      {
        fungsi: 'Mengontrol BAB',
        detail: [
          {
            type: 'checkbox',
            caption: 'Inkontinen/ tidak teratur (perlu enema)',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Kadang inkontinen (1x seminggu)',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Kontinen teratur',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'Mengontrol BAK',
        detail: [
          {
            type: 'checkbox',
            caption: 'Inkontinen/ pakai kateter dan tidak terkontrol',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Kadang inkontinen (max 1x 24jam)',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'Membersihkan diri(lap muka, sisir rambut, sikat gigi',
        detail: [
          {
            type: 'checkbox',
            caption: 'Butuh pertolongan orang lain',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '1',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi:
          'Penggunaan toilet, pergi ke dalam dari WC(melepas, memakai, celana, menyeka, menyiram)',
        detail: [
          {
            type: 'checkbox',
            caption: 'Tergantung pertolongan orang lain',
            value: '0',
          },
          {
            type: 'checkbox',
            caption:
              'Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain',
            value: '1',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'Makan',
        detail: [
          {
            type: 'checkbox',
            caption: 'Tidak Mampu',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Perlu seseorang menolong memotong makanan',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'berpindah tempat dari tidur ke duduk',
        detail: [
          {
            type: 'checkbox',
            caption: 'Tidak Mampu',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Perlu banyak bantuan untuk bisa duduk (2 orang)',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Bantuan 1 orang',
            value: '2',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'Mobilisasi/berjalan',
        detail: [
          {
            type: 'checkbox',
            caption: 'Tidak Mampu',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Dengan kursi roda',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Bantuan Satu orang',
            value: '2',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'berpakaian',
        detail: [
          {
            type: 'checkbox',
            caption: 'Tergantung orang lain',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Sebagian dibantu misal mengancing baju',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'naik turun tangga',
        detail: [
          {
            type: 'checkbox',
            caption: 'Tidak Mampu',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Butuh pertolongan',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
      {
        fungsi: 'Mandi',
        detail: [
          {
            type: 'checkbox',
            caption: 'Tidak Mampu',
            value: '0',
          },
          {
            type: 'checkbox',
            caption: 'Tegantung orang lain',
            value: '1',
          },
          {
            type: 'checkbox',
            caption: 'Mandiri',
            value: '2',
          },
          {
            type: 'textbox',
            caption: 'Ket',
            value: '3',
          },
          {
            type: 'skor',
            caption: 'Skor',
          },
        ],
      },
    ],
  }
}

export function DiagnosaKeperawatanRanap() {
  return [
    { key: 'nyeriakut', value: 'Nyeri akut b/d kondisi fisik' },
    {
      key: 'bersihan',
      value:
        'Bersihan jalan nafas tidak efektif b/d alergi jalan nafas, adanya eksudat di jalan nafas / sekresi tertahan',
    },
    {
      key: 'risikojantung',
      value:
        'Risiko /Penurunan curah jantung b/d anomaly jantung / peningkatan beban kerja ventrikel',
    },
    {
      key: 'risikokekurangan',
      value:
        'Risiko / Kekurangan Volume Cairan b/d kehilangan volume cairan secara aktif',
    },
    {
      key: 'kurangpengetahuan',
      value:
        'Kurang pengetahuan tentang penyakit, rencana tindakan dan pengobatan b/d kurang terpajannya informasi',
    },
    {
      key: 'ansietas',
      value: 'Ansietas b/d krisis situasi, kebutuhan yang tidak terpenuhi',
    },
    { key: 'integritas', value: 'Risiko gangguan integritas kulit' },
    {
      key: 'kelebihanvolume',
      value: 'Kelebihan volume cairan b/d asupan cairan berlebihan',
    },
    { key: 'kesiapan', value: 'Kesiapan meningkatkan status kesehatan' },
    {
      key: 'ketidakefektifan',
      value: 'Ketidakefektifan pemeliharaan kesehatan b/d hambatan kognitif',
    },
    { key: 'hambatan', value: 'Hambatan mobilitas fisik b/d intoleran aktivitas' },
    {
      key: 'diareakut',
      value: 'Diare akut b/d mal absorbsi, peningkatan motilitas usus',
    },
    {
      key: 'nausea',
      value: 'Nausea b/d biofisik, psikologis, pemberian kemotherapi, pemberian steroid',
    },
    {
      key: 'kadarglukosa',
      value:
        'Risiko Ketidakstabilan kadar Glukosa Darah b/d Kurang pengetahuan tentang menejemen diabetes, Asupan diet, Pemantauan glukosa darah tidak adekuat',
    },
    {
      key: 'hipertermia',
      value: 'Hipertermia b/d kekurangan cairan, proses infeksi, gangguan termoregulasi',
    },
    { key: 'fungsigigi', value: 'Gangguan fungsi gigi' },
    { key: 'jaringankeras', value: 'Gangguan jaringan keras gigi' },
    { key: 'jaringanlunak', value: 'Gangguan jaringan lunak dan pendukung gigi' },
    { key: 'estetika', value: 'Gangguan estetika' },
    { key: 'sensori', value: 'Gangguan persepsi sensori' },
    {
      key: 'riwayatjatuh',
      value:
        'Risiko jatuh b/d riwayat terjatuh/usia lebih dari 65th / menggunakan alat bantu (walker, tongkat, kursi roda) / sulit penglihatan',
    },
    {
      key: 'polaasi',
      value:
        'Pola pemberian ASI tidak efektif b/d ketidakefektifan pemeliharaan kesehatan',
    },
    { key: 'CBPolaNapasDK', value: 'Pola napas tidak efektif' },
    { key: 'CBRisikoInfeksiDK', value: 'Risiko Infeksi' },
    { key: 'CBPInterimNeonatusDK', value: 'Interim Neonatus' },
    { key: 'CBTermoRegulasiDK', value: 'Termo regulasi tidak efektif' },
    { key: 'CBGangguanPertukaranGasDK', value: 'Gangguan pertukaran gas' },
  ]
}

export function RencanaKeperawatan() {
  return [
    {
      key: 'istirahatkan',
      value:
        'Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien',
    },
    {
      key: 'berikaninfo',
      value:
        'Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri',
    },
    {
      key: 'bantupasien',
      value:
        'Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien',
    },
    { key: 'observasi', value: 'Observasi tanda-tanda vital' },
    {
      key: 'ajarkan',
      value:
        'Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung',
    },
    { key: 'monitor', value: 'Monitor Frekuensi nafas pasien/ status oksigen pasien' },
    {
      key: 'posisikan',
      value: 'Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)',
    },
    { key: 'latihanbatuk', value: 'Latihan teknik batuk efektif' },
    { key: 'chest', value: 'Lakukan chest fisioterapi sesuai indikasi/bila perlu' },
    { key: 'berikie', value: 'Beri KIE tentang tanda-tanda penurunan curah jantung' },
    {
      key: 'latihrentang',
      value:
        'Latih rentang pergerakan aktif/pasif untuk memperbaiki kekuatan dan daya tahan otot',
    },
    {
      key: 'edukasi',
      value: 'Edukasi untuk memberikan kompres dengan air biasa/ hangat',
    },
    {
      key: 'kaji',
      value:
        'Kaji dan dokumentasi frekuensi, warna, konsistensi, jumlah (ukuran) feces, turgor kulit dan kondisi mukosa mulut sebagai indikator dehidrasi',
    },
    {
      key: 'sarankan',
      value:
        'Sarankan menghindari makanan yang mengandung lactose, makan makanan yang rendah serat, tinggi kalori dan tinggi protein',
    },
    { key: 'imunisasi', value: 'Lakukan manajemen imunisasi/vaksinasi' },
    { key: 'dukungan', value: 'Beri dudkungan dalam mengambil keputusan' },
    {
      key: 'kontrol',
      value:
        'Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien',
    },
    { key: 'kaji', value: 'Kaji integritas kulit' },
    {
      key: 'ajarkanteknik',
      value:
        'Ajarkan teknik nonfarmakologis seperti : Relaksasi napas dalam/ otot progesif, Distraksi, Kompres hangat/ dingin, Terapi music, Massage punggung',
    },
    { key: 'identifikasi', value: 'Identifikasi level cemas pada pasien' },
    {
      key: 'cemas',
      value:
        'Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas',
    },
    {
      key: 'prosedur',
      value: 'Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur',
    },
    {
      key: 'dekatipasien',
      value: 'Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut',
    },
    { key: 'dengarkan', value: 'Dengarkan pasien dengan penuh perhatian' },
    { key: 'CBPolaNapasRK', value: 'Pola napas tidak efektif' },
    { key: 'CBRisikoInfeksiRK', value: 'Risiko Infeksi' },
    { key: 'CBPInterimNeonatusRK', value: 'Interim Neonatus' },
    { key: 'CBTermoRegulasiRK', value: 'Termo regulasi tidak efektif' },
    { key: 'CBGangguanPertukaranGasRK', value: 'Gangguan pertukaran gas' },
  ]
}

export function RencanaKeperawatanRawatInap() {
  return [
    {
      label: 'Rencana Keperawatan Resiko Infeksi',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Resiko Infeksi',
    },
    {
      label: 'Rencana Keperawatan Resiko Konfusi Akut',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Resiko Konfusi Akut',
    },
    {
      label: 'Rencana Keperawatan Resiko Cidera',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Resiko Cidera',
    },
    {
      label: 'Rencana Keperawatan Defisit Nutrisi',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Defisit Nutrisi',
    },
    {
      label: 'Rencana Keperawatan Hipertermia',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Hipertermia',
    },
    {
      label: 'Rencana Keperawatan Ansietas',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Ansietas',
    },
    {
      label: 'Rencana Keperawatan Berduka',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Berduka',
    },
    {
      label: 'Rencana Keperawatan Termoregulasi Tidak Efektif',
      model: 'jenisRencanaKeperawatanRanap',
      value: 'Termoregulasi Tidak Efektif',
    },
  ]
}

export function ResikoInfeksi() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Risiko Infeksi berhubungan dengan',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Penyakit Kronis',
          value: 'Penyakit kronis',
          model: 'penyakitKronis',
        },
        {
          labelDetail: '2. Efek prosedur imvasif',
          value: 'Efek prosedur imvasif',
          model: 'efekProsedurImvasif',
        },
        {
          labelDetail: '3. Malnutrisi',
          value: 'Malnutrisi',
          model: 'malnutrisi',
        },
        {
          labelDetail: '4. Peningkatan paparan organism patogen lingkungan',
          value: 'Peningkatan paparan organism patogen lingkungan',
          model: 'peningkatanPaparanOrganismPatogenLingkungan',
        },
        {
          labelDetail:
            '5. Ketidakuatan pertahanan tubuh primer (gangguan peristaltic, kerusakan integritas kulit, ketuban pecah lama, ketuban pecah dini, statis cairan tubuh)',
          value:
            'Ketidakuatan pertahanan tubuh primer (gangguan peristaltic, kerusakan integritas kulit, ketuban pecah lama, ketuban pecah dini, statis cairan tubuh)',
          model: 'ketidakuatanPertahananTubuhPrimer',
        },
        {
          labelDetail:
            '6. Ketidakadekuatan pertahanan tubuh sekunder (penurunan hemoglobin, imunosupresi, leucopenia, vaksinasi tidak adekuat)',
          value:
            'Ketidakadekuatan pertahanan tubuh sekunder (penurunan hemoglobin, imunosupresi, leucopenia, vaksinasi tidak adekuat)',
          model: 'ketidakadekuatanPertahananTubuhSekunder',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Keberhasilan tangan meningkat',
          value: 'Keberhasilan tangan meningkat',
          model: 'keberhasilanTanganMeningkat',
        },
        {
          labelDetail: '2. Keberhasilan badan meningkat',
          value: 'Keberhasilan badan meningkat',
          model: 'keberhasilanBadanMeningkat',
        },
        {
          labelDetail: '3. Demam menurun',
          value: 'Demam menurun',
          model: 'demamMenurun',
        },
        {
          labelDetail: '4. Nyeri menurun',
          value: 'Nyeri menurun',
          model: 'nyeriMenurun',
        },
        {
          labelDetail: '5. Bengkak menurun',
          value: 'Bengkak menurun',
          model: 'bengkakMenurun',
        },
        {
          labelDetail: '6. Kemerahan Menurun',
          value: 'Kemerahan Menurun',
          model: 'kemerahanMenurun',
        },
        {
          labelDetail: '7. Sputum berwarna hijau menurun',
          value: 'Sputum berwarna hijau menurun',
          model: 'sputumBerwarnaHijauMenurun',
        },
        {
          labelDetail: '8. Letargi menurun',
          value: 'Letargi menurun',
          model: 'letargiMenurun',
        },
        {
          labelDetail: '9. Kultur darah membaik',
          value: 'Kultur darah membaik',
          model: 'kulturDarahMembaik',
        },
        {
          labelDetail: '10. Kultur urine membaik',
          value: 'Kultur urine membaik',
          model: 'kulturUrineMembaik',
        },
        {
          labelDetail: '11. Kultur sputum membaik',
          value: 'Kultur sputum membaik',
          model: 'kulturSputumMembaik',
        },
        {
          labelDetail: '12. Kultur feses membaik',
          value: 'Kultur feses membaik',
          model: 'kulturFesesMembaik',
        },
        {
          labelDetail: '13. Kultur area luka membaik',
          value: 'Kultur area luka membaik',
          model: 'kulturAreaLukaMembaik',
        },
        {
          labelDetail: '14. Kadar sel darah putih membaik',
          value: 'Kadar sel darah putih membaik',
          model: 'kadarSelDarahPutihMembaik',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Monitor tanda dan gejala infeksi lokal dan sistemik',
          value: 'Monitor tanda dan gejala infeksi lokal dan sistemik',
          model: 'monitorTandaDanGejalaInfeksiLokalDanSistemik',
        },
        {
          labelDetail: '2. Batasi jumlah pengunjung',
          value: 'Batasi jumlah pengunjung',
          model: 'batasiJumlahPengunjung',
        },
        {
          labelDetail: '3. Berikan perawatan kulit pada area edema',
          value: 'Berikan perawatan kulit pada area edema',
          model: 'berikanPerawatanKulitPadaAreaEdema',
        },
        {
          labelDetail:
            '4. Cuci tangan sebelum dan sesudah kontak dengan pasien dan lingkungan pasien',
          value:
            'Cuci tangan sebelum dan sesudah kontak dengan pasien dan lingkungan pasien',
          model: 'cuciTanganSebelumDanSesudahKontakDenganPasienDanLingkunganPasien',
        },
        {
          labelDetail:
            '5. Pertahankan teknik aseptik pada pasien beresiko tinggi, jelaskan tanda dan gejala infeksi',
          value:
            'Pertahankan teknik aseptik pada pasien beresiko tinggi, jelaskan tanda dan gejala infeksi',
          model:
            'pertahankanTeknikAseptikPadaPasienBeresikoTinggiJelaskanTandaDanGejalaInfeksi',
        },
        {
          labelDetail: '6. Ajarkan cara mencuci tangan dengan benar',
          value: 'Ajarkan cara mencuci tangan dengan benar',
          model: 'ajarkanCaraMencuciTanganDenganBenar',
        },
        {
          labelDetail: '7. Ajarkan etika batuk',
          value: 'Ajarkan etika batuk',
          model: 'ajarkanEtikaBatuk',
        },
        {
          labelDetail: '8. Ajarkan mengenali tanda-tanda infeksi',
          value: 'Ajarkan mengenali tanda-tanda infeksi',
          model: 'ajarkanMengenaliTandaTandaInfeksi',
        },
        {
          labelDetail: '9. Anjurkan meningkatkan asupan nutrisi',
          value: 'Anjurkan meningkatkan asupan nutrisi',
          model: 'anjurkanMeningkatkanAsupanNutrisi',
        },
        {
          labelDetail: '10. Kolaborasi pemberian imunisasi, jika perlu',
          value: 'Kolaborasi pemberian imunisasi, jika perlu',
          model: 'kolaborasiPemberianImunisasiJikaPerlu',
        },
        {
          labelDetail: '11. Kolaborasi pemberian antibiotik, jika perlu',
          value: 'Kolaborasi pemberian antibiotik, jika perlu',
          model: 'kolaborasiPemberianAntibiotikJikaPerlu',
        },
        {
          labelDetail: '12. Identifikasi kontraindikasi pemberian imunisasi',
          value: 'Identifikasi kontraindikasi pemberian imunisasi',
          model: 'identifikasiKontindikasiPemberianImunisasi',
        },
        {
          labelDetail: '13. Berikan suntikan pada bayi dibagian paha anterolateral',
          value: 'Berikan suntikan pada bayi dibagian paha anterolateral',
          model: 'berikanSuntikanPadaBayiDibagianPahaAnterolateral',
        },
        {
          labelDetail:
            '14. Jelaskan tujuan, manfaat, reaksi yang terjadi, jadwal dan efek samping',
          value: 'Jelaskan tujuan, manfaat, reaksi yang terjadi, jadwal dan efek samping',
          model: 'jelaskanTujuanManfaatReaksiYangTerjadiJadwalDanEfekSamping',
        },
        {
          labelDetail: '15. Monitor sputum (jumlah, warna, aroma)',
          value: 'Monitor sputum (jumlah, warna, aroma)',
          model: 'monitorSputumJumlahWarnaAroma',
        },
        {
          labelDetail: '16. Posisikan pasien fowler/semifowler',
          value: 'Posisikan pasien fowler/semifowler',
          model: 'posisikanPasienFowlerSemifowler',
        },
        {
          labelDetail: '17. Lakukan penghisapan lendir berkala dan lakukan oral hygne',
          value: 'Lakukan penghisapan lendir berkala dan lakukan oral hygne',
          model: 'lakukanPenghisapanLendirBerkalaDanLakukanOralHygne',
        },
        {
          labelDetail: '18. Identifikasi indikasi dilakukan pemasangan selang',
          value: 'Identifikasi indikasi dilakukan pemasangan selang',
          model: 'identifikasiIndikasiDilakukanPemasanganSelang',
        },
        {
          labelDetail: '19. Monitor kulit dan jumlah, warna, konsistensi drainase selang',
          value: 'Monitor kulit dan jumlah, warna, konsistensi drainase selang',
          model: 'monitorKulitDanJumlahWarnaKonsistensiDrainaseSelang',
        },
        {
          labelDetail: '20. Ganti selang secara rutin sesuai indikasi',
          value: 'Ganti selang secara rutin sesuai indikasi',
          model: 'gantiSelangSecaraRutinSesuaiIndikasi',
        },
        {
          labelDetail: '21. Kosongkan kantung penampung sesuai indikasi',
          value: 'Kosongkan kantung penampung sesuai indikasi',
          model: 'kosongkanKantungPenampungSesuaiIndikasi',
        },
      ],
    },
  ]
}

export function ResikoKonfusiAkut() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Risiko Konfusi Akut berhubungan dengan Faktor resiko',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Usia di atas 60 tahun',
          value: 'Usia di atas 60 tahun',
          model: 'usiaDiAtas60Tahun',
        },
        {
          labelDetail: '2. Perubahan fungsi kognitif',
          value: 'Perubahan fungsi kognitif',
          model: 'perubahanFungsiKognitif',
        },
        {
          labelDetail: '3. Fluktuasi siklus tidur bangun',
          value: 'Fluktuasi siklus tidur bangun',
          model: 'fluktuasiSiklusTidurBangun',
        },
        {
          labelDetail: '4. Dehidrasi',
          value: 'Dehidrasi',
          model: 'dehidrasi',
        },
        {
          labelDetail: '5. Dimensia',
          value: 'Dimensia',
          model: 'dimensia',
        },
        {
          labelDetail: '6. Riwayat stroke',
          value: 'Riwayat stroke',
          model: 'riwayatStroke',
        },
        {
          labelDetail: '7. Gangguan mobilitas',
          value: 'Gangguan mobilitas',
          model: 'gangguanMobilitas',
        },
        {
          labelDetail:
            '8. Gagguan fungsi metabolik (azotemia, penurunan Hb, ketidakseimbngan elektrolit, peningkatan Bun kreatinin)',
          value:
            'Gagguan fungsi metabolik (azotemia, penurunan Hb, ketidakseimbngan elektrolit, peningkatan Bun kreatinin)',
          model: 'gagguanFungsiMetabolik',
        },
        {
          labelDetail: '9. Infeksi',
          value: 'Infeksi',
          model: 'infeksi',
        },
        {
          labelDetail: '10. Malnutrisi',
          value: 'Malnutrisi',
          model: 'malnutrisi',
        },
        {
          labelDetail: '11. Nyeri',
          value: 'Nyeri',
          model: 'nyeri',
        },
        {
          labelDetail: '12. Penyalahgunaan zat',
          value: 'Penyalahgunaan zat',
          model: 'penyalahgunaanZat',
        },
        {
          labelDetail: '13. Efek agen farmakologis',
          value: 'Efek agen farmakologis',
          model: 'efekAgenFarmakologis',
        },
        {
          labelDetail: '14. Penurunan sensori',
          value: 'Penurunan sensori',
          model: 'penurunanSensori',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Fungsi kognitif meningkat',
          value: 'Fungsi kognitif meningkat',
          model: 'fungsiKognitifMeningkat',
        },
        {
          labelDetail: '2. Tingkat kesadaran meningkat',
          value: 'Tingkat kesadaran meningkat',
          model: 'tingkatKesadaranMeningkat',
        },
        {
          labelDetail: '3. Aktivitas psikomotorik meningkat',
          value: 'Aktivitas psikomotorik meningkat',
          model: 'aktivitasPsikomotorikMeningkat',
        },
        {
          labelDetail: '4. Gelisah menurun',
          value: 'Gelisah menurun',
          model: 'gelisahMenurun',
        },
        {
          labelDetail: '5. Respon terhadap stimulus membaik',
          value: 'Respon terhadap stimulus membaik',
          model: 'responTerhadapStimulusMembaik',
        },
        {
          labelDetail: '6. Perilaku halusinasi menurun',
          value: 'Perilaku halusinasi menurun',
          model: 'perilakuHalusinasiMenurun',
        },
        {
          labelDetail: '7. Memori jangka pendek meningkat',
          value: 'Memori jangka pendek meningkat',
          model: 'memoriJangkaPendekMeningkat',
        },
        {
          labelDetail: '8. Memori jangka panjang meningkat',
          value: 'Memori jangka panjang meningkat',
          model: 'memoriJangkaPanjangMeningkat',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Identifikasi riwayat fisik, sosial, psikologis dan kebiasaan',
          value: 'Identifikasi riwayat fisik, sosial, psikologis dan kebiasaan',
          model: 'identifikasiRiwayatFisikSosialPsikologisDanKebiasaan',
        },
        {
          labelDetail: '2. Identifikasi pola aktivitas',
          value: 'Identifikasi pola aktivitas',
          model: 'identifikasiPolaAktivitas',
        },
        {
          labelDetail:
            '3. Identifikasi faktor resiko delirium (misal usia >75 tahun, disfungsi kognitif)',
          value:
            'Identifikasi faktor resiko delirium (misal usia >75 tahun, disfungsi kognitif)',
          model: 'identifikasiFaktorResikoDelirium',
        },
        {
          labelDetail: '4. Identifikasi tipe delirium (hipoaktif atau hiperaktif)',
          value: 'Identifikasi tipe delirium (hipoaktif atau hiperaktif)',
          model: 'identifikasiTipeDelirium',
        },
        {
          labelDetail:
            '5. Orientasikan tempat, waktu dan orang. Gunakan distraksi untuk mengatasi perilaku',
          value:
            'Orientasikan tempat, waktu dan orang. Gunakan distraksi untuk mengatasi perilaku',
          model: 'orientasikanTempatWaktuDanOrangGunakanDistraksi',
        },
        {
          labelDetail: '6. Monitor status neurologis dan tingkat delirium',
          value: 'Monitor status neurologis dan tingkat delirium',
          model: 'monitorStatusNeurologisDanTingkatDelirium',
        },
        {
          labelDetail: '7. Lakukan pengekangan fisik sesuai indikasi',
          value: 'Lakukan pengekangan fisik sesuai indikasi',
          model: 'lakukanPengekanganFisikSesuaiIndikasi',
        },
        {
          labelDetail: '8. Hindari stimulus sensorik berlebih',
          value: 'Hindari stimulus sensorik berlebih',
          model: 'hindariStimulusSensorikBerlebih',
        },
        {
          labelDetail: '9. Anjurkan kunjungan keluarga, jika perlu',
          value: 'Anjurkan kunjungan keluarga, jika perlu',
          model: 'anjurkanKunjunganKeluargaJikaPerlu',
        },
        {
          labelDetail: '10. Kolaborasi pemberian obat ansietas atau agitasi, jika perlu',
          value: 'Kolaborasi pemberian obat ansietas atau agitasi, jika perlu',
          model: 'kolaborasiPemberianObatAnsietasAtauAgitasiJikaPerlu',
        },
      ],
    },
  ]
}

export function ResikoCidera() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Resiko cedera berhubungan dengan',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Disfungsi autoimun',
          value: 'Disfungsi autoimun',
          model: 'disfungsiAutoimun',
        },
        {
          labelDetail: '2. Hipoksia jaringan',
          value: 'Hipoksia jaringan',
          model: 'hipoksiaJaringan',
        },
        {
          labelDetail: '3. Malnutrisi',
          value: 'Malnutrisi',
          model: 'malnutrisi',
        },
        {
          labelDetail: '4. Perubahan fungsi psikomotor',
          value: 'Perubahan fungsi psikomotor',
          model: 'perubahanFungsiPsikomotor',
        },
        {
          labelDetail: '5. Perubahan fungsi kognitif',
          value: 'Perubahan fungsi kognitif',
          model: 'perubahanFungsiKognitif',
        },
        {
          labelDetail: '6. Kejang',
          value: 'Kejang',
          model: 'kejang',
        },
        {
          labelDetail: '7. Sinkop',
          value: 'Sinkop',
          model: 'sinkop',
        },
        {
          labelDetail: '8. Vertigo',
          value: 'Vertigo',
          model: 'vertigo',
        },
        {
          labelDetail: '9. Gangguan penglihatan',
          value: 'Gangguan penglihatan',
          model: 'gangguanPenglihatan',
        },
        {
          labelDetail: '10. Hipotensi',
          value: 'Hipotensi',
          model: 'hipotensi',
        },
        {
          labelDetail: '11. Retardasi mental',
          value: 'Retardasi mental',
          model: 'retardasiMental',
        },
        {
          labelDetail: '12. Penyakit Parkinson',
          value: 'Penyakit Parkinson',
          model: 'penyakitParkinson',
        },
        {
          labelDetail: '13. Terpapar patogen',
          value: 'Terpapar patogen',
          model: 'terpaparPatogen',
        },
        {
          labelDetail: '14. Terpapar zat kimia toksik',
          value: 'Terpapar zat kimia toksik',
          model: 'terpaparZatKimiaToksik',
        },
        {
          labelDetail: '15. Terpapar agen nosokomial',
          value: 'Terpapar agen nosokomial',
          model: 'terpaparAgenNosokomial',
        },
        {
          labelDetail: '16. Ketidaknormalan profil darah',
          value: 'Ketidaknormalan profil darah',
          model: 'ketidaknormalanProfilDarah',
        },
        {
          labelDetail: '17. Perubahan orientasi afektif',
          value: 'Perubahan orientasi afektif',
          model: 'perubahanOrientasiAfektif',
        },
        {
          labelDetail: '18. Perubahan sensasi',
          value: 'Perubahan sensasi',
          model: 'perubahanSensasi',
        },
        {
          labelDetail: '19. Disfungsi biokimia',
          value: 'Disfungsi biokimia',
          model: 'disfungsiBiokimia',
        },
        {
          labelDetail: '20. Gangguan pendengaran',
          value: 'Gangguan pendengaran',
          model: 'gangguanPendengaran',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Kejadian cedera luka/lecet menurun',
          value: 'Kejadian cedera luka/lecet menurun',
          model: 'kejadianCederaLukaLecetMenurun',
        },
        {
          labelDetail: '2. Toleransi aktivitas meningkat',
          value: 'Toleransi aktivitas meningkat',
          model: 'toleransiAktivitasMeningkat',
        },
        {
          labelDetail: '3. Nafsu makan meningkat',
          value: 'Nafsu makan meningkat',
          model: 'nafsuMakanMeningkat',
        },
        {
          labelDetail: '4. Agitasi menurun',
          value: 'Agitasi menurun',
          model: 'agitasiMenurun',
        },
        {
          labelDetail: '5. Gangguan mobilitas menurun',
          value: 'Gangguan mobilitas menurun',
          model: 'gangguanMobilitasMenurun',
        },
        {
          labelDetail: '6. Gangguan kognitif menurun',
          value: 'Gangguan kognitif menurun',
          model: 'gangguanKognitifMenurun',
        },
        {
          labelDetail: '7. Ketegangan otot/kejang menurun',
          value: 'Ketegangan otot/kejang menurun',
          model: 'keteganganOtotKejangMenurun',
        },
        {
          labelDetail: '8. Tekanan darah membaik',
          value: 'Tekanan darah membaik',
          model: 'tekananDarahMembaik',
        },
        {
          labelDetail: '9. Frekuensi nadi membaik',
          value: 'Frekuensi nadi membaik',
          model: 'frekuensiNadiMembaik',
        },
        {
          labelDetail: '10. Pola istirahat/tidur membaik',
          value: 'Pola istirahat/tidur membaik',
          model: 'polaIstirahatTidurMembaik',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Gunakan perangkat pelindung fisik dan relatif',
          value: 'Gunakan perangkat pelindung fisik dan relatif',
          model: 'gunakanPerangkatPelindungFisikDanRelatif',
        },
        {
          labelDetail: '2. Identifikasi obat yang berpotensi menyebabkan kerusakan',
          value: 'Identifikasi obat yang berpotensi menyebabkan kerusakan',
          model: 'identifikasiObatYangBerpotensiMenyebabkanKerusakan',
        },
        {
          labelDetail: '3. Pastikan pencahayaan yang memadai',
          value: 'Pastikan pencahayaan yang memadai',
          model: 'pastikanPencahayaanYangMemadai',
        },
        {
          labelDetail: '4. Sosialisasikan pasien dan keluarga',
          value: 'Sosialisasikan pasien dan keluarga',
          model: 'sosialisasikanPasienDanKeluarga',
        },
        {
          labelDetail: '5. Sediakan pispot atau informasi yang jelas',
          value: 'Sediakan pispot atau informasi yang jelas',
          model: 'sediakanPispotAtauInformasiYangJelas',
        },
        {
          labelDetail: '6. Tingkatkan komunikasi dan transparens',
          value: 'Tingkatkan komunikasi dan transparens',
          model: 'tingkatkanKomunikasiDanTransparens',
        },
        {
          labelDetail: '7. Anjurkan dan delegasikan tugas',
          value: 'Anjurkan dan delegasikan tugas',
          model: 'anjurkanDanDelegasikanTugas',
        },
        {
          labelDetail: '8. Diskusikan hasil dan rencana dengan tim',
          value: 'Diskusikan hasil dan rencana dengan tim',
          model: 'diskusikanHasilDanRencanaDenganTim',
        },
        {
          labelDetail: '9. Pastikan keamanan dan kesejahteraan pasien',
          value: 'Pastikan keamanan dan kesejahteraan pasien',
          model: 'pastikanKeamananDanKesejahteraanPasien',
        },
        {
          labelDetail: '10. Pastikan komunikasi dan kolaborasi yang efektif',
          value: 'Pastikan komunikasi dan kolaborasi yang efektif',
          model: 'pastikanKomunikasiDanKolaborasiYangEfektif',
        },
        {
          labelDetail: '11. Pertahankan hubungan yang baik dengan keluarga',
          value: 'Pertahankan hubungan yang baik dengan keluarga',
          model: 'pertahankanHubunganYangBaikDenganKeluarga',
        },
        {
          labelDetail: '12. Pertahankan kesabaran dan partisipasi',
          value: 'Pertahankan kesabaran dan partisipasi',
          model: 'pertahankanKesabaranDanPartisipasi',
        },
        {
          labelDetail: '13. Gunakan peralatan dan sumber daya yang tersedia',
          value: 'Gunakan peralatan dan sumber daya yang tersedia',
          model: 'gunakanPeralatanDanSumberDayaYangTersedia',
        },
        {
          labelDetail: '14. Diskusikan hasil dan rencana dengan keluarga',
          value: 'Diskusikan hasil dan rencana dengan keluarga',
          model: 'diskusikanHasilDanRencanaDenganKeluarga',
        },
        {
          labelDetail: '15. Anjurkan pertemuan dan komunikasi berkala',
          value: 'Anjurkan pertemuan dan komunikasi berkala',
          model: 'anjurkanPertemuanDanKomunikasiBerkala',
        },
        {
          labelDetail: '16. Minta keluarga untuk terlibat',
          value: 'Minta keluarga untuk terlibat',
          model: 'mintaKeluargaUntukTerlibat',
        },
        {
          labelDetail: '17. Jelaskan proses dan hasil',
          value: 'Jelaskan proses dan hasil',
          model: 'jelaskanProsesDanHasil',
        },
        {
          labelDetail: '18. Pertahankan komunikasi yang terbuka dan transparan',
          value: 'Pertahankan komunikasi yang terbuka dan transparan',
          model: 'pertahankanKomunikasiYangTerbukaDanTransparan',
        },
        {
          labelDetail: '19. Pastikan keamanan dan privasi pasien',
          value: 'Pastikan keamanan dan privasi pasien',
          model: 'pastikanKeamananDanPrivasiPasien',
        },
        {
          labelDetail: '20. Pertahankan kesabaran dan partisipasi',
          value: 'Pertahankan kesabaran dan partisipasi',
          model: 'pertahankanKesabaranDanPartisipasi',
        },
        {
          labelDetail: '21. Pertahankan komunikasi yang efektif',
          value: 'Pertahankan komunikasi yang efektif',
          model: 'pertahankanKomunikasiYangEfektif',
        },
        {
          labelDetail: '22. Pertahankan hubungan yang baik dengan keluarga',
          value: 'Pertahankan hubungan yang baik dengan keluarga',
          model: 'pertahankanHubunganYangBaikDenganKeluarga',
        },
        {
          labelDetail: '23. Gunakan peralatan dan sumber daya yang tersedia',
          value: 'Gunakan peralatan dan sumber daya yang tersedia',
          model: 'gunakanPeralatanDanSumberDayaYangTersedia',
        },
        {
          labelDetail: '24. Diskusikan hasil dan rencana dengan tim',
          value: 'Diskusikan hasil dan rencana dengan tim',
          model: 'diskusikanHasilDanRencanaDenganTim',
        },
        {
          labelDetail: '25. Minta keluarga untuk terlibat',
          value: 'Minta keluarga untuk terlibat',
          model: 'mintaKeluargaUntukTerlibat',
        },
        {
          labelDetail: '26. Jelaskan proses dan hasil',
          value: 'Jelaskan proses dan hasil',
          model: 'jelaskanProsesDanHasil',
        },
        {
          labelDetail: '27. Pertahankan komunikasi yang terbuka dan transparan',
          value: 'Pertahankan komunikasi yang terbuka dan transparan',
          model: 'pertahankanKomunikasiYangTerbukaDanTransparan',
        },
        {
          labelDetail: '28. Pertahankan keamanan dan kesejahteraan pasien',
          value: 'Pertahankan keamanan dan kesejahteraan pasien',
          model: 'pertahankanKeamananDanKesejahteraanPasien',
        },
        {
          labelDetail: '29. Pertahankan kesabaran dan partisipasi',
          value: 'Pertahankan kesabaran dan partisipasi',
          model: 'pertahankanKesabaranDanPartisipasi',
        },
      ],
    },
  ]
}

export function DefisitNutrisi() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Resiko cedera berhubungan dengan',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Kurangnya asupan makanan',
          value: 'Kurangnya asupan makanan',
          model: 'kurangnyaAsupanMakanan',
        },
        {
          labelDetail: '2. Ketidakmampuan menelan makanan',
          value: 'Ketidakmampuan menelan makanan',
          model: 'ketidakmampuanMenelanMakanan',
        },
        {
          labelDetail: '3. Ketidakmampuan mencerna makanan',
          value: 'Ketidakmampuan mencerna makanan',
          model: 'ketidakmampuanMencernaMakanan',
        },
        {
          labelDetail: '4. Ketidakmampuan mengabsorbsi nutrien',
          value: 'Ketidakmampuan mengabsorbsi nutrien',
          model: 'ketidakmampuanMengabsorbsiNutrien',
        },
        {
          labelDetail: '5. Peningkatan kebutuhan metabolisme',
          value: 'Peningkatan kebutuhan metabolisme',
          model: 'peningkatanKebutuhanMetabolisme',
        },
        {
          labelDetail: '6. Faktor ekonomi (misalnya, tidak mencukupi)',
          value: 'Faktor ekonomi (misalnya, tidak mencukupi)',
          model: 'faktorEkonomi',
        },
        {
          labelDetail: '7. Faktor psikologis (misalnya, stres, keengganan makan)',
          value: 'Faktor psikologis (misalnya, stres, keengganan makan)',
          model: 'faktorPsikologis',
        },
        {
          labelDetail: '8. Cepat kenyang setelah makan',
          value: 'Cepat kenyang setelah makan',
          model: 'cepatKenyangSetelahMakan',
        },
        {
          labelDetail: '10. Kram dan nyeri abdomen',
          value: 'Kram dan nyeri abdomen',
          model: 'kramDanNyeriAbdomen',
        },
        {
          labelDetail: '11. Nafsu makan menurun',
          value: 'Nafsu makan menurun',
          model: 'nafsuMakanMenurun',
        },
        {
          labelDetail: '12. Berat badan menurun, minimal 10% di bawah rentang ideal',
          value: 'Berat badan menurun, minimal 10% di bawah rentang ideal',
          model: 'beratBadanMenurun',
        },
        {
          labelDetail: '13. Bising hiperaktif',
          value: 'Bising hiperaktif',
          model: 'bisingHiperaktif',
        },
        {
          labelDetail: '14. Otor mengunyah lemah',
          value: 'Otor mengunyah lemah',
          model: 'otorMengunyahLemah',
        },
        {
          labelDetail: '15. Otot menelan lemah',
          value: 'Otot menelan lemah',
          model: 'ototMenelanLemah',
        },
        {
          labelDetail: '16. Memberan mukosa pucat',
          value: 'Memberan mukosa pucat',
          model: 'memberanMukosaPucat',
        },
        { labelDetail: '17. Sariawan', value: 'Sariawan', model: 'sariawan' },
        {
          labelDetail: '18. Serum albumin turun',
          value: 'Serum albumin turun',
          model: 'serumAlbuminTurun',
        },
        {
          labelDetail: '19. Rambut rontok berlebihan',
          value: 'Rambut rontok berlebihan',
          model: 'rambutRontokBerlebihan',
        },
        { labelDetail: '20. Diare', value: 'Diare', model: 'diare' },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Porsi makanan yang dihabiskan meningkat',
          value: 'Porsi makanan yang dihabiskan meningkat',
          model: 'porsiMakananYangDihabiskanMeningkat',
        },
        {
          labelDetail: '2. Kekuatan otot pengunyah meningkat',
          value: 'Kekuatan otot pengunyah meningkat',
          model: 'kekuatanOtotPengunyahMeningkat',
        },
        {
          labelDetail: '3. Kekuatan otot menelan meningkat',
          value: 'Kekuatan otot menelan meningkat',
          model: 'kekuatanOtotMenelanMeningkat',
        },
        {
          labelDetail: '4. Frekuensi makan membaik',
          value: 'Frekuensi makan membaik',
          model: 'frekuensiMakanMembaik',
        },
        {
          labelDetail: '5. Nafsu makan membaik',
          value: 'Nafsu makan membaik',
          model: 'nafsuMakanMembaik',
        },
        {
          labelDetail: '6. Peristaltic usus',
          value: 'Peristaltic usus',
          model: 'peristalticUsus',
        },
        {
          labelDetail: '7. Nyeri abdomen menurun',
          value: 'Nyeri abdomen menurun',
          model: 'nyeriAbdomenMenurun',
        },
        {
          labelDetail: '8. Diare menurun',
          value: 'Diare menurun',
          model: 'diareMenurun',
        },
        {
          labelDetail: '9. Serum albumin meningkat',
          value: 'Serum albumin meningkat',
          model: 'serumAlbuminMeningkat',
        },
        {
          labelDetail: '10. Berat badan membaik',
          value: 'Berat badan membaik',
          model: 'beratBadanMembaik',
        },
        {
          labelDetail: '11. Dyspepsia menurun',
          value: 'Dyspepsia menurun',
          model: 'dyspepsiaMenurun',
        },
        {
          labelDetail: '12. Jumlah residu cairan lambung menurun',
          value: 'Jumlah residu cairan lambung menurun',
          model: 'jumlahResiduCairanLambungMenurun',
        },
        {
          labelDetail: '13. Verbalisasi keinginan untuk meningkatkan nutrisi meningkat',
          value: 'Verbalisasi keinginan untuk meningkatkan nutrisi meningkat',
          model: 'verbalisasiKeinginanUntukMeningkatkanNutrisiMeningkat',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Identifikasi status nutrisi',
          value: 'Identifikasi status nutrisi',
          model: 'identifikasiStatusNutrisi',
        },
        {
          labelDetail: '2. Identifikasi alergi dan intoleransi makanan',
          value: 'Identifikasi alergi dan intoleransi makanan',
          model: 'identifikasiAlergiDanIntoleransiMakanan',
        },
        {
          labelDetail: '3. Identifikasi kebutuhan kalori dan jenis nutrien',
          value: 'Identifikasi kebutuhan kalori dan jenis nutrien',
          model: 'identifikasiKebutuhanKaloriDanJenisNutrien',
        },
        {
          labelDetail: '4. Monitor asupan makan',
          value: 'Monitor asupan makan',
          model: 'monitorAsupanMakan',
        },
        {
          labelDetail: '5. Monitor berat badan',
          value: 'Monitor berat badan',
          model: 'monitorBeratBadan',
        },
        {
          labelDetail: '6. Monitor pemeriksaan laboratorium',
          value: 'Monitor pemeriksaan laboratorium',
          model: 'monitorPemeriksaanLaboratorium',
        },
        {
          labelDetail: '7. Identifikasi perlunya penggunaan selang nasogastric',
          value: 'Identifikasi perlunya penggunaan selang nasogastric',
          model: 'identifikasiPerlunyaPenggunaanSelangNasogastric',
        },
        {
          labelDetail: '8. Lakukan oral hygiene sebelum makan, jika perlu',
          value: 'Lakukan oral hygiene sebelum makan, jika perlu',
          model: 'lakukanOralHygieneSebelumMakanJikaPerlu',
        },
        {
          labelDetail: '9. Fasilitasi menentukan pedoman diet',
          value: 'Fasilitasi menentukan pedoman diet',
          model: 'fasilitasiMenentukanPedomanDiet',
        },
        {
          labelDetail: '10. Berikan makan tinggi serat untuk mencegah konstipasi',
          value: 'Berikan makan tinggi serat untuk mencegah konstipasi',
          model: 'berikanMakanTinggiSeratUntukMencegahKonstipasi',
        },
        {
          labelDetail: '11. Berikan makan tinggi kalori dan tinggi protein',
          value: 'Berikan makan tinggi kalori dan tinggi protein',
          model: 'berikanMakanTinggiKaloriDanTinggiProtein',
        },
        {
          labelDetail:
            '12. Hentikan pemberian makan melalui selang nasogatrik jika asupan oral dapat ditoleransi',
          value:
            'Hentikan pemberian makan melalui selang nasogatrik jika asupan oral dapat ditoleransi',
          model:
            'hentikanPemberianMakanMelaluiSelangNasogatrikJikaAsupanOralDapatDitoleransi',
        },
        {
          labelDetail: '13. Anjurkan posisi duduk, jika mampu',
          value: 'Anjurkan posisi duduk, jika mampu',
          model: 'anjurkanPosisiDudukJikaMampu',
        },
        {
          labelDetail: '14. Ajarkan diet yang diprogramkan',
          value: 'Ajarkan diet yang diprogramkan',
          model: 'ajarkanDietYangDiprogramkan',
        },
        {
          labelDetail: '15. Kolaborasi pemberian medikasi sebelum makan, jika perlu',
          value: 'Kolaborasi pemberian medikasi sebelum makan, jika perlu',
          model: 'kolaborasiPemberianMedikasiSebelumMakanJikaPerlu',
        },
        {
          labelDetail:
            '16. Kolaborasi dengan ahli gizi untuk menentukan jumlah kalori dan jenis nutrien yang dibutuhkan, jika perlu',
          value:
            'Kolaborasi dengan ahli gizi untuk menentukan jumlah kalori dan jenis nutrien yang dibutuhkan, jika perlu',
          model:
            'kolaborasiDenganAhliGiziUntukMenentukanJumlahKaloriDanJenisNutrienYangDibutuhkanJikaPerlu',
        },
        {
          labelDetail: '17. Identifikasi penyebab kemungkinan BB kurang',
          value: 'Identifikasi penyebab kemungkinan BB kurang',
          model: 'identifikasiPenyebabKemungkinanBbKurang',
        },
        {
          labelDetail: '18. Identifikasi makanan yang disukai',
          value: 'Identifikasi makanan yang disukai',
          model: 'identifikasiMakananYangDisukai',
        },
        {
          labelDetail: '19. Monitor mual dan muntah',
          value: 'Monitor mual dan muntah',
          model: 'monitorMualDanMuntah',
        },
        {
          labelDetail: '20. Monitor warna konjungtiva',
          value: 'Monitor warna konjungtiva',
          model: 'monitorWarnaKonjungtiva',
        },
        {
          labelDetail: '21. Monitor Albumin, Limfosit dan elektrolit serum',
          value: 'Monitor Albumin, Limfosit dan elektrolit serum',
          model: 'monitorAlbuminLimfositDanElektrolitSerum',
        },
        {
          labelDetail: '22. Identifikasi kemampuan menelan',
          value: 'Identifikasi kemampuan menelan',
          model: 'identifikasiKemampuanMenelan',
        },
        {
          labelDetail: '23. Identifikasi perlunya penggunaan selang nasogastric',
          value: 'Identifikasi perlunya penggunaan selang nasogastric',
          model: 'identifikasiPerlunyaPenggunaanSelangNasogastric',
        },
        {
          labelDetail: '24. Berikan perawatan mulut sebelum memberikan makan',
          value: 'Berikan perawatan mulut sebelum memberikan makan',
          model: 'berikanPerawatanMulutSebelumMemberikanMakan',
        },
        {
          labelDetail: '25. Sediakan makanan yang tepat sesuai dengan kondisi pasien',
          value: 'Sediakan makanan yang tepat sesuai dengan kondisi pasien',
          model: 'sediakanMakananYangTepatSesuaiDenganKondisiPasien',
        },
        {
          labelDetail: '26. Jelaskan makanan yang bergizi tinggi',
          value: 'Jelaskan makanan yang bergizi tinggi',
          model: 'jelaskanMakananYangBergiziTinggi',
        },
        {
          labelDetail: '27. Berikan suplemen makanan',
          value: 'Berikan suplemen makanan',
          model: 'berikanSuplemenMakanan',
        },
        {
          labelDetail: '28. Anjurkan makan sedikit tapi sering',
          value: 'Anjurkan makan sedikit tapi sering',
          model: 'anjurkanMakanSedikitTapiSering',
        },
      ],
    },
  ]
}

export function Hipertermia() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Hipertermia berhubungan dengan',
      detailDiagnosaKeperawatan: [
        { labelDetail: '1. Dehidrasi', value: 'Dehidrasi', model: 'dehidrasi' },
        {
          labelDetail: '2. Terpapar lingkungan panas',
          value: 'Terpapar lingkungan panas',
          model: 'terpaparLingkunganPanas',
        },
        {
          labelDetail: '3. Proses penyakit (mis. Infeksi, kanker)',
          value: 'Proses penyakit (mis. Infeksi, kanker)',
          model: 'prosesPenyakit',
        },
        {
          labelDetail: '4. Ketidaksesuaian pakaian dengan suhu lingkungan',
          value: 'Ketidaksesuaian pakaian dengan suhu lingkungan',
          model: 'ketidaksesuaianPakaianDenganSuhuLingkungan',
        },
        {
          labelDetail: '5. Peningkatan laju metabolisme',
          value: 'Peningkatan laju metabolisme',
          model: 'peningkatanLajuMetabolisme',
        },
        {
          labelDetail: '6. Respon trauma',
          value: 'Respon trauma',
          model: 'responTrauma',
        },
        {
          labelDetail: '7. Aktivitas berlebihan',
          value: 'Aktivitas berlebihan',
          model: 'aktivitasBerlebihan',
        },
        {
          labelDetail: '8. Penggunaan inkubator',
          value: 'Penggunaan inkubator',
          model: 'penggunaanInkubator',
        },
        { labelDetail: '9. Prematuritas', value: 'Prematuritas', model: 'prematuritas' },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Suhu tubuh membaik',
          value: 'Suhu tubuh membaik',
          model: 'suhuTubuhMembaik',
        },
        {
          labelDetail: '2. Menggigil menurun',
          value: 'Menggigil menurun',
          model: 'menggigilMenurun',
        },
        {
          labelDetail: '3. Kulit merah menurun',
          value: 'Kulit merah menurun',
          model: 'kulitMerahMenurun',
        },
        {
          labelDetail: '4. Akrosianosis menurun',
          value: 'Akrosianosis menurun',
          model: 'akrosianosisMenurun',
        },
        {
          labelDetail: '5. Kejang menurun',
          value: 'Kejang menurun',
          model: 'kejangMenurun',
        },
        {
          labelDetail: '6. Takikardia menurun',
          value: 'Takikardia menurun',
          model: 'takikardiaMenurun',
        },
        {
          labelDetail: '7. Takipneu menurun',
          value: 'Takipneu menurun',
          model: 'takipneuMenurun',
        },
        {
          labelDetail: '8. Tekanan darah membaik',
          value: 'Tekanan darah membaik',
          model: 'tekananDarahMembaik',
        },
        {
          labelDetail: '9. Hipoksia menurun',
          value: 'Hipoksia menurun',
          model: 'hipoksiaMenurun',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Identifikasi penyebab hipertermia',
          value: 'Identifikasi penyebab hipertermia',
          model: 'identifikasiPenyebabHipertermia',
        },
        {
          labelDetail: '2. Monitor suhu tubuh',
          value: 'Monitor suhu tubuh',
          model: 'monitorSuhuTubuh',
        },
        {
          labelDetail: '3. Monitor kadar elektrolit',
          value: 'Monitor kadar elektrolit',
          model: 'monitorKadarElektrolit',
        },
        {
          labelDetail: '4. Monitor haluaran urine',
          value: 'Monitor haluaran urine',
          model: 'monitorHaluaranUrine',
        },
        {
          labelDetail: '5. Monitor komplikasi akibat hipertermi',
          value: 'Monitor komplikasi akibat hipertermi',
          model: 'monitorKomplikasiAkibatHipertermi',
        },
        {
          labelDetail: '6. Sediakan lingkungan yang dingin',
          value: 'Sediakan lingkungan yang dingin',
          model: 'sediakanLingkunganYangDingin',
        },
        {
          labelDetail: '7. Longgarkan atau lepaskan pakaian',
          value: 'Longgarkan atau lepaskan pakaian',
          model: 'longgarkanAtauLepaskanPakaian',
        },
        {
          labelDetail: '8. Berikan cairan oral',
          value: 'Berikan cairan oral',
          model: 'berikanCairanOral',
        },
        {
          labelDetail: '9. Anjurkan tirah baring',
          value: 'Anjurkan tirah baring',
          model: 'anjurkanTirahBaring',
        },
        {
          labelDetail: '10. Berikan oksigen jika perlu',
          value: 'Berikan oksigen jika perlu',
          model: 'berikanOksigenJikaPerlu',
        },
        {
          labelDetail: '11. Kolaborasi pemberian cairan dan elektrolit intravena',
          value: 'Kolaborasi pemberian cairan dan elektrolit intravena',
          model: 'kolaborasiPemberianCairanDanElektrolitIntravena',
        },
        {
          labelDetail: '12. Monitor warna dan suhu kulit',
          value: 'Monitor warna dan suhu kulit',
          model: 'monitorWarnaDanSuhuKulit',
        },
        {
          labelDetail: '13. Monitor vital sign (Nadi, RR, TD, Temperatur)',
          value: 'Monitor vital sign (Nadi, RR, TD, Temperatur)',
          model: 'monitorVitalSign',
        },
        {
          labelDetail: '14. Berikan kompres hangat atau dingin',
          value: 'Berikan kompres hangat atau dingin',
          model: 'berikanKompresHangatAtauDingin',
        },
        {
          labelDetail: '15. Tingkatkan asupan cairan dan nutrisi yang adekuat',
          value: 'Tingkatkan asupan cairan dan nutrisi yang adekuat',
          model: 'tingkatkanAsupanCairanDanNutrisiYangAdekuat',
        },
        {
          labelDetail: '16. Pasang alat pemantauan suhu kontinu',
          value: 'Pasang alat pemantauan suhu kontinu',
          model: 'pasangAlatPemantauanSuhuKontinu',
        },
        {
          labelDetail:
            '17. Lakukan pendinginan eksternal (penggunaan selimut hipotermia)',
          value: 'Lakukan pendinginan eksternal (penggunaan selimut hipotermia)',
          model: 'lakukanPendinginanEksternal',
        },
        {
          labelDetail: '18. Atur suhu inkubator sesuai kebutuhan',
          value: 'Atur suhu inkubator sesuai kebutuhan',
          model: 'aturSuhuInkubatorSesuaiKebutuhan',
        },
        {
          labelDetail: '19. Kolaborasi pemberian antipiretik',
          value: 'Kolaborasi pemberian antipiretik',
          model: 'kolaborasiPemberianAntipiretik',
        },
      ],
    },
  ]
}

export function Ansietas() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Ansietas berhubungan dengan',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Krisis situasional',
          value: 'Krisis situasional',
          model: 'krisisSituasional',
        },
        {
          labelDetail: '2. Kebutuhan tidak terpenuhi',
          value: 'Kebutuhan tidak terpenuhi',
          model: 'kebutuhanTidakTerpenuhi',
        },
        {
          labelDetail: '3. Krisis maturasional',
          value: 'Krisis maturasional',
          model: 'krisisMaturasional',
        },
        {
          labelDetail: '4. Ancaman terhadap konsep diri',
          value: 'Ancaman terhadap konsep diri',
          model: 'ancamanTerhadapKonsepDiri',
        },
        {
          labelDetail: '5. Ancaman terhadap kematian',
          value: 'Ancaman terhadap kematian',
          model: 'ancamanTerhadapKematian',
        },
        {
          labelDetail: '6. Kekhawatiran mengalami kegagalan',
          value: 'Kekhawatiran mengalami kegagalan',
          model: 'kekhawatiranMengalamiKegagalan',
        },
        {
          labelDetail: '7. Disfungsi sistem keluarga',
          value: 'Disfungsi sistem keluarga',
          model: 'disfungsiSistemKeluarga',
        },
        {
          labelDetail: '8. Hubungan orang tua-anak tidak memuaskan',
          value: 'Hubungan orang tua-anak tidak memuaskan',
          model: 'hubunganOrangTuaAnakTidakMemuaskan',
        },
        {
          labelDetail: '9. Faktor keturunan (temperamen mudah teragitasi sejak lahir)',
          value: 'Faktor keturunan (temperamen mudah teragitasi sejak lahir)',
          model: 'faktorKeturunan',
        },
        {
          labelDetail: '10. Penyalahgunaan zat',
          value: 'Penyalahgunaan zat',
          model: 'penyalahgunaanZat',
        },
        {
          labelDetail: '11. Terpapar bahaya lingkungan (toksin, polutan, dll)',
          value: 'Terpapar bahaya lingkungan (toksin, polutan, dll)',
          model: 'terpaparBahayaLingkungan',
        },
        {
          labelDetail: '12. Kurang terpapar informasi',
          value: 'Kurang terpapar informasi',
          model: 'kurangTerpaparInformasi',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Verbalisasi kebingungan menurun',
          value: 'Verbalisasi kebingungan menurun',
          model: 'verbalisasiKebingunganMenurun',
        },
        {
          labelDetail: '2. Verbalisasi kekhawatiran akibat kondisi yang dihadapi menurun',
          value: 'Verbalisasi kekhawatiran akibat kondisi yang dihadapi menurun',
          model: 'verbalisasiKekhawatiranMenurun',
        },
        {
          labelDetail: '3. Perilaku gelisah menurun',
          value: 'Perilaku gelisah menurun',
          model: 'perilakuGelisahMenurun',
        },
        {
          labelDetail: '4. Perilaku tegang menurun',
          value: 'Perilaku tegang menurun',
          model: 'perilakuTegangMenurun',
        },
        {
          labelDetail: '5. Keluhan pusing menurun',
          value: 'Keluhan pusing menurun',
          model: 'keluhanPusingMenurun',
        },
        {
          labelDetail: '6. Diaforesis menurun',
          value: 'Diaforesis menurun',
          model: 'diaforesisMenurun',
        },
        {
          labelDetail: '7. Frekuensi HR, RR dan TD membaik',
          value: 'Frekuensi HR, RR dan TD membaik',
          model: 'frekuensiHRRRTDMembaik',
        },
        {
          labelDetail: '8. Konsentrasi membaik',
          value: 'Konsentrasi membaik',
          model: 'konsentrasiMembaik',
        },
        {
          labelDetail: '9. Pola tidur membaik',
          value: 'Pola tidur membaik',
          model: 'polaTidurMembaik',
        },
        {
          labelDetail: '10. Anoreksia menurun',
          value: 'Anoreksia menurun',
          model: 'anoreksiaMenurun',
        },
        {
          labelDetail: '11. Palpitasi menurun',
          value: 'Palpitasi menurun',
          model: 'palpitasiMenurun',
        },
        {
          labelDetail: '12. Tremor menurun',
          value: 'Tremor menurun',
          model: 'tremorMenurun',
        },
        {
          labelDetail: '13. Pucat menurun',
          value: 'Pucat menurun',
          model: 'pucatMenurun',
        },
        {
          labelDetail: '14. Pola berkemih membaik',
          value: 'Pola berkemih membaik',
          model: 'polaBerkemihMembaik',
        },
        {
          labelDetail: '15. Kontak mata membaik',
          value: 'Kontak mata membaik',
          model: 'kontakMataMembaik',
        },
        {
          labelDetail: '16. Orientasi membaik',
          value: 'Orientasi membaik',
          model: 'orientasiMembaik',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail:
            '1. Identifikasi saat tingkat ansietas berubah (kondisi, waktu, stresor)',
          value: 'Identifikasi saat tingkat ansietas berubah',
          model: 'identifikasiTingkatAnsietasBerubah',
        },
        {
          labelDetail: '2. Identifikasi kemampuan mengambil keputusan',
          value: 'Identifikasi kemampuan mengambil keputusan',
          model: 'identifikasiKemampuanMengambilKeputusan',
        },
        {
          labelDetail: '3. Monitor tanda-tanda ansietas (verbal dan non verbal)',
          value: 'Monitor tanda-tanda ansietas',
          model: 'monitorTandaTandaAnsietas',
        },
        {
          labelDetail: '4. Ciptakan suasana terapeutik untuk menumbuhkan kepercayaan',
          value: 'Ciptakan suasana terapeutik',
          model: 'ciptakanSuasanaTerapeutik',
        },
        {
          labelDetail: '5. Temani pasien untuk mengurangi kecemasan',
          value: 'Temani pasien untuk mengurangi kecemasan',
          model: 'temaniPasienKurangiKecemasan',
        },
        {
          labelDetail: '6. Pahami situasi yang membuat ansietas',
          value: 'Pahami situasi yang membuat ansietas',
          model: 'pahamiSituasiAnsietas',
        },
        {
          labelDetail: '7. Latih teknik relaksasi',
          value: 'Latih teknik relaksasi',
          model: 'latihTeknikRelaksasi',
        },
        {
          labelDetail: '8. Dengarkan dengan penuh perhatian',
          value: 'Dengarkan dengan penuh perhatian',
          model: 'dengarkanDenganPenuhPerhatian',
        },
        {
          labelDetail: '9. Gunakan pendekatan yang tenang dan meyakinkan',
          value: 'Gunakan pendekatan tenang dan meyakinkan',
          model: 'pendekatanTenangMeyakinkan',
        },
        {
          labelDetail: '10. Kolaborasi pemberian obat antiansietas, jika perlu',
          value: 'Kolaborasi pemberian obat antiansietas',
          model: 'kolaborasiObatAntiansietas',
        },
        {
          labelDetail: '11. Identifikasi kehilangan yang dihadapi',
          value: 'Identifikasi kehilangan yang dihadapi',
          model: 'identifikasiKehilanganDihadapi',
        },
        {
          labelDetail: '12. Identifikasi proses berduka yang dialami',
          value: 'Identifikasi proses berduka yang dialami',
          model: 'identifikasiProsesBerduka',
        },
        {
          labelDetail:
            '13. Identifikasi kebutuhan pelaksanaan ibadah sesuai agama yang dianut',
          value: 'Identifikasi kebutuhan ibadah',
          model: 'identifikasiKebutuhanIbadah',
        },
        {
          labelDetail: '14. Identifikasi reaksi awal terhadap kehilangan',
          value: 'Identifikasi reaksi awal terhadap kehilangan',
          model: 'identifikasiReaksiKehilangan',
        },
        {
          labelDetail: '15. Tunjukkan sikap menerima dan empati',
          value: 'Tunjukkan sikap menerima dan empati',
          model: 'sikapMenerimaEmpati',
        },
        {
          labelDetail:
            '16. Motivasi untuk menguatkan dukungan keluarga atau orang terdekat',
          value: 'Motivasi dukungan keluarga/orang terdekat',
          model: 'motivasiDukunganKeluarga',
        },
        {
          labelDetail:
            '17. Fasilitasi melakukan kebiasaan sesuai dengan budaya, agama, dan norma sosial',
          value: 'Fasilitasi kebiasaan budaya/agama',
          model: 'fasilitasiKebiasaanBudayaAgama',
        },
        {
          labelDetail:
            '18. Fasilitasi penuntunan ibadah oleh keluarga dan/atau rohaniawan',
          value: 'Fasilitasi penuntunan ibadah',
          model: 'fasilitasiPenuntunanIbadah',
        },
        {
          labelDetail: '19. Anjurkan mengekspresikan perasaan tentang kehilangan',
          value: 'Anjurkan mengekspresikan perasaan',
          model: 'anjurkanEkspresiPerasaan',
        },
        {
          labelDetail: '20. Ajarkan melewati proses berduka secara bertahap',
          value: 'Ajarkan proses berduka',
          model: 'ajarkanProsesBerduka',
        },
        {
          labelDetail:
            '21. Jelaskan secara faktual mengenai diagnosis, pengobatan, dan prognosis',
          value: 'Jelaskan mengenai diagnosis, pengobatan, prognosis',
          model: 'jelaskanDiagnosisPengobatanPrognosis',
        },
        {
          labelDetail: '22. Anjurkan keluarga untuk tetap bersama pasien',
          value: 'Anjurkan keluarga bersama pasien',
          model: 'anjurkanKeluargaBersamaPasien',
        },
        {
          labelDetail: '23. Anjurkan mengungkapkan perasaan dan persepsi',
          value: 'Anjurkan ungkapan perasaan/persepsi',
          model: 'anjurkanUngkapanPerasaan',
        },
        {
          labelDetail:
            '24. Rujuk pada rohaniawan, konseling profesi, dan kelompok pendukung pada situasi spiritual dan ritual',
          value: 'Rujuk pada rohaniawan, konseling, dan dukungan',
          model: 'rujukRohaniawanKonselingDukungan',
        },
      ],
    },
  ]
}

export function AnsietasDS() {
  return [
    {
      label: '1. Merasa Bingung',
      value: 'Merasa Bingung',
      model: 'merasaBingung',
    },
    {
      label: '2. Merasa Kawatir',
      value: 'Merasa khawatir dengan akibat dari kondisi yang dihadapi',
      model: 'merasaKawatir',
    },
    {
      label: '3. Sulit Berkonsentrasi',
      value: 'Sulit Berkonsentrasi',
      model: 'sulitBerkonsentrasi',
    },
    {
      label: '4. Mengeluh pusing',
      value: 'Mengeluh pusing',
      model: 'mengeluhPusing',
    },
    {
      label: '5. Anoreksia',
      value: 'Anoreksia',
      model: 'anoreksia',
    },
    {
      label: '6. Merasa tidak berdaya',
      value: 'Merasa tidak berdaya',
      model: 'merasaTidakBerdaya',
    },
  ]
}
export function AnsietasDO() {
  return [
    {
      label: '1. Tampak Gelisah',
      value: 'Tampak gelisah',
      model: 'tampakGelisah',
    },
    {
      label: '2. Tampak Tegang',
      value: 'Tampak tegang',
      model: 'tampakTegang',
    },
    {
      label: '3. Sulit Tidur',
      value: 'Sulit tidur',
      model: 'sulitTidur',
    },
    {
      label: '4. Frekuensi RR, HR Meningkat',
      value: 'Frekuensi RR, HR meningkat',
      model: 'frekuensiRRHRMeningkat',
    },
    {
      label: '5. TD Meningkat',
      value: 'TD meningkat',
      model: 'tdMeningkat',
    },
    {
      label: '6. Diaforesis',
      value: 'Diaforesis',
      model: 'diaforesis',
    },
    {
      label: '7. Tremor',
      value: 'Tremor',
      model: 'tremor',
    },
    {
      label: '8. Muka Tampak Pucat',
      value: 'Muka tampak pucat',
      model: 'mukaTampakPucat',
    },
    {
      label: '9. Suara Bergetar',
      value: 'Suara bergetar',
      model: 'suaraBergetar',
    },
    {
      label: '10. Kontak Mata Buruk',
      value: 'Kontak mata buruk',
      model: 'kontakMataBuruk',
    },
    {
      label: '11. Sering Berkemih',
      value: 'Sering berkemih',
      model: 'seringBerkemih',
    },
    {
      label: '12. Berorientasi pada Masa Lalu',
      value: 'Berorientasi pada masa lalu',
      model: 'berorientasiMasaLalu',
    },
  ]
}
export function Berduka() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Ansietas berhubungan dengan',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Kematian keluarga atau orang yang berarti',
          value: 'Kematian keluarga atau orang yang berarti',
          model: 'kematianKeluargaAtauOrangYangBerarti',
        },
        {
          labelDetail: '2. Antisipasi kematian keluarga atau orang yang berarti',
          value: 'Antisipasi kematian keluarga atau orang yang berarti',
          model: 'antisipasiKematianKeluargaAtauOrangYangBerarti',
        },
        {
          labelDetail:
            '3. Kehilangan (objek, pekerjaan, status, bagian tubuh, hubungan sosial)',
          value: 'Kehilangan (objek, pekerjaan, status, bagian tubuh, hubungan sosial)',
          model: 'kehilangan',
        },
        {
          labelDetail:
            '4. Antisipasi kehilangan (objek, pekerjaan, status, bagian tubuh, hubungan sosial)',
          value:
            'Antisipasi kehilangan (objek, pekerjaan, status, bagian tubuh, hubungan sosial)',
          model: 'antisipasiKehilangan',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Verbalisasi menerima kehilangan',
          value: 'Verbalisasi menerima kehilangan',
          model: 'verbalisasiMenerimaKehilangan',
        },
        {
          labelDetail: '2. Verbalisasi harapan meningkat',
          value: 'Verbalisasi harapan meningkat',
          model: 'verbalisasiHarapanMeningkat',
        },
        {
          labelDetail: '3. Verbalisas perasaan sedih',
          value: 'Verbalisas perasaan sedih',
          model: 'verbalisasPerasaanSedih',
        },
        {
          labelDetail: '4. Verbalisas perasaan bersalah atau menyalahkan orang lain',
          value: 'Verbalisas perasaan bersalah atau menyalahkan orang lain',
          model: 'verbalisasPerasaanBersalahAtauMenyalahkanOrangLain',
        },
        {
          labelDetail: '5. Menangis',
          value: 'Menangis',
          model: 'menangis',
        },
        {
          labelDetail: '6. Perasaan marah',
          value: 'Perasaan marah',
          model: 'perasaanMarah',
        },
        {
          labelDetail: '7. Pola tidur membaik',
          value: 'Pola tidur membaik',
          model: 'polaTidurMembaik',
        },
        {
          labelDetail: '8. Konsentrasi membaik',
          value: 'Konsentrasi membaik',
          model: 'konsentrasiMembaik',
        },
        {
          labelDetail: '9. Verbalisasi keputusasaan',
          value: 'Verbalisasi keputusasaan',
          model: 'verbalisasiKeputusasaan',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail:
            '1. Identifikasi saat tingkat ansietas berubah (kondisi, waktu, stresor)',
          value: 'Identifikasi saat tingkat ansietas berubah',
          model: 'identifikasiTingkatAnsietasBerubah',
        },
        {
          labelDetail: '2. Identifikasi kemampuan mengambil keputusan',
          value: 'Identifikasi kemampuan mengambil keputusan',
          model: 'identifikasiKemampuanMengambilKeputusan',
        },
        {
          labelDetail: '3. Monitor tanda-tanda ansietas (verbal dan non verbal)',
          value: 'Monitor tanda-tanda ansietas',
          model: 'monitorTandaTandaAnsietas',
        },
        {
          labelDetail: '4. Ciptakan suasana terapeutik untuk menumbuhkan kepercayaan',
          value: 'Ciptakan suasana terapeutik',
          model: 'ciptakanSuasanaTerapeutik',
        },
        {
          labelDetail: '5. Temani pasien untuk mengurangi kecemasan',
          value: 'Temani pasien untuk mengurangi kecemasan',
          model: 'temaniPasienMengurangiKecemasan',
        },
        {
          labelDetail: '6. Pahami situasi yang membuat ansietas',
          value: 'Pahami situasi yang membuat ansietas',
          model: 'pahamiSituasiMembuatAnsietas',
        },
        {
          labelDetail: '7. Latih teknik relaksasi',
          value: 'Latih teknik relaksasi',
          model: 'latihTeknikRelaksasi',
        },
        {
          labelDetail: '8. Dengarkan dengan penuh perhatian',
          value: 'Dengarkan dengan penuh perhatian',
          model: 'dengarkanDenganPenuhPerhatian',
        },
        {
          labelDetail: '9. Gunakan pendekatan yang tenang dan meyakinkan',
          value: 'Gunakan pendekatan yang tenang',
          model: 'gunakanPendekatanTenangMeyakinkan',
        },
        {
          labelDetail: '10. Kolaborasi pemberian obat antiansietas, jika perlu',
          value: 'Kolaborasi pemberian obat antiansietas',
          model: 'kolaborasiPemberianObatAntiansietas',
        },
        {
          labelDetail: '11. Identifikasi kehilangan yang dihadapi',
          value: 'Identifikasi kehilangan yang dihadapi',
          model: 'identifikasiKehilanganYangDihadapi',
        },
        {
          labelDetail: '12. Identifikasi proses berduka yang dialami',
          value: 'Identifikasi proses berduka yang dialami',
          model: 'identifikasiProsesBerdukaYangDialami',
        },
        {
          labelDetail: '13. Identifikasi reaksi awal terhadap kehilangan',
          value: 'Identifikasi reaksi awal terhadap kehilangan',
          model: 'identifikasiReaksiAwalTerhadapKehilangan',
        },
        {
          labelDetail:
            '14. Identifikasi perasaan khawatir, kesepian, dan ketidakberdayaan',
          value: 'Identifikasi perasaan khawatir, kesepian, dan ketidakberdayaan',
          model: 'identifikasiPerasaanKhawatirKesepianKetidakberdayaan',
        },
        {
          labelDetail: '15. Tunjukkan sikap menerima dan empati',
          value: 'Tunjukkan sikap menerima dan empati',
          model: 'tunjukkanSikapMenerimaEmpati',
        },
        {
          labelDetail: '16. Motivasi agar mau mengungkapkan perasaan kehilangan',
          value: 'Motivasi agar mau mengungkapkan perasaan kehilangan',
          model: 'motivasiUngkapkanPerasaanKehilangan',
        },
        {
          labelDetail:
            '17. Motivasi untuk menguatkan dukungan keluarga atau orang terdekat',
          value: 'Motivasi untuk menguatkan dukungan keluarga',
          model: 'motivasiDukunganKeluarga',
        },
        {
          labelDetail:
            '18. Fasilitasi melakukan kebiasaan sesuai dengan budaya, agama, dan norma',
          value: 'Fasilitasi kebiasaan budaya, agama, dan norma',
          model: 'fasilitasiKebiasaanBudayaAgamaNorma',
        },
        {
          labelDetail: '19. Fasilitasi melakukan kegiatan ibadah',
          value: 'Fasilitasi kegiatan ibadah',
          model: 'fasilitasiKegiatanIbadah',
        },
        {
          labelDetail: '20. Diskusikan strategi koping yang dapat digunakan',
          value: 'Diskusikan strategi koping',
          model: 'diskusikanStrategiKoping',
        },
        {
          labelDetail: '21. Sediakan privasi dan waktu tenang untuk aktivitas spiritual',
          value: 'Sediakan privasi dan waktu tenang',
          model: 'sediakanPrivasiWaktuTenang',
        },
        {
          labelDetail:
            '22. Fasilitasi penuntunan ibadah oleh keluarga dan/atau rohaniawan',
          value: 'Fasilitasi penuntunan ibadah',
          model: 'fasilitasiPenuntunanIbadah',
        },
        {
          labelDetail: '23. Anjurkan mengekspresikan perasaan tentang kehilangan',
          value: 'Anjurkan ekspresi perasaan tentang kehilangan',
          model: 'anjurkanEkspresiPerasaanKehilangan',
        },
        {
          labelDetail: '24. Ajarkan melewati proses berduka secara bertahap',
          value: 'Ajarkan proses berduka bertahap',
          model: 'ajarkanProsesBerdukaBertahap',
        },
      ],
    },
  ]
}

export function AnsietasDSBerduka() {
  return [
    {
      label: '1. Merasa sedih',
      value: '1. Merasa sedih',
      model: 'merasaSedih',
    },
    {
      label: '2. Merasa bersalah atau menyalahkan orang lain',
      value: '2. Merasa bersalah atau menyalahkan orang lain',
      model: 'merasaBersalah',
    },
    {
      label: '3. Tidak menerima kehilangan',
      value: '3. Tidak menerima kehilangan',
      model: 'tidakMenerimaKehilangan',
    },
    {
      label: '4. Merasa tidak ada harapan',
      value: '4. Merasa tidak ada harapan',
      model: 'merasaTidakAdaHarapan',
    },
    {
      label: '5. Mimpi buruk atau pola mimpi berubah',
      value: '5. Mimpi buruk atau pola mimpi berubah',
      model: 'mimpiBuruk',
    },
    {
      label: '6. Merasa tidak berguna',
      value: '6. Merasa tidak berguna',
      model: 'merasaTidakBerguna',
    },
    {
      label: '7. Fobia',
      value: '7. Fobia',
      model: 'fobia',
    },
  ]
}

export function AnsietasDOBerduka() {
  return [
    {
      label: '1. Menangis',
      value: '1. Menangis',
      model: 'menangis',
    },
    {
      label: '2. Pola tidur berubah',
      value: '2. Pola tidur berubah',
      model: 'polaTidurBerubah',
    },
    {
      label: '3. Tidak mampu berkonsentrasi',
      value: '3. Tidak mampu berkonsentrasi',
      model: 'tidakMampuBerkonsentrasi',
    },
    {
      label: '4. Marah',
      value: '4. Marah',
      model: 'marah',
    },
    {
      label: '5. Tampak panik',
      value: '5. Tampak panik',
      model: 'tampakPanic',
    },
    {
      label: '6. Fungsi imunitas terganggu',
      value: '6. Fungsi imunitas terganggu',
      model: 'fungsiImunitasTerganggu',
    },
  ]
}

export function Termoregulasi() {
  return [
    {
      labelDiagnosaKeperawatan: 'Diagnosa Keperawatan',
      keteranganTambahan: 'Ansietas berhubungan dengan',
      detailDiagnosaKeperawatan: [
        {
          labelDetail:
            '1. Identifikasi saat tingkat ansietas berubah (kondisi, waktu, stresor)',
          value: 'Identifikasi saat tingkat ansietas berubah',
          model: 'identifikasiTingkatAnsietasBerubah',
        },
        {
          labelDetail: '2. Identifikasi kemampuan mengambil keputusan',
          value: 'Identifikasi kemampuan mengambil keputusan',
          model: 'identifikasiKemampuanMengambilKeputusan',
        },
        {
          labelDetail: '3. Monitor tanda-tanda ansietas (verbal dan non verbal)',
          value: 'Monitor tanda-tanda ansietas',
          model: 'monitorTandaAnsietas',
        },
        {
          labelDetail: '4. Ciptakan suasana terapeutik untuk menumbuhkan kepercayaan',
          value: 'Ciptakan suasana terapeutik',
          model: 'ciptakanSuasanaTerapeutik',
        },
        {
          labelDetail: '5. Temani pasien untuk mengurangi kecemasan',
          value: 'Temani pasien untuk mengurangi kecemasan',
          model: 'temaniPasienKurangiKecemasan',
        },
        {
          labelDetail: '6. Pahami situasi yang membuat ansietas',
          value: 'Pahami situasi yang membuat ansietas',
          model: 'pahamiSituasiMembuatAnsietas',
        },
        {
          labelDetail: '7. Latih teknik relaksasi',
          value: 'Latih teknik relaksasi',
          model: 'latihTeknikRelaksasi',
        },
        {
          labelDetail: '8. Dengarkan dengan penuh perhatian',
          value: 'Dengarkan dengan penuh perhatian',
          model: 'dengarkanPenuhPerhatian',
        },
        {
          labelDetail: '9. Gunakan pendekatan yang tenang dan meyakinkan',
          value: 'Gunakan pendekatan tenang dan meyakinkan',
          model: 'pendekatanTenangMeyakinkan',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'Tujuan',
      keteranganTambahan: 'Setelah dilakukan tindakan keperawatan selama',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Menggigil menurun',
          value: 'Menggigil menurun',
          model: 'menggigilMenurun',
        },
        {
          labelDetail: '2. Suhu tubuh dan kulit membaik',
          value: 'Suhu tubuh dan kulit membaik',
          model: 'suhuTubuhDanKulitMembaik',
        },
        {
          labelDetail: '3. Ventilasi membaik',
          value: 'Ventilasi membaik',
          model: 'ventilasiMembaik',
        },
        {
          labelDetail: '4. Kulit merah menurun',
          value: 'Kulit merah menurun',
          model: 'kulitMerahMenurun',
        },
        {
          labelDetail: '5. Kejang menurun',
          value: 'Kejang menurun',
          model: 'kejangMenurun',
        },
        {
          labelDetail: '6. Akrosianosis menurun',
          value: 'Akrosianosis menurun',
          model: 'akrosianosisMenurun',
        },
        {
          labelDetail: '7. Piloereksi menurun',
          value: 'Piloereksi menurun',
          model: 'piloereksiMenurun',
        },
        {
          labelDetail: '8. Vasokonstriksi perifer menurun',
          value: 'Vasokonstriksi perifer menurun',
          model: 'vasokonstriksiPeriferMenurun',
        },
        {
          labelDetail: '9. Pucat menurun',
          value: 'Pucat menurun',
          model: 'pucatMenurun',
        },
        {
          labelDetail: '10. Takikardia menurun',
          value: 'Takikardia menurun',
          model: 'takikardiaMenurun',
        },
        {
          labelDetail: '11. Takipneu menurun',
          value: 'Takipneu menurun',
          model: 'takipneuMenurun',
        },
        {
          labelDetail: '12. Bradikardia menurun',
          value: 'Bradikardia menurun',
          model: 'bradikardiaMenurun',
        },
        {
          labelDetail: '13. Dasar kuku sianotik menurun',
          value: 'Dasar kuku sianotik menurun',
          model: 'dasarKukuSianotikMenurun',
        },
        {
          labelDetail: '14. Hipoksia menurun',
          value: 'Hipoksia menurun',
          model: 'hipoksiaMenurun',
        },
        {
          labelDetail: '15. Tekanan darah membaik',
          value: 'Tekanan darah membaik',
          model: 'tekananDarahMembaik',
        },
        {
          labelDetail: '16. Kadar glukosa darah membaik',
          value: 'Kadar glukosa darah membaik',
          model: 'kadarGlukosaDarahMembaik',
        },
        {
          labelDetail: '17. Pengisian kapiler membaik',
          value: 'Pengisian kapiler membaik',
          model: 'pengisianKapilerMembaik',
        },
      ],
    },
    {
      labelDiagnosaKeperawatan: 'RENCANA TINDAKAN',
      keteranganTambahan: '',
      detailDiagnosaKeperawatan: [
        {
          labelDetail: '1. Monitor suhu tubuh tiap dua jam',
          value: 'Monitor suhu tubuh tiap dua jam',
          model: 'monitorSuhuTubuhTiapDuaJam',
        },
        {
          labelDetail: '2. Monitor tekanan darah, frekuensi pernafasan dan nadi',
          value: 'Monitor tekanan darah, frekuensi pernafasan dan nadi',
          model: 'monitorTekananDarahFrekuensiPernafasanDanNadi',
        },
        {
          labelDetail: '3. Monitor warna dan suhu kulit',
          value: 'Monitor warna dan suhu kulit',
          model: 'monitorWarnaDanSuhuKulit',
        },
        {
          labelDetail:
            '4. Monitor dan catat tanda dan gejala hipotermia atau hipertermia',
          value: 'Monitor dan catat tanda dan gejala hipotermia atau hipertermia',
          model: 'monitorDanCatatTandaGejalaHipotermiaHipertermia',
        },
        {
          labelDetail: '5. Pasang alat pemantau suhu continue',
          value: 'Pasang alat pemantau suhu continue',
          model: 'pasangAlatPemantauSuhuContinue',
        },
        {
          labelDetail: '6. Tingkatkan asupan cairan dan nutrisi yang adekuat',
          value: 'Tingkatkan asupan cairan dan nutrisi yang adekuat',
          model: 'tingkatkanAsupanCairanDanNutrisiAdekuat',
        },
        {
          labelDetail: '7. Bedong bayi segera setelah lahir',
          value: 'Bedong bayi segera setelah lahir',
          model: 'bedongBayiSetelahLahir',
        },
        {
          labelDetail:
            '8. Masukkan bayi BBLR ke dalam plastik segera setelah lahir (mis. bahan polythlene)',
          value: 'Masukkan bayi BBLR ke dalam plastik segera setelah lahir',
          model: 'masukkanBayiBBLRDalamPlastikSetelahLahir',
        },
        {
          labelDetail: '9. Tempatkan bayi baru lahir di bawah radiant warmer',
          value: 'Tempatkan bayi baru lahir di bawah radiant warmer',
          model: 'tempatkanBayiDiRadiantWarmer',
        },
        {
          labelDetail: '10. Pertahankan kelembaban inkubator 50% atau lebih',
          value: 'Pertahankan kelembaban inkubator 50% atau lebih',
          model: 'pertahankanKelembabanInkubator',
        },
        {
          labelDetail:
            '11. Gunakan matras penghangat, selimut hangat dan penghangat ruangan',
          value: 'Gunakan matras penghangat, selimut hangat dan penghangat ruangan',
          model: 'gunakanMatrasPenghangatSelimutPenghangatRuangan',
        },
        {
          labelDetail: '12. Atur suhu inkubator sesuai kebutuhan',
          value: 'Atur suhu inkubator sesuai kebutuhan',
          model: 'aturSuhuInkubatorSesuaiKebutuhan',
        },
        {
          labelDetail:
            '13. Gunakan kasur pendingin, water circulation blanket, ice pack atau gel pad / blanket warmer',
          value:
            'Gunakan kasur pendingin, water circulation blanket, ice pack atau gel pad / blanket warmer',
          model: 'gunakanKasurPendinginWaterCirculationBlanket',
        },
        {
          labelDetail: '14. Sesuaikan suhu lingkungan dengan kebutuhan pasien',
          value: 'Sesuaikan suhu lingkungan dengan kebutuhan pasien',
          model: 'sesuaikanSuhuLingkunganDenganKebutuhanPasien',
        },
        {
          labelDetail: '15. Jelaskan cara pencegahan heat exhaustion dan heat stroke',
          value: 'Jelaskan cara pencegahan heat exhaustion dan heat stroke',
          model: 'jelaskanCaraPencegahanHeatExhaustionDanHeatStroke',
        },
        {
          labelDetail:
            '16. Jelaskan cara pencegahan hipotermia karena terpapar udara dingin',
          value: 'Jelaskan cara pencegahan hipotermia karena terpapar udara dingin',
          model: 'jelaskanCaraPencegahanHipotermia',
        },
        {
          labelDetail: '17. Demonstrasikan perawatan metode kangguru (PMK)',
          value: 'Demonstrasikan perawatan metode kangguru (PMK)',
          model: 'demonstrasikanPerawatanMetodeKangguruPMK',
        },
        {
          labelDetail: '18. Kolaborasi pemberian antipiretik, jika perlu',
          value: 'Kolaborasi pemberian antipiretik, jika perlu',
          model: 'kolaborasiPemberianAntipiretik',
        },
      ],
    },
  ]
}

export function TermoregulasiDO() {
  return [
    {
      label: '1. Kulit Teraba Dingin/Hangat',
      value: 'Kulit teraba dingin/hangat',
      model: 'kulitTerabaDinginHangat',
    },
    {
      label: '2. Menggigil',
      value: 'Menggigil',
      model: 'menggigil',
    },
    {
      label: '3. Suhu Tubuh Fluktuatif',
      value: 'Suhu tubuh fluktuatif',
      model: 'suhuTubuhFluktuatif',
    },
    {
      label: '4. Piloereksi',
      value: 'Piloereksi',
      model: 'piloereksi',
    },
    {
      label: '5. Pengisian Kapiler >3 Detik',
      value: 'Pengisian kapiler >3 detik',
      model: 'pengisianKapilerLebih3Detik',
    },
    {
      label: '6. Tekanan Darah Meningkat',
      value: 'Tekanan darah meningkat',
      model: 'tekananDarahMeningkat',
    },
    {
      label: '7. Pucat',
      value: 'Pucat',
      model: 'pucat',
    },
    {
      label: '8. Frekuensi Nafas Meningkat',
      value: 'Frekuensi nafas meningkat',
      model: 'frekuensiNafasMeningkat',
    },
    {
      label: '9. Takikardia',
      value: 'Takikardia',
      model: 'takikardia',
    },
    {
      label: '10. Kejang',
      value: 'Kejang',
      model: 'kejang',
    },
    {
      label: '11. Kulit Kemerahan',
      value: 'Kulit kemerahan',
      model: 'kulitKemerahan',
    },
    {
      label: '12. Dasar Kuku Sianotik',
      value: 'Dasar kuku sianotik',
      model: 'dasarKukuSianotik',
    },
  ]
}
