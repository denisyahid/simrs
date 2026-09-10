export function vitalSign(): any {
  return [
    {
      label: 'Tekanan Darah',
      model: 'tekananDarah',
      addon: 'mmHG',
    },
    {
      label: 'Frekuensi Nadi',
      model: 'nadi',
      addon: 'x/mnt',
    },
    {
      label: 'Suhu',
      model: 'suhu',
      addon: '°C',
    },
    {
      label: 'Frekuensi Nafas',
      model: 'pernapasan',
      addon: 'x/mnt',
    },
    {
      label: 'Berat Bada',
      model: 'beratBadan',
      addon: 'kg',
    },
    {
      label: 'Tinggi Badan',
      model: 'tinggiBadan',
      addon: 'cm',
    },
    {
      label: 'Lingkar Kepala',
      model: 'lingkarKepala',
      addon: 'cm',
    },
  ]
}

export function JenisKelamin(): any{
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' }
  ]
}

export function skriningGizi(): any {
  return [
    {
      label:
        ' 1. Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir?',
      children: [
        {
          label: 'a. Tidak ada penurunan berat badan',
          type: 'checkbox',
          model: 'tidakAdaTurunBeratBadan',
          text :"Ya",
          value: 0,
        },
        {
          label: 'b. Tidak yakin / tidak tahu/ terasa baju lebih longgar',
          type: 'checkbox',
          text :"Ya",
          model: 'tidakAdaTurunBeratBadan',
          value: 2,
        },
        {
          label: 'c. Jika ya, berapa penurunan berat badan tersebut',
          children: [
            {
              type: 'checkbox',
              label: '1 - 5 kg',
              model: 'turunBeratBadan',
              value: 1,
            },
            {
              type: 'checkbox',
              label: '6 - 10 kg',
              model: 'turunBeratBadan',
              value: 2,
            },
            {
              type: 'checkbox',
              label: '11 - 15 kg',
              model: 'turunBeratBadan',
              value: 3,
            },
            {
              type: 'checkbox',
              label: '> 15 kg',
              model: 'turunBeratBadan',
              value: 4,
            },
          ],
        },
      ],
    },
    {
      label: ' 2. Apakah asupan makan berkurang karena tidak nafsu makan?',
      children: [
        {
          text :"Ya",
          type: 'checkbox',
          model: 'asupanMakan',
          value: 1,
        },
        {
          text :"Tidak",
          type: 'checkbox',
          model: 'asupanMakan',
          value: 0,
        },
      ],
    },
  ]
}

export function diagnosaKhusus(): any {
  return [
    {
      label: 'DM',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'dm',
    },
    {
      label: 'Kemoterapi',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'Kemoterapi',
    },
    {
      label: 'Hemodialisa',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'Hemodialisa',
    },
    {
      label: 'Geriatri',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'Geriatri',
    },
    {
      label: 'Immunitas menurun',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'immunitasmMnurun',
    },
    {
      label: 'Lain-lain',
      model: 'diagnosaKhusus',
      type: 'checkbox',
      value: 'lain_lain',
    },
  ]
}

export function Pilihan(): any {
  return [
      {
          "title": "Ya",
          "model": "pilihan"
      },
      {
          "title": "Tidak",
          "model": "pilihan"
      },
  ]
}

export function pegangKursisaatDuduk(): any {
  return [
      {
          "title": "Ya",
          "model": "pegangKursisaatDuduk"
      },
      {
          "title": "Tidak",
          "model": "pegangKursisaatDuduk"
      },
  ]
}

export function hasilResikoJatuh(): any {
  return [
      {
          "title": "Tidak berisiko (tidak ditemukan a dan b)",
          "model": "hasilResikoJatuh"
      },
      {
          "title": "Resiko Tinggi (ditemukan a dan b)",
          "model": "hasilResikoJatuh"
      },
      {
          "title": "Risiko rendah sedang (ditemukan a atau b)",
          "model": "hasilResikoJatuh"
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

export function skriningNyeri(): any {
  return [
    {
      label: '',
      children: [
        {
          label: 'Tidak ada nyeri',
          type: 'checkbox',
          model: 'statusNyeri',
          value: 'tidakAdaNyeri',
        },
        {
          label: 'Nyeri kronis',
          type: 'checkbox',
          model: 'statusNyeri',
          value: 'nyeriKronis',
        },
        {
          label: 'Nyeri Akut',
          type: 'checkbox',
          model: 'statusNyeri',
          value: 'nyeriAkut',
        },
        {
          label: 'Skala Nyeri',
          type: 'text',
          model: 'sklanyeri',
        },
        {
          label: 'Lokasi',
          type: 'text',
          model: 'lokasiNyeri',
        },
        {
          label: 'Durasi',
          type: 'text',
          model: 'durasiNyeri',
        },
        {
          label: 'Frekuensi',
          type: 'text',
          model: 'frekuensiNyeri',
        },
      ],
    },
    {
      label: 'Nyeri hilang,bila :',
      children: [
        {
          label: 'Minum Obat',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'minumObat',
        },
        {
          label: 'Mendengarkan Musik',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'mendengarkanMusik',
        },
        {
          label: 'Istirahat',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'istirahat',
        },
        {
          label: 'Berubah posisi / tidur',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'berubahPosisiTidur',
        },
        {
          label: 'Lain-lain',
          type: 'checkbox',
          model: 'penghilangNyeri',
          value: 'lainLain',
        },
        {
          label: 'Sebutkan',
          type: 'text',
          model: 'penghilangNyeriLain',
        },
      ],
    },
    {
      label: 'Diberitahuan ke dokter :',
      children: [
        {
          label: 'Ya',
          type: 'checkbox',
          model: 'nyeriDiberitahukanKeDokter',
          value: 'ya',
        },
        {
          label: 'Pukul',
          type: 'date',
          model: 'waktuNyeriDiberitahukanKeDokter',
        },
        {
          label: 'Tidak',
          type: 'checkbox',
          model: 'nyeriDiberitahukanKeDokter',
          value: 'tidak',
        },
      ],
    },
  ]
}
