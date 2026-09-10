export function antisipasiKondisi(): any {
  return [
    {
      title: 'Dokter Operator',
      value: [
        {
          subTitle: 'Berapa banyak kehilangan darah diantisipasi ?',
          type: 'checkBox',
          model: 'adaDarah',
          model1: 'isiDarah',
          model2: 'isiJumlahDarah',
        },
        {
          subTitle: 'Apakah ada persyaratan peralatan khusus atau investigasi khusus ?',
          type: 'checkBox',
          model: 'adaPersyaratan',
          model2: 'isiPersyaratan',
        },
        {
          subTitle: 'Apakah ada langkah/tak terduga yang perlu diketahui tim lain?',
          type: 'checkBox',
          model: 'adaLangkah',
          model2: 'isiLangkah',
        },
      ],
    },
    {
      title: 'Dokter Anestesi',
      value: [
        {
          subTitle: 'Apakah ada masalah sepesifik pada pasien ?',
          type: 'checkBox',
          model: 'adaMasalah',
          model2: 'isiMasalah',
        },
        {
          subTitle: 'Drajat ASA pasien',
          type: 'checkBox',
          model: 'adaDrajat',
          model2: 'isiDrajat',
        },
        {
          subTitle:
            'Apa pemantauan peralatan dan dukungan khusus yang diperlukan misalnya,darah?',
          type: 'checkBox',
          model: 'adaPemantaun',
          model2: 'isiPemantaun',
        },
      ],
    },
    {
      title: 'Perawat',
      value: [
        {
          subTitle:
            'Apakah sterilisasi intrumental dikonfirmasi(termasuk kertas indikator steril) ?',
          type: 'checkBox',
          model: 'adaSterilisasi',
          model2: 'isiSterilisasi',
        },
        {
          subTitle: 'Apakah ada permasalahan peralatan yang perlu dipertambahkan',
          type: 'checkBox',
          model: 'adaPermasalahan',
          model2: 'isiPermasalahan',
        },
      ],
    },
  ]
}

export function others(): any {
  return [
    {
      title: 'Apakah infeksi lokasi operasi telah diantisipasi ?',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'adaInfeksiLokasiOperasi',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'adaInfeksiLokasiOperasi',
        },
      ],
    },
    {
      title: 'Antibiotik profilaksis dalam 60 menit terakhir ?',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'adaPemberiaanAntibiotikProfilaksi',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'adaPemberiaanAntibiotikProfilaksi',
        },
      ],
    },
    {
      title: 'Penghangat Pasien ?',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'adaPenghangatPasien',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'adaPenghangatPasien',
        },
      ],
    },
    {
      title: 'Cukur rambut area operasi jika diperlukan ?',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'adaCukurRambutAreaOperasi',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'adaCukurRambutAreaOperasi',
        },
      ],
    },
    {
      title: 'Kontrol gula darah ?',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'adaKontrolGulaDarah',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'adaKontrolGulaDarah',
        },
      ],
    },
    {
      title: 'Apakah profilaksis VTE telah dilakukan ?',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'adaMelakukanProfilaksisVTE',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'adaMelakukanProfilaksisVTE',
        },
      ],
    },
    {
      title: 'Apakah hasil radiologi dipasang ?',
      value: [
        {
          subTitle: 'Ya',
          type: 'checkBox',
          model: 'adaPemasanganHasilRadiologi',
        },
        {
          subTitle: 'Tidak',
          type: 'checkBox',
          model: 'adaPemasanganHasilRadiologi',
        },
      ],
    },
  ]
}
