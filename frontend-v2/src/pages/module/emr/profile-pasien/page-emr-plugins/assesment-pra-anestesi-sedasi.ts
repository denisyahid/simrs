export function statusAnestesi():any{
  return [
    {
      "label" : "Diagnosa Pra Bedah",
      "model" : "diagPraBedah",
    },
    {
      "label" : "Jenis Pembedahan",
      "model" : "jenisPembedahan",
    },
    {
      "label" : "Diagnosis Pasca Bedah",
      "model" : "diagnosaPasBedah",
    },
  ]
}

export function asesmenSedasi():any{
  return [
    {
      "label_1" : "Penyakit yang menyertai",
      "value_1" : [
        {
          "model" : "penyekitMenyertai",
          "type" : "textarea",
        }
      ],
      "label_2" : "Pengobatan",
      "value_2" :  [
        {
          "label" : "teratur",
          "model" : "pengobatanPenyakit",
          "type" : "checkBox",
        },
        {
          "label" : "teratur",
          "model" : "pengobatanPenyakit",
          "type" : "checkBox",
        },
      ],
      "label_3" : "Obat-obatan",
      "value_3" : [
        {
          "model" : "obatPenyakit",
          "type" : "textarea",
        }
      ]
    },
    {
      "label_1" : "Riwayat Alergi",
      "value_1" : [
        {
          "label" : "Ya",
          "model" : "riwayatAlergi",
          "type" : "checkBox",
        },
        {
          "label" : "No",
          "model" : "riwayatAlergi",
          "type" : "checkBox",
        },
        {
          "model" : "jenisAlergi",
          "label" : "Jenis Alergi",
          "type" : "textBox"
        }
      ],
      "label_2" : "Pengobatan",
      "value_2" :  [
        {
          "label" : "teratur",
          "model" : "pengobatanAlergi",
          "type" : "checkBox",
        },
        {
          "label" : "teratur",
          "model" : "pengobatanAlergi",
          "type" : "checkBox",
        },
      ],
      "label_3" : "Obat-obatan",
      "value_3" : [
        {
          "model" : "obatAlergi",
          "type" : "textarea",
        }
      ]
    },
    {
      "label_1" : "Riwayat Anestesi",
      "value_1" : [
        {
          "model" : "penyekitMenyertai",
          "type" : "textarea",
        }
      ],
      "label_2" : "Penyulit",
      "value_2" :  [
        {
          "label" : "Tidak",
          "model" : "penyulitAnestesia",
          "type" : "checkBox",
        },
        {
          "label" : "Ya",
          "model" : "penyulitAnestesia",
          "type" : "checkBox",
        },
      ],
      "label_3" : "",
      "value_3" : [
        {
          "model" : "ketAnestesia",
          "type" : "textBox",
        }
      ]
    },
  ]
}

export function fisikDanPenunjang():any{
  return [
    {
      "label" : "Tekanan Darah",
      "model" : "tekananDarah"
    },
    {
      "label" : "Berat Badan",
      "model" : "beratBadan",
    },
    {
      "label" : "Kepala",
      "model" : "tekananDarah"
    },
    {
      "label" : "Nadi",
      "model" : "nadi",
    },
    {
      "label" : "Tinggi Badan",
      "model" : "tinggiBadan",
    },
    {
      "label" : "Thorax",
      "model" : "thorax",
    },
    {
      "label" : "Pernapasan",
      "model" : "pernapasan",
    },
    {
      "label" : "Golongan Darah",
      "model" : "golDarah",
    },
    {
      "label" : "Abdomen",
      "model" : "abdomen",
    },
    {
      "label" : "SpO2",
      "model" : "spo2",
    },
    {
      "label" : "Extremitas",
      "model" : "extremitas",
    },
  ]
}

export function pemrksPenunjang():any{
  return [
    {
      "label" : "Laboratorium",
      "model" : "pemriksaanLab",
    },
    {
      "label" : "EKG",
      "model" : "pemeriksaanEkg",
    },
    {
      "label" : "Radiologi",
      "model" : "pemeriksaanRadiologi",
    },
    {
      "label" : "Lain-lain",
      "model" : "pemeriksaanLain",
    },
  ]
}

export function analisaFisik():any{
  return [
    {
      "label" : "ASA",
      "model" : "analisaFisik_1",
    },
    {
      "label" : "PS",
      "model" : "analisaFisik_2",
    },
    {
      "label" : "1",
      "model" : "analisaFisik_3",
    },
    {
      "label" : "2",
      "model" : "analisaFisik_4",
    },
    {
      "label" : "3",
      "model" : "analisaFisik_5",
    },
    {
      "label" : "4",
      "model" : "analisaFisik_6",
    },
    {
      "label" : "5",
      "model" : "analisaFisik_7",
    },
    {
      "label" : "E",
      "model" : "analisaFisik_8",
    },
  ]
}

export function rencanaAnestesi():any{
  return [
    {
      "title" : "Anestesi Umum",
      "detail" : [
        {
          "label" : "Inhas",
          "model" : "anestesiUmum_1",
        },
        {
          "label" : "TIVA",
          "model" : "anestesiUmum_2",
        },
        {
          "label" : "Kombinasi",
          "model" : "anestesiUmum_3",
        },
        {
          "label" : "GA - ETT",
          "model" : "anestesiUmum_4",
        },
        {
          "label" : "GA - NTT",
          "model" : "anestesiUmum_5",
        },
        {
          "label" : "GA - LMA",
          "model" : "anestesiUmum_6",
        },
        {
          "label" : "GA - Mask",
          "model" : "anestesiUmum_7",
        },
      ]
    },
    {
      "title" : "Anestesi Regional",
      "detail" : [
        {
          "label" : "Spinal",
          "model" : "anestesiRegional_1",
        },
        {
          "label" : "Epidural : Lumba / Torakal",
          "model" : "anestesiRegional_2",
        },
        {
          "label" : "Kaudal",
          "model" : "anestesiRegional_3",
        },
        {
          "label" : "Blok Perifer",
          "model" : "anestesiRegional_4",
        },
      ]
    },
    {
      "title" : "Lain-lain",
      "detail" : [
        {
          "label" : "MAC",
          "model" : "teknikAnestiLain_1",
        },
        {
          "label" : "Sedasi : Ya / Tidak",
          "model" : "teknikAnestiLain_2",
        },
        {
          "label" : "Infitrasi lokal",
          "model" : "teknikAnestiLain_3",
        },
      ]
    },
  ]
}

export function alatKhusus():any{
  return [
    {
      "label" : "Continous pump",
      "model" : "alatKhusus_1"
    },
    {
      "label" : "Ventilasi satu paru",
      "model" : "alatKhusus_2"
    },
    {
      "label" : "Stimulator saraf",
      "model" : "alatKhusus_3"
    },
    {
      "label" : "USG",
      "model" : "alatKhusus_4"
    },
    {
      "label" : "Fiberoptik",
      "model" : "alatKhusus_5"
    },
    {
      "label" : "Bronkoskopi",
      "model" : "alatKhusus_6"
    },
    {
      "label" : "Hipotensi",
      "model" : "alatKhusus_7"
    },
    {
      "label" : "CPB",
      "model" : "alatKhusus_8"
    },
    {
      "label" : "TCI",
      "model" : "alatKhusus_9"
    },
    {
      "label" : " Lain-lain",
      "model" : "alatKhusus_10"
    },
  ]
}
