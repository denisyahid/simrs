export function checked(): any {
  return [
    {
      label: 'Pasien akan dirujuk',
      model: 'pasienAkanDirujuk',
      value: 'true',
    },
    {
      label: 'Pasien akan di IGD dengan surat penyataan',
      model: 'pasienIgd',
      value: 'true',
    },
    {
      label: 'Mohon untuk didaftarakan sebagai pasien daftar tunggu / reservasi',
      model: 'pasienTunggu',
      value: 'true',
    },
  ]
}
