export function ANAMNESIS(): any {
  return [
      {
        label: "Anamnesa",
        model: "anamnesa",
        type: "checkbox",
        column : "is-12",
        children: [
          {
            label: " Autooanamnesa",
            column : "is-4",
            value: " Autooanamnesa",
          },
          {
            label: "Heteroanamnesa",
            value: "Heteroanamnesa",
            column : "is-4",
          },
        ]
    },
    {
      label: "Keluhan Utama",
      model: "keluhanUtama",
      type: "textarea",
      column : "is-6",
    },
    {
      column: "is-6",
      label: "Riwayat Penyakit Sekarang",
      model: "riwayatPenyakitSekarang",
      type: "textarea"
     },
    {
      column: "is-6",
      label: "Riwayat Penyakit Dahulu",
      model: "riwayatPenyakitDahulu",
      type: "textarea"
     },
    {
      column: "is-6",
      label: "Riwayat Penyakit Keluarga",
      model: "riwayatPenyakitKeluarga",
      type: "textarea"
     },
    {
      column: "is-6",
      label: "Riwayat Pengobatan/Operasi",
      model: "riwayatPengobatanOperasi",
      type: "textarea"
     },
    {
      column: "is-6",
      label: "Riwayat Alergi",
      model: "riwayatAlergi",
      type: "textarea"
     },
     {
      label: "Akses HD",
      model: "alkesHd",
      type: "checkbox",
      column : "is-12",
      children: [
        {
          column: "col-3",
          label: "Sementara",
          value: "Sementara",

        },
        {
          column: "col-3",
          label: "Permanen",
          value: "Permanen",

        },
        {
          column: "col-3",
          label: "CDL",
          value: "CDL",
        },
        {
          column: "col-3",
          label: "AVF",
          value: "AVF",
        },
      ]
  },
  {
    label: "Dialiser",
    model: "dialiser",
    type: "checkbox",
    column : "is-12",
    children: [
      { column: "is-3",
        label: "Reused",
        value: "Reused",
      },
      { column: "is-4",
        label: "Baru, kemudian dipakai ulang",
        value: "Baru, kemudian dipakai ulang",
      },
      { column: "is-4",
        label: "Reused* - ke  :",
        value: "Reused* - ke  :",
      },
      { column: "is-3",
        label: "Lain-lain",
        value: "Lain-lain",
      }
    ]
},
  ]
}
export function TANDATANDAVITAL(): any {
  return [
        {
          label : "Suhu Badan",
          model : "suhuBadan",
          satuan : "°C",
          column :"is-3"
        },
        {
          label : "Berat Badan",
          model : "beratBadan",
          satuan : "kg",
          column :"is-3"
        },
        {
          label : "Nadi",
          model : "nadi",
          satuan : "x/menit",
          column :"is-3"
        },
        {
          label : "Tinggi Badan",
          model : "tinggiBadan",
          satuan : "cm",
          column :"is-3"
        },
        {
          label : "Pernafasan",
          model : "pernafasan",
          satuan : "x/mnt",
          column :"is-3"
        },
        {
          label : "IMT",
          model : "imt",
          satuan : "x/mnt",
          column :"is-3"
        },
        {
          label : "Tekanan Darah",
          model : "tekananDarah",
          satuan : "mmHg",
          column :"is-3"
        },
        {
          label : "Lingkar Perut",
          model : "lingkarPerut",
          satuan : "cm",
          column :"is-3"
        },
        {
          label: "Note (*)  satuan / puluhan / ratusan = kg",
          type: "labelinfo",
          column :"is-12"
        },
        {
          label: "Berat Badan",
          type: "labelinfo",
          column :"is-12"
        },
        {
          label: "BB Pre HD",
          model: "BBPreHD",
          type: "textbox",
          satuan: "Kg/Gram",
          column :"is-3"
        },
        {
          label: "BBK",
          model: "BBK",
          type: "textbox",
          satuan: "Kg/Gram",
          column :"is-3"
        },
        {
          label: "BB Post HD Lalu",
          model: "BBPostHDLalu",
          type: "textbox",
          satuan: "Kg/Gram",
          column :"is-3"
        },
        {
          label: "BB Post HD",
          model: "BBPostHD",
          type: "textbox",
          satuan: "Kg/Gram",
          column :"is-3"
        },
        {
          label: "Tinggi Badan",
          model: "tinggiBadan",
          type: "textbox",
          satuan: "Cm",
          column :"is-3"
        },
        {
          label: "IMT",
          model: "IMT",
          type: "textbox",
          satuan: "",
          column :"is-3"
        },
        {
          label: "Kategori IMT",
          model: "kategoriIMT",
          type: "textbox",
          satuan: "",
          column :"is-3"
        },
        {
          label: "Kenaikan BB Interdialisis",
          model: "kenaikanBBInterdialisis",
          type: "textbox",
          satuan: "Kg/Gram",
          column :"is-3"
        },
  ]}
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
  export function STATUSFUNGSIONAL():any {
    return [
      {
        label: "Alat Bantu",
        model: "alatBantu",
        type: "text",
        column : "is-6",
      },
      {
        label: "Prothesa",
        model: "prothesa",
        type: "text",
        column : "is-6",
      },
      {
        label: "Cacat Tubuh",
        model: "catatTuhub",
        type: "text",
        column : "is-6",
      },
      {
        label: "ADL",
        model: "adl",
        type: "checkbox",
        column : "is-12",
        children: [
          {
            label: " Mandiri",
            value: " Mandiri",
            column : "is-4",
          },
          {
            label: "Dibantu",
            value: "Dibantu",
            column : "is-4",
          },
        ]
    },
    ]
  }
  export function IMGNYERI() : any {
    return {
        "nama": "Hurts", "detail": [
            {
                "nama": "No Hurt", "descNilai": 0,
                "img": "/images/skalanyeri/1.png"
            },
            {
                "nama": "Hurts Little Bit", "descNilai": 2,
                "img": "/images/skalanyeri/2.png",
            },
            {
                "nama": "Hurts Little More", "descNilai": 4,
                "img": "/images/skalanyeri/3.png",
            },
            {
                "nama": "Hurts Even More", "descNilai": 6,
                "img": "/images/skalanyeri/4.png",
            },
            {
                "nama": "Hurts Whole Lot", "descNilai": 8,
                "img": "/images/skalanyeri/5.png",
            },
            {
                "nama": "Hurts whorts", "descNilai": 10,
                "img": "/images/skalanyeri/6.png",
            }]
    }
}
export function SKORNYERI() : any {
  return{
      "nama": "Score ", "detail": [
          { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
          { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
          { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
          { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
          { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
          { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
      ]
  }
}
export function RESIKONUTRISINAL() : any {
  return [
    {
      label :"BB turun tanpa diinginkan dalam 6 bulan terakhir?      ",
      model :"penurunanBeratBadan",
      children :[
        {
          label : "Tidak",
          value :0,
        },
        {
          label : "Ya",
          value : 1,
        },
      ]
    },
    {
      label :"Mengalami penurunan selera makan?",
      model :"penurunanNafsuMakan",
      children :[
        {
          label : "Tidak",
          value :0,
        },
        {
          label : "Ya",
          value : 1,
        },
      ]
    }
  ]
}
export function RESIKONUTRISINALDATA() : any {
  return [
    {
      nama: "BBK",
      type: "text",
      satuan: "Kg/Gram",
      model :"BBKResiko",
    },
    {
      nama: "Tinggi Badan",
      type: "text",
      model :"TinggiBadanResiko",
      satuan: "Cm",
    },
    {
      nama: "IMT",
      type: "text",
      model :"IMTResiko",
      satuan: "",
    },
    {
      nama: "IMT < 18,5  atau> 30",
      type: "text",
      model :"IMTLebih18Resiko",
      satuan: "",
    },
  ]
}
export function SOSIALEKONOMI() : any {
  return [
    {
      label :"ASPEK PENGKAJIAN RISIKO JATUH",
      children :[
        {
          label : "",
          model :"resikoJatuh",
          column : "is-12",
          children :[
            {
              type : "checkbox",
              name : "Autooanamnesa",
              label : "Autooanamnesa",
              column : "is-6"
            },
            {
              type : "checkbox",
              name : "Heteroanamnesa",
              label : "Heteroanamnesa",
              column : "is-6"
            },
          ]
        }
      ]
    }
  ]
}
