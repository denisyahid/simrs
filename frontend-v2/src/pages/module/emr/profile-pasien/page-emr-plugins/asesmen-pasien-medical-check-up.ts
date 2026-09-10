export function asesmenPasienMedicalCheckUp(): any {
  return [
    {
      title: 'ANAMNESIS',
    },
    {
      title: 'KHUSUS TENAGA KERJA',
    },
    {
      title: 'STATUS SOSIAL EKONOMI KULTURAL SPIRITUAL',
    },
    {
      title: 'STATUS PSIKOLOGI',
    },
    {
      title: 'TANDA VITAL',
    },
  ]
}
export function ANAMNESIS():any {
  return [
    {
      field : "",
      type : "checkbox",
      name : "Autooanamnesa",
      label : "Autooanamnesa",
      column : "is-4"
    },
    {
      field : "",
      type : "checkbox",
      name : "Heteroanamnesa",
      label : "Heteroanamnesa",
      column : "is-4"
    },
    {
      field : "Jenis MCU",
      type : "textarea",
      row: 2,
      name : "jenisMCu",
      column : "is-12",
      placeholder : "Jenis MCU",
    },
    {
      field : "Keluhan Utama",
      type : "textarea",
      row: 2,
      name : "keluhanUtama",
      column : "is-6",
      placeholder : "Keluhan Utama.."
    },
    {
      field : "Riwayat Penyakit Sekarang",
      type : "textarea",
      row: 2,
      name : "riwayatPenyakitSekarang",
      column : "is-6",
      placeholder : "Riwayat Penyakit Sekarang.."
    },
    {
      field : "Riwayat Penyakit Dahulu",
      type : "textarea",
      row: 2,
      name : "riwayatPenyakitDahulu",
      column : "is-6",
      placeholder : "Riwayat Penyakit Dahulu.."
    },
    {
      field : "Riwayat Penyakit Keluarga",
      type : "textarea",
      row: 2,
      name : "riwayatPenyakitKeluarga",
      column : "is-6",
      placeholder : "Riwayat Penyakit Keluarga.."
    },
    {
      field : "Riwayat Pengobatan/Operasi",
      type : "textarea",
      row: 2,
      name : "riwayatPengobatanOperasi",
      column : "is-6",
      placeholder : "Riwayat Pengobatan/Operasi.."
    },
    {
      field : "Riwayat Alergi",
      type : "textarea",
      row: 2,
      name : "riwayatAlergi",
      column : "is-6",
      placeholder : "Riwayat Alergi.."
    },
    {
      field : "Diet",
      type : "textarea",
      row: 2,
      name : "diet",
      column : "is-6",
      placeholder : "Diet.."
    },
    {
      field : "Kebiasaan Olah raga",
      type : "textarea",
      row: 2,
      name : "keiasaanOlahraga",
      column : "is-6",
      placeholder : "Kebiasaan Olah raga.."
    },
    {
      field : "Kebiasaan merokok",
      type : "textarea",
      row: 2,
      name : "kebiasaanMerokok",
      column : "is-6",
      placeholder : "Kebiasaan merokok.."
    },
    {
      field : "Konsumsi Alkohol",
      type : "textarea",
      row: 2,
      name : "kosumsiAlkohol",
      column : "is-6",
      placeholder : "Konsumsi Alkohol.."
    },
    {
      field : "Konsumsi kopi",
      type : "textarea",
      row: 2,
      name : "konsumsiKopi",
      column : "is-6",
      placeholder : "Konsumsi kopi.."
    },
    {
      field : "Obat-obatan yang dikonsumsi",
      type : "textarea",
      row: 2,
      name : "obatObatanYangDikonsumsi",
      column : "is-6",
      placeholder : "Obat-obatan yang dikonsumsi.."
    },
    {
      field : "Medical Ckeck Up Terakhir",
      type : "textarea",
      row: 2,
      name : "medicalCheckupTerakhir",
      column : "is-12",
      placeholder : "Medical Ckeck Up Terakhir.."
    },
  ]
}
export function STATUSSOSIAL():any {
  return [
    {
      field : "Pendidikan",
      type : "input",
      name : "pendidikan",
      column : "is-4",
      placeholder : "Pendidikan"
    },
    {
      field : "Pekerjaan",
      type : "input",
      name : "pekerjaan",
      column : "is-4",
      placeholder : "Pekerjaan"
    },
    {
      field : "Status",
      type : "input",
      name : "statusPasien",
      column : "is-4",
      placeholder : "Status"
    },
    {
      field : "Kebangsaan",
      type : "input",
      name : "kebangsaan",
      column : "is-4",
      placeholder : "Kebangsaan"
    },
    {
      field : "Agama",
      type : "input",
      name : "agama",
      column : "is-4",
      placeholder : "Agama"
    },
    {
      field : "Suku",
      type : "input",
      name : "suku",
      column : "is-4",
      placeholder : "Suku"
    },
  ]
}
export function KHUSUSTENAGAKERJA():any {
  return [
    {
      field : "",
      type : "checkbox",
      name : "khususTenagaKerja",
      label : "Khusus Tenaga Kerja",
      column : "is-12"
    },
    {
      type : "textarea",
      name : "jenisPekerjaan",
      field : "Jenis Pekerjaan",
      column : "is-6",
      placeholder :"Jenis Pekerjaan..",
      row : 2
    },
    {
      type : "textarea",
      name : "posisiTubuhSaatKerja",
      field : "Posisi tubuh saat kerja",
      column : "is-6",
      placeholder : "Posisi tubuh saat kerja..",
      row : 2
    },
    {
      type : "textarea",
      name : "tempatPerkerjaan",
      field : "Tempat pekerjaan",
      column : "is-6",
      placeholder : "Tempat pekerjaan..",
      row : 2
    },
    {
      type : "textarea",
      name : "lamaKerja",
      field : "Lama Kerja",
      column : "is-6",
      placeholder : "Lama Kerja..",
      row : 2
    },
    {
      type : "textarea",
      name : "riwayatBahayaLingkunganKerja",
      field : "Riwayat bahaya lingkungan kerja",
      column : "is-6",
      placeholder : "Riwayat bahaya lingkungan kerja..",
      row : 2
    },
    {
      type : "textarea",
      name : "lainLain",
      field : "Lain - lain",
      column : "is-6",
      placeholder : "Lain - lain..",
      row : 2
    },
  ]
}
export function TANDAVITAL():any {
  return [
    {
      "title": "TD",
      "model": "tekananDarah",
      "satuan": "mmHg"
  },
  {
      "model": "RR",
      "title": "RR",
      "satuan": "x/min"
  },
  {
      "title": "HR",
      "model": "nadi",
      "satuan": ""
  },
  {
      "title": "Suhu",
      "model": "suhu",
      "satuan": "°C"
  },
  {
      "title": "SPO2",
      "model": "SPO2",
      "satuan": ""
  },
  {
      "title": "TB",
      "model": "tinggiBadan",
      "satuan": "Cm"
  },
  {
      "title": "BB",
      "model": "beratBadan",
      "satuan": "Kg"
  },
  {
      "title": "LP",
      "model": "lingkarPerut",
      "satuan": ""
  },
  ]
}
export function STATUSPSIKOLOGI():any {
  return [
    {
      field : "",
      type : "checkbox",
      name : "tenang",
      label : "Tenang",
      column : "is-3"
    },
    {
      field : "",
      type : "checkbox",
      name : "cemas",
      label : "Cemas",
      column : "is-3"
    },
    {
      field : "",
      type : "checkbox",
      name : "takut",
      label : "Takut",
      column : "is-3"
    },
    {
      field : "",
      type : "checkbox",
      name : "merah",
      label : "Merah",
      column : "is-3"
    },
    {
      field : "",
      type : "checkbox",
      name : "sedih",
      label : "Sedih",
      column : "is-3"
    },
    {
      field : "",
      type : "checkbox",
      name : "kencenderunganBunuhDiri",
      label : "Kencenderungan Bunuh Diri",
      column : "is-3"
    },
    {
      field : "",
      type : "checkbox",
      name : "positifNormal",
      label : "Positif/Normal",
      column : "is-3"
    },
  ]
}

