import { check } from 'k6'
import { generateToken, getTindakanWithDetails } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = getTindakanWithDetails(
    { idRuangan: '575', idKelas: '6', idJenisPelayanan: '1', isLabRad: true },
    { token: data.token }
  )
  check(res, {
    'get-tindakan-with-details is status 200': r => r.status === 200
  })
}
