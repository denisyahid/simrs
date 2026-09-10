import { generateToken } from '../../../src/api.js'
import getDashboardRj from './get-dashboard-rj.js'
import getDetailEmr from './get-detail-emr.js'
import getRiwayatOrderLab from './get-riwayat-order-lab.js'
import insertCppt from './insert-cppt.js'
import insertOrderLab from './insert-order-lab.js'
import insertVitalSign from './insert-vital-sign.js'


export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDashboardRj({ token: data.token })
  getDetailEmr({ token: data.token })
  insertCppt({ token: data.token })
  insertVitalSign({ token: data.token })
  insertOrderLab({ token: data.token })
  getRiwayatOrderLab({ token: data.token })
 
  
}
