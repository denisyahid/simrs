import { generateToken } from '../../../src/api.js'
import getDropdownHarga from './get-dropdown-harga.js'
import getHargaNetto from './get-harga-netto.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getDropdownHarga({ token: data.token })
  getHargaNetto({ token: data.token })
}
