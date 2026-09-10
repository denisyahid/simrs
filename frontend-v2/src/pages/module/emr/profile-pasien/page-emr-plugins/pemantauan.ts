export function pemantauan(): any {
  return [
    {
      'label': 'Mulai Pembiusan Pkl',
      'model' :'mulaiPembiusan'
    },
    {
      'label': 'Selesai Pembiusan Pkl',
      'model' :'selesaiPembiusan'
    },
    {
      'label': 'Mulai Pembedahan Pkl',
      'model' :'mulaiPembedahan'
    },
    {
      'label': 'Selesai Pembedahan Pkl',
      'model' :'selesaiPembedahan'
    },
  ]
}

export function durations():any{
    return [
        {
            'label' :'Lama Pembiusan',
            'values':[
                {
                    'name' :'Jam',
                    'model' :'lamaJamPembiusan'
                },
                {
                  'name' :'Menit',
                  'model' :'lamaMenitPembiusan'
                }
            ]
        },
        {
          'label' :'Lama Pembedahan',
          'values':[
              {
                  'name':'Jam',
                  'model' :'lamaJamPembedahan'
              },
              {
                'name' :'Menit',
                'model' :'lamaMenitPembedahan'
              }
          ]
      }
    ]
}