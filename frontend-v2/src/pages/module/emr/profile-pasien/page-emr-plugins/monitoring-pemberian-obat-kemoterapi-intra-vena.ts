export function Kebutuhan (): any {
  return [
    {
      label: 'Keadaan Umum',
      addons: false,
      inputan: [
        {
          model: 'keadaanUmumSebelumKemo',
          label: 'Keadaan Umum Sebelum Kemoterapi',
        },
        {
          model: 'keadaanUmumSelamaKemo15Menit',
          label: 'Keadaan Umum Selama kemoterapi 15 menit',
        },
        {
          model: 'keadaanUmumSelamaKemo30Menit',
          label: 'Keadaan Umum Selama kemoterapi 30 menit',
        },
        {
          model: 'keadaanUmumSelamaKemo1Jam',
          label: 'Keadaan Umum Selama kemoterapi 1 jam',
        },
        {
          model: 'keadaanUmumBilaTerjadiHipersensitifitas',
          label: 'Keadaan Umum Bila Terjadi Hipersensitifitas',
        }
      ],
    },
    {
      label: 'Suhu',
      inputan: [
        {
          model: 'suhuSebelumKemo',
          label: 'Suhu Sebelum Kemoterapi',
        },
        {
          model: 'suhuSelamaKemo15Menit',
          label: 'Suhu Selama kemoterapi 15 menit',
        },
        {
          model: 'suhuSelamaKemo30Menit',
          label: 'Suhu Selama kemoterapi 30 menit',
        },
        {
          model: 'suhuSelamaKemo1Jam',
          label: 'Suhu Selama kemoterapi 1 jam',
        },
        {
          model: 'suhuBilaTerjadiHipersensitifitas',
          label: 'Suhu Bila Terjadi Hipersensitifitas',
        }
      ]
    },
    {
      label: 'Nadi',
      inputan: [
        {
          model: 'NadiSebelumKemo',
          label: 'Nadi Sebelum Kemoterapi',
        },
        {
          model: 'NadiSelamaKemo15Menit',
          label: 'Nadi Selama kemoterapi 15 menit',
        },
        {
          model: 'NadiSelamaKemo30Menit',
          label: 'Nadi Selama kemoterapi 30 menit',
        },
        {
          model: 'NadiSelamaKemo1Jam',
          label: 'Nadi Selama kemoterapi 1 jam',
        },
        {
          model: 'NadiBilaTerjadiHipersensitifitas',
          label: 'Nadi Bila Terjadi Hipersensitifitas',
        }
      ]
    },
    {
      label: 'Tensi',
      inputan: [
        {
          model: 'TensiSebelumKemo',
          label: 'Tensi Sebelum Kemoterapi',
        },
        {
          model: 'TensiSelamaKemo15Menit',
          label: 'Tensi Selama kemoterapi 15 menit',
        },
        {
          model: 'TensiSelamaKemo30Menit',
          label: 'Tensi Selama kemoterapi 30 menit',
        },
        {
          model: 'TensiSelamaKemo1Jam',
          label: 'Tensi Selama kemoterapi 1 jam',
        },
        {
          model: 'TensiBilaTerjadiHipersensitifitas',
          label: 'Tensi Bila Terjadi Hipersensitifitas',
        }
      ]
    },
    {
      label: 'Respirasi',
      inputan: [
        {
          model: 'RespirasiSebelumKemo',
          label: 'Respirasi Sebelum Kemoterapi',
        },
        {
          model: 'RespirasiSelamaKemo15Menit',
          label: 'Respirasi Selama kemoterapi 15 menit',
        },
        {
          model: 'RespirasiSelamaKemo30Menit',
          label: 'Respirasi Selama kemoterapi 30 menit',
        },
        {
          model: 'RespirasiSelamaKemo1Jam',
          label: 'Respirasi Selama kemoterapi 1 jam',
        },
        {
          model: 'RespirasiBilaTerjadiHipersensitifitas',
          label: 'Respirasi Bila Terjadi Hipersensitifitas',
        }
      ]
    },
    {
      label: 'Produksi Urine',
      addons: false,
      inputan: [
        {
          model: 'ProduksiUrineSebelumKemo',
          label: 'Produksi Urine Sebelum Kemoterapi',
        },
        {
          model: 'ProduksiUrineSelamaKemo15Menit',
          label: 'Produksi Urine Selama kemoterapi 15 menit',
        },
        {
          model: 'ProduksiUrineSelamaKemo30Menit',
          label: 'Produksi Urine Selama kemoterapi 30 menit',
        },
        {
          model: 'ProduksiUrineSelamaKemo1Jam',
          label: 'Produksi Urine Selama kemoterapi 1 jam',
        },
        {
          model: 'ProduksiUrineBilaTerjadiHipersensitifitas',
          label: 'Produksi Urine Bila Terjadi Hipersensitifitas',
        }
      ]
    },
  ]
}
