export function ANAMNESIS(): any {
  return [
    {
      title: 'Anamnesis Pasien dan Kebiasan',
      category :'anamnesis',
      children :[
        {
          model : "riwayatAlergi",
          type : "textarea",
          placeholder:"Riwayat Alergi..",
          label :"Riwayat Alergi",
          column :"is-6"
        },
        {
          model : "merokok",
          type : "textarea",
          placeholder:"Merokok..",
          label :"Merokok",
          column :"is-6"
        },
        {
          model : "riwayatPenyakitDahuluOperasi",
          type : "textarea",
          placeholder:"Riwayat Penyakit Dahulu/Operasi..",
          label :"Riwayat Penyakit Dahulu/Operasi",
          column :"is-6"
        },
        {
          model : "alkoholKopi",
          type : "textarea",
          placeholder:"Alkohol / Kopi..",
          label :"Alkohol / Kopi",
          column :"is-6"
        },
        {
          model : "riwayatPenyakitSekarang",
          type : "textarea",
          placeholder:"Riwayat Penyakit Sekarang..",
          label :"Alkohol / Kopi",
          column :"is-6"
        },
        {
          model : "olahraga",
          type : "textarea",
          placeholder:"Olahraga..",
          label :"Olahraga",
          column :"is-6"
        },
        {
          model : "riwayatPenyakitKeluarga",
          type : "textarea",
          placeholder:"Riwayat Penyakit Keluarga..",
          label :"Riwayat Penyakit Keluarga",
          column :"is-6"
        },
        {
          model : "riwayatPekerjaan",
          type : "textarea",
          placeholder:"Riwayat Pekerjaan..",
          label :"Riwayat Pekerjaan",
          column :"is-6"
        },
      ]
    },
    {
      title : 'Tanda-tanda Vital',
      category :'vitalSign',
      children :[
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
      ]
    }
  ]
}
export function PENGKAJIAN(): any {
  return [
    {
      label : "Keadaan Umum",
      model : "keadaanUmum",
      children :[
        {
          label : "Normal",
          value : "Normal",
          column : "is-4"
        },
        {
          label : "Abnormal",
          value : "Abnormal",
          column : "is-4"
        }
      ]
    },
      {
        label: "Keadaan Umum",
        model: "Keadaan Umum",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Kepala",
        model: "Kepala",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Telinga, Hidung, Mulut dan Tenggorokan",
        model: "Telinga, Hidung, Mulut dan Tenggorokan",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Leher",
        model: "Leher",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Mata",
        model: "Mata",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Dada",
        model: "Dada",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Payudara (Famale)",
        model: "Payudara (Famale)",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },

    {
        label: "Sistem Pernafasan",
        model: "Sistem Pernafasan",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Sistem Jantung dan Pembuluh Darah",
        model: "Sistem Jantung dan Pembuluh Darah",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Abdomen dan organ di dalamnya, hernia",
        model: "Abdomen dan organ di dalamnya, hernia",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Sistem perkemihan",
        model: "Sistem perkemihan",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },

    {
        label: "Sistem pencernaan bagian bawah",
        model: "Sistem pencernaan bagian bawah",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },


    {
        label: "Ekstremitas",
        model: "Ekstremitas",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Tulang belakang dan muskuloskeletal",
        model: "Tulang belakang dan muskuloskeletal",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Kulit dan sistemkelenjr getah bening",
        model: "Kulit dan sistemkelenjr getah bening",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            },
        ]
    },
    {
        label: "Sistem saraf pusat",
        model: "Sistem saraf pusat",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            }
        ]
    },
    {
        label: "Sistem saraf perifer, Refleks",
        model: "Sistem saraf perifer, Refleks",
        children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Abnormal",
              value: "Abnormal",
              type: "checkbox2"
            }
        ]
    },
    {
      label: "Lainnya, Jelaskan",
      model: "lainnyaJelaskan",
      children: [
          {
            label:"Normal",
            value:"Normal",
            type: "checkbox"
          },
          {
            label:"Abnormal",
            value:"Abnormal",
            type: "checkbox2"
          }
      ]
  },
]}
