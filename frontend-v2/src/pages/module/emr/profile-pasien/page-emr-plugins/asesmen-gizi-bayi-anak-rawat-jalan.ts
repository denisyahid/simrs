export function ANTROPOMETRI() {
  return [
    { label: 'Berat Badan Lahir', model: 'beratBadanLahir', addon: 'gr' },
    { label: 'Panjang Badan Lahir', model: 'panjangBadanLahir', addon: 'cm' },
    { label: 'Berat Badan Sekarang', model: 'beratBadanSekarang', addon: 'gr' },
    { label: 'Berat Badan Ideal', model: 'beratBadanIdeal', addon: 'gr' },
    { label: 'Panjang Badan (<2 Tahun)', model: 'panjangBadan', addon: 'cm' },
    { label: 'Tinggi Badan (>2 Tahun)', model: 'tinggiBadan', addon: 'cm' },
    { label: 'Lingkar Kepala', model: 'lingkarKepala', addon: 'cm' },
    { label: 'Lingkar Lengan Atas', model: 'lingkarLenganAtas', addon: 'cm' },
    { label: 'Waterlow', model: 'waterlow', addon: '%' },
    { label: 'Berat Badan/Umur', model: 'beratBadanUmur', addon: '' },
    { label: 'Tinggi Badan/Umur', model: 'tinggiBadanUmur', addon: '' },
    { label: 'Berat Badan/Tinggi Badan', model: 'beratBadanTinggiBadan', addon: '' },
    { label: 'Lingkar Lengan Atas/Umur', model: 'lingkarLenganAtasUmur', addon: '' },
    { label: 'Indek Masa Tubuh/Umur', model: 'indekMasaTubuhUmur', addon: '' }
  ];
}

export function BIOKIMIA() {
  return [
    { label: 'Hb', model: 'hb', addon: 'gr/dl' },
    { label: 'Limfosit', model: 'limfosit', addon: '%' },
    { label: 'Albumin', model: 'albumin', addon: 'g/dl' },
    { label: 'Natrium', model: 'natrium', addon: 'g/dl' },
    { label: 'Gula Darah', model: 'gulaDarah', addon: 'g/dl' },
    { label: 'Keton Urin', model: 'ketonUrin', addon: '' },
    { label: 'AGD: pH', model: 'agdPh', addon: '' },
    { label: 'pCO3', model: 'pco3', addon: '' },
    { label: '-HCO3', model: 'hco3', addon: '' },
    { label: 'Kalium', model: 'kalium', addon: 'g/dl' },
    { label: 'Clorida', model: 'clorida', addon: 'g/dl' },
    { label: 'Anion Gap', model: 'anionGap', addon: '' }
  ];
}

export function KLINIS() {
  return [
    { label: 'Mual', model: 'mual', addon: '' },
    { label: 'Muntah', model: 'muntah', addon: '' },
    { label: 'Diare', model: 'diare', addon: '' },
    { label: 'Sembelit', model: 'sembelit', addon: '' },
    { label: 'Kesulitan Mengunyah', model: 'kesulitanMengunyah', addon: '' },
    { label: 'Kesulitan Menelan', model: 'kesulitanMenelan', addon: '' },
    { label: 'Mikrosefali', model: 'mikrosefali', addon: '' },
    { label: 'Flag Sign', model: 'flagSign', addon: '' },
    { label: 'Old Man Face', model: 'oldManFace', addon: '' },
    { label: 'Moon Face', model: 'moonFace', addon: '' },
    { label: 'Anemia', model: 'anemia', addon: '' },
    { label: 'Bitot Spot', model: 'bitotSpot', addon: '' },
    { label: 'Papil Lidah Atropi', model: 'papilLidahAtropi', addon: '' },
    { label: 'Iga Gambang', model: 'igaGambang', addon: '' },
    { label: 'Pembesaran Hati', model: 'pembesaranHati', addon: '' },
    { label: 'Baggy Pant', model: 'baggyPant', addon: '' },
    { label: 'Edema Kaki', model: 'edemaKaki', addon: '' },
    { label: 'Lemak Subkutan Sedikit', model: 'lemakSubkutanSedikit', addon: '' }
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
    { label: 'Densitas', model: 'Densitas', addon: '' },
  ];
}
