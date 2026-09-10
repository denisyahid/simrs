import { generateToken } from '../../../src/api.js'
import getStokRuangan from './get-stok-ruangan.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getStokRuangan({ token: data.token })
}
