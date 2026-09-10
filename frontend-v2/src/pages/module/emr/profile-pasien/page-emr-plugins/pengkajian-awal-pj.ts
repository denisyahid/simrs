export function hubunganPasien(): any {
  return [
    {
      title: 'Baik',
      model: 'hubunganPasien_',
    },
    {
      title: 'Tidak baik',
      model: 'hubunganPasien_',
    },
  ]
}

export function spritualPasien(): any {
  return [
    {
      title: 'Kecenderuangan bunuh diri dilaporkan ke..',
      value : 'laporanBunuhdiri',
      ketValue : 'ketLaporkan',
      model: 'spritualPasien',
    },
    {
      title: 'Lain-lain sebutkan..',
      value : 'kecenderuanganLain',
      ketValue : 'ketKecenderuangan',
      model: 'spritualPasien',
    },
  ]
}
export function statusPsikologisPasien(): any {
  return [
    {
      model: 'statusPsikologisPasien',
      label: 'Tenang',
      value: 'Tenang',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Cemas',
      value: 'Cemas',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Takut',
      value: 'Takut',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Marah',
      value: 'Marah',
    },
    {
      model: 'statusPsikologisPasien',
      label: 'Sedih',
      value: 'Sedih',
    },
  ]
}
export function vitalSign(): any {
  return [
    {
      label: 'TD',
      model: 'tekananDarah',
      addon: 'mmHG',
    },
    {
      label: 'BB',
      model: 'beratBadan',
      addon: 'kg',
    },
    {
      label: 'TB',
      model: 'tinggiBadan',
      addon: 'cm',
    },
    {
      label: 'Nadi',
      model: 'nadi',
      addon: 'x/mnt',
    },
    {
      label: 'Pernafasan',
      model: 'pernapasan',
      addon: 'x/mnt',
    },
    {
      label: 'Suhu',
      model: 'suhu',
      addon: '°C',
    },
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
export function statusFungsional(): any {
  return [
    {
      label: 'Mandiri',
      value: 'mandiri',
      model: 'statusFungsional',
    },
    {
      label: 'Perlu bantuan',
      value: 'perluBantuan',
      model: 'statusFungsional',
    },
    {
      label: 'Alat Bantu',
      value: 'alatBantu',
      model: 'statusFungsional',
    },
    {
      label: 'Prothesa',
      value: 'prothesa',
      model: 'statusFungsional',
    },
    {
      label: 'Cacat Tubuh',
      value: 'cacatTubuh',
      model: 'statusFungsional',
    },
    {
      label: 'Ketergantungan Total',
      value: 'ketergantunganTotal',
      model: 'statusFungsional',
    },
  ]
}

export function skriningResikoJatuh(): any {
  return [
    {
      title:
        'Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak tidak seimbang (sempoyongan / limbung) ?',
      value: [
        {
          subTitle: 'Ya',
          value: 'ya',
          type: 'checkBox',
          model: 'pasienTidakSeimbang',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          value: 'tidak',
          model: 'pasienTidakSeimbang',
        },
      ],
    },
    {
      title:
        'Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk?',
      value: [
        {
          subTitle: 'Ya',
          value: 'ya',
          type: 'checkBox',
          model: 'butuhBantuanJalan',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          value: 'tidak',
          model: 'butuhBantuanJalan',
        },
      ],
    },
    {
      title: 'Hasil',
      value: [
        {
          subTitle: 'Tidak Berisiko (tidak ditemukan a dan b)',
          type: 'checkBox',
          value: 'tidakBerisiko',
          model: 'resikoPasien',
        },
        {
          subTitle: 'Risiko Tinggi (a dan b ditemukan)',
          type: 'checkBox',
          value: 'resikoTinggi',
          model: 'resikoPasien',
        },
        {
          subTitle: 'Risiko Rendah (ditemukan a atau b)',
          type: 'checkBox',
          value: 'resikoRendah',
          model: 'resikoPasien',
        },
      ],
    },
  ]
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
export function detailKeperawatan(): any {
  return [
    // {
    //   label: 'DAFTAR MASALAH',
    //   placeholder: 'Daftar Masalah...',
    //   model: 'daftarMasalah',
    // },
    {
      label: 'RENCANA KEPERAWATAN',
      placeholder: 'Rencana keperawatan...',
      model: 'rencanaKeperawatan',
    },
    {
      label: 'INSTRUKSI KEPERAWATAN',
      placeholder: 'Intruksi keperawatan...',
      model: 'intruksiKeperawatan',
    },
    {
      label: 'SASARAN',
      placeholder: 'Sasaran...',
      model: 'sasaran',
    },
  ]
}
export function skriningGizi2(): any {
  return [
    {
      label: '',
      chilren: [],
    },
    {
      label: '',
      chilren: [],
    },
    {
      label: '',
      chilren: [],
    },
  ]
}
