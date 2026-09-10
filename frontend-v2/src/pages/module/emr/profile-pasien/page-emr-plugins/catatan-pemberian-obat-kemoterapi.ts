export function vitalSign(): any {
  return [
    {
      label: 'Berat Badan',
      model: 'beratBadan',
      addon: 'kg',
    },
    {
      label: 'Tinggi Badan',
      model: 'tinggiBadan',
      addon: 'cm',
    },
    {
      label: 'BSA',
      model: 'BSA',
      addon: 'BSA',
    },
    {
      label: 'Alergi',
      model: 'alergi',
      addon: 'alergi',
    }
  ]
}
export function JenisKelamin(): any {
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
