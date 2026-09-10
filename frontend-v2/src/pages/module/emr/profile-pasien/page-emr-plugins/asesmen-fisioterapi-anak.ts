export function ASESMENFISIOTERAPI(): any {
  return [
    {
      title : '1. IDENTITAS ANAK',
      children: [
          {
            label: "A. Nama ",
            model: "namaAnak",
            type: "text",
            column : "is-6"
          },
          {
            label: "B. Tempat, tanggal lahir ",
            model: "tempatTanggalLahirAnak",
            type: "text",
            column : "is-6"
          },
          {
            label: "C. Pendidikan ",
            model: "pendidikanAnak",
            type: "text",
            column : "is-6"
          },
          {
            label: "D. Alamat ",
            model: "alamatAnak",
            type: "textarea",
            column : "is-6"
          },
          {
            label: "E. Anak Ke __ dari __ ",
            model: "anakKeDari",
            type: "text",
            column : "is-6"
          },
      ]
    },
    {
        title : '2. IDENTITAS ORANG TUA',
        children :[
          {
            type :"heading",
            label : "Ayah",
            children :[
              {
                label: "Nama",
                model: "namaAyah",
                type: "text",
                column : "is-12"
              },
              {
                label: "Usia ",
                model: "usiaAyah",
                type: "text",
                column : "is-12"
              },
              {
                label: "Pendidikan ",
                model: "pendidikanAyah",
                type: "text",
                column : "is-12"
              },
              {
                label: "Pekerjaan ",
                model: "pekerjaanAyah",
                type: "text",
                column : "is-12"
              },
              {
                label: "Agama ",
                model: "agamaAyah",
                type: "text",
                column : "is-12"
              },
              {
                label: "Alamat ",
                model: "alamatAyah",
                type: "textarea",
                column : "is-12"
              },
              {
                label: "No. Telp. ",
                model: "noTelpAyah",
                type: "text",
                column : "is-12"
              },
            ]
          },
          {
            type :"heading",
            label : "Ibu",
            children :[
              {
                label: "Nama",
                model: "namaIbu",
                type: "text",
                column : 'is-12',
              },
              {
                label: "Usia ",
                model: "usiaIbu",
                type: "text",
                column : 'is-12',
              },
              {
                label: "Pendidikan ",
                model: "pendidikanIbu",
                type: "text",
                column : 'is-12',
              },
              {
                label: "Pekerjaan ",
                model: "pekerjaanIbu",
                type: "text",
                column : 'is-12',
              },
              {
                label: "Agama ",
                model: "agamaIbu",
                type: "text",
                column : 'is-12',
              },
              {
                label: "Alamat ",
                model: "alamatIbu",
                type: "textarea",
                column : 'is-12',
              },
              {
                label: "No. Telp. ",
                model: "noTelpIbu",
                type: "text",
                column : 'is-12',
              },
            ]
          }
        ]
    },
    {
        title : '3. ANAMNESIS',
        children: [
          {
            label: "1. Keluhan Utama",
            model: "keluhanUtama",
            type: "textarea"
          },
          {
            label: "2. Diagnosis",
            model: "diagnosis",
            type:"textarea"
          },
        ]
    },
    {
        title : '5. Asesment Fisioterapi',
        children: [
          {
            type :"heading",
            label : "A. Observasi",
            children :[
              {
                label: "Perkembangan Fisik ",
                model: "perkembanganFisik",
                type: "textarea"
              },
              {
                label: "Perkembangan Bahasa ",
                model: "perkembanganBahasa",
                type: "textarea"
              },
              {
                label: "Perkembangan Sosial ",
                model: "perkembanganSosial",
                type: "textarea"
              },
              {
                label: "Perkembangan Motorik ",
                model: "perkembanganMotorik",
                type: "textarea"
              },
            ]
          },
          {
            type :"heading",
            label : "B. SPESIFIK TEST",
            children :[
              {
                label: "DRIVER II ",
                model: "DRIVERII",
                type: "textarea"
              },
              {
                label: "Hasil Test ",
                model: "hasilTest",
                type: "textarea"
              },
              {
                label: "KPSP ",
                model: "KPSP",
                type: "textarea"
              },
              {
                label: "Hasil Test ",
                model: "hasilTest",
                type: "textarea"
              },
            ]
          }
        ]
    },
    {
        title : '6. Intervensi Fisioterapi',
        children :[
          {
            label: "A. Tujuan Intervensi ",
            model: "tujuanIntervensi",
            type: "textarea"
           },
          {
            label: "B. Teknologi Intervensi ",
            model: "teknologiIntervensi",
            type: "textarea"
           },
          {
            label: "C. Pelaksanaan Intervensi ",
            model: "pelaksanaanIntervensi",
            type: "textarea"
           },
          {
            label: "D. Evaluasi ",
            model: "Evaluasi",
            type: "textarea"
           },
        ]
    },
    {
        title : '7. Prognosis',
        children :[
          {
            label: "Qua Ad Vitam ",
            type :"checkbox",
            column :"is-6",
            model: "QuaAdVitam",
            children: [
              {
                label: "Dubia ad Bonam",
                value: "Dubia ad Bonam",
                type: "checkbox"
              },
              {
                label: "Malam",
                value: "Malam",
                type: "checkbox"
              },
            ]
          },
          {
            label: "Qua Ad Sanam ",
            type :"checkbox",
            column :"is-6",
            model: "QuaAdSanam",
            children: [
              {
                label: "Dubia ad Bonam",
                value: "Dubia ad Bonam",
                type: "checkbox"
              },
              {
                label: "Malam",
                value: "Malam",
                type: "checkbox"
              },
            ]
          },
          {
            label: "Qua Ad Fungsionam ",
            type :"checkbox",
            column :"is-6",
            model: "QuaAdSanam",
            children: [
              {
                label: "Dubia ad Bonam",
                value: "Dubia ad Bonam",
                type: "checkbox"
              },
              {
                label: "Malam",
                value: "Malam",
                type: "checkbox"
              },
            ]
          },
          {
            label: "Qua Ad Cosmeticam ",
            type :"checkbox",
            column :"is-6",
            model: "QuaAdCosmeticam",
            children: [
              {
                label: "Dubia ad Bonam",
                value: "Dubia ad Bonam",
                type: "checkbox"
              },
              {
                label: "Malam",
                value: "Malam",
                type: "checkbox"
              },
            ]
          },
        ]
    },
    {
        title : '8. Re Evaluasi',
        children: [
          {
            label: "A. Pertemuan Selanjutnya ",
            type: "textarea"
          },
          {
            label: "B. Diakhiri / Discharges ",
            type: "textarea"
          },
        ]
    },
  ]
}
