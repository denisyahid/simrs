export function ASESMEN(): any {
  return [
    {
      label : "Pengkajian Awal Rawat Inap Neonatus",
      children :[
      ]
    },
    {
      label : "I. Anamnesis",
      children :[
      ]
    },
    {
      label : "Riwayat Biopsikososial, Kultural, Spiritual & Ekonomi",
      children :[
      ]
    },
    {
      label : "Riwayat Biopsikososial, Kultural, Spiritual & Ekonomi",
      children :[
      ]
    },
    {
      label : "Pengkajian Awal Rawat Inap Neonatus",
      children :[
      ]
    },
    {
      label : "Pengkajian Awal Rawat Inap Neonatus",
      children :[
      ]
    },
  ]
}

export function PENGKAJIANAWAL ():any {
 return [
  {
    label : "Sumber Informasi",
    type :"checkbox",
    model :"sumberInformasi",
    column : "is-12",
    children :[
      {
        label :"Pasien",
        value :"Pasien",
      },
      {
        label :"Keluarga/Orang Lain",
        value :"Keluarga/Orang Lain",
        column:"is-6",
        isShowChildren : true,
        children :[
          {
            label :"Sebutkan",
            model :"sumberInformasiLain",
            isHide : false
          },
        ]
      },

    ]
  },
  {
    label : "Cara Masuk",
    type :"checkbox",
    model :"caraMasuk",
    column : "is-12",
    children :[
      {
        label :"Digendong",
        value :"Digendong",
      },
      {
        label :"Infant Warmer",
        value :"Infant Warmer",
      },
      {
        label :"Lain-lain",
        value :"Lain-lain",
        column:"is-6",
        isShowChildren : true,
        children :[
          {
            label :"Sebutkan",
            model :"sumberInformasiLain",
            isHide : false
          },
        ]
      },

    ]
  },
  {
    label : "Asal Masuk",
    type :"autocomplete",
    suggestions:"d_Ruangan",
    fetch : "fetchRuangan",
    model :"asalMasuk",
    column : "is-6",
  }
 ]
}
export function ANAMNESIS ():any {
  return [
      {
        label : "Keluhan Utama",
        model :"keluhan Utama",
        type : "textarea",
        column : "is-6",
      },
      {
        label : "Riwayat Penyakit Sekarang",
        model :"riwayatPenyakitSekarang",
        type : "textarea",
        column : "is-6",
      },
      {
        label : "Riwayat Penyakit Dahulu",
        model :"riwayatPenyakitDahulu",
        type : "textarea",
        column : "is-6",
      },
      {
        label : "Riwayat Penyakit Keluarga ",
        model :"riwayatPenyakitKeluarga",
        type : "textarea",
        column : "is-6",
      },
      {
        label : "Riwayat Pengobatan/Operasi",
        model :"riwayatPengobatanOperasi",
        type : "textarea",
        column : "is-6",
      },
      {
        label : "Riwayat Alergi",
        model :"riwayatAlergi",
        type : "textarea",
        column : "is-6",
      },
  ]
}
export function RIWAYATBIOPSIKOSOSISAL ():any {
  return [
    {
      label : "Agama",
      type :"checkbox",
      model :"agama",
      column : "is-12",
      children :[
        {
          label :"Islam",
          value :"Islam",
        },
        {
          label :"Infant Warmer",
          value :"Infant Warmer",
        },
        {
          label :"Advent",
          value :"Advent",
        },
        {
          label :"Kristen",
          value :"Kristen",
        },
        {
          label: "Hindu",
          value: "Hindu",
        },
        {
          label: "Budha",
          value: "Budha",
        },
        {
          label: "Katolik",
          value: "Katolik",
        },
        {
          label :"Lain-lain",
          value :"Lain-lain",
          column:"is-6",
          isShowChildren : true,
          children :[
            {
              label :"Sebutkan",
              model :"agamaLain",
              isHide : false
            },
          ]
        },

      ]
    },
    {
      label: "Pekerjaan",
      type: "checkbox",
      model: "pekerjaan",
      column: "is-12",
      children: [
        {
          label: "PNS / POLRI",
          value: "PNS / POLRI",
        },
        {
          label: "Swasta",
          value: "Swasta",
        },
        {
          label: "Pensiun",
          value: "Pensiun",
        },
        {
          label: "Lain-lain",
          value: "Lain-lain",
          isShowChildren : true,
          children: [
            {
              label: "Sebutkan",
              model: "pekerjaanLain",
              type: "text",
              isHide : false
            },
          ],
        },
      ],
    },
    {
      label: "Tinggal Bersama",
      type: "checkbox",
      model: "tinggalBersama",
      column: "is-12",
      children: [
        {
          label: "Suami/Istri",
          value: "Suami/Istri",
        },
        {
          label: "Orang Tua",
          value: "Orang Tua",
        },
        {
          label: "Anak",
          value: "Anak",
        },
        {
          label: "Tinggal Sendiri",
          value: "Tinggal Sendiri",
        },
        {
          label: "Lain-Lain",
          value: "Lain-Lain",
          isShowChildren : true,
          children: [
            {
              label: "Sebutkan",
              model: "tinggal_bersama_lain",
              type: "text",
              isHide : false
            },
          ],
        },
      ],
    },
    {
      label: "Status Mental",
      type: "checkbox",
      model: "status_mental",
      column: "is-12",
      children: [
        {
          label: "Orientasi Baik",
          value: "Orientasi Baik",
        },
        {
          label: "Agitasi",
          value: "Agitasi",
        },
        {
          label: "Menyerang",
          value: "Menyerang",
        },
        {
          label: "Tidak Ada Respon",
          value: "Tidak Ada Respon",
        },
        {
          label: "Lain-Lain",
          value: "Lain-Lain",
          isShowChildren : true,
          children: [
            {
              label: "Sebutkan",
              model: "statusMentalLain",
              type: "text",
              isHide : false
            },
          ],
        },
      ],
    },
    {
      label: "Status Psikologis",
      type: "checkbox",
      model: "status_psikologis",
      column: "is-12",
      children: [
        {
          label: "Kooperatif",
          value: "Kooperatif",
        },
        {
          label: "Disorientasi",
          value: "Disorientasi",
        },
        {
          label: "Tenang",
          value: "Tenang",
        },
        {
          label: "Hiperaktif",
          value: "Hiperaktif",
        },
        {
          label: "Cemas",
          value: "Cemas",
        },
        {
          label: "Kecenderungan Bunuh Diri",
          value: "Kecenderungan Bunuh Diri",
        },
        {
          label: "Gelisah",
          value: "Gelisah",
        },
        {
          label: "Depresi",
          value: "Depresi",
        },
        {
          label: "Marah",
          value: "Marah",
        },
        {
          label: "Lain-Lain",
          value: "Lain-Lain",
          isShowChildren : true,
          children: [
            {
              label: "Sebutkan",
              model: "statusPsikologisLain",
              isHide : false
            },
          ],
        },
      ],
    },
    {
      label: "Budaya Yang Dianut",
      type: "checkbox",
      model: "budayaYangDianut",
      column: "is-12",
      children: [
        {
          label: "Tidak",
          value: "Tidak",
          column: "is-12",
        },
        {
          label: "Ya, Alasan",
          value: "Ya, Alasan",
          isShowChildren : true,
          column: "is-12",
          children: [
            {
              label: "Sebutkan",
              model: "budayaYangDianutLain",
              type: "textarea",
              isHide : false
            },
          ],
        },
      ],
    },
    {
      label: "Pasien Baru Lahir (Di RS)",
      type: "checkbox",
      model: "pasienBaruLahir",
      column: "is-12",
      children: [
        {
          label: "Tidak",
          value: "Tidak",
          column: "is-12",
        },
        {
          label: "Ya",
          value: "Ya",
          isShowChildren : true,
          column: "is-12",
          children: [
            {
              label: "PB",
              model: "pb",
              type: "textbox",
              satuan: "cm",
              isHide : false
            },
            {
              label: "BB",
              model: "bb",
              type: "textbox",
              satuan: "g",

              isHide : false
            },
            {
              label: "Lingkar Kepala",
              model: "lingkar_kepala",
              type: "textbox",
              satuan: "cm",
              isHide : false
            },
            {
              label: "Lingkar Dada",
              model: "lingkar_dada",
              type: "textbox",
              satuan: "cm",
              isHide : false
            },
            {
              label: "Lingkar Perut",
              model: "lingkar_perut",
              type: "textbox",
              satuan: "cm",
              isHide : false
            },
          ],
        },
      ],
    },
  ]

}
