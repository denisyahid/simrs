import { generateToken } from '../../../src/api.js'
import getEmrTransaksi from './get-emr-transaksi.js'
import getMenuRekamMedisDynamic from './get-menu-rekam-medis-dynamic.js'
import getRekamMedisDynamic from './get-rekam-medis-dynamic.js'
import insertVitalSign from './insert-vital-sign.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getEmrTransaksi({ token: data.token })
  getMenuRekamMedisDynamic({ token: data.token })
  getRekamMedisDynamic({ token: data.token })
  insertVitalSign({ token: data.token })
}
