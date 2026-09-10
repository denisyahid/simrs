export function jenisTindakanRadioterapi(): any {
  return [
    {
      label: 'SSD',
      model: 'SSD',
      value: 'SSD',
    },
    {
      label: 'SAD',
      model: 'SAD',
      value: 'SAD',
    },
    {
      label: 'Coplanar',
      model: 'Coplanar',
      value: 'Coplanar',
    },
    {
      label: 'Non Coplanar',
      model: 'nonCoplanar',
      value: 'Non Coplanar',
    },
  ]
}

export function teknikPenyinaran(): any {
  return [
    {
      label: '2D',
      model: 'teknikPenyinaran',
      value: '2D',
    },
    {
      label: '3D CRT',
      model: 'teknikPenyinaran',
      value: '3D CRT',
    },
    {
      label: 'IMRT',
      model: 'teknikPenyinaran',
      value: 'IMRT',
    },
    {
      label: 'VMAT',
      model: 'teknikPenyinaran',
      value: 'VMAT',
    },
  ]
}

export function JenisKelamin(): any{
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' }
  ]
}

export function energy(): any{
  return [
    {
      label: 'Foton 6MV',
      value: 'Foton6MV',
    },
    {
      label: 'Foton 10MV',
      value: 'Foton10MV',
    },
    {
      label: 'Elektron 4MeV',
      value: 'Elektron4MeV',
    },
    {
      label: 'Elektron 6MeV',
      value: 'Elektron6MeV',
    },
    {
      label: 'Elektron 9MeV',
      value: 'Elektron9MeV',
    },
    {
      label: 'Elektron 12MeV',
      value: 'Elektron12MeV',
    },
    {
      label: 'Elektron 16MeV',
      value: 'Elektron16MeV',
    },
  ]
}

export function accessories(): any{
  return [
    {
      label: 'MLC',
      value: 'MLC',
    },
    {
      label: 'Aplikator',
      value: 'Aplikator',
    },
    {
      label: 'Bolus',
      value: 'Bolus',
    },
    {
      label: 'Blok',
      value: 'Blok',
    },
    {
      label: 'Wedge',
      value: 'Wedge',
    },
  ]
}

export function formField(): any{
  return [
    {
      label: 'VRT',
      value: 'VRT',
      addons: 'cm'
    },
    {
      label: 'LNG',
      value: 'LNG',
      addons: 'cm'
    },
    {
      label: 'LAT',
      value: 'LAT',
      addons: 'cm'
    },
    {
      label: 'ROT',
      value: 'ROT',
      addons: 'n0'
    },
    {
      label: 'Waktu',
      value: 'Waktu',
      addons: 'menit'
    },
    {
      label: 'Field 1',
      value: 'Field 1',
      addons: 'MU'
    },
    {
      label: 'Field 2',
      value: 'Field 2',
      addons: 'MU'
    },
    {
      label: 'Field 3',
      value: 'Field 3',
      addons: 'MU'
    },
    {
      label: 'Field 4',
      value: 'Field 4',
      addons: 'MU'
    },
    {
      label: 'Field 5',
      value: 'Field 5',
      addons: 'MU'
    },
    {
      label: 'Field 6',
      value: 'Field 6',
      addons: 'MU'
    },
    {
      label: 'Field 7',
      value: 'Field 7',
      addons: 'MU'
    },
    {
      label: 'Field 8',
      value: 'Field 8',
      addons: 'MU'
    },
    {
      label: 'Field 9',
      value: 'Field 9',
      addons: 'MU'
    },
    {
      label: 'Dosis Tumor',
      value: 'Dosis Tumor',
      addons: 'Gy'
    },
  ]
}

export function pesawat():any{
  return [
    {
      label: 'Clinac CX',
      value: 'Clinac CX',
    }
  ]
}
