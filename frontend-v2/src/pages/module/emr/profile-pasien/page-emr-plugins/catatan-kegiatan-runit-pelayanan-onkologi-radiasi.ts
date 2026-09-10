export function vitalSign(): any {
  return [
    {
      label: 'Tekanan Darah',
      model: 'tekananDarah',
      addon: 'mmHG',
    },
    {
      label: 'PR',
      model: 'nadi',
      addon: 'x/mnt',
    },
    {
      label: 'RR',
      model: 'nafas',
      addon: 'x/mnt',
    },
    {
      label: 'Suhu',
      model: 'celcius',
      addon: '°C',
    },
    {
      label: 'SaO2',
      model: 'sao2',
      addon: '%',
    }
  ]
}
