export function Gejala(): any{
  return [
    { label: 'Tidak dijelaskan', value: 'Tidak dijelaskan', model: 'tidakDijelaskan' },
    { label: 'Syncope', value: 'Syncope', model: 'Syncope' },
    { label: 'Bradicardia', value: 'Bradicardia', model: 'Bradicardia' },
    { label: 'Takikardia', value: 'Takikardia', model: 'Takikardia' },
    { label: 'Payah jantung', value: 'Payah jantung', model: 'payahJantung' },
    { label: 'Lainnya', value: 'Lainnya', model: 'lainnya' },
  ]
}

export function EKG(): any {
  return [
    { label: 'Irama tak dijelaskan', value: 'Irama tak dijelaskan', model: 'iramaTakDijelaskan' },
    { label: 'Irama tak docodeir', value: 'Irama tak docodeir', model: 'iramaTakDocodeir' },
    { label: 'Irama sinus normal', value: 'Irama sinus normal', model: 'iramaSinusNormal' },
    { label: 'Heart block', value: 'Heart block', model: 'heartBlock' },
    { label: 'Heart block wenchebach', value: 'Heart block wenchebach', model: 'heartBlockWenchebach' },
    { label: 'Heart block mobitz', value: 'Heart block mobitz', model: 'heartBlockMobitz' },
    { label: 'CHB', value: 'CHB', model: 'chb' },
    { label: 'RBBB', value: 'RBBB', model: 'rbbb' },
    { label: 'LBBB', value: 'LBBB', model: 'lbbb' },
    { label: 'SSS', value: 'SSS', model: 'sss' },
    { label: 'SSS + AV block', value: 'SSS + AV block', model: 'sssAvBlock' },
    { label: 'VES', value: 'VES', model: 'ves' },
    { label: 'VT', value: 'VT', model: 'vt' },
    { label: 'Atrial takikardia', value: 'Atrial takikardia', model: 'atrialTakikardia' },
    { label: 'Pre-eksitasi', value: 'Pre-eksitasi', model: 'preEksitasi' },
    { label: 'Lainnya', value: 'Lainnya', model: 'lainnyaEKG' },
  ]
}

export function Etiologi(): any {
  return [
    { label: 'Iskhemik', value: 'Iskhemik', model: 'iskhemik' },
    { label: 'Pasca infark', value: 'Pasca infark', model: 'pascaInfark' },
    { label: 'Miokardiopati', value: 'Miokardiopati', model: 'miokardiopati' },
    { label: 'Miokarditis', value: 'Miokarditis', model: 'miokarditis' },
    { label: 'Penyakit katup', value: 'Penyakit katup', model: 'penyakitKatup' },
    { label: 'Sindroma karotis sinus', value: 'Sindroma karotis sinus', model: 'sindromaKarotisSinus' },
    { label: 'Bedah', value: 'Bedah', model: 'bedah' },
    { label: 'Bawaan', value: 'Bawaan', model: 'bawaan' },
    { label: 'Lainnya', value: 'Lainnya', model: 'lainnya' },
  ]
}

export function Single(): any {
  return [
    { label: 'Frek. PPM', value: 'Frek. PPM', model: 'frek_ppm' },
    { label: 'Magnet rate', value: 'Magnet rate', model: 'magnet_rate' },
    { label: 'Pulse width', value: 'Pulse width', model: 'pulse_width' },
    { label: 'Amplitude', value: 'Amplitude', model: 'Amplitude' },
    { label: 'Sensitivity', value: 'Sensitivity', model: 'Sensitivity' },
    { label: 'Refraktory periode', value: 'Refraktory periode', model: 'refraktory_periode' },
  ]
}

export function Double(): any {
  return [
    { label: 'Low rate', value: 'Low rate', model: 'lowRate' },
    { label: 'Upper rate', value: 'Upper rate', model: 'upperRate' },
    { label: 'Magnet rate', value: 'Magnet rate', model: 'magnetRate' },
    { label: 'AV interval', value: 'AV interval', model: 'avInterval' },
    { label: 'Pulse width atrium', value: 'Pulse width atrium', model: 'pulseWidthAtrium' },
    { label: 'Sensitivity atrium', value: 'Sensitivity atrium', model: 'sensitivityAtrium' },
    { label: 'Pulse width ventrikel', value: 'Pulse width ventrikel', model: 'pulseWidthVentrikel' },
    { label: 'Amplitude ventrikel', value: 'Amplitude ventrikel', model: 'amplitudeVentrikel' },
    { label: 'Sensitivity ventrikel', value: 'Sensitivity ventrikel', model: 'sensitivityVentrikel' }
  ]
}

export function LeadAntrium(): any{
  return [
    {
      label: 'model',
      model: 'model_lead_antrium',
      type: 'textbox',
    },
    {
      label: 'Serial Number',
      model: 'serial_number_lead_antrium',
      type: 'textbox',
    },
    {
      label: 'jenis',
      model: 'jenis_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Steroid eluting',
      model: 'steroid_eluting_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Screw in',
      model: 'screw_in_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Ambang sensing',
      model: 'ambang_sensing_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Sensitivity',
      model: 'sensitivity_lead_antrium',
      type: 'addons',
    },
    {
      label: 'Ambang Pacu',
      model: 'ambang_pacu_lead_antrium',
      type: 'multi_textbox',
    }
  ]
}

