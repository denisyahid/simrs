import { generateToken } from '../../../src/api.js'
import getPenerimaanKasirDropdown from './get-penerimaan-kasir-dropdown.js'
import getPenerimaanKasir from './get-penerimaan-kasir.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  getPenerimaanKasirDropdown({ token: data.token })
  getPenerimaanKasir({ token: data.token })
}
