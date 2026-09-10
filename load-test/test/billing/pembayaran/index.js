import { generateToken } from '../../../src/api.js'
// import getDaftarTagihanPasien from './get-daftar-tagihan-pasien.js'
// import getDataComboKasir from './get-data-combo-kasir.js'
// import getDataDaftarSbm from './get-data-daftar-sbm.js'
// import getDataPembayaran from './get-data-pembayaran.js'
// import simpanDataPembayaran from './simpan-data-pembayaran.js'
import GetDashboardKasir from './get-dashboard-kasir.js'
import GetTagihanPasien from './get-tagihan-pasien.js'
import GetPasienPulang from './get-pasien-pulang.js'
import GetNonLayanan from './get-non-layanan.js'
import GetPasienAktif from './get-pasien-aktif.js'
import GetPiutangPasien from './get-piutang-pasien.js'
import GetPenerimaanKasir from './get-penerimaan-kasir.js'
import GetPengeluaranKasir from './get-pengeluaran-kasir.js'
import GetDepositPasien from './get-deposit-pasien.js'
import GetTindakanNonLayanan from './tindakan-non-layanan.js'
import GetPembayaranTagihan from './get-pembayaran-tagihan.js'
import GetKwitansiRJWNA from './kwitansi-rajal-wna.js'
import GetKwitansiRI from './kwitansi-ranap.js'
import GetDropdownPenerimaan from './get-dropdown-penerimaan.js'
import GetPenerimaanHarian from './get-penerimaan-harian.js'
import GetBillingKasir from './get-billing-kasir.js'
import GetPetugasTindakan from './get-petugas-tindakan.js'
import GetJenisPetugas from './get-jenis-petugas.js'
import GetDropdownKonversi from './get-dropdown-konversi.js'
import GetTagihanKonversi from './get-tagihan-konversi.js'
import SaveTanggalTindakan from './update-tgl-tindakan.js'
import DeleteJenisPetugas from './delete-jenis-petugas.js'
import SaveJenisPetugas from './save-jenis-petugas.js'
import UpdateHargaKonversi from './update-harga-konversi.js'
import SaveNonLayanan from './simpan-non-layanan.js'
import SavePembayaranTagihan from './simpan-pembayaran-tagihan.js'
import SaveCaraBayar from './ubah-cara-bayar.js'
import SaveVerifikasiTagihan from './verifikasi-tagihan.js'
import SaveBatalPulang from './save-batal-pulang.js'
import SaveBatalBayar from './save-batal-bayar.js'
import SaveHapusTindakan from './save-hapus-tindakan.js'
import SaveHargaKonversi from './save-harga-konversi.js'
import SaveClosing from './save-closing.js'
import SavePengembalianDeposit from './pengembalian-deposit.js'

export function setup () {
  return {
    token: generateToken().json()['response']['token']
  }
}

export default function (data) {
  // getDataComboKasir({ token: data.token })
  // getDaftarTagihanPasien({ token: data.token })
  // getDataPembayaran({ token: data.token })
  // simpanDataPembayaran({ token: data.token })
  // getDataDaftarSbm({ token: data.token })


  GetDashboardKasir({ token: data.token })
  GetTagihanPasien({ token: data.token })
  GetPasienPulang({ token: data.token })
  GetNonLayanan({ token: data.token })
  GetPasienAktif({ token: data.token })
  GetPiutangPasien({ token: data.token })
  GetPenerimaanKasir({ token: data.token })
  GetPengeluaranKasir({ token: data.token })
  GetDepositPasien({ token: data.token })
  GetTindakanNonLayanan({ token: data.token })
  GetPembayaranTagihan({ token: data.token })
  GetKwitansiRJWNA({ token: data.token })
  GetKwitansiRI({ token: data.token })
  GetDropdownPenerimaan({ token: data.token })
  GetPenerimaanHarian({ token: data.token })
  GetBillingKasir({ token: data.token })
  GetPetugasTindakan({ token: data.token })
  GetJenisPetugas({ token: data.token })
  GetDropdownKonversi({ token: data.token })
  GetTagihanKonversi({ token: data.token })
  SaveTanggalTindakan({ token: data.token })
  DeleteJenisPetugas({ token: data.token })
  SaveJenisPetugas({ token: data.token })
  UpdateHargaKonversi({ token: data.token })
  SaveNonLayanan({ token: data.token })
  SavePembayaranTagihan({ token: data.token })
  SaveCaraBayar({ token: data.token })
  SaveVerifikasiTagihan({ token: data.token })
  SaveBatalPulang({ token: data.token })
  SaveBatalBayar({ token: data.token })
  SaveHapusTindakan({ token: data.token })
  SaveHargaKonversi({ token: data.token })
  SaveClosing({ token: data.token })
  SavePengembalianDeposit({ token: data.token })
}