export function LeadVerintekel(): any{
  return [
    {
      label: 'model',
      model: 'model_lead_antrium',
      type: 'textbox',
    },
    {
      label: 'Serial Number',
      model: 'serial_number_lead_antrium',
      type: 'textbox',
    },
    {
      label: 'jenis',
      model: 'jenis_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Steroid eluting',
      model: 'steroid_eluting_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Screw in',
      model: 'screw_in_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Ambang sensing',
      model: 'ambang_sensing_lead_antrium',
      type: 'checkbox',
    },
    {
      label: 'Sensitivity',
      model: 'sensitivity_lead_antrium',
      type: 'addons',
    },
    {
      label: 'Ambang Pacu',
      model: 'ambang_pacu_lead_antrium',
      type: 'multi_textbox',
    }
  ]
}

export function IndikasiPenggantian(): any {
  return [
    { label: 'Tidak dijelaskan', value: 'Tidak dijelaskan', model: 'tidakDijelaskan' },
    { label: 'Tidak dicodeir', value: 'Tidak dicodeir', model: 'tidakDicodeir' },
    { label: 'Elektif', value: 'Elektif', model: 'elektif' },
    { label: 'Elektif untuk ganti sistim', value: 'Elektif untuk ganti sistim', model: 'elektifGantiSistim' },
    { label: 'Elektif untuk gangguan elektroda', value: 'Elektif untuk gangguan elektroda', model: 'elektifGangguanElektroda' },
    { label: 'Protrusi mekanis', value: 'Protrusi mekanis', model: 'protrusiMekanis' },
    { label: 'Stimulasi ekstra kardiak', value: 'Stimulasi ekstra kardiak', model: 'stimulasiEkstraKardiak' },
    { label: 'Inhibisi EMG', value: 'Inhibisi EMG', model: 'inhibisiEMG' },
    { label: 'Gagal tidak dijelaskan', value: 'Gagal tidak dijelaskan', model: 'gagalTidakDijelaskan' },
    { label: 'Gagal output rendah', value: 'Gagal output rendah', model: 'gagalOutputRendah' },
    { label: 'Gagal rate magnetic lambat', value: 'Gagal rate magnetic lambat', model: 'gagalRateMagneticLambat' },
    { label: 'Gagal rate cepat', value: 'Gagal rate cepat', model: 'gagalRateCepat' },
    { label: 'Gagal rate magnetic cepat', value: 'Gagal rate magnetic cepat', model: 'gagalRateMagneticCepat' },
    { label: 'Gagal gangguan konektor', value: 'Gagal gangguan konektor', model: 'gagalGangguanKonektor' },
    { label: 'Gagal enkapsulasi', value: 'Gagal enkapsulasi', model: 'gagalEnkapsulasi' },
    { label: 'Undersensing', value: 'Undersensing', model: 'undersensing' },
    { label: 'Gagal oversensing', value: 'Gagal oversensing', model: 'gagalOversensing' },
    { label: 'Gagal magnetic switch', value: 'Gagal magnetic switch', model: 'gagalMagneticSwitch' },
    { label: 'Gagal programming', value: 'Gagal programming', model: 'gagalProgramming' },
    { label: 'Gagal deplesi batere (EOL)', value: 'Gagal deplesi batere (EOL)', model: 'gagalDeplesiBatereEOL' },
    { label: 'Gagal deplesi batere (premature)', value: 'Gagal deplesi batere (premature)', model: 'gagalDeplesiBaterePremature' }
  ];
}

export function indikasiPengngantianLead(): any {
  return [
    { label: 'Tidak dijelaskan', value: 'Tidak dijelaskan', model: 'tidakDijelaskan' },
    { label: 'Tidak dicodeir', value: 'Tidak dicodeir', model: 'tidakDicodeir' },
    { label: 'Elektif', value: 'Elektif', model: 'elektif' },
    { label: 'Kegagalan konektor', value: 'Kegagalan konektor', model: 'kegagalanKonektor' },
    { label: 'Exit block', value: 'Exit block', model: 'exitBlock' },
    { label: 'Inhibisi EMG', value: 'Inhibisi EMG', model: 'inhibisiEMG' },
    { label: 'Stimulasi ekstra kardiak', value: 'Stimulasi ekstra kardiak', model: 'stimulasiEkstraKardiak' },
    { label: 'Undersensing', value: 'Undersensing', model: 'undersensing' },
    { label: 'Perporasi', value: 'Perporasi', model: 'perporasi' },
    { label: 'Insulator terbuka', value: 'Insulator terbuka', model: 'insulatorTerbuka' },
    { label: 'Konduktor patah', value: 'Konduktor patah', model: 'konduktorPatah' }
  ];
}

export function IndikasiPenggantianCatatan(): any {
  return [
    { label: 'Tidak dijelaskan', value: 'Tidak dijelaskan', model: 'tidakDijelaskanCatatan' },
    { label: 'Pacu jantung dikeluarkan', value: 'Pacu jantung dikeluarkan', model: 'pacuJantungDikeluarkan' },
    { label: 'Hilang pada follow up', value: 'Hilang pada follow up', model: 'hilangPadaFollowUp' },
    { label: 'Kematian', value: 'Kematian', model: 'kematian' }
  ];
}

export function KeteranganLainnya() {
  return [
    { label: 'Operator', value: 'Operator', model: 'operator' },
    { label: 'Nurse scrub', value: 'Nurse scrub', model: 'nurseScrub' },
    { label: 'Monitoring', value: 'Monitoring', model: 'monitoring' },
    { label: 'Persiapan alat', value: 'Persiapan alat', model: 'persiapanAlat' },
    { label: 'Lama flouroskopi', value: 'Lama flouroskopi', model: 'lamaFlouroskopi' },
    { label: 'Lama tindakan', value: 'Lama tindakan', model: 'lamaTindakan' },
    { label: 'Anjuran/rencana', value: 'Anjuran/rencana', model: 'anjuranRencana' },
    { label: 'Follow up', value: 'Follow up', model: 'followUp' }
  ];
}
