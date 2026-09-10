import { generateToken } from '../../../src/api.js'
import getComboEmr from './get-combo-emr.js'
import getDiagnosaPasienBynoreg from './get-diagnosa-pasien-bynoreg.js'
import getHistoryCppt from './get-history-cppt.js'
import getEMRTransaksi from '../vital-sign/get-emr-transaksi.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getComboEmr({ token: data.token })
  getEMRTransaksi({ token: data.token })
  getDiagnosaPasienBynoreg({ token: data.token })
  getHistoryCppt({ token: data.token })
}
