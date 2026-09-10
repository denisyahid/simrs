
import jurnal from './jurnal.js'
import neraca from './neraca.js'
import bukuBesar from './buku-besar.js'
import { generateToken } from '../../src/api.js'
export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  neraca({ token: data.token })
  jurnal({ token: data.token })
  bukuBesar({ token: data.token })
}
