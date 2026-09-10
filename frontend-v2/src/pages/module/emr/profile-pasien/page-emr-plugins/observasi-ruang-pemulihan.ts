export function checklist(): any {
  return [{
    'label' :'TD',
    'model' :'td',
    'satuan' :'mmHg',
  },{
    'label' :'N',
    'model' :'n',
    'satuan' :'x/mnt',
  },
  {
    'label' :'RR',
    'model' :'rr',
    'satuan' :'x/mnt',
  },
  {
    'label' :'SpO2',
    'model' :'SpO2',
    'satuan' :'x/mnt',
  }
]
}
export function checkbox(): any {
  return [
   {
    'label' :'Jalan Nafas',
    'values':[
        {
            'name' :'Tidak Ada Jalan Nafas',
            'model' :'jalanNafas',
            'value' :'Tidak Ada Jalan Nafas'
        }
    ]
  },
  {
    'label' :'Pernafasan',
    'values':[
        {
            'name' :'Spontan',
            'model' :'pernafasan',
            'value' :'spontan'
        },
        {
            'name' :'Dibantu',
            'model' :'pernafasan',
            'value' :'dibantu'
        }
    ]
  },
  {
    'label' :'Bila Spontan',
    'values':[
        {
            'name' :'Adekuat Bersuara',
            'model' :'bilaSpontan',
            'value' :'Adekuat Bersuara'
        },
        {
            'name' :'Penyumbatan',
            'model' :'bilaSpontan',
            'value' :'penyumbatan'
        },
        {
            'name' :'Membutuhkan Bantuan Alat',
            'model' :'bilaSpontan',
            'value' :'Membutuhkan Bantuan Alat'
        }
    ]
  }
  ,{
    'label' :'Kesadaran',
    'values':[
        {
            'name' :'Sadar Betul',
            'model' :'kesadaran',
            'value' :'Sadar Betul'
        },
        {
            'name' :'Belum Sadar Betul',
            'model' :'kesadaran',
            'value' :'Belum Sadar Betul'
        },
        {
            'name' :'Tidur Dalam',
            'model' :'kesadaran',
            'value' :'Tidur Dalam'
        }
    ]
  }
]
}
