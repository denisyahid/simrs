export function ANTROPOMETRI() {
  return [
    { label: 'Berat badan saat masuk rumah sakit', model: 'beratBadanRumahSakit', addon: 'kg' },
    { label: 'Tinggi Badan', model: 'tinggiBadan', addon: 'cm' },
    { label: 'Indeks Masa Tubuh', model: 'indekMasaTubuh', addon: 'kg/m2' },
    { label: 'Status gizi', model: 'statusGizi', addon: '' },
    { label: 'LILA', model: 'lila', addon: 'cm' },
    { label: 'Tinggi lutut(bila TB tidak dapat diukur)', model: 'tinggiLutut', addon: 'cm' },
    { label: 'Estimasi tinggi badan', model: 'estimasiTinggiBadan', addon: 'cm' },
    { label: 'Lingkar betis', model: 'lingkarBetis', addon: 'cm' },
  ];
}

export function BioKimias1(): any {
  return [
    {
      "label": "Hemoglobin",
      "value": "Hemoglobin",
      "model": "hemoglobin"
    },
    {
      "label": "*laki-laki : 13.5 – 17.5 g/dL",
      "model": "hemoglobinLaki-laki",
      "value": "laki-laki : 13.5 – 17.5 g/dL"
    },
    {
      "label": "*perempuan : 12.0 – 16.0 g/dL",
      "model": "hemoglobinPerempuan",
      "value": "perempuan : 12.0 – 16.0 g/dL"
    },
    {
      "label": "Albumin",
      "value": "Albumin: 3.5 – 5.2 mg/dL",
      "model": "albumin"
    },
    {
      "label": "Urea",
      "value": "Urea: 0 – 50 mg/dL",
      "model": "urea"
    },
    {
      "label": "Kreatinin",
      "value": "Kreatinin",
      "model": "kreatinin"
    },
    {
      "label": "*laki-laki : 0.6 – 1.2 mg/dL",
      "model": "kreatininLaki-laki",
      "value": "laki-laki : 0.6 – 1.2 mg/dL"
    },
    {
      "label": "*perempuan : 0.5 – 1 mg/dL",
      "model": "kreatininPerempuan",
      "value": "perempuan : 0.5 – 1 mg/dL"
    },
    {
      "label": "ALT / GPT",
      "value": "ALT / GPT",
      "model": "altGpt"
    },
    {
      "label": "*laki-laki : < 45 U/L",
      "model": "altGptLaki-laki",
      "value": "laki-laki : < 45 U/L"
    },
    {
      "label": "*perempuan : < 34 U/L",
      "model": "altGptPerempuan",
      "value": "perempuan : < 34 U/L"
    }
  ];
}

export function BioKimias2(): any {
  return [
    {
      "label": "AST / GOT",
      "value": "AST / GOT",
      "model": "astGot"
    },
    {
      "label": "*laki-laki : < 35 U/L",
      "model": "astGotLaki-laki",
      "value": "laki-laki : < 35 U/L"
    },
    {
      "label": "*perempuan : < 31 U/L",
      "model": "astGotPerempuan",
      "value": "perempuan : < 31 U/L"
    },
    {
      "label": "GDP",
      "value": "GDP",
      "model": "gdp"
    },
    {
      "label": "Glukosa 2JPP",
      "value": "Glukosa 2JPP : 70 – 140 mg/dL",
      "model": "glukosa2Jpp"
    },
    {
      "label": "Bilirubin Total",
      "value": "Bilirubin Total : 0.3 – 1.2",
      "model": "bilirubinTotal"
    },
    {
      "label": "Bilirubin Direct",
      "value": "Bilirubin direct < 0.3",
      "model": "bilirubinDirect"
    },
    {
      "label": "Kolesterol Total",
      "value": "Kolesterol total : < 200",
      "model": "kolesterolTotal"
    },
    {
      "label": "Natrium (Na)",
      "value": "Natrium (Na) : 136 – 145 mmol/L (*rendah/*tinggi)",
      "model": "natrium"
    },
    {
      "label": "Kalium (K)",
      "value": "Kalium (K) : 3.50 – 5.10 mmol/L (*rendah/*tinggi)",
      "model": "kalium"
    },
    {
      "label": "Clorida (Cl)",
      "value": "Clorida (Cl) : 94 – 110 mmol/L (*rendah/*tinggi)",
      "model": "clorida"
    },

  ];
}

export function KLINIS() {
  return [
    { label: 'Mual', model: 'mual', addon: '' },
    { label: 'Muntah', model: 'muntah', addon: '' },
    { label: 'Diare', model: 'diare', addon: '' },
    { label: 'Konstipasi', model: 'Konstipasi', addon: '' },
    { label: 'Kesulitan Mengunyah', model: 'kesulitanMengunyah', addon: '' },
    { label: 'Kesulitan Menelan', model: 'kesulitanMenelan', addon: '' },
    { label: 'Anoreksia', model: 'Anoreksia', addon: '' },
    { label: 'Sesak', model: 'Sesak', addon: '' },
    { label: 'Edema / Asites', model: 'edemaAsites', addon: '' },
    { label: 'Hipertensi', model: 'Hipertensi', addon: '' },
  ];
}

