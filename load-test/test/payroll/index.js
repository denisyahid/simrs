import { generateToken } from '../../src/api.js'
import gajiPegawai from './gaji-pegawai.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  gajiPegawai({ token: data.token })
}
