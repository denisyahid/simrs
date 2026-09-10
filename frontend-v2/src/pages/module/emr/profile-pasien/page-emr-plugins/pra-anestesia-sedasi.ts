export function statusAnestesi(): any {
  return [
    {
      label: 'Diagnosa Pra Bedah',
      model: 'diagPraBedah',
    },
    {
      label: 'Jenis Pembedahan',
      model: 'jenisPembedahan',
    },
    {
      label: 'Diagnosis Pasca Bedah',
      model: 'diagnosaPasBedah',
    },
  ]
}

export function asesmentAnestesi(): any {
  return [
    {
      subtitle: 'Cek list persiapan Anestesi',
      values: [
        {
          label: 'Inform consent',
          model: 'checklistPersiapan_1',
        },
        {
          label: 'Obat-obatan anestesi',
          model: 'checklistPersiapan_2',
        },
        {
          label: 'Tatalaksana jalan nafas',
          model: 'checklistPersiapan_3',
        },
        {
          label: 'Mesin Anestesi',
          model: 'checklistPersiapan_4',
        },
        {
          label: 'Monitoring',
          model: 'checklistPersiapan_6',
        },
        {
          label: 'Obat-obatan emergensi',
          model: 'checklistPersiapan_5',
        },
        {
          label: 'Suction appratus',
          model: 'checklistPersiapan_7',
        },
      ],
    },
    {
      subtitle: 'Monitoring',
      values: [
        {
          label: 'EKG',
          model: 'monitoring_1',
        },
        {
          label: 'SpO2',
          model: 'monitoring_2',
        },
        {
          label: 'A-line',
          model: 'monitoring_3',
        },
        {
          label: 'Stetoskop',
          model: 'monitoring_4',
        },
        {
          label: 'Temp.',
          model: 'monitoring_5',
        },
        {
          label: 'Koteter urin',
          model: 'monitoring_6',
        },
        {
          label: 'Lain-lain',
          model: 'monitoring_7',
        },
        {
          label: 'NIBP',
          model: 'monitoring_8',
        },
        {
          label: 'EtCO2',
          model: 'monitoring_9',
        },
        {
          label: 'CVP',
          model: 'monitoring_10',
        },
        {
          label: 'Cath A-pulmo',
          model: 'monitoring_11',
        },
        {
          label: 'NGT',
          model: 'monitoring_12',
        },
        {
          label: 'BIS',
          model: 'monitoring_13',
        },
      ],
    },
  ]
}
export function detailsAnestesi(): any {
  return [
    {
      subTitle: 'Kesadaran',
      type: 'input',
      model: 'kesadaran',
    },
    {
      subTitle: 'TD',
      type: 'input',
      model: 'td',
    },
    {
      subTitle: 'N',
      type: 'input',
      model: 'n',
    },
    {
      subTitle: 'R',
      type: 'input',
      model: 'r',
    },
    {
      subTitle: 'SpO2',
      type: 'input',
      model: 'spo2',
    },
    {
      subTitle: 'Keluhan',
      type: 'input',
      model: 'keluahan',
    },
    {
      subTitle: 'Pemeriksaan Fisik',
      type: 'input',
      model: 'pemerikasaanFisik',
    },
    {
      subTitle: 'Pasien dapat dilakukan tindakan pemberian anestesi / pembedahan :',
      type: 'checkbox',
      values: [
        {
          subTitle: 'Ya',
          model: 'pasienDapatDilakukanTindakanAnestesi',
        },
        {
          subTitle: 'Tidak',
          model: 'pasienDapatDilakukanTindakanAnestesi',
        },
      ],
    },
    {
      subTitle: 'Perubahan rencana teknik anestesi :',
      type: 'checkbox',
      values: [
        {
          subTitle: 'Ya',
          model: 'perubahanAnestesi',
        },
        {
          subTitle: 'Tidak',
          model: 'perubahanAnestesi',
        },
      ],
    },
    {
      subTitle: 'Rencana Teknik Anestesi',
      type: 'input',
      model: 'rencanaTeknikAnestesi',
    },
  ]
}
