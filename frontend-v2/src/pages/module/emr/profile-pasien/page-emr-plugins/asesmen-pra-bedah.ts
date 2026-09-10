export function riwayat():any{
  return [
    {
      "title" : "Riwayat Penyakit",
      "detail" : [
        {
          "label" : "DM",
          "type" : "checkBox",
          "model" : "riwayatPen_1"
        },
        {
          "label" : "Asma",
          "type" : "checkBox",
          "model" : "riwayatPen_2"
        },
        {
          "label" : "CRV",
          "type" : "checkBox",
          "model" : "riwayatPen_3"
        },
        {
          "label" : "HIV / AIDS",
          "type" : "checkBox",
          "model" : "riwayatPen_4"
        },
        {
          "label" : "Hipertensi",
          "type" : "checkBox",
          "model" : "riwayatPen_5"
        },
        {
          "label" : "TB Baru",
          "type" : "checkBox",
          "model" : "riwayatPen_6"
        },
        {
          "label" : "Hepatitis B-C",
          "type" : "checkBox",
          "model" : "riwayatPen_7"
        },
        {
          "label" : "Lain-lain",
          "type" : "checkBox",
          "model" : "riwayatPen_8"
        },
        {
          "type" : "textBox",
          "model" : "ketRiwayatPenyakit"
        },
      ]
    },
    {
      "title" : "Data Subjektif",
      "detail" : [
        {
          "label" : "Sesak Nafas",
          "type" : "checkBox",
          "model" : "dataSubjektif_1"
        },
        {
          "label" : "Batuk",
          "type" : "checkBox",
          "model" : "dataSubjektif_2"
        },
        {
          "label" : "Pusing",
          "type" : "checkBox",
          "model" : "dataSubjektif_3"
        },
        {
          "label" : "Lain-lain",
          "type" : "checkBox",
          "model" : "dataSubjektif_4"
        },
        {
          "type" : "textBox",
          "model" : "ketDataSubjektif"
        },
      ]
    },
    {
      "title" : "Riwayat alergi",
      "type" : "textBox",
      "model" : "riwayatAlergi"
    },
    {
      "title" : "Riwayat operasi sebelumnya",
      "type" : "textBox",
      "model" : "riwayatOPSebelumnya"
    },
    {
      "title" : "Anamnesis Singkat",
      "type" : "textBox",
      "model" : "AnanesisSingkat"
    },
  ]
}

export function pemeriksaanFisik():any{
  return [
    {
      "label" : "Tekanan Darah",
      "model" : "tekananDarah",
      "satuan" : "mm/Hg"
    },
    {
      "label" : "Suhu",
      "model" : "suhu",
      "satuan" : "°C"
    },
    {
      "label" : "Nadi",
      "model" : "nadi",
      "satuan" : "x/menit"
    },
    {
      "label" : "Pernapasan",
      "model" : "pernapasan",
      "satuan" : "x/menit"
    },
    {
      "label" : "Skor Nyeri",
      "model" : "skorNyeri",
    }
  ]
}

export function pemeriksaanVital():any{
  return [
    {
      "label" : "Laboratorium",
      "model" : "pemeriksaanLab",
    },
    {
      "label" : "MRI",
      "model" : "pemeriksaanMRI",
    },
    {
      "label" : "Rontgen",
      "model" : "pemeriksaanRont",
    },
    {
      "label" : "USG",
      "model" : "pemeriksaanUSG",
    },
    {
      "label" : "CT-Scan",
      "model" : "pemeriksaanScan",
    },
    {
      "label" : "EKG",
      "model" : "pemeriksaanEKG",
    },
    {
      "label" : "Lain-lain",
      "model" : "pemeriksaanLain",
    },
    {
      "model" : "ketPemVital",
    },
  ]
}

export function rencanaTindakan():any{
  return [
    {
      "label" : "Rencana Tindakan Operasi",
      "model" : "renTinOperasi",
    },
    {
      "label" : "Profilaksis",
      "model" : "profilaksis",
    },
    {
      "label" : "Produk Darah",
      "model" : "produkDarah",
    },
    {
      "label" : "Alat Khusus / Implant",
      "model" : "implant",
    },
    {
      "label" : "Posisi Pasien",
      "model" : "posisiPasien",
    },
  ]
}
