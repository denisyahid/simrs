export function signIn(): any {
    return [
      {
        "title": "",
        "value": [
          {
              type: "label",
              label: "Tanggal dan Jam :",
              column: 2,
          },
          {
              type: "dateTime",
              model: "tanggalSignIn",
              column: 4,
          },
          {
              type: "kosong",
              column: 5,
          },
          {
            type: "label",
            label: "<h3 style='font-size: 1.5rem; font-weight: bold; margin-top: 1rem;'>MINIMAL ADA PERAWAT DAN DOKTER ANASTESI</h3>",
            column: 12,
          },
          {
            type: "label",
            label: "Apakah identitas pasien sudah benar, rencana tindakan sudah jelas dan ada persetujuan tindakan medis yang akan dilakukan (<i>inform concern</i>) ?",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "SI1Ya",
            column: 2,
          },
          {
            type:'kosong',
            column: 10,
          },
          {
            type: "label",
            label: "Apakah area yang akan dioperasi sudah diberi tanda ?",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "SI2Ya",
            column: 6,
          },
          {
            type: "checkBox",
            subTitle: "Tidak diperlukan",
            model: "SI2TidakDiperlukan",
            column: 6,
          },
          {
            type: "label",
            label: "Apakah mesin anastesi dan obat-obatan sudah lengkap ?",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "SI3Ya",
            column: 2,
          },
          {
            type:'kosong',
            column: 10,
          },
          {
              type: "label",
              label: "Apakah mesin sudah memakai “pulse oksimetri” dan sudah berfungsi baik ?",
              column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "SI4Ya",
            column: 2,
          },
          {
            type:'kosong',
            column: 10,
          },
          {
            type: "label",
            label: "Apakah pasien memiliki :",
            column: 12,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "label",
            label: "Riwayat alergi ?",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "SI5Ya",
            column: 5,
          },
          {
            type: "checkBox",
            subTitle: "Tidak",
            model: "SI5Tidak",
            column: 6,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "label",
            label: "Gangguan pernafasan ?",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Ya dan alat/bantuan sudah tersedia",
            model: "SI6YaDanAlatBantuanSudahTersedia",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Tidak",
            model: "SI6Tidak",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "label",
            label: "Resiko pendarahan > 5000 ml (7 ml/kg bagi anak-anak)",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Ya dan sudah direncanakan pemasangan infus 2 line",
            model: "SI7YaDanSudahDirencanakan",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Tidak",
            model: "SI7Tidak",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "label",
            label: "Ketersediaan alat inplant",
            column: 11,
          },
          {
            type:'kosong',
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "SI8Ya",
            column: 5,
          },
          {
            type: "checkBox",
            subTitle: "Tidak",
            model: "SI8Tidak",
            column: 6,
          },
        ]
      },
    ]
}

