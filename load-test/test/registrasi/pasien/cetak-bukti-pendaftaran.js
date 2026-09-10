import { check } from 'k6'
import { generateToken, cetakBuktiPendaftaran } from '../../../src/api.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  const res = cetakBuktiPendaftaran({ token: data.token })
  check(res, {
    'report/bukti-pendaftaran is status 200': r => r.status === 200
  })
}
