export function kriteria(): any {
  return [
    {
      label: "PASIEN TB PARU",
      detail: [
        {
          deskripsi: "Pasien sudah mendapat OAT fase intensif minimal 14 hari dengan perbaikan klinis",
          model: "pasienTBParu"
        }
      ]
    },
    {
      label: "PASIEN COVID 19",
      detail: [
        {
          deskripsi: "Pasien suspect covid 19 dengan hasil swab PCR (Polymerase Chain Reaction) negatif dua hari berturut-turut",
          model: "pasienSuspectCovid"
        },
        {
          deskripsi: "Pasien terkonfirmasi covid 19 dengan hasil swab PCR (Polymerase Chain Reaction) negatif dua hari berturut-turut",
          model: "pasienTerkonfirmasiCovid"
        }
      ]
    },
    {
      label: "PERTIMBANGAN LAIN LAIN",
      detail: [
        {
          deskripsi: "Penilaian dokter penanggung jawab pelayanan berdasarkan klinis pasien",
          model: "penilaianDokterPenanggungJawab"
        }
      ]
    }
  ];
}


export function JenisKelamin():any{
  return [
    { label: 'Laki-laki', value: 'Laki-laki' },
    { label: 'Perempuan', value: 'Perempuan' },
  ]
}