export function FUNGSIONAL():any {
  return [
    {
      field : "Alat Bantu",
      type : "input",
      name : "alatBantu",
      column : "is-4",
      placeholder : "Alat Bantu.."
    },
    {
      field : "Prothesa",
      type : "input",
      name : "prothesa",
      column : "is-4",
      placeholder : "Prothesa.."
    },
    {
      field : "Cacat Tubuh",
      type : "input",
      name : "cacatTubuh",
      column : "is-4",
      placeholder : "Cacat Tubuh.."
    },
    {
      field :"ADL :",
      name : "adl",
      column  : "is-12",
      type : "checkbox",
      children :[
        {
          label : "Mandiri",
          value : "Mandiri"
        },
        {
          label : "Dibantu",
          value : "Dibantu"
        }
      ]
    }
  ]
}
export function GLASGOWS():any {
  return [
    {
      label : "Respon Buka Mata (Eye Opening : E)",
      model : "responBukaMata",
      children :[
        {
          label :"Spontan",
          score :4
        },
        {
          label :"Terhdap Suara",
          score :3
        },
        {
          label :"Terhdap Nyeri",
          score :2
        },
        {
          label :"Tidak Ada",
          score :1
        }
      ]
    },
    {
      label : "Respon Motorik Terbaik (M)",
      model : "responMotorik",
      children :[
        {
          label :"Turut Perintah",
          score :6
        },
        {
          label :"Melokalisir Nyeri",
          score :5
        },
        {
          label :"Fleksi Normal (Menarik anggota gerak yang dirangsang)",
          score :4
        },
        {
          label :"Fleksi Abnormal (dekortikasi)",
          score :3
        },
        {
          label :"Ekstensi Abnormal (deserebrasi)",
          score :2
        },
        {
          label :"Tidak Ada",
          score :2
        },
      ]
    },
    {
      label : "Respon Verbal (V)",
      model : "responVerbal",
      children : [
        {
          label :"Berorientasi Baik",
          score :5
        },
        {
          label :"Berbicara mengacau (bingung)",
          score :4
        },
        {
          label :"Kata-Kata tidak teratur",
          score :3
        },
        {
          label :"Susah Tidak Jelas",
          score :2
        },
        {
          label :"Tidak Ada",
          score :2
        },
      ]
    }
  ]
}
export function RESIKOJATUH():any {
  return [
    {
      label :"a. Perhatikan cara berjalan pasien saat akan duduk dikursi, apakah pasien tampak tidak seimbang (sempoyongan/ limbung)",
      model :"caraJalanSempoyongan",
      children :[
        {
          label : "Tidak" ,
          score :0
        },
        {
          label : "Ya" ,
          score :1
        }
      ]
    },
    {
      label : "b. Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk",
      model :"memegangangKursiSaatJalan",
      children :[
        {
          label : "Tidak" ,
          score :0
        },
        {
          label : "Ya" ,
          score :1
        }
      ]
    }
  ]
}

