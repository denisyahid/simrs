import { generateToken } from '../../../src/api.js'
import getDashRad from './get-dash-rad.js'
import getDokterRadiologi from './get-dokter-radiologi.js'
import getPenunjang from './get-daftar-penunjang.js'
import getDetail from './get-detail.js'
import getDetailPelayanan from './get-detail-pelayanan.js'
import getDetailOrder from './get-detail-order.js'
import saveVerifRad from './save-verif-brid-radiologi.js'
import saveBilling from './save-billing.js'


export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {

    getDashRad({token:data.token})
    getDokterRadiologi({token:data.token})
    getPenunjang({token:data.token})
    getDetailPelayanan({token:data.token})
    getDetail({token:data.token})
    getDetailOrder({token:data.token})
    saveVerifRad({token:data.token})
    saveBilling({token:data.token})
  
}