export function DIAGNOSANUTRISI() {
  return [
    {
      label: 'Gagal tumbuh',
      model: 'gagalTumbuh',
      value:  'Gagal Tumbuh'
    },
    {
      label: 'Gizi Kurang',
      model: 'giziKurang',
      value:  'Gizi Kurang'
    },
    {
      label: 'Kwashiorkor',
      model: 'kwashiorkor',
      value:  'Kwashiorkor'
    },
    {
      label: 'Perawakan Pendek',
      model: 'perawakanPendek',
      value:  'Perawakan Pendek'
    },
    {
      label: 'Obese',
      model: 'obese',
      value:  'Obese'
    },
    {
      label: 'malnutrisi sedang',
      model: 'malnutrisiSedang',
      value:  'malnutrisi sedang'
    },
    {
      label: 'Kwashiorkor – Marasmus',
      model: 'kwashiorkorMarasmus',
      value:  'Kwashiorkor – Marasmus'
    },
    {
      label: 'Gizi Baik',
      model: 'giziBaik',
      value:  'Gizi Baik'
    },
    {
      label: 'Maltrunisi Ringan',
      model: 'MaltrunisiRingan',
      value:  'Maltrunisi Ringan'
    }
  ];
}

export function KEBUTUHANNUTRISI() {
  return [
    { label: 'Energi', model: 'Energi', addon: 'kilo kal' },
    { label: 'Protein', model: 'Protein', addon: 'gr' },
    { label: 'Cairan', model: 'Cairan', addon: 'ml' },
    { label: 'Lainnya', model: 'Lainnya', addon: '' },
  ];
}
export function TANDAVITAL():any {
  return [
    {
      "title": "TD",
      "model": "tekananDarah",
      "satuan": "mmHg"
  },
  {
      "model": "RR",
      "title": "RR",
      "satuan": "x/min"
  },
  {
      "title": "HR",
      "model": "nadi",
      "satuan": ""
  },
  {
      "title": "Suhu",
      "model": "suhu",
      "satuan": "°C"
  },
  {
      "title": "SPO2",
      "model": "SPO2",
      "satuan": ""
  },
  {
      "title": "TB",
      "model": "tinggiBadan",
      "satuan": "Cm"
  },
  {
      "title": "BB",
      "model": "beratBadan",
      "satuan": "Kg"
  },
  {
      "title": "LP",
      "model": "lingkarPerut",
      "satuan": ""
  },
  ]
}

export function MATA(): any{
  return [
    {
      label: 'Anemis',
      model: 'anemis',
      value:  'Anemis'
    },
    {
      label: 'Ikterus',
      model: 'Ikterus',
      value:  'Ikterus'
    },
    {
      label: 'Reflek Pupil',
      model: 'reflekPupil',
      value:  'Reflek Pupil'
    },
    {
      label: 'Oedema Palpebrae.',
      model: 'oedemaPalpebrae.',
      value:  'Oedema Palpebrae.'
    },
  ]
}

export function THT(): any{
  return [
    {
      label: 'Tonsil',
      model: 'Tonsil',
      value:  'Tonsil'
    },
    {
      label: 'Pharing',
      model: 'Pharing',
      value:  'Pharing'
    },
    {
      label: 'Telinga',
      model: 'Telinga',
      value:  'Telinga'
    },
    {
      label: 'Hidung',
      model: 'Hidung',
      value:  'Hidung'
    },
    {
      label: 'Bibir',
      model: 'Bibir',
      value:  'Bibir'
    },
    {
      label: 'Lainnya',
      model: 'Lainnya',
      value:  'Lainnya'
    }
  ]
}

export function PULMO():any {
  return [
    {
      label: 'Ronchi',
      model: 'Ronchi',
      value:  'Ronchi'
    },
    {
      label: 'Wheezing',
      model: 'Wheezing',
      value:  'Wheezing'
    },
    {
      label: 'Veskuler',
      model: 'Veskuler',
      value:  'Veskuler',
    },
    {
      label: 'Lainnya',
      model: 'Lainnya',
      value:  'Lainnya',
    },
  ]
}

export function Abdomen():any {
  return [
    {
      label: 'Souffle',
      model: 'Souffle',
      value:  'Souffle'
    },
    {
      label: 'Distensi',
      model: 'Distensi',
      value:  'Distensi'
    },
    {
      label: 'Meteorismus',
      model: 'Meteorismus',
      value:  'Meteorismus',
    },
  ]
}