export function MASALAHKEPERAWATAN() :any {
  return [
    {
      model: 'masalahkeperawatan_',
      label: "Obesitas",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Berat badan lebih",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Defisit Nutrisi",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Ketidakstabilan Kadar Glukosa Darah",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Keletihan",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Risikp Perfusi miokard/renall/serebal/tidak efektif",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Ansietas",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Perilaku kesehatan cenderung berisiko",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Defisit pengetahuan",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Ketidakpatuhan",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Kesiapan peningkatan manajemen kesehatan",
      type: "checkbox"
    },
    {
      model :"masalahkeperawatan_",
      label: "Risiko jatuh",
      type: "checkbox"
    },
  ]
}
export function TINDAKANKEPERWATAN() :any {
  return [
    {
      label: "Edukasi berat badan efektif",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen berat badan ",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Edukasi diet",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen nutrisi",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Konseling nutrisi",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen prilaku",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen hyperglikemia",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen hypoglikemia",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Edukasi aktifitas/istirahat",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen energi",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen cairan ",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },

    {
      label: "Edukasi program pengobatan ",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Edukadi berhenti merokok",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Reduksi  Ansietas",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Terapi relaksasi",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Modifikasi perilaku",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Promosi perilaku upaya kesehatan ",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Edukasi kesehatan",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Dukungan kepatuhan program pengobatan ",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Promosi kesadaran diri ",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Bimbingan antisipatif",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Identifikasi risiko",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Pencegahan jatuh",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
    {
      label: "Manajemen keselamatan lingkungan ",
      type: "checkbox",
      model :"tindakankeperawatan_"
    },
  ]
}
export function ANAMNESAKEPERWATAN() :any {
  return [
      {
        field : "",
        type : "checkbox",
        name : "Autooanamnesa",
        label : "Autooanamnesa",
        column : "is-6"
      },
      {
        field : "",
        type : "checkbox",
        name : "Heteroanamnesa",
        label : "Heteroanamnesa",
        column : "is-6"
      },
      {
        field : "Keluhan Utama",
        type : "textarea",
        row: 2,
        name : "perawatKeluhanUtama",
        column : "is-6",
        placeholder : "Keluhan Utama..",
      },
      {
        field : "Riwayat Penyakit Sekarang",
        type : "textarea",
        row: 2,
        name : "perawatRiwayatPenyakitSekarang",
        column : "is-6",
        placeholder : "Riwayat Penyakit Sekarang..",
      },
      {
        field : "Riwayat Penyakit Dahulu",
        type : "textarea",
        row: 2,
        name : "perawatRiwayatPenyakitDahulu",
        column : "is-6",
        placeholder : "Riwayat Penyakit Dahulu..",
      },
      {
        field : "Riwayat Penyakit Keluarga",
        type : "textarea",
        row: 2,
        name : "perawatRiwayatPenyakitKeluarga",
        column : "is-6",
        placeholder : "Riwayat Penyakit Keluarga..",
      },
      {
        field : "Riwayat Pengobatan/Operasi",
        type : "textarea",
        row: 2,
        name : "perawatRiwayatPengobatan",
        column : "is-6",
        placeholder : "Riwayat Pengobatan/Operasi..",
      },
      {
        field : "Riwayat Alergi",
        type : "textarea",
        row: 2,
        name : "perawatRiwayatAlergi",
        column : "is-6",
        placeholder : "Riwayat Alergi..",
      },
  ]
}

export function PERAWAT () :any {
  return [
    {
      title : "Anamnesa",
      children :[
        {
          field : "",
          type : "checkbox",
          name : "Autooanamnesa",
          label : "Autooanamnesa",
          column : "is-6"
        },
        {
          field : "",
          type : "checkbox",
          name : "Heteroanamnesa",
          label : "Heteroanamnesa",
          column : "is-6"
        },
        {
          field : "Keluhan Utama",
          type : "textarea",
          row: 2,
          name : "perawatKeluhanUtama",
          column : "is-6",
          placeholder : "Keluhan Utama..",
        },
        {
          field : "Riwayat Penyakit Sekarang",
          type : "textarea",
          row: 2,
          name : "perawatRiwayatPenyakitSekarang",
          column : "is-6",
          placeholder : "Riwayat Penyakit Sekarang..",
        },
        {
          field : "Riwayat Penyakit Dahulu",
          type : "textarea",
          row: 2,
          name : "perawatRiwayatPenyakitDahulu",
          column : "is-6",
          placeholder : "Riwayat Penyakit Dahulu..",
        },
        {
          field : "Riwayat Penyakit Keluarga",
          type : "textarea",
          row: 2,
          name : "perawatRiwayatPenyakitKeluarga",
          column : "is-6",
          placeholder : "Riwayat Penyakit Keluarga..",
        },
        {
          field : "Riwayat Pengobatan/Operasi",
          type : "textarea",
          row: 2,
          name : "perawatRiwayatPengobatan",
          column : "is-6",
          placeholder : "Riwayat Pengobatan/Operasi..",
        },
        {
          field : "Riwayat Alergi",
          type : "textarea",
          row: 2,
          name : "perawatRiwayatAlergi",
          column : "is-6",
          placeholder : "Riwayat Alergi..",
        },
      ]
    },
    {
      title : "Status Gizi",
      children :[
        {
          field :"Status Gizi :",
          name : "perawatStatusGizi",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Gizi Kurang / Buruk",
              value : "Gizi Kurang / Buruk",
              column  : "is-12",
            },
            {
              label : "Gizi Cukup",
              value : "Gizi Cukup",
              column  : "is-12",
            }
          ]
        }
      ]
    },
    {
      title : "PEMERIKSAAN FISIK UMUM",
      children :[
        {
          field : "Riwayat Alergi",
          type : "textarea",
          row: 2,
          name : "perawatRiwayatAlergi",
          column : "is-12",
          placeholder : "Riwayat Alergi..",
        },
        {
          field :"Kepala",
          name : "perawatKepala",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Mata",
          name : "perawatMata",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Leher",
          name : "perawatLeher",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Thoraks",
          name : "perawatThoraks",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Payudara",
          name : "perawatPayudara",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Paru - paru",
          name : "perawatParuParu",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Jantung",
          name : "perawatJantung",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Abdomen",
          name : "perawatAbdomen",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Urgoenital",
          name : "perawatUrgoenital",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Spine",
          name : "perawatSpine",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Kulit",
          name : "perawatKulit",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field :"Ekstremitas",
          name : "perawatEkstremitas",
          column  : "is-12",
          type : "checkbox",
          children :[
            {
              label : "Normal",
              value : "Normal",
              column  : "is-4",
            },
            {
              label : "Tidak Normal",
              value : "Tidak Normal",
              column  : "is-4",
            }
          ]
        },
        {
          field : "Lainnya, Jelaskan",
          type : "textarea",
          row: 2,
          name : "perawatLainya",
          column : "is-12",
          placeholder : "Lainnya, Jelaskan..",
        },
      ]
    },
    {
      title : "PEMERIKSAAN PENUNJANG",
      children :[
        {
          field : "Laboratorium",
          type : "textarea",
          row: 2,
          name : "perawatLaboratorium",
          column : "is-12",
          placeholder : "Laboratorium..",
        },
        {
          field : "Radiologi",
          type : "textarea",
          row: 2,
          name : "perawatRadiologi",
          column : "is-12",
          placeholder : "Radiologi..",
        },
        {
          field : "EKG",
          type : "textarea",
          row: 2,
          name : "perawatEKG",
          column : "is-12",
          placeholder : "EKG..",
        },
        {
          field : "EEG",
          type : "textarea",
          row: 2,
          name : "perawatEEG",
          column : "is-12",
          placeholder : "EEG..",
        },
        {
          field : "Lain-Lain",
          type : "textarea",
          row: 2,
          name : "perawatLainLain",
          column : "is-12",
          placeholder : "Lain-Lain..",
        },
        {
          field : "Diagnosis Medis / Kesimpulan",
          type : "textarea",
          row: 2,
          name : "perawatDiagnosis",
          column : "is-12",
          placeholder : "Diagnosis Medis / Kesimpulan..",
        },
        {
          field : "Rekomendasi",
          type : "textarea",
          row: 2,
          name : "perawatRekomendasi",
          column : "is-12",
          placeholder : "Rekomendasi..",
        },
      ]
    }
  ]
}
