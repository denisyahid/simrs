import { generateToken } from '../../src/api.js'
import getAgendaRapat from './get-agenda-rapat.js'
import getNotulen from './get-notulen.js'
import getSuratMasuk from './get-surat-masuk.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getAgendaRapat({ token: data.token })
  getNotulen({ token: data.token })
  getSuratMasuk({ token: data.token })
}
