export function JenisKelamin(): any {
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}

export function studyType(): any {
  return [
    { label: 'Trans Thoraca Echo (Bayi)', value: 'TransThoracaEchoBayi', id: 1 },
    { label: 'Trans Thoraca Echo (Dewasa)', value: 'TransThoracaEchoDewasa', id: 2 },
    { label: 'Lower extermity dupplex ultrasound (USG Doppler)', value: 'LowerExtermityDuplexUltrasoundUSGDoppler', id: 3 },
    { label: 'Carotid dupplex ultrasound', value: 'CarotidDuplexUltrasound', id: 4 },
    { label: 'Bruce protocol', value: 'BruceProtocol', id: 5},
    { label: 'EKG Jantung', value: 'EKGJantung', id: 5},
  ]
}

export function jenisPemeriksaanObgyn(): any{
  return [
    { label: 'Pemeriksaan Kardiotokografi', value: 'PemeriksaanKardiotokografi', id: 1 },
    { label: 'Pemeriksaan USG Obstetri', value: 'PemeriksaanUSGObstetri', id: 2 },
    { label: 'Pemeriksaan USG Gynekologi', value: 'PemeriksaanUSGGynekologi', id: 3 },
    { label: 'Pemeriksaan USG Fetal', value: 'PemeriksaanUSGFetal', id: 4 },
  ]
}

export function resultTransThoracaEchoBayi(): any{
  return [
    {label: 'Ao Diameter', model: 'aoDiameter', addons: 'mm'},
    {label: 'LA Diameter', model: 'laDiameter', addons: 'mm'},
    {label: 'EF Biplane', model: 'efBiplane', addons: '%'},
    {label: 'IVC Min', model: 'ivcMin', addons: 'mm'},
    {label: 'IVC Max', model: 'ivcMax', addons: 'mm'},
    {label: 'Tapse', model: 'tapse', addons: 'mm'},
    {label: 'IVSd', model: 'ivsd', addons: 'mm'},
    {label: 'LVIDd', model: 'lvidd', addons: 'mm'},
    {label: 'LVPWD', model: 'lpwd', addons: 'mm'},
    {label: 'EIF TEICH', model: 'eifTEICH', addons: '%'},
    {label: 'LVMI', model: 'lvmi', addons: 'g/m2'},
    {label: 'RWT', model: 'lvpwd', addons: 'mm'},
    {label: 'MV/EA Ratio', model: 'mvEaRatio', addons: ''},
    {label: 'MV e spetal', model: 'mvESpetal', addons: 'cm/s'},
    {label: 'MV e Lateral', model: 'mvELateral', addons: 'cm/s'},
    {label: 'E/e¹', model: 'ee', addons: ''},
    {label: 'PV ACT', model: 'pvAct', addons: 'm/s'},
    {label: 'AO VMax', model: 'aoVMax', addons: 'm/s'},
  ]
}

export function resultTransThoracaEchoDewasa(): any{
  return[
    {label: 'Ao Diameter', model: 'aoDiameter', addons: 'mm'},
    {label: 'LA Diameter', model: 'laDiameter', addons: 'mm'},
    {label: 'EF Biplane', model: 'efBiplane', addons: '%'},
    {label: 'IVC Min', model: 'ivcMin', addons: 'mm'},
    {label: 'IVC Max', model: 'ivcMax', addons: 'mm'},
    {label: 'Tapse', model: 'tapse', addons: 'mm'},
    {label: 'IVSd', model: 'ivsd', addons: 'mm'},
    {label: 'LVIDd', model: 'lvidd', addons: 'mm'},
    {label: 'LVPWD', model: 'lvpwd', addons: 'mm'},
    {label: 'EIF TEICH', model: 'eifTEICH', addons: '%'},
    {label: 'LV MASS', model: 'lvmass', addons: 'g'},
    {label: 'RWT', model: 'lpwd', addons: 'mm'},
    {label: 'MV/EA Ratio', model: 'mvEaRatio', addons: ''},
    {label: 'MV e spetal', model: 'mvESpetal', addons: 'cm/s'},
    {label: 'MV e Lateral', model: 'mvELateral', addons: 'cm/s'},
    {label: 'E/e¹', model: 'ee', addons: ''},
    {label: 'PV ACT', model: 'pvAct', addons: 'm/s'},
    {label: 'AO VMax', model: 'aoVMax', addons: 'm/s'},
  ]
}

export function leftColumnItems(): any{
  return [
    { label: "Cardiac chamber dimension", model: "cardiacChamber" },
    { label: "LVH", model: "lvh" },
    { label: "Systolic LV function", model: "systolicLv" },
    { label: "Diastolic LV function", model: "diastolicLv" },
    { label: "RV contractility", model: "rvContractility" },
    { label: "LV wall motion", model: "lvWallMotion" },
  ]
}

export function rightColumnItems(): any{
  return [
    { label: "Aortic valve", model: "aorticValve" },
    { label: "Mitral valve", model: "mitralValve" },
    { label: "Tricuspid valve", model: "tricuspidValve" },
    { label: "Pulmonary valve", model: "pulmonaryValve" },
  ]
}

export function findingTransThoracaEchoDewasa(): any{
  return [
    {label: 'Cardhiac Chamber Dimension', model: 'cardiacChamberDimension'},
    {label: 'LVH', model: 'lnh'},
    {label: 'Systolic LV Function', model: 'systolicLVFunction'},
    {label: 'Diastolic LV Function', model: 'diastolicLVFunction'},
    {label: 'RV Contractility', model: 'rvContractility'},
    {label: 'LV Wall Motion', model: 'lvWallMotion'},
    // {label: 'Heart Wave', model: 'heartWave'},
    {label: 'Aortic Valve', model: 'aorticValve'},
    {label: 'Mitral Valve', model: 'mitralValve'},
    {label: 'Tricuspid Valve', model: 'tricuspidValve'},
    {label: 'Pulmonary Valve', model: 'pulmonaryValve'},
  ]
}
