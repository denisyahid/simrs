export function rincian():any{
  return [
    {
      "no" : "1",
      "rincian" : "Nama pasien pada resep dan etiket sesuai",
      "sesuai" : {
        "model" : "namaPasienSesuai",
        "value" : "Ya",
      },
      "tidakSesuai" : {
        "model" : "namaPasienSesuai",
        "value" : "Tidak",
      },
    },
    {
      "no" : "2",
      "rincian" : "Nama obat pada resep, etiket dan fisik obat sesuai",
      "sesuai" : {
        "model" : "namaObatSesuai",
        "value" : "Ya",
      },
      "tidakSesuai" : {
        "model" : "namaObatSesuai",
        "value" : "Tidak",
      },
    },
    {
      "no" : "3",
      "rincian" : "Jumlah fisik obat sesuai",
      "sesuai" : {
        "model" : "jumlahObatSesuai",
        "value" : "Ya",
      },
      "tidakSesuai" : {
        "model" : "jumlahObatSesuai",
        "value" : "Tidak",
      },
    },
    {
      "no" : "4",
      "rincian" : "Perhitungan dosis sesuai",
      "sesuai" : {
        "model" : "dosisSesuai",
        "value" : "Ya",
      },
      "tidakSesuai" : {
        "model" : "dosisSesuai",
        "value" : "Tidak",
      },
    },
    {
      "no" : "5",
      "rincian" : "Signa/aturan pakai obat pada resep dan etiket sesuai",
      "sesuai" : {
        "model" : "signaSesuai",
        "value" : "Ya",
      },
      "tidakSesuai" : {
        "model" : "signaSesuai",
        "value" : "Tidak",
      },
    },
    {
      "no" : "6",
      "rincian" : "Informasi obat dan expired date pada etiket dan fisik obat sesuai",
      "sesuai" : {
        "model" : "expiredObatSesuai",
        "value" : "Ya",
      },
      "tidakSesuai" : {
        "model" : "expiredObatSesuai",
        "value" : "Tidak",
      },
    },

  ]
}
