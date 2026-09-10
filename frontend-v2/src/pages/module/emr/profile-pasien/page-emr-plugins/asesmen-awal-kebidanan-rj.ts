export function detailRiwayatKehamilan(): any {
    return [
        {
            child: [
                { type: "tanggal" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "tanggal" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "tanggal" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "tanggal" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "tanggal" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        }
    ]
}

export function detailSkriningNutrisi(): any {
    return [
        {
            child: [
                { type: "text", colspan: "2", caption: "Apakah pasien mengalami penurunan berat badan yang tidak direncanakan selama 6 bulan terakhir?", style: "background-color:lightgray" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Tidak" },
                { type: "nilai", caption: "0" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Tidak yakin (ada tanda: baju menjadi lebih longgar)" },
                { type: "nilai", caption: "2" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Ya, bila ya berapa penurunan berat badan" },
                { type: "nilai" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "1-5Kg" },
                { type: "nilai", caption: "1" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "6-10Kg" },
                { type: "nilai", caption: "2" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "11-15Kg" },
                { type: "nilai", caption: "3" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: ">15Kg" },
                { type: "nilai", caption: "4" }
            ]
        },
        {
            child: [
                { type: "text", colspan: "2", caption: "Apakah terjadi penurunan nafsu makan?", style: "background-color:lightgray" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Ya" },
                { type: "nilai", caption: "1" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Tidak" },
                { type: "nilai", caption: "0" }
            ]
        },
        {
            child: [
                { type: "text", caption: "Total Skor : " },
                { type: "textbox" }
            ]
        },
    ]
}

export function detailStatusFungsional(): any {
    return [
        {
            child: [
                { type: "text", caption: "Mengontrol BAB" },
                { type: "text", caption: "Inkontinen/ tidak teratur (perlu enema)" },
                { type: "text", caption: "Kadang inkontinen (1x seminggu)" },
                { type: "text", caption: "Kontinen teratur" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Mengontrol BAK" },
                { type: "text", caption: "Inkontinen/ pakai kateter dan tidak terkontrol" },
                { type: "text", caption: "Kadang inkontinen (max 1x24 jam)" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Membersihkan diri (lap muka, sisir rambut, sikat gigi)"},
                { type: "text", caption: "Butuh pertolongan orang lain" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Penggunaan toilet, pergi ke dalam dari WC (melepas, memakai, celana, menyeka, menyiram)" },
                { type: "text", caption: "Tergantung pertolongan orang lain" },
                { type: "text", caption: "Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Makan" },
                { type: "text", caption: "Tidak Mampu" },
                { type: "text", caption: "Perlu seseorang menolong memotong makanan" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Berpindah tempat dari tidur ke duduk" },
                { type: "text", caption: "Tidak Mampu" },
                { type: "text", caption: "Perlu banyak bantuan untuk bisa duduk (2 orang)" },
                { type: "text", caption: "Bantuan 1 orang" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Mobilisasi/berjalan" },
                { type: "text", caption: "Tidak Mampu" },
                { type: "text", caption: "Dengan kursi roda" },
                { type: "text", caption: "Bantuan 1 orang" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Berpakaian (memakai baju)" },
                { type: "text", caption: "Tergantung pertolongan orang lain" },
                { type: "text", caption: "Sebagian dibantu (misal mengancing baju)" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Naik turun tangga" },
                { type: "text", caption: "Tidak mampu" },
                { type: "text", caption: "Butuh pertolongan" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        },
        {
            child: [
                { type: "text", caption: "Mandi" },
                { type: "text", caption: "Tergantung pertolongan orang lain" },
                { type: "text", caption: "Mandiri" },
                { type: "textbox" },
                { type: "textbox" },
                { type: "textbox" },
            ]
        }
    ]
}

export function statusFungsional():any{
  return{
    statusFungsional:[
      {
        fungsi:"Mengontrol BAB",
        detail:[
          {
            type:"checkbox",
            caption:"Inkontinen/ tidak teratur (perlu enema)",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Kadang inkontinen (1x seminggu)",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Kontinen teratur",
            value:"2"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"Mengontrol BAK",
        detail:[
          {
            type:"checkbox",
            caption:"Inkontinen/ pakai kateter dan tidak terkontrol",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Kadang inkontinen (max 1x 24jam)",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"2"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"Membersihkan diri(lap muka, sisir rambut, sikat gigi",
        detail:[
          {
            type:"checkbox",
            caption:"Butuh pertolongan orang lain",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"1"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"2"
          },
          {
            type:"textbox",
            caption:"ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"Penggunaan toilet, pergi ke dalam dari WC(melepas, memakai, celana, menyeka, menyiram)",
        detail:[
          {
            type:"checkbox",
            caption:"Tergantung pertolongan orang lain",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Perlu pertolongan pada beberapa aktivitas terapi dan dapat mengerjakan sendiri beberapa aktivitas lain",
            value:"1"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"2"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"Makan",
        detail:[
          {
            type:"checkbox",
            caption:"Tidak Mampu",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Perlu seseorang menolong memotong makanan",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"2"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"berpindah tempat dari tidur ke duduk",
        detail:[
          {
            type:"checkbox",
            caption:"Tidak Mampu",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Perlu banyak bantuan untuk bisa duduk (2 orang)",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Bantuan 1 orang",
            value:"2"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"Mobilisasi/berjalan",
        detail:[
          {
            type:"checkbox",
            caption:"Tidak Mampu",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Dengan kursi roda",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Bantuan Satu orang",
            value:"2"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          },

        ]
      },
      {
        fungsi:"berpakaian",
        detail:[
          {
            type:"checkbox",
            caption:"Tergantung orang lain",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Sebagian dibantu misal mengancing baju",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"2"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"naik turun tangga",
        detail:[
          {
            type:"checkbox",
            caption:"Tidak Mampu",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Butuh pertolongan",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"2"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      },
      {
        fungsi:"Mandi",
        detail:[
          {
            type:"checkbox",
            caption:"Tidak Mampu",
            value:"0"
          },
          {
            type:"checkbox",
            caption:"Tegantung orang lain",
            value:"1"
          },
          {
            type:"checkbox",
            caption:"Mandiri",
            value:"2"
          },
          {
            type:"textbox",
            caption:"Ket",
            value:"3"
          },
          {
            type:"skor",
            caption:"Skor",
          }
        ]
      }
    ]
  }
}

export function detailRencanaKebidanan(): any {
    return [
        {
            child: [
                { type: "checkbox", caption: "Informasikan hasil pemeriksaan dan kondisi saat ini" },
                { type: "checkbox", caption: "Berikan pengetahuan yang adekuat tentang penyakit yang diderita untuk mengurangi cemas" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Kolaborasi dengan dokter spesialis obgyn untuk tindakan dan therapy selanjutnya" },
                { type: "checkbox", caption: "Jelaskan semua posedur, termasuk beberapa pengalaman sebelum prosedur" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Fasilitasi dokter dalam pemeriksaan USG" },
                { type: "checkbox", caption: "Dekati pasien untuk memberikan rasa aman dan mengurangi rasa takut" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Lakukan pemeriksaan NST" },
                { type: "checkbox", caption: "Dengarkan pasien dengan penuh perhatian" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Anjurkan pasien untuk skrining rutin kehamilan" },
                { type: "checkbox", caption: "Fasilitasi dokter pemeriksaan papsmear" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan pengetahuan tentang tanda bahaya kehamilan, keluhan lazim dan cara mengatasinya" },
                { type: "checkbox", caption: "Fasilitasi dokter pemeriksaan biopsi" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan informasi tentang deteksi dan pencegahan kelainan kongenital" },
                { type: "checkbox", caption: "Fasilitasi dokter pemeriksaan cryoterapi" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan pengetahuan tentang Nutrisi/ gizi" },
                { type: "checkbox", caption: "Ajarkan teknik nonfarmakologis seperti Relaksasi napas dalam/otot progesif, Distraksi, kompres hangat/dingin, terapi music, massage punggung," }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan informasi tentang gerak dan aktivitas selama hamil/ nifas" },
                { type: "checkbox", caption: "Monitor Frekuensi nafas pasien/ status oksigen pasien" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Beritahu pasien dan keluarga tentang persiapan persalinan, peran pendamping, persiapan menyusui, termasuk Calon Pendonor" },
                { type: "checkbox", caption: "Posisikan pasien untuk memaksimalkan ventilasi (head up/semifowler)" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan informasi tentang kelas ibu hamil, Senam Hamil" },
                { type: "checkbox", caption: "Lakukan manajemen imunisasi/vaksinasi" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Beritahu pasien tentang tanda – tanda persalinan" },
                { type: "checkbox", caption: "Beri dukungan dalam mengambil keputusan" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan informasi tentang tanda Bahaya masa nifas" },
                { type: "checkbox", caption: "Edukasi dan sarankan untuk kontrol sebagai upaya meningkatkan status kesehatan pasien" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Ajarkan pasien cara memeriksa kontraksi uterus, Cara masase uterus, perawatan Perineum, Senam nifas" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan Informasi cara menyusui yang benar, dan ASI Ekslusif, perawatan payudara" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Beri KIE tentang, Keuntungan, Kelemahan, Efek samping, Lama penggunaan , Cara mengatasi efek samping Kontrasepsi" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Beri KIE tentang Sex Hygine/ hubungan seksual" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Beri KIE pasien tentang Perawatan luka pasca operasi" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Istirahatkan pasien pada posisi yang nyaman dalam batas yang ditoleransi oleh pasien" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Berikan informasi tentang nyeri meliputi penyebab, lamanya nyeri berlangsung, faktor yang dapat memperburuk atau meredakan nyeri" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Bantu pasien mengidentifikasi tindakan memenuhi kebutuhan rasa nyaman yang telah berhasil dilakukan oleh pasien" },
                { type: "checkboxTB" }
            ]
        },
        {
            child: [
                { type: "checkbox", caption: "Observasi tanda-tanda vital" },
                { type: "checkboxTB" }
            ]
        }
    ]
}
