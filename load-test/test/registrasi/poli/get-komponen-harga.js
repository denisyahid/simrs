import { check } from 'k6'
import { generateToken, getKomponenHargaRegis } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getKomponenHargaRegis({ token: data.token })
  check(res, {
    'tindakan/list-tindakan-komponen is status 200': r => r.status === 200
  })
}