export function timeOut(): any {
    return [
      {
        "title": "",
        "value": [
          {
              type: "label",
              label: "Tanggal dan Jam :",
              column: 2,
          },
          {
              type: "dateTime",
              model: "tanggalTimeOut",
              column: 4,
          },
          {
              type: "kosong",
              column: 5,
          },
          {
            type: "label",
            label: "<h3 style='font-size: 1.5rem; font-weight: bold; margin-top: 1rem;'>DENGAN PERAWAT, DOKTER ANESTESI DAN DOKTER BEDAH</h3>",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Memastikan bahwa semua anggota tim medis sudah memperkenalkan diri (nama dan peran)",
            model: "TO1PerkenalanDiri",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Memastikan dan baca ulang nama pasien, tindakan medis dan area yang akan diinsisi",
            model: "TO2BacaUlang",
            column: 12,
          },
          {
            type: "label",
            label: "Apakah profilaksis antibiotik sudah diberikan 1 jam sebelumnya ?",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "TO3Ya",
            column: 6,
          },
          {
            type: "checkBox",
            subTitle: "Tidak Perlu",
            model: "TO3TidakPerlu",
            column: 6,
          },
          {
            type: "label",
            label: "Kejadian beresiko yang perlu diantisipasi",
            column: 12,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "label",
            label: "UNTUK DOKTER BEDAH",
            column: 11,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Apakah tindakan beresiko atau tindakan tidak rutin yang akan dilakukan ?",
            model: "TO4ApakahTindakanBeresiko",
            model2: "TO4ApakahTindakanBeresikoText",
            column: 11,
            column1: 12,
            column2: 12,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Berapa lama tindakan ini akan dikerjakan ?",
            model: "TO5BerapaLamaTindakan",
            model2: "TO5BerapaLamaTindakanText",
            column: 11,
            column1: 12,
            column2: 12,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Apakah sudah antisipasi pendarahan ?",
            model: "TO6ApakahSudahAntisipasi",
            model2: "TO6ApakahSudahAntisipasiText",
            column: 11,
            column1: 12,
            column2: 12,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "label",
            label: "UNTUK DOKTER ANESTESI",
            column: 11,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Apakah ada hal khusus untuk pasien ini ?",
            model: "TO7ApakahAdaHalKhusus",
            model2: "TO7ApakahAdaHalKhususText",
            column: 11,
            column1: 12,
            column2: 12,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "label",
            label: "UNTUK TIM PERAWAT",
            column: 11,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Apakah sudah dipastikan kesterilitasnya (ada indikator kesterilannya) ?",
            model: "TO8ApakahSudahDipastikan",
            model2: "TO8ApakahSudahDipastikanText",
            column: 11,
            column1: 8,
            column2: 4,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Apakah ada masalah dengan peralatan atau masalah alat yang dikawatirkan ?",
            model: "TO9ApakahAdaMasalah",
            model2: "TO9ApakahAdaMasalahText",
            column: 11,
            column1: 12,
            column2: 12,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Apakah hasil radiologi yang diperlukan sudah ada ?",
            model: "TO10ApakahHasilRadiologi",
            column: 11,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBox",
            subTitle: "Ya",
            model: "TO11Ya",
            column: 5,
          },
          {
            type: "checkBox",
            subTitle: "Tidak diperlukan",
            model: "TO11Tidak",
            column: 6,
          },
        ]
      },
    ]
}

export function signOut(): any {
    return [
      {
        "title": "",
        "value": [
          {
              type: "label",
              label: "Tanggal dan Jam :",
              column: 2,
          },
          {
              type: "dateTime",
              model: "tanggalSignOut",
              column: 4,
          },
          {
              type: "kosong",
              column: 5,
          },
          {
            type: "label",
            label: "<h3 style='font-size: 1.5rem; font-weight: bold; margin-top: 1rem;'>DENGAN PERAWAT, DOKTER ANESTESI DAN DOKTER BEDAH</h3>",
            column: 12,
          },
          {
            type: "label",
            label: "Secara verbal perawat memastikan :",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Nama tindakan",
            model: "SO1NamaTindakan",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Kelengkapan alat, jumlah kasa dan jarum",
            model: "SO2KelengkapanAlat",
            column: 12,
          },
          {
            type: "checkBox",
            subTitle: "Pelabelan specimen dengan (barcode)",
            model: "SO2PelabelanSpecimen",
            column: 12,
          },
          {
            type: "checkBoxText",
            subTitle: "Apakah ada masalah peralatan yang perlu disampaikan",
            model: "SO3ApakahAdaMasalahPeralatan",
            model2: "SO3ApakahAdaMasalahPeralatanText",
            column: 12,
            column1: 12,
            column2: 12,
          },
          {
            type: "checkBox",
            subTitle: "Alat inplant yang dipakai",
            model: "SO4AlatImplantDipakai",
            column: 12,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Nama inplant :",
            model: "SO5NamaInplant",
            model2: "SO5NamaInplantText",
            column: 11,
            column1: 3,
            column2: 7,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Ukuran :",
            model: "SO6Ukuran",
            model2: "SO6UkuranText",
            column: 11,
            column1: 3,
            column2: 7,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "checkBoxText",
            subTitle: "Jenis :",
            model: "SO7Jenis",
            model2: "SO7JenisText",
            column: 11,
            column1: 3,
            column2: 7,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "label",
            label: "Barcode Inplant :",
            column: 11,
          },
          {
            type: "kosong",
            column: 1,
          },
          {
            type: "textarea",
            model: "SO8BarcodeInplant",
            column: 11,
          },
          {
            type: "label",
            label: "UNTUK DOKTER BEDAH, DOKTER ANESTESI DAN PERAWAT",
            column: 12,
          },
          {
            type: "checkBoxText",
            subTitle: "Apakah ada catatan khusus untuk proses recovery dan penanganan perawatan pasien ini ?",
            model: "SO9CatatanKhusus",
            model2: "SO9CatatanKhususText",
            column: 12,
            column1: 12,
            column2: 12,
          },
        ]
      },
    ]
}