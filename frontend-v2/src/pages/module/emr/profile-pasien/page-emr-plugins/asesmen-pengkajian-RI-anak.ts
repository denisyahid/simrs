export function EXAMPLE(): any {
  return [

  ]
}
export function ASESMEN(): any {
  return [
    {
      label : "I. Anamnesis",
      children :[
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
    },
    {
      label : "Riwayat Biopsikososial, Kultural, Spiritual & Ekonomi",
      children :[
        {
          label : "Pendidikan",
          model :"pendidikan",
          type : "text",
          column : "is-4",
        },
        {
          label : "Pekerjaan",
          model :"pekerjaan",
          type : "text",
          column : "is-4",
        },
        {
          label : "Status",
          model :"statusPerkawinan",
          type : "text",
          column : "is-4",
        },
        {
          label : "Kebangsaan",
          model :"kebangsaan",
          type : "text",
          column : "is-4",
        },
        {
          label : "Agama",
          model :"agama",
          type : "text",
          column : "is-4",
        },
        {
          label : "Tinggal Bersama",
          model :"tinggalBersama",
          type : "checkbox",
          column : "is-12",
          children: [
              {
                label: "Suami/Istri",
                value: "Suami/Istri",
                type: "checkbox"
              },
              {
                label: "Orang Tua",
                value: "Orang Tua",
                type: "checkbox"
              },
              {
                label: "Anak",
                value: "Anak",
                type: "checkbox"
              },
              {
                label: "Tinggal Sendiri",
                value: "Tinggal Sendiri",
                type: "checkbox",
              },
              {
                label: "Lain-Lain",
                value: "Lain-Lain",
                type: "checkbox",
                isShowChildren: true,
                children :[
                  {
                    label: "Sebutkan",
                    placeholder: "Sebutkan",
                    type: "text",
                    isHide : true,
                  },
                ]
              }
          ]
      },
      {
        label : "Status Mental",
        model :"statusMental",
        type : "checkbox",
        column : "is-12",
        children: [
          {
            label: "Orientasi Baik",
            value: "Orientasi Baik",
            type: "checkbox"
          },
          {
            label: "Agitasi",
            value: "Agitasi",
            type: "checkbox"
          },
          {
            label: "Menyerang",
            value: "Menyerang",
            type: "checkbox"
          },
          {
            label: "Tidak Ada Respon",
            value: "Tidak Ada Respon",
            type: "checkbox"
          },
          {
            label: "Lain-Lain",
            value: "Lain-Lain",
            type: "checkbox",
            isShowChildren: true,
            children :[
              {
                label: "Sebutkan",
                placeholder: "Sebutkan",
                type: "text",
                isHide : true,
              },
            ]
          },
        ]
      },
      {
        label : "Status Psikologis",
        model :"statusPsikologis",
        type : "checkbox",
        column : "is-12",
        children: [
            {
               label: "Kooperatif",
               value: "Kooperatif",
               type: "checkbox"
            },
            {
               label: "Disorientasi",
               value: "Disorientasi",
               type: "checkbox"
            },
            {
               label: "Tenang",
               value: "Tenang",
               type: "checkbox"
            },
            {
               label: "Hiperaktif",
               value: "Hiperaktif",
               type: "checkbox"
            },
            {
               label: "Cemas",
               value: "Cemas",
               type: "checkbox"
            },
            {
               label: "Kecenderungan Bunuh Diri",
               value: "Kecenderungan Bunuh Diri",
               type: "checkbox"
            },
            {
               label: "Gelisah",
               value: "Gelisah",
               type: "checkbox"
            },
            {
               label: "Depresi",
               value: "Depresi",
               type: "checkbox"
            },
            {
               label: "Marah",
               value: "Marah",
               type: "checkbox"
            },
            {
               label: "Lain-Lain",
               value: "Lain-Lain",
               type: "checkbox",
               isShowChildren: true,
               children :[
                 {
                   label: "Sebutkan",
                   placeholder: "Sebutkan",
                   type: "text",
                   isHide : true,
                 },
               ]
            },

        ]
      },
      {
        label : "Penggunaan Restrain",
        model :"penggunaanRestrain",
        type : "checkbox",
        column : "is-12",
        children: [
          {
            label : "Tidak",
            value: "Tidak",
            type: "checkbox"
          },
          {
            label: "Ya, Alasan",
            value: "Ya, Alasan",
            type: "checkbox",
            isShowChildren: true,
            children :[
              {
                label: "Sebutkan",
                placeholder: "Sebutkan",
                type: "text",
                isHide : true,
              },
            ]
          },
        ]
      },
      {
        label : "Riwayat Menstruasi",
        model :"riwayatMunstruasi",
        type : "checkbox",
        column : "is-12",
        children: [
          {
            label : "Belum/Tidak Menstruasi",
            value: "Belum/Tidak Menstruasi",
            type: "checkbox",
            column : "is-12",
          },
          {
            label : "Sudah Menstruasi",
            value: "Sudah Menstruasi",
            type: "checkbox",
            column : "is-6",
            isShowChildren: true,
            children :[
              {
                label: "-Umur Menarche",
                placeholder: "-Umur Menarche",
                type: "text",
                isHide : true,
                model :"umurMencahe",
                column : "is-6",
              },
              {
                label: "-HPPT",
                placeholder: "-HPPT",
                type: "dateTime",
                model :"hppt",
                isHide : true,
                column : "is-6",
              },
              {
                label: "-Siklus Haid",
                placeholder: "-Siklus Haid",
                type: "text",
                model :"siklusHaid",
                isHide : true,
                column : "is-6",
              },
              {
                label: "-Perkiraan Menstruasi Berikutnya",
                placeholder: "-Perkiraan Menstruasi Berikutnya",
                type: "dateTime",
                model :"perkiraanMenstruasiBerikutnya",
                isHide : true,
                column : "is-6",
              },
            ]
          },
        ]
      }
      ]
    },
    {
      label : "Riwayat Kehamilan Ibu",
      children :[
        {
          label : "Perawatan Antenatal",
          model :"riwayatAntenatal",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Rutin",
              value: "Rutin",
              type: "checkbox"
            },
            {
              label: "Tidak Rutin",
              value: "Tidak Rutin",
              type: "checkbox",
            },
            {
              label: "Tidak Pernah",
              value: "Tidak Pernah",
              type: "checkbox",
            },
          ]
        },
        {
          label : "Penyakit Kehamilan",
          model :"penyakitKehamilan",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Tidak",
              value: "Tidak",
              type: "checkbox",
              column : "is-6",
            },
            {
              label: "Ya",
              value: "Ya",
              type: "checkbox",
              isShowChildren: true,
              column : "is-6",
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },

          ]
        },
        {
          label : "Obat Yang Diminum",
          model :"obat Yang Diminum",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Tidak",
              value: "Tidak",
              type: "checkbox",
              column : "is-6",
            },
            {
              label: "Ya",
              value: "Ya",
              type: "checkbox",
              isShowChildren: true,
              column : "is-6",
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },

          ]
        }
      ]
    },
    {
      label : "Riwayat Kelahiran",
      children :[
        {
          label : "Persalinan",
          model :"persalinan",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Spontan",
              value: "Spontan",
              type: "checkbox"
             },
            {
              label: "Sectio Caesaria",
              value: "Sectio Caesaria",
              type: "checkbox"
             },
            {
              label: "Ekstraksi Vakum",
              value: "Ekstraksi Vakum",
              type: "checkbox",
             },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              column : "is-6",
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },

          ]
        },
        {
          label : "Penolong Persalinan",
          model :"penolonganPersalinan",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Dokter Obsgyn",
              value: "Dokter Obsgyn",
              type: "checkbox"
             },
            {
              label: "Dokter Umum",
              value: "Dokter Umum",
              type: "checkbox"
             },
            {
              label: "Bidan",
              value: "Bidan",
              type: "checkbox",
             },
            {
              label: "Dukun",
              value: "Dukun",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              column : "is-6",
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },
          ]
        },
        {
          label : "Masa Gestasi",
          model :"masaGestasi",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Cukup Bulan",
              value: "Cukup Bulan",
              type: "checkbox"
             },
            {
              label: "Kurang Bulan",
              value: "Kurang Bulan",
              type: "checkbox"
             },
            {
              label: "Lebih Bulan",
              value: "Lebih Bulan",
              type: "checkbox",
              isShowChildren: true,
              column : "is-6",
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },
          ]
        },
        {
          label : "Keadaan Bayi",
          model :"keaddanBayi",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Berat Badan Lahir",
              value: "Berat Badan Lahir",
              type: "checkbox"
             },
            {
              label: "Panjang Badan",
              value: "Panjang Badan",
              type: "checkbox"
             },
            {
              label: "Lingkar Kepala",
              value: "Lingkar Kepala",
              type: "checkbox"
             },
            {
              label: "Nilai Apgar",
              value: "Nilai Apgar",
              type: "checkbox"
             },
            {
              label: "Kelainan Bawaan",
              value: "Kelainan Bawaan",
              type: "checkbox"
             },
            {
              label: "Tidak",
              value: "Tidak",
              type: "checkbox"
             },
            {
              label: "Ya",
              value: "Ya",
              type: "checkbox"
             },
            {
              label: "Lebih Bulan",
              value: "Lebih Bulan",
              type: "checkbox",
              isShowChildren: true,
              column : "is-6",
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },
          ]
        },
      ]
    },
    {
      label : "Riwayat Tumbuh Kembang",
      children :[
        {
          label : "Riwayat Tumbuh Kembang",
          model :"riwayatTumbuhKembang",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Sesuai dengan tahap tumbuh kembang",
              value: "Sesuai dengan tahap tumbuh kembang",
              type: "checkbox",
              column :"is-6"
             },
            {
              label: "Tidak sesuai dengan tahap tumbuh kembang",
              value: "Tidak sesuai dengan tahap tumbuh kembang",
              type: "checkbox",
              isShowChildren: true,
              column : "is-6",
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },
          ]
        },
      ]
    },
    {
      label : "Riwayat Imunisasi",
      children :[
        {
          label : "Riwayat Imunisasi",
          model :"riwayatImunisasi",
          type : "textarea",
          column : "is-12",
        },
      ]
    },
    {
      label : "TANDA VITAL",
      children :[
        {
          title: "TD",
          model: "tekananDarah",
          satuan: "mmHg",
            column :"is-4"
        },
        {
            "model": "RR",
            title: "RR",
            satuan: "x/min",
            column: "is-4"
          },
        {
            title: "HR",
            model: "nadi",
            satuan: "",
            column :"is-4"
          },
        {
            title: "Suhu",
            model: "suhu",
            satuan: "°C",
            column :"is-4"
          },
        {
            title: "SPO2",
            model: "SPO2",
            satuan: "",
            column :"is-4"
          },
        {
            title: "TB",
            model: "tinggiBadan",
            satuan: "Cm",
            column :"is-4"
          },
        {
            title: "BB",
            model: "beratBadan",
            satuan: "Kg",
            column :"is-4"
          },
        {
            title: "LP",
            model: "lingkarPerut",
            satuan: "",
            column :"is-4"
          },
      ]
    },
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
export function ASESMEN2():any {
  return [
    {
      label : "PEMERIKSAAN FISIK UMUM",
      children :[
        {
          label : "Kepala",
          model :"kepala",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Mikrosefa",
              value: "Mikrosefa",
              type: "checkbox"
            },
            {
              label: "Asimetris",
              value: "Asimetris",
              type: "checkbox"
            },
            {
              label: "Hematoma",
              value: "Hematoma",
              type: "checkbox"
            },
            {
              label: "Caput Succedaneum",
              value: "Caput Succedaneum",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Rambut",
          model :"rambut",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Kotor",
              value: "Kotor",
              type: "checkbox"
            },
            {
              label: "Berminyak",
              value: "Berminyak",
              type: "checkbox"
            },
            {
              label: "Kering",
              value: "Kering",
              type: "checkbox"
            },
            {
              label: "Rontok",
              value: "Rontok",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Muka",
          model :"muka",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Asimetris",
              value: "Asimetris",
              type: "checkbox"
            },
            {
              label: "Bells palsy",
              value: "Bells palsy",
              type: "checkbox"
            },
            {
              label: "Tic Facial",
              value: "Tic Facial",
              type: "checkbox"
            },
            {
              label: "Kelainan konginetal",
              value: "Kelainan konginetal",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Mata",
          model :"mata",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Penurunan Visus",
              value: "Penurunan Visus",
              type: "checkbox"
            },
            {
              label: "Sclera Ikterik",
              value: "Sclera Ikterik",
              type: "checkbox"
            },
            {
              label: "Konjungtiva anemis",
              value: "Konjungtiva anemis",
              type: "checkbox"
            },
            {
              label: "Anisokor",
              value: "Anisokor",
              type: "checkbox"
            },
            {
              label: "Midriasis/Miosis",
              value: "Midriasis/Miosis",
              type: "checkbox"
            },
            {
              label: "Tidak ada reaksi cahaya",
              value: "Tidak ada reaksi cahaya",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Hidung",
          model :"hidung",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Epitaksis",
              value: "Epitaksis",
              type: "checkbox"
            },
            {
              label: "Asimetris",
              value: "Asimetris",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Hidung",
          model :"hidung",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Bibir Pucat",
              value: "Bibir Pucat",
              type: "checkbox"
            },
            {
              label: "Asimetris",
              value: "Asimetris",
              type: "checkbox"
            },
            {
              label: "Sariawan",
              value: "Sariawan",
              type: "checkbox"
            },
            {
              label: "Kelainan konginetal",
              value: "Kelainan konginetal",
              type: "checkbox"
            },
            {
              label: "Mucosa Kering/ lembab",
              value: "Mucosa Kering/ lembab",
              type: "checkbox"
            },
            {
              label: "Gangguan Bicara",
              value: "Gangguan Bicara",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Gigi",
          model :"gigi",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Caries",
              value: "Caries",
              type: "checkbox"
            },
            {
              label: "Gigi Goyang",
              value: "Gigi Goyang",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Lidah",
          model :"lidah",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Kotor",
              value: "Kotor",
              type: "checkbox"
            },
            {
              label: "Gerakan Asimetris",
              value: "Gerakan Asimetris",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Tenggorokan",
          model :"tenggorokan",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Faring Merah",
              value: "Faring Merah",
              type: "checkbox"
            },
            {
              label: "Tonsil Membesar",
              value: "Tonsil Membesar",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Leher",
          model :"leher",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Pembesaran Tiroid",
              value: "Pembesaran Tiroid",
              type: "checkbox"
            },
            {
              label: "Pemb. Vena Jugularis",
              value: "Pemb. Vena Jugularis",
              type: "checkbox"
            },
            {
              label: "Kaku Kuduk",
              value: "Kaku Kuduk",
              type: "checkbox"
            },
            {
              label: "Keterbatasan Gerak",
              value: "Keterbatasan Gerak",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            }
          ]
        },
        {
          label : "Dada & Paru-paru",
          model :"dadaDanParuParu",
          type : "checkbox",
          column : "is-12",
          children: [
            {
              label: "Simetris",
              value: "Simetris",
              type: "checkbox"
            },
            {
              label: "Asimetris",
              value: "Asimetris",
              type: "checkbox"
            },
            {
              label: "Suara nafas",
              value: "Suara nafas",
              type: "checkbox"
            },
            {
              label: "Kanan kiri sama",
              value: "Kanan kiri sama",
              type: "checkbox"
            },
            {
              label: "Ronchi",
              value: "Ronchi",
              type: "checkbox"
            },
            {
              label: "Wheezing",
              value: "Wheezing",
              type: "checkbox"
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },
            {
              label: "Respiratori",
              value: "Respiratori",
              type: "checkbox"
            },
            {
              label: "Spontan tanpa alat bantu",
              value: "Spontan tanpa alat bantu",
              type: "checkbox"
            },
            {
              label: "Spontan dengan alat bantu",
              value: "Spontan dengan alat bantu",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide: true,
                  model : "alatBantuParu"
                },
              ]
            },
          ]
        },
        {
          label: "Abdomen",
          model: "abdomen",
          type: "checkbox",
          column: "is-12",
          children: [
            {
              label: "Normal",
              value: "Normal",
              type: "checkbox"
            },
            {
              label: "Tegang",
              value: "Tegang",
              type: "checkbox"
            },
            {
              label: "Distensi",
              value: "Distensi",
              type: "checkbox"
            },
            {
              label: "Bising usus",
              value: "Bising usus",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide: true,
                  model : "bisingUsus"
                },
              ]
            },
            {
              label: "Lain-Lain",
              value: "Lain-Lain",
              type: "checkbox",
              isShowChildren: true,
              children :[
                {
                  label: "Sebutkan",
                  placeholder: "Sebutkan",
                  type: "text",
                  isHide : true,
                },
              ]
            },
          ]
        }
      ]
    }
  ]
}
