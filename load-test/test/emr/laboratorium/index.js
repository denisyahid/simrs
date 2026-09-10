import { generateToken } from '../../../src/api.js'
import getComboPenunjang from './get-combo-penunjang.js'
import getDaftarOrder from './get-daftar-order.js'
import getRincianPelayanan from './get-rincian-pelayanan.js'
import getRiwayatOrderPenunjang from './get-riwayat-order-penunjang.js'
import getTindakanWithDetails from './get-tindakan-with-details.js'
import saveBridgingVansLab from './save-bridging-vans-lab.js'
import saveOrderPelayanan from './save-order-pelayanan.js'
import savePelayananPasien from './save-pelayanan-pasien.js'
import getDashboardLabDetail from './dashboard-get-lab-detail.js'
import getDashboardSOLab from './dashboard-so-lab.js'
import getPasienLab from './get-registrasi-pasien-lab.js'
import getStatusClose from './get-status-close.js'
import getDataChartLabRuangan from './get-chart-lab-ruangan.js'
import getPenunjangLab from './dashboard-get-penujang-lab.js'
import getRegistrasiPasien from './get-registrasi-pasien-lab.js'
import getDetailLab from './get-detail-lab.js'
import getListVerif from './get-list-verif.js'
import getStatusCloseLab from './get-status-close.js'
import getOrder from './get-order.js'
import getPelayananLab from './get-pelayanan-lab.js'
import getPegawai from './get-pegawai_m.js'
import getListLab from './get-list-lab.js'


export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  // getComboPenunjang({ token: data.token })
  // getRiwayatOrderPenunjang({ token: data.token })
  // getTindakanWithDetails({ token: data.token })
  // saveOrderPelayanan({ token: data.token })
  // getDaftarOrder({ token: data.token })
  // saveBridgingVansLab({ token: data.token })
  // savePelayananPasien({ token: data.token })
  // getRincianPelayanan({ token: data.token })
  // GetDashboardSOLab({ token: data.token })
  // getDataChartLabRuangan({ token: data.token })
  // getPenunjangLab({ token: data.token })
  // getPasienLab({ token: data.token })
  // getDetailLab({ token: data.token })
  // getListVerif({ token: data.token })
  // getStatusClose({ token: data.token })
  // getOrder({ token: data.token })
  // getPelayananLab({ token: data.token })
  // getPegawai({ token: data.token })
  getDashboardSOLab({ token: data.token })
  getDashboardLabDetail({ token: data.token })
  getDataChartLabRuangan({ token: data.token })
  getPenunjangLab({ token: data.token })
  getListLab({ token: data.token })
  getRegistrasiPasien({ token: data.token })
  getListVerif({ token: data.token })
  getDetailLab({ token: data.token })
  getStatusCloseLab({ token: data.token })
  getOrder({ token: data.token })
  getPelayananLab({ token: data.token })
  getPegawai({ token: data.token })
  
}
