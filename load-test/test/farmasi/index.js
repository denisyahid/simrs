import { generateToken } from '../../src/api.js'
// import getDetailOrderEResep from './get-detail-order-e-resep.js'
// import getDropdownEResep from './get-dropdown-e-resep.js'
// import listEResep from './list-e-resep.js'
// import verifikasiResep from './verifikasi-resep.js'
import getDashboardEResep from './get-dashboard-eresep.js'
import getProdukDetail from './get-produkdetail.js'
import getDropdownObat from './get-dropdown-obat.js'
import saveInputResep from './input-resep-save.js'
import getCetakResep from './get-cetak-resep.js'
import getCetakLabel from './get-cetak-label.js'
import getTransaksiPelayanan from './get-transaksi-pelayanan.js'
import saveResepManual from './save-resep-manual.js'
import getPasienFramasi from './get-pasien-farmasi.js'
import saveRetur from './save-retur.js'
import getStokRuangan from './get-stok-ruangan.js'
import savePaketObat from './save-paket-obat.js'
import getDaftarRetur from './get-daftar-retur.js'
import saveBatalVerifikasi from './save-batal-verifikasi.js'
import getInputResepOrder from './get-input-resep-order.js'


export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  // getDropdownEResep({ token: data.token })
  // getDetailOrderEResep({ token: data.token })
  // listEResep({ token: data.token })
  // verifikasiResep({ token: data.token })

  getDashboardEResep({ token: data.token })
  getProdukDetail({ token: data.token })
  getDropdownObat({ token: data.token })
  saveInputResep({ token: data.token })
  getCetakResep({ token: data.token })
  getCetakLabel({ token: data.token })
  getTransaksiPelayanan({ token: data.token })
  saveResepManual({ token: data.token })
  getPasienFramasi({ token: data.token })
  saveRetur({ token: data.token })
  getStokRuangan({ token: data.token })
  savePaketObat({ token: data.token })
  getDaftarRetur({ token: data.token })
  saveBatalVerifikasi({ token: data.token })
  getInputResepOrder({ token: data.token })
}
