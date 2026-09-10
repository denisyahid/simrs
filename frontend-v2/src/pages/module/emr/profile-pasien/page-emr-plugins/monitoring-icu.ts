export function waktu(): any {
  return ["07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00",
    "19:00", "20:00", "21:00", "22:00", "23:00", "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00",
    "19:00", "20:00", "21:00", "22:00", "23:00", "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00"
  ]
}

export function dataTableDescrip(): any {

  let tanda1 = 'I. Tanda-tanda Vital'
  let tanda2 = 'II. Sistem Saraf'

  return [
    { deskripsi: 'Tensi', group: tanda1 },
    { deskripsi: 'Nadi', group: tanda1 },
    { deskripsi: 'RR', group: tanda1 },
    { deskripsi: 'Suhu', group: tanda1 },
    { deskripsi: 'MAP', group: tanda1 },
    { deskripsi: 'SpO2', group: tanda1 },
    { deskripsi: 'GCS', group: tanda2 },
    { deskripsi: 'Kesadaran', group: tanda2 },
    { deskripsi: 'Reaksi Pupil', group: tanda2 },
    { deskripsi: 'Ekstremitas', group: tanda2 },
    { deskripsi: 'Atas', group: tanda2 },
    { deskripsi: 'Bawah', group: tanda2 }
  ]
}

export function statusRespirasi(): any {
  return [
    {
      "label": "Alat Bantu",
      "model": "alatBantu",
      "type": "select",
    },
    {
      "label": "No. ETT",
      "model": "noETT",
      "type": "textBox",
    },
    {
      "label": "PEEP",
      "model": "noPEEP",
      "type": "textBox",
    },
    {
      "label": "RR",
      "model": "rr",
      "type": "textBox",
    },
    {
      "label": "FiO2",
      "model": "fio2",
      "type": "textBox",
    },
    {
      "label": "Flow",
      "model": "flow",
      "type": "textBox",
    },
    {
      "label": "PIP",
      "model": "pip",
      "type": "textBox",
    },
    {
      "label": "I:E",
      "model": "ie",
      "type": "textBox",
    },
    {
      "label": "TV",
      "model": "tv",
      "type": "textBox",
    },
  ]
}

export function Hemodinamik(): any {
  return [
    {
      "label": "CVP",
      "model": "cvp",
      "type": "textBox",
    },
    {
      "label": "CRT",
      "model": "crt",
      "type": "select",
    },
    {
      "label": "Sedasi Sore",
      "model": "sedasiScore",
      "type": "textBox",
    },
    {
      "label": "Gambaran EKG",
      "model": "ekg",
      "type": "textBox",
    },
  ]
}

export function tableBalansCairan(): any {

  let tanda1 = '1. Intake'
  let tanda2 = '2. Output'
  let tanda3 = '3. Balans'

  return [
    { deskripsi: 'Cairan', group: tanda1 },
    { deskripsi: 'NGT', group: tanda1 },
    { deskripsi: 'Oral', group: tanda1 },
    { deskripsi: 'Transfusi', group: tanda1 },
    { deskripsi: 'Total Intake', group: tanda1 },
    { deskripsi: 'Drain', group: tanda2 },
    { deskripsi: 'Urine', group: tanda2 },
    { deskripsi: 'NGT', group: tanda2 },
    { deskripsi: 'BAB', group: tanda2 },
    { deskripsi: 'WSD', group: tanda2 },
    { deskripsi: 'IWL', group: tanda2 },
    { deskripsi: 'Pendarahan', group: tanda2 },
    { deskripsi: 'Total Output', group: tanda2 },
    { deskripsi: 'Total Balans', group: tanda3 }
  ]
}

export function listDescripWaktu(): any {
  return {
    "ketWaktu": [
      "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM",
      "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA", "CM", "CA",
    ],
    "descWaktu": [
      "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis",
      "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML", "Jenis", "ML",
    ]
  }
}

export function titleMore(): any {
  return [
    {
      "title": "VIII. Catatan Penting",
      "value" : "catatanPenting"
    },
  ]
}
