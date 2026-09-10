export function JenisKelamin(): any {
    return [
      { label: 'Laki-laki', value: 'Laki-laki' },
      { label: 'Perempuan', value: 'Perempuan' },
    ]
  }
  
  export function StatusPernikahan(): any {
    return [
      { label: 'Menikah', value: 'Menikah' },
      { label: 'Single', value: 'Single' },
      { label: 'Cerai', value: 'Cerai' },
    ]
  }
  
  export function JenisPasien(): any {
    return [
      { label: 'Rawat Jalan', value: 'RawatJalan' },
      { label: 'Rawat Inap', value: 'RawatInap' },
      { label: 'Cito', value: 'Cito' },
      { label: 'Travelling HD', value: 'TravellingHD' },
      { label: 'Reguler', value: 'Reguler' },
      { label: 'Elektif', value: 'Elektif' },
    ]
  }
  export function kesadaran(): any {
    return [
      {label: 'Sadar', value: 'Sadar'},
      {label: 'Tidak Sadar', value: 'Tidak Sadar'},
      {label: 'Apatis', value: 'Apatis'},
    ]
  }
  export function kesadaranUmum(): any {
    return [
      {label: 'Baik', value: 'Baik'},
      {label: 'Lemah', value: 'Lemah'},
      {label: 'Sangat Lemah', value: 'Sangat Lemah'},
    ]
  }
  export function vitalSign(): any{
    return [
      {label: 'Tekanan Darah', value: 'TekananDarah', addOns: 'mmHg'},
      {label: 'Suhu', value: 'Suhu', addOns: '°C'},
      {label: 'Nadi', value: 'Nadi', addOns: 'x/mnt'},
      {label: 'Respirasi', value: 'Respirasi', addOns: 'x/mnt'},
    ]
  }
  export function Konjungtiva(): any{
    return [
      {label: 'Palpebra Edema', value: 'PalpebraEdema'},
      {label: 'Anemesis', value: 'Anemesis'},
      {label: 'Sklera Ikterik', value: 'SkleraIkterik'},
    ]
  }
  
  export function Ekstremitas(): any{
    return [
      {label: 'Tidak Edema', value: 'TidakEdema'},
      {label: 'Edema', value: 'Edema'},
      {label: 'Pucat/Dingin', value: 'Pucat/Dingin'},
    ]
  }
  
  export function aksesVaskular(): any{
    return[
      {label: 'Fistula', value: 'Fistula'},
      {label: 'Sinistra', value: 'Sinistra'},
      {label: 'Dextra', value: 'Dextra'},
      {label: 'Double Lument', value: 'DoubleLument'},
      {label: 'Femoral', value: 'Femoral'},
      {label: 'Junggularis', value: 'Junggularis'},
      {label: 'Femoral', value: 'Femoral'},
      {label: 'Subclavia', value: 'Subclavia'},
    ]
  }
  
  export function resikoJatuh(): any{
    return[
      {label: 'Skala Morse', value: 'SkalaMorse'},
      {label: 'Skala Mumpty Dumpty', value: 'SkalaMumptyDumpty'},
      {label: 'Rendah 0-7', value: 'Rendah0-7'},
      {label: 'Rendah 7-11', value: 'Rendah7-11'},
      {label: 'Sedang 8-13', value: 'Sedang8-13'},
      {label: 'Tinggi ≥ 12', value: 'Tinggi≥12'},
    ]
  }
  
  export function imgNyeri(): any {
    return {
      nama: 'Hurts',
      detail: [
        {
          nama: 'No Hurt',
          descNilai: 0,
          img: '/images/skalanyeri/1.png',
        },
        {
          nama: 'Hurts Little Bit',
          descNilai: 2,
          img: '/images/skalanyeri/2.png',
        },
        {
          nama: 'Hurts Little More',
          descNilai: 4,
          img: '/images/skalanyeri/3.png',
        },
        {
          nama: 'Hurts Even More',
          descNilai: 6,
          img: '/images/skalanyeri/4.png',
        },
        {
          nama: 'Hurts Whole Lot',
          descNilai: 8,
          img: '/images/skalanyeri/5.png',
        },
        {
          nama: 'Hurts whorts',
          descNilai: 10,
          img: '/images/skalanyeri/6.png',
        },
      ],
    }
  }
  export function skoringNyeri(): any {
    return {
      nama: 'Score ',
      detail: [
        { nama: '0 - 1 = Tidak Ada Nyeri', descNilai: 0 },
        { nama: '2 - 3 = Sedikit Nyeri', descNilai: 2 },
        { nama: '4 - 5 = Cukup Nyeri', descNilai: 4 },
        { nama: '6 - 7 = Lumayan Nyeri', descNilai: 6 },
        { nama: '8 - 9 = Sangat Nyeri', descNilai: 8 },
        { nama: '10 = Amat Sangat Nyeri', descNilai: 10 },
      ],
    }
  }
  
  export function pemantauanTindakanKeperawatan(): any{
    return [
      {label: '1. Pemantauan tanda vital', value: 'Pemantauan tanda vital', model:'pemantauanTandaVital'},
      {label: '2. Pemantauan cairan', value: 'Pemantauan cairan', model:'pemantauanCairan'},
      {label: '3. Pemantauan elektrolit', value: 'Pemantauan elektrolit', model:'pemantauanElektrolit'},
      {label: '4. Pencegahan perdarahan', value: 'Pencegahan perdarahan', model:'pencegahanPerdarahan'},
      {label: '5. Pencegahan infeksi', value: 'Pencegahan infeksi', model:'pencegahanInfeksi'},
      {label: '6. Pencegahan syok', value: 'Pencegahan syok', model:'pencegahanSyok'},
      {label: '7. Manajemen hipervolemia', value: 'Manajemen hipervolemia', model:'manajemenHipervolemia'},
      {label: '8. Manajemen hemodialis', value: 'Manajemen hemodialis', model:'manajemenHemodialis'},
      {label: '9. Perawatan dialisis', value: 'Perawatan dialisis', model:'perawatanDialisis'},
      {label: '10. Manajemen lingkungan', value: 'Manajemen lingkungan', model:'manajemenLingkungan'},
      {label: '11. Reduksi ansietas', value: 'Reduksi ansietas', model:'reduksiAnsietas'},
      {label: '12. Manajemen nutrisi', value: 'Manajemen nutrisi', model:'manajemenNutrisi'},
      {label: '13. Tranfusi darah', value: 'Tranfusi darah', model:'tranfusiDarah'},
      {label: '14. Edukasi', value: 'Edukasi', model:'edukasi'},
    ]
  }
  