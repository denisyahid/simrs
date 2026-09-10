<template>
  <section>
    <div class="column is-12">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title pt-2 mb-0">
          <label class="title-page">Master Barang Investasi</label>
          <label>Registrasi Barang Aset</label>
        </div>
        <div class="column mt-3">
          <div class="columns is-multiline">
            <div class="column is-2">
              <VField label="No Aset">
                <VControl>
                  <input v-model="item.noaset" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField label="No. Registrasi Aset" class="required-vfield">
                <VControl >
                  <input v-model="item.noRegisterAset" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Kode Produk" class="required-vfield">
                <VControl>
                  <input v-model="item.kdProduk" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Kode BMN">
                <VControl>
                  <input v-model="item.kodeBmn" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Kode External">
                <VControl>
                  <input v-model="item.kdEksternal" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Kode Aspek">
                <VControl>
                  <input v-model="item.kdAspak" class="input" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-2">
              <VField label="Kode RS">
                <VControl>
                  <input v-model="item.kdRs" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select required-vfield" label="Nama Barang">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.produk" :suggestions="d_Produk" @complete="produk($event)"
                    :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namaproduk'" @item-select="item.kdProduk = item.produk.id" />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Tgl Pembelian">
                <Calendar v-model="item.TglPembelian" dateFormat="dd-mm-yy" showIcon class="w-100" />
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Tgl Distribusi">
                <Calendar v-model="item.TglDistribusi" dateFormat="dd-mm-yy" showIcon class="w-100" />
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Tahun Registrasi">
                <Calendar v-model="item.TglRegistrasi" dateFormat="dd-mm-yy" showIcon class="w-100" />
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Tahun Perolehan">
                <Calendar v-model="item.TahunPerolehan" view="year" dateFormat="yy" showIcon class="w-100" />
              </VField>
            </div>
            <div class="column is-2">
              <VField label="Harga Perolehan">
                <VControl>
                  <input v-model="item.HargaPengadaan" class="input" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column pt-0">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select required-vfield" label="Ruangan Asal">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.ruanganAsal" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'namaruangan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" placeholder=" Ruangan Asal" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select required-vfield" label="Ruangan">
                <VControl icon="feather:search" class="prime-auto-select">
                  <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'namaruangan'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'namaruangan'" placeholder=" Ruangan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Qty Aset" class="required-vfield">
                <VControl>
                  <input v-model="item.QtyAset" class="input" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField label="Judul / Pencipta">
                <VControl>
                  <input v-model="item.Judul" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Spesifikasi">
                <VControl>
                  <input v-model="item.Spesifikasi" class="input" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Jenis Aset">
                <VControl>
                  <input v-model="item.JenisAset" class="input" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column p-0">
          <TabView class="tabview-custom">
            <TabPanel>
              <template #header>
                <span>ALAMAT</span>
              </template>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-8">
                    <VField>
                      <VLabel>Alamat</VLabel>
                      <VControl>
                        <VTextarea v-model="item.alamatLengkap" rows="3" placeholder="Alamat Lengkap">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="RT / RW">
                      <VControl>
                        <input v-model="item.rtrw" class="input custom-text-filter" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Kode Pos">
                      <VControl>
                        <input v-model="item.kodePos" class="input custom-text-filter" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Provinsi</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.propinsi" :options="d_Provinsi" :optionLabel="'namapropinsi'"
                          placeholder="Pilih Provinsi" :optionValue="'id'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear @change="changeProvinsi(item.propinsi)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Kota Kabupaten</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.kotaKabupaten" :options="d_KotaKabupaten"
                          :optionLabel="'namakotakabupaten'" placeholder="Pilih Kota Kabupaten" :loading="isLoadingKot"
                          :optionValue="'id'" style="width: 100%;" :filter="true" appendTo="body" showClear
                          @change="changeKota(item.kotaKabupaten)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Kecamatan</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.kecamatan" :options="d_Kecamatan" :optionLabel="'namakecamatan'"
                          placeholder="Pilih Kecamatan" :loading="isLoadingKec" :optionValue="'id'" style="width: 100%;"
                          :filter="true" appendTo="body" showClear @change="changeKecamatan(item.kecamatan)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Kelurahan</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.desaKelurahan" :options="d_Kelurahan" :optionLabel="'namadesakelurahan'"
                          placeholder="Pilih Desa" :loading="isLoadingDes" :optionValue="'id'" style="width: 100%;"
                          :filter="true" appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>KATEGORI</span>
              </template>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Jenis</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.jenisProduk" :options="d_Jenis" :optionLabel="'label'"
                          placeholder="Pilih Jenis" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Detail Jenis</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.detailJenisProduk" :options="d_DetailJenis" :optionLabel="'label'"
                          placeholder="Pilih Detail Jenis" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Sumber Dana</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.asalproduk" :options="d_SumberDana" :optionLabel="'label'"
                          placeholder="Pilih Sumber Dana" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Kelompok Aset</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.kelompokaset" :options="d_KelompokAset" :optionLabel="'label'"
                          placeholder="Pilih Kelompok Aset" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>

                </div>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>SPESIFIKASI</span>
              </template>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField label="No Seri">
                      <VControl>
                        <input v-model="item.NoSeriSpek" class="input custom-text-filter" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Fungsi</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.fungsiProduk" :options="d_FungsiProduk" :optionLabel="'label'"
                          placeholder="Pilih Fungsi Produk" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Bahan</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.bahanProduk" :options="d_BahanProduk" :optionLabel="'label'"
                          placeholder="Pilih Bahan Produk" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Type</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.typeProduk" :options="d_TypeProduk" :optionLabel="'label'"
                          placeholder="Pilih Type Produk" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Warna</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.warnaProduk" :options="d_WarnaProduk" :optionLabel="'label'"
                          placeholder="Pilih Warna Produk" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Merk Produk</VLabel>
                      <VControl icon="feather:search" class="prime-auto">
                        <Dropdown v-model="item.merkProduk" :options="d_merkProduk" :optionLabel="'label'"
                          placeholder="Pilih Merk Produk" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField label="Deskripsi">
                      <VControl>
                        <input v-model="item.spesifikasi" class="input custom-text-filter" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Panjang">
                      <VControl>
                        <input v-model="item.Panjang" class="input custom-text-filter" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Lebar">
                      <VControl>
                        <input v-model="item.Lebar" class="input custom-text-filter" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Tinggi">
                      <VControl>
                        <input v-model="item.Tinggi" class="input custom-text-filter" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField label="Daya Listrik"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" v-model="item.Listrik" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Watt</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Klasifikasi teknologi">
                      <VControl>
                        <input v-model="item.Teknologi" class="input custom-text-filter mt-2" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Daya Listrik"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" v-model="item.UsiaPakai" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Tahun</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Usia Teknis"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" v-model="item.UsiaTeknis" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Tahun</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Sisa Umur"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" v-model="item.SisaUmur" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Tahun</VButton>
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">

                    <VField label="Tgl Produksi">
                      <Calendar v-model="item.TglProduksi" dateFormat="dd-mm-yy" showIcon class="w-100" showTime />
                    </VField>
                  </div>
                </div>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>SATUAN</span>
              </template>
              <div class="column is-3">
                <VField class=" is-rounded-select is-autocomplete-select">
                  <VLabel>Satuan</VLabel>
                  <VControl icon="feather:search" class="prime-auto">
                    <Dropdown v-model="item.satuanStandar" :options="d_SatuanStandar" :optionLabel="'label'"
                      placeholder="Pilih Satuan Standar" :optionValue="'value'" style="width: 100%;" :filter="true"
                      appendTo="body" showClear />
                  </VControl>
                </VField>
              </div>

            </TabPanel>

            <TabPanel>
              <template #header>
                <span>KENDARAAN</span>
              </template>
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VField label="No Mesin">
                    <VControl>
                      <input v-model="item.NoMesin" class="input custom-text-filter" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="No BPKB">
                    <VControl>
                      <input v-model="item.NoBPKB" class="input custom-text-filter" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="No Model">
                    <VControl>
                      <input v-model="item.NoModel" class="input custom-text-filter" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="No Rangka">
                    <VControl>
                      <input v-model="item.NoRangka" class="input custom-text-filter" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="No Seri">
                    <VControl>
                      <input v-model="item.NoSeri" class="input custom-text-filter" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField label="No Polisi">
                    <VControl>
                      <input v-model="item.NoPolisi" class="input custom-text-filter" />
                    </VControl>
                  </VField>
                </div>
                <div class="column">
                  <VField class="is-rounded-select is-autocomplete-select" label="BPKB Atas Nama">
                    <VControl icon="feather:search" class="prime-auto-select">
                      <AutoComplete v-model="item.BPKBPegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih Pegawai" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </TabPanel>

            <TabPanel>
              <template #header>
                <span>SERTIFIKAT</span>
              </template>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Jenis Sertifikat</VLabel>
                      <VControl icon="feather:search" class="prime-auto mt-3">
                        <Dropdown v-model="item.JenisSertifikat" :options="d_JenisSertifikat" :optionLabel="'label'"
                          placeholder="Pilih Jenis Sertifikat" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField label="Klasifikasi teknologi">
                      <VControl>
                        <input v-model="item.NoSertifikat" class="input custom-text-filter mt-1" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField class="is-rounded-select is-autocomplete-select" label="Sertifikat atas nama">
                      <VControl icon="feather:search" class="prime-auto-select mt-3">
                        <AutoComplete v-model="item.Pegawai" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                          :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                          :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pilih" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Masa berlaku sertifikat"></VField>
                    <VField addons>
                      <VControl expanded>
                        <VInput type="number" class="input" v-model="item.MasaBerlakuSertifikat" />
                      </VControl>
                      <VControl class="field-addon-body">
                        <VButton static>Tahun</VButton>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>REKANAN</span>
              </template>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Produsen</VLabel>
                      <VControl icon="feather:search" class="prime-auto mt-3">
                        <Dropdown v-model="item.produsenProduk" :options="d_Produsen" :optionLabel="'label'"
                          placeholder="Pilih Produsen" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField class=" is-rounded-select is-autocomplete-select">
                      <VLabel>Rekanan</VLabel>
                      <VControl icon="feather:search" class="prime-auto mt-3">
                        <Dropdown v-model="item.rekanan" :options="d_Rekanan" :optionLabel="'label'"
                          placeholder="Pilih Rekanan" :optionValue="'value'" style="width: 100%;" :filter="true"
                          appendTo="body" showClear />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>PENYUSUTAN ASSET</span>
              </template>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField label="Tahun Perolehan">
                      <Calendar v-model="item.TahunPerolehan" view="year" dateFormat="yy" showIcon class="modif" />
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Harga Perolehan">
                      <VControl>
                        <input v-model="item.HargaPengadaan" class="input custom-text-filter mt-1" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Nilai Sisia">
                      <VControl>
                        <input v-model="item.nilaiSisa" class="input custom-text-filter mt-1" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField label="Umur Ekonomis">
                      <VControl>
                        <input v-model="item.umurEkonomis" class="input custom-text-filter mt-1" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-1" style="padding-top: 2.5rem;">
                    <VButton color="primary" @click="hitungPenyusutan">Hitung</VButton>
                  </div>
                </div>
              </div>

              <div class="column is-12">
                <DataTable :rows="5" :value="dataGridPenyusutan" :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm"
                  breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                  tableStyle="min-width: 30rem"
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                  <template #empty style="text-align: center;"> No data found. </template>
                  <Column :exportable="false" header="#" style="width:40px">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="pi pi-trash" class="mr-3" color="danger" circle outlined raised
                        v-tooltip-prime="'Hapus'" @click="hapus(slotProps.data)"
                       >
                      </VIconButton>
                    </template>
                  </Column>
                  <Column v-for="col in columnGridPenyusutan" :field="col.field" :header="col.title" :sortable="true"
                    :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{ col.template !=
                        undefined ?
                        H.formatRupiah(slotProps.data[col.field], '')
                        : slotProps.data[col.field] }}</span>
                      <span v-else>
                        <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                      </span>
                    </template>
                  </Column>
                </DataTable>
              </div>
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>RIWAYAT PERPINDAHAN</span>
              </template>
              <div class="column is-12">
                <DataTable :rows="5" :value="dataGridHistoryAsset" :rowsPerPageOptions="[5, 10, 15]"
                  class="p-datatable-sm" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                  tableStyle="min-width: 30rem"
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                  <Column v-for="col in columnGridHistoryAsset" :field="col.field" :header="col.title" :sortable="true"
                    :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{ col.template !=
                        undefined ?
                        H.formatRupiah(slotProps.data[col.field], '')
                        : slotProps.data[col.field] }}</span>
                      <span v-else>
                        <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                      </span>
                    </template>
                  </Column>
                </DataTable>
              </div>
            </TabPanel>
            <TabPanel v-if="norecNoAsset != ''">
              <template #header>
                <span>JADWAL KALIBRASI</span>
                <Badge :value="dataGridHistoryKalibrasiAsset.length" v-if="dataGridHistoryKalibrasiAsset.length > 0" severity="danger" class="ml-2" />
              </template>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <VCard class="card-round-4">
                      <div class="columns is-multiline">
                        <div class="column is-7">
                          <VField label="Jadwal Kalibrasi">
                          <VControl class="prime-auto">
                            <Calendar v-model="item.jadwalKalibrasi" showIcon class="w-100 mb-4 " dateFormat="dd-mm-yy"
                              :showTime="true" />
                          </VControl>
                        </VField>

                        </div>
                        <div class="column is-12 pt-0">
                          <VField label="Keterangan Kalibrasi">
                            <VControl>
                              <input v-model="item.keteranganKalibrasi" class="input" />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column pt-0">
                          <VField class="is-rounded-select is-autocomplete-select" label="PIC">
                            <VControl icon="feather:search" class="prime-auto-select mt-3">
                              <AutoComplete v-model="item.staff" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                                :optionLabel="'namalengkap'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'namalengkap'" placeholder="Pilih " />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-12">
                        <VButton type="button" icon="pi pi-save" class="mr-3 mb-2" color="success" circle raised
                          :loading="isLoadingPem" @click="saveKalibrasi()">
                          Tambah
                        </VButton>
                      </div>

                      </div>
                    </VCard>
                  </div>
                  <div class="column is-8">
                    <VCard class="card-round-2">


                      <div class="column is-12">
                        <DataTable :rows="5" :value="dataGridHistoryKalibrasiAsset" :rowsPerPageOptions="[5, 10, 15]" class="p-datatable-sm"
                          breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                          tableStyle="min-width: 30rem"
                          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                          paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
                          <template #header>
                            <div class="columns is-multiline">
                              <div class="column is-3">
                                <VField label="Periode">
                                  <VControl class="prime-auto">
                                    <Calendar inputId="range" v-model="item.tglAwal" selectionMode="range"
                                      :manualInput="false" class="w-100 mb-4 " :showIcon="true" date-format="dd-mm-yy" />
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-1 mt-5 ">
                                <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                  @click="DaftarJadwalKalibrasiPemeliharaan()" :loading="isLoadingPel">
                                </VIconButton>
                              </div>
                            </div>
                          </template>
                          <template #empty style="text-align: center;"> No data found. </template>
                          <Column :exportable="false" header="#" style="width:220px">
                            <template #body="slotProps">
                              <VIconButton type="button" icon="pi pi-trash" class="mr-2" color="danger" circle outlined raised
                            v-tooltip-prime="'Hapus '" @click="hapusPem(slotProps.data)"
                            :loading="slotProps.data.isLoading">
                          </VIconButton>
                          <VButton type="button" class="mr-2" color="warning" circle outlined
                            raised v-tooltip-prime="'Worklist '" @click="worklist2(slotProps.data)">
                            Worklist
                          </VButton>
                          <VButton type="button" class="mr-2" color="warning" circle outlined
                            raised v-tooltip-prime="'Start '" @click="startBtn2(slotProps.data)">
                            Start
                          </VButton>
                          <VButton type="button" class="mr-2" color="warning" circle outlined raised
                            v-tooltip-prime="'Finish '" @click="finishBtn2(slotProps.data)">
                            Finish
                          </VButton>
                          <VButton type="button" class="mr-2" color="warning" circle outlined raised
                            v-tooltip-prime="'Inpeksi '" @click="Inspeksi2(slotProps.data)">
                            Inpeksi
                          </VButton>

                            </template>
                          </Column>
                          <Column v-for="col in columnGridHistoryKalibrasiAsset" :field="col.field" :header="col.title"
                            :sortable="true"
                            :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
                            <template #body="slotProps">
                              <span v-if="col.tag == undefined">{{ col.template !=
                                undefined ?
                                H.formatRupiah(slotProps.data[col.field], '')
                                : slotProps.data[col.field] }}</span>
                              <span v-else>
                                <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                              </span>
                            </template>
                          </Column>

                        </DataTable>
                      </div>
                    </VCard>
                  </div>
                </div>
              </div>
            </TabPanel>
            <TabPanel v-if="norecNoAsset != ''">
              <template #header>
                <span>JADWAL PEMELIHARAAN</span>
                <Badge :value="dataGridHistoryPemeliharaanAsset.length" v-if="dataGridHistoryPemeliharaanAsset.length > 0" severity="danger" class="ml-2" />
              </template>
              <div>

                <div class="columns is-multiline">
                  <div class="column is-3">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <VField label="Jadwal Pemeliharaan">
                          <VControl class="prime-auto">
                            <Calendar v-model="item.jadwalPemeliharaan" showIcon class="w-100 mb-4 " dateFormat="dd-mm-yy"
                              :showTime="true" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField label="Keterangan Pemeliharaan">
                          <VControl>
                            <input v-model="item.keteranganPemeliharaan" class="input custom-text-filter" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField class=" is-rounded-select is-autocomplete-select">
                          <VLabel>PIC</VLabel>
                          <VControl icon="feather:search" class="prime-auto">
                            <AutoComplete v-model="item.staff" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                              :optionLabel="'namalengkap'" :dropdown="true" :minLength="3" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'namalengkap'" placeholder="ketik nama" />

                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VButton type="button" icon="pi pi-save" class="mr-3" color="success" circle raised
                          :loading="isLoadingPem" @click="savePemeliharaan()">
                          Tambah
                        </VButton>
                      </div>
                    </div>
                  </div>
                  <div class="column is-9">
                    <DataTable :value="dataGridHistoryPemeliharaanAsset" paginator :rows="10" dataKey="id" filterDisplay="row"
                      :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]" :globalFilterFields="['namaproduk']"
                      :class="`p-datatable-small`">
                      <template #header>
                        <div class="columns is-multiline">
                          <div class="column is-3">
                            <VField label="Periode">
                              <VControl class="prime-auto">
                                <Calendar inputId="range" v-model="item.tglAwal" selectionMode="range"
                                  :manualInput="false" class="w-100 mb-4 " :showIcon="true" date-format="dd-mm-yy" />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-1 mt-5 ">
                            <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                              @click="DaftarJadwalKalibrasiPemeliharaan()" :loading="isLoadingPel">
                            </VIconButton>
                          </div>
                        </div>
                      </template>
                      <template #empty style="text-align: center;"> No data found. </template>
                      <Column :exportable="false" header="#" style="width:200px">
                        <template #body="slotProps">
                          <VIconButton type="button" icon="pi pi-trash" class="mr-2" color="danger" circle outlined raised
                            v-tooltip-prime="'Hapus '" @click="hapusPem(slotProps.data)"
                            :loading="slotProps.data.isLoading">
                          </VIconButton>
                          <VButton type="button"  class="mr-2" color="warning" circle outlined
                            raised v-tooltip-prime="'Worklist '" @click="worklist(slotProps.data)">
                            Worlist
                          </VButton>
                          <VButton type="button" class="mr-2" color="warning" circle outlined
                            raised v-tooltip-prime="'Start '" @click="startBtn(slotProps.data)">
                            Start
                          </VButton>
                          <VButton type="button"  class="mr-2" color="warning" circle outlined raised
                            v-tooltip-prime="'Finish '" @click="finishBtn(slotProps.data)">
                            Finish
                          </VButton>
                          <VButton type="button" class="mr-2" color="warning" circle outlined raised
                            v-tooltip-prime="'Inpeksi '" @click="Inspeksi(slotProps.data)">
                            Inpeksi
                          </VButton>
                        </template>
                      </Column>
                      <Column v-for="col in columnPemeliharaan" :field="col.field" :header="col.title" :sortable="true"
                        :style="'width:' + col.width + ';text-align:' + (col.template != undefined ? 'right' : '')">
                        <template #body="slotProps">
                          <span v-if="col.tag == undefined">{{ col.template !=
                            undefined ?
                            H.formatRupiah(slotProps.data[col.field], '')
                            : slotProps.data[col.field] }}</span>
                          <span v-else>
                            <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                          </span>
                        </template>
                      </Column>
                    </DataTable>

                  </div>
                </div>
              </div>
            </TabPanel>

          </TabView>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1 is-offset-10">
              <VButton raised icon="feather:arrow-left" class="w-100" color="danger" rounded light @click="Kosongkan()">
                Kembali
              </VButton>
            </div>
            <div class="column is-1">
              <VButton type="button" rounded color="primary" raised icon="feather:save" :loading="isLoading" class="w-100"
                @click="SimpanDetail()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <Dialog v-model:visible="isModalKalibrasi" modal :header="'Work List'" :style="{ width: '30vw' }">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField label="Keterangan">
            <VControl icon="feather:search">
              <input v-model="item.desc" type="text" class="input is-rounded" placeholder="Keterangan" disabled />
            </VControl>
          </VField>
        </div>

        <div class="column is-12">
          <VField>
            <VLabel class="required-field">Work List</VLabel>
            <VControl>
              <VTextarea v-model="item.worklist" rows="3" placeholder="Work List">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>

      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100 " light dark-outlined @click="isModalKalibrasi = false">
          Tutup
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
          class="ml-2" @click="saveWL()"> Simpan
        </VButton>
      </template>
    </Dialog>
    <Dialog v-model:visible="isModalInspek" modal :header="'Inspeksi'" :style="{ width: '30vw' }">
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField label="Keterangan">
            <VControl icon="feather:search">
              <input v-model="item.desc" type="text" class="input is-rounded" placeholder="Keterangan" disabled />
            </VControl>
          </VField>
        </div>

        <div class="column is-12">
          <VField>
            <VLabel class="required-field">Inspeksi </VLabel>
            <VControl>
              <VTextarea v-model="item.inspeksi" rows="3" placeholder="Inspeksi">
              </VTextarea>
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField class=" is-rounded-select is-autocomplete-select">
            <VLabel>Staff Inspeksi</VLabel>
            <VControl icon="feather:search" class="prime-auto">
              <AutoComplete v-model="item.staff" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'namalengkap'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'namalengkap'" placeholder="ketik nama" />

            </VControl>
          </VField>
        </div>
      </div>

      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100 " light dark-outlined @click="isModalInspek = false">
          Tutup
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
          class="ml-2" @click="saveIS()"> Simpan
        </VButton>
      </template>
    </Dialog>
  </section>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
import DataTable from 'primevue/datatable'
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Dropdown from 'primevue/dropdown';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import Badge from 'primevue/badge'
import Dialog from 'primevue/dialog'

import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment from 'moment';
// app.directive('tooltip', Tooltip);

useHead({
  title: 'Registrasi Aset - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = reactive({
  TahunPerolehan: new Date(),
  tglRegistrasi: new Date(),
  filterTglPeriode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  jadwalPemeliharaan: new Date(),
  jadwalKalibrasi:new Date(),
  tglAwal: [
    new Date(),
    new Date()
  ],
  QtyAset:1,
  TglDistribusi: new Date(),
  TglPembelian: new Date(),
  TglRegistrasi: new Date(),
})
const d_Produk: any = ref([])
const isLoadingPel: any = ref(false)
const activeTab = ref(0);
const dataSourceDetail: any = ref([])
const dataSourceRekap: any = ref([])
const dataSource: any = ref([])
const dataSourceNilaiPagu: any = ref([])
const router = useRouter();
const norecNoAsset: any = ref(useRoute().query.norec ? useRoute().query.norec : '')

const isLoadingPem: any = ref(false)
let d_KelompokPasien: any = ref([])
let d_Dokter: any = ref([])
let d_Pegawai: any = ref([])
let d_Ruangan: any = ref([])
let d_Provinsi: any = ref([])
let d_Kecamatan: any = ref([])
let d_KotaKabupaten: any = ref([])
let d_Kelurahan: any = ref([])
let d_Departemen: any = ref([])
let d_Jenis: any = ref([])
let d_DetailJenis: any = ref([])
let d_SumberDana: any = ref([])
let d_KelompokAset: any = ref([])
let d_FungsiProduk: any = ref([])
let d_BahanProduk: any = ref([])
let d_TypeProduk: any = ref([])
let d_WarnaProduk: any = ref([])
let d_merkProduk: any = ref([])
let d_SatuanStandar: any = ref([])
let d_JenisSertifikat: any = ref([])
let d_Produsen: any = ref([])
let d_Rekanan: any = ref([])
let d_Staff: any = ref([])
let dataGridPenyusutan: any = ref([])
let dataGridHistoryAsset: any = ref([])
let dataSourceParamedis: any = ref([])
let dataGridHistoryPemeliharaanAsset: any = ref([])
let dataGridHistoryKalibrasiAsset: any = ref([])
let isLoading: any = ref(false)
let isLoadingKot: any = ref(false)
let isLoadingKec: any = ref(true)
let isLoadingDes: any = ref(true)
let isModalKalibrasi: any = ref(false)
let isModalInspek: any = ref(false)
const columnPemeliharaan: any = ref([
  {
    "field": "tglplanning",
    "title": "Tgl Pemeliharaan",
    "width": "50px",
  },
  {
    "field": "keteranganlainnya",
    "title": "Keterangan",
    "width": "50px",
  },
  {
    "field": "namalengkap",
    "title": "Nama PIC",
    "width": "70px",
  },
  {
    "field": "startdate",
    "title": "Mulai",
    "width": "50px",
  },
  {
    "field": "duedate",
    "title": "Selesai",
    "width": "50px",
  }
])
const columnGridPenyusutan: any = ref([{
  "field": "no",
  "title": "No",
  "width": "20px",
},
{
  "field": "eoy",
  "title": "EOY",
  "width": "50px",
},
{
  "field": "inttahun",
  "title": "Tahun",
  "width": "50px",
},
{
  "field": "hargaperolehan",
  "title": "Harga Perolehan",
  "width": "70px",
  "template": ""
},
{
  "field": "nilaisisa",
  "title": "Nilai Sisa",
  "width": "70px",
  "template": ""
},
{
  "field": "lifetime",
  "title": "Life Time",
  "width": "60px",
},
{
  "field": "nilaipenyusutan",
  "title": "Penyusutan",
  "width": "70px",
  "template": ""
},
{
  "field": "akumpenyusutan",
  "title": "Akum. Penyusutan",
  "width": "80px",
  "template": ""
},
{
  "field": "nilaibuku",
  "title": "Nilai Buku",
  "width": "80px",
  "template": ""
},
// {
//   "field": "status",
//   "title": "Status",
//   "width": "80px",
// },
])
const columnGridHistoryAsset: any = ref([
  {
    "field": "no",
    "title": "No",
    "width": "20px",
  },
  {
    "field": "NoStruk",
    "title": "NoStruk",
    "width": "50px",
  },
  {
    "field": "inttahun",
    "title": "Tgl Pindah",
    "width": "50px",
  },
  {
    "field": "hargaperolehan",
    "title": "Dari Ruangan",
    "width": "70px"
  },
  {
    "field": "nilaisisa",
    "title": "Ke Ruangan",
    "width": "70px",
  }])
const columnGridHistoryKalibrasiAsset: any = ref([
  {
    "field": "tglplanning",
    "title": "Tgl Kalibrasi",
    "width": "50px",
  },
  {
    "field": "keteranganlainnya",
    "title": "Keterangan",
    "width": "50px",
  },
  {
    "field": "namalengkap",
    "title": "Nama PIC",
    "width": "70px",
  },
  {
    "field": "startdate",
    "title": "Mulai",
    "width": "50px",
  },
  {
    "field": "duedate",
    "title": "Selesai",
    "width": "50px",
  }
])

async function listDropdown() {

  const response = await useApi().get(`/registrasi/list-dropdown`)
  // d_Agama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id, default: e } })
  // d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e } })
  // d_HubunganPasien.value = response.hubunganpasien.map((e: any) => { return { label: e.hubungankeluarga, value: e.id } })
  // d_StatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id, default: e } })
  // d_Pendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id, default: e } })
  // d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id, default: e } })
  // d_Etnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id, default: e } })
  // d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id, default: e } })
  // d_Negara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id, default: e } })
  // d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e } })
  d_Provinsi.value = response.provinsi.map((e: any) => { return { namapropinsi: e.namapropinsi, id: e.id, } })

}


async function changeProvinsi(event: any) {
  d_KotaKabupaten.value = []
  let query = event == '' ? '' : event;
  isLoadingKot.value = true

  const response = await useApi().get(`/registrasi/kotakabupaten?provfk=${query}`)
  isLoadingKot.value = false

  d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { namakotakabupaten: e.namakotakabupaten, id: e.id, } })

}
async function changeKota(event: any) {
  d_Kecamatan.value = []
  let query = event == '' ? '' : event;
  isLoadingKec.value = true

  const response = await useApi().get(`/registrasi/kecamatan?kotafk=${query}`)
  isLoadingKec.value = false

  d_Kecamatan.value = response.kecamatan.map((e: any) => { return { namakecamatan: e.namakecamatan, id: e.id } })

}
async function changeKecamatan(event: any) {
  d_Kelurahan.value = []
  let query = event == '' ? '' : event;
  isLoadingDes.value = true

  const response = await useApi().get(
    `/registrasi/desakelurahan?kecfk=${query}`)
  isLoadingDes.value = false

  d_Kelurahan.value = response.desa.map((e: any) => { return { namadesakelurahan: e.namadesakelurahan, id: e.id } })

}

const dataCombo = async () => {
  await useApi().get('asset/get-data-combo-asset').then((response) => {
    d_Jenis.value = response.jenis.map((e: any) => { return { label: e.jenisproduk, value: e.id } })
    d_DetailJenis.value = response.detailJenis.map((e: any) => { return { label: e.detailjenisproduk, value: e.id } })
    d_SumberDana.value = response.asalproduk.map((e: any) => { return { label: e.asalproduk, value: e.id } })
    d_KelompokAset.value = response.kelompokaset.map((e: any) => { return { label: e.kelompokaset, value: e.id } })
    d_FungsiProduk.value = response.fungsiProduk.map((e: any) => { return { label: e.fungsiproduk, value: e.id } })
    d_BahanProduk.value = response.bahanProduk.map((e: any) => { return { label: e.namabahanproduk, value: e.id } })
    d_TypeProduk.value = response.typeProduk.map((e: any) => { return { label: e.typeproduk, value: e.id } })
    d_WarnaProduk.value = response.warnaProduk.map((e: any) => { return { label: e.warnaproduk, value: e.id } })
    d_merkProduk.value = response.merkProduk.map((e: any) => { return { label: e.merkproduk, value: e.id } })
    d_SatuanStandar.value = response.satuanStandar.map((e: any) => { return { label: e.satuanstandar, value: e.id } })
    d_JenisSertifikat.value = response.jenisSertifikat.map((e: any) => { return { label: e.jenissertifikat, value: e.id } })
    d_Rekanan.value = response.rekanan.map((e: any) => { return { label: e.namarekanan, value: e.id } })
    d_Produsen.value = response.produsenProduk.map((e: any) => { return { label: e.namaprodusenproduk, value: e.id } })
  })
}

const dataDepartemen = async () => {
  await useApi().get('sysadmin/master-ruangan-dropdown').then((response) => {
    d_Departemen.value = response.namadepartemen.map((e: any) => {
      return { label: e.namadepartemen, value: e.id }
    })
  })
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(`/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response.map((e: any) => { return { id: e.value, namaruangan: e.label } })
}

const fetchPegawai = async (filter: any) => {
  if (!filter.query) {

  }

  const response = await useApi().get(
    `/asset/pegawai-paging?name=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

const DaftarJadwalKalibrasiPemeliharaan = async () => {

  var tglAwal = moment(item.tglAwal[0]).format('YYYY-MM-DD 00:00:00');
  var tglAkhir = moment((item.tglAwal[1] ? item.tglAwal[1] : item.tglAwal[0])).format('YYYY-MM-DD 23:59:59');
  isLoadingPel.value = true
  await useApi().get(
    "asset/get-data-jadwal-kalibrasi?norecAsset=" + norecNoAsset.value +
    "&tglAwal=" + tglAwal + "&tglAkhir=" + tglAkhir
  ).then((response) => {
    isLoadingPel.value = false
    dataGridHistoryKalibrasiAsset.value = response
  })
  await useApi().get(
    "asset/get-data-jadwal-pemeliharaan?norecAsset=" + norecNoAsset.value +
    "&tglAwal=" + tglAwal + "&tglAkhir=" + tglAkhir
  ).then((response) => {
    isLoadingPel.value = false
    dataGridHistoryPemeliharaanAsset.value = response
  })
}
const hapusPem = async (e: any) => {
  e.isLoading = true
  await useApi().post('asset/delete-data-jadwal-pemeliharaan', { norec: e.norec }).then((response) => {
    e.isLoading = false
    DaftarJadwalKalibrasiPemeliharaan()
  }).catch(e => {
    e.isLoading = false
  })
}
const savePemeliharaan = async () => {
  if (!item.jadwalPemeliharaan) {
    H.alert('error', 'Jadwal Pemeliharaan harus di isi')
    return
  }
  if (!item.keteranganPemeliharaan) {
    H.alert('error', 'Keterangan Pemeliharaan harus di isi')
    return
  }
  if (!item.staff) {
    H.alert('error', 'PIC  harus di isi')
    return
  }
  isLoadingPem.value = true
  var objSave =
  {
    norec: item.norecPemeliharaan ? item.norecPemeliharaan : '',
    tglplanning: H.formatDate(item.jadwalPemeliharaan, 'YYYY-MM-DD HH:mm:ss'),
    keteranganlainnya: item.keteranganPemeliharaan,
    noregisterassetfk: norecNoAsset.value,
    objectpegawaipjawabfk: item.staff.id,
  }

  await useApi().post('asset/save-data-jadwal-pemeliharaan', objSave).then((response) => {
    isLoadingPem.value = false
    DaftarJadwalKalibrasiPemeliharaan()
  }).catch(e => {
    isLoadingPem.value = false
  })
}
const worklist = (e: any) => {
  isModalKalibrasi.value = true
  item.norecAL = e.norec
  item.desc = e.keteranganlainnya
}
const worklist2 = (e: any) => {
  isModalKalibrasi.value = true
  item.norecAL = e.norec
  item.desc = e.keteranganlainnya
}
const Inspeksi = (e: any) => {
  isModalInspek.value = true
  item.norecAL = e.norec
  item.desc = e.keteranganlainnya
}
const Inspeksi2 = (e: any) => {
  isModalInspek.value = true
  item.norecAL = e.norec
  item.desc = e.keteranganlainnya
}
const saveWL = async () => {
  var objSave =
  {
    norec: item.norecAL,
    deskripsiplanning: item.worklist
  }
  isLoading.value = true
  await useApi().post('asset/save-worklist', objSave).then((response) => {
    isLoading.value = false
    isModalKalibrasi.value = false
    DaftarJadwalKalibrasiPemeliharaan()
  })

}

const saveIS = async () => {
  var objSave =
  {
    norec: item.norecAL,
    keteranganverifikasi: item.inspeksi,
    objectpegawaipjawabevaluasifk: item.staff.id
  }
  isLoading.value = true
  await useApi().post('asset/save-inspeksi', objSave).then((response) => {
    isLoading.value = false
    isModalInspek.value = false
    DaftarJadwalKalibrasiPemeliharaan()
  })

}
const startBtn = async (e: any) => {
  var objSave =
  {
    norec: e.norec
  }
  await useApi().post('asset/save-startdate', objSave).then((response) => {
    DaftarJadwalKalibrasiPemeliharaan()
  })

}
const startBtn2 = async (e: any) => {
  var objSave =
  {
    norec: e.norec
  }
  await useApi().post('asset/save-startdate', objSave).then((response) => {
    DaftarJadwalKalibrasiPemeliharaan()
  })

}

const finishBtn = async (e: any) => {
  var objSave =
  {
    norec: e.norec
  }
  await useApi().post('asset/save-duedate', objSave).then((response) => {
    DaftarJadwalKalibrasiPemeliharaan()
  })

}
const finishBtn2 = async (e: any) => {
  var objSave =
  {
    norec: e.norec
  }
  await useApi().post('asset/save-duedate', objSave).then((response) => {
    DaftarJadwalKalibrasiPemeliharaan()
  })

}

const SimpanDetail = async () => {

  if (!item.produk) {
    H.alert('error', 'Pilih Produk')
    return
  }

  if (!item.ruangan) {
    H.alert('error', 'Pilih Ruangan')
    return
  }
  if (!item.TahunPerolehan) {
    H.alert('error', 'Pilih Tahun Perolehan')
    return
  }
  if (!item.QtyAset) {
    H.alert('error', 'Isi Qty Asset')
    return
  }

  var noRegisaset = '';
  if (item.noRegisterAset != undefined) {
    noRegisaset = item.noRegisterAset;
  }

  var KodeBmn = '';
  if (item.kodeBmn != undefined) {
    KodeBmn = item.kodeBmn;
  }

  var kodeRS = '';
  if (item.kdRs != undefined) {
    kodeRS = item.kdRs;
  }

  var hargaperolehan = 0;
  if (item.HargaPengadaan != undefined) {
    hargaperolehan = parseFloat(item.HargaPengadaan);
  }

  var noaset = '-';
  if (item.noaset != undefined) {
    noaset = item.noaset;
  }

  var ruanganasalfk = null;
  if (item.ruanganAsal != undefined) {
    ruanganasalfk = item.ruanganAsal.id;
  }
  // * END UMUM

  // * ALAMAT
  var alamatlengkap = '-';
  if (item.alamatLengkap != undefined) {
    alamatlengkap = item.alamatLengkap;
  }
  var kodepos = '-'
  if (item.kodePos != undefined) {
    kodepos = item.kodePos
  }
  var objectdesakelurahanfk = null;
  var desakelurahan = '';
  if (item.desaKelurahan != undefined) {
    objectdesakelurahanfk = item.desaKelurahan.id;
    desakelurahan = item.desaKelurahan.namadesakelurahan;
  }
  var objectkecamatanfk = null;
  var kecamatan = '';
  if (item.kecamatan != undefined) {
    objectkecamatanfk = item.kecamatan.id;
    kecamatan = item.kecamatan.namakecamatan;
  }
  var objectkotakabupatenfk = null;
  var kotakabupaten = '';
  if (item.kotaKabupaten != undefined) {
    objectkotakabupatenfk = item.kotaKabupaten.id;
    kotakabupaten = item.kotaKabupaten.namakotakabupaten;
  }
  var objectpropinsifk = null;
  if (item.propinsi != undefined) {
    objectpropinsifk = item.propinsi.id;
  }
  // * END ALAMAT

  // * SERTIFIKAT
  var jenissertifikat = null;
  if (item.JenisSertifikat != undefined) {
    jenissertifikat = item.JenisSertifikat.id;
  }
  var Nosertifikat = '';
  if (item.NoSertifikat != undefined) {
    Nosertifikat = item.NoSertifikat;
  }
  var pegawaiSertifikat = null;
  if (item.Pegawai != undefined) {
    pegawaiSertifikat = item.Pegawai.id;
  }
  var MasaBerlakuSertifikat = '';
  if (item.MasaBerlakuSertifikat != undefined) {
    MasaBerlakuSertifikat = item.MasaBerlakuSertifikat + " " + "Tahun";
  }
  // * END SERTIFIKAT

  // * KENDARAAN
  var nomesin = '';
  if (item.NoMesin != undefined) {
    nomesin = item.NoMesin;
  }
  var nobpkb = '';
  if (item.NoBPKB != undefined) {
    nobpkb = item.NoBPKB;
  }
  var nomodel = '';
  if (item.NoModel != undefined) {
    nomodel = item.NoModel;
  }
  var norangka = '';
  if (item.NoRangka != undefined) {
    norangka = item.NoRangka;
  }
  var noserispek = '-';
  if (item.NoSeriSpek != undefined) {
    noserispek = item.NoSeriSpek;
  }
  var nopolisi = '';
  if (item.NoPolisi != undefined) {
    nopolisi = item.NoPolisi;
  }
  var bpkb_atasnama = null;
  if (item.BPKBPegawai != undefined) {
    bpkb_atasnama = item.BPKBPegawai.id;
  }
  var noSeri = '';
  if (item.NoSeri != undefined) {
    noSeri = item.NoSeri
  }
  // * END KENDARAAN

  // * REKANAN
  var objectprodusenprodukfk = null;
  if (item.produsenProduk != undefined) {
    objectprodusenprodukfk = item.produsenProduk.id;
  }
  var rekanan = null;
  if (item.rekanan != undefined) {
    rekanan = item.rekanan.id;
  }
  // * END REKANAN

  var objectasalprodukfk = null;
  if (item.asalproduk != undefined) {
    objectasalprodukfk = item.asalproduk.id;
  }

  var kelompokaset = null;
  if (item.kelompokaset != undefined) {
    kelompokaset = item.kelompokaset.id;
  }

  var judul = "";
  if (item.judul != undefined) {
    judul = item.judul;
  }

  var SpesifikasiDet = "";
  if (item.Spesifikasi != undefined) {
    SpesifikasiDet = item.Spesifikasi;
  }

  var JenisAset = "-";
  if (item.JenisAset != undefined) {
    JenisAset = item.JenisAset;
  }

  var NilaiSisa = 0;
  if (item.nilaiSisa != undefined) {
    NilaiSisa = item.nilaiSisa;
  }

  var UmurAsset = 0;
  if (item.umurEkonomis != undefined) {
    UmurAsset = item.umurEkonomis;
  }


  var regAset = {

    // * UMUM
    norec: norecNoAsset.value,
    tglregisteraset: moment(item.TglRegistrasi).format('YYYY-MM-DD HH:mm'),
    tglpembelian: moment(item.TglPembelian).format('YYYY-MM-DD HH:mm'),
    tgldistribusi: moment(item.TglDistribusi).format('YYYY-MM-DD HH:mm'),
    noRegisaset: noRegisaset,
    objectprodukfk: item.produk.id,
    kdbmn: KodeBmn,
    hargaperolehan: hargaperolehan,
    noaset: noaset,
    // :item.kdEksternal,
    // :item.kdAspak,
    kdrsabhk: kodeRS,
    objectruanganfk: ruanganasalfk,
    objectruanganposisicurrentfk: item.ruangan.id,
    tahunperolehan: moment(item.TahunPerolehan).format('YYYY'),
    judul: judul,
    spesifikasi: SpesifikasiDet,
    jenisaset: JenisAset,
    qtyprodukaset: item.QtyAset,
    // * END UMUM

    // * ALAMAT
    alamatlengkap: alamatlengkap,
    kodepos: kodepos,
    objectdesakelurahanfk: objectdesakelurahanfk,
    desakelurahan: desakelurahan,
    objectkecamatanfk: objectkecamatanfk,
    kecamatan: kecamatan,
    objectkotakabupatenfk: objectkotakabupatenfk,
    kotakabupaten: kotakabupaten,
    objectpropinsifk: objectpropinsifk,
    // * END ALAMAT

    // * KATEGORY
    objectjenisproduk: item.jenisProduk ? item.jenisProduk.id : null,
    objectdetailjenisproduk: item.detailJenisProduk ? item.detailJenisProduk.id : null,
    objectasalprodukfk: objectasalprodukfk,
    objectkelompokasetfk: kelompokaset,
    // * END KATEGORY

    // * SPESIFIKASI
    fungsikegunaan: item.fungsiProduk ? item.fungsiProduk.fungsiproduk : null,
    objectbahanprodukfk: item.bahanProduk ? item.bahanProduk.id : null,
    objecttypeprodukfk: item.typeProduk ? item.typeProduk.id : null,
    objectwarnaprodukfk: item.warnaProduk ? item.warnaProduk.id : null,
    objectmerkprodukfk: item.merkProduk ? item.merkProduk.id : null,
    keteranganlainnya: item.Spesifikasi ? item.Spesifikasi : '',
    lb_lebar: item.Lebar ? item.Lebar : 0,
    lb_panjang: item.Panjang ? item.Panjang : 0,
    lb_tinggi: item.Tinggi ? item.Tinggi : 0,
    dayalistrik: item.Listrik ? item.Listrik : '',
    klasifikasiteknologi: item.Teknologi ? item.Teknologi : '',
    usiapakai: item.UsiaPakai ? item.UsiaPakai : 0,
    usiateknis: item.UsiaTeknis ? item.UsiaTeknis : 0,
    sisaumur: item.SisaUmur ? item.SisaUmur : 0,
    tglproduksi: moment(item.TglProduksi).format('YYYY-MM-DD HH:mm'),
    noserispek: noserispek,
    // * END SPESIFIKASI

    // * SATUAN
    objectsatuan: item.satuanStandar ? item.satuanStandar.id : null,
    // * END SATUAN

    // * KENDARAAN
    nomesin: nomesin,
    nobpkb: nobpkb,
    nomodel: nomodel,
    norangka: norangka,
    noseri: noSeri,
    nopolisi: nopolisi,
    bpkb_atasnama: bpkb_atasnama,
    // * END KENDARAAN

    // * SERTIFIKAT
    kdjenissertifikat: jenissertifikat,
    nosertifikat: Nosertifikat,
    sertifikat_atasnama: pegawaiSertifikat,
    masaberlakusertifikat: MasaBerlakuSertifikat,
    // * END SERTIFIKAT

    // * REKANAN
    objectsupplier: rekanan,
    objectprodusenprodukfk: objectprodusenprodukfk,
    // * END REKANAN

    // * PENYUSUTAN
    nilaisisa: NilaiSisa,
    umurasset: UmurAsset
    // * END PENYUSUTAN
  }

  var objSave = {
    regAset: regAset
  }
  isLoading.value = true
  await useApi().post('asset/simpan-detail-regisaset', objSave).then((response) => {
    isLoading.value = false
    Kosongkan();


  }).catch(e=>{
    isLoading.value = false
  })
}
const Kosongkan = () => {
  // // * UMUM
  // item.noRegisterAset = "";
  // item.kdProduk = "";
  // item.kodeBmn = "";
  // item.kdEksternal = "";
  // item.kdAspak = "";
  // item.kdRs = "";
  // item.produk = "";
  // item.ruanganAsal = undefined;
  // item.ruangan = undefined;
  // item.TahunPerolehan = new Date();
  // item.HargaPengadaan = "";
  // item.TglRegistrasi = new Date();
  // item.TglPembelian = new Date();
  // item.TglDistribusi = new Date();
  // item.noaset = "";
  // // * END UMUM

  // // * ALAMAT
  // item.alamatLengkap = "";
  // item.kodePos = "";
  // item.desaKelurahan = undefined;
  // item.kecamatan = undefined;
  // item.kotaKabupaten = undefined;
  // item.propinsi = undefined;
  // // * END ALAMAT

  // // * KATEGORY
  // item.jenisProduk = undefined;
  // item.detailJenisProduk = undefined;
  // item.asalproduk = undefined;
  // item.kelompokaset = undefined;
  // // * END KATEGORY

  // // * SPESIFIKASI
  // item.fungsiProduk = undefined;
  // item.bahanProduk = undefined;
  // item.typeProduk = undefined;
  // item.warnaProduk = undefined;
  // item.merkProduk = undefined;
  // item.spesifikasi = undefined;
  // item.Lebar = 0;
  // item.Panjang = 0;
  // item.Tinggi = 0;
  // item.Listrik = 0;
  // item.Teknologir = "";
  // item.UsiaPakai = 0;
  // item.UsiaTeknis = 0;
  // item.SisaUmur = 0;
  // item.TglProduksi = new Date();
  // item.NoSeriSpek = "";
  // // * END SPESIFIKASI

  // // * SATUAN
  // item.satuanStandar = "";
  // // * END SATUAN

  // // * KENDARAAN
  // item.NoMesin = "";
  // item.NoBPKB = "";
  // item.NoModel = "";
  // item.NoRangka = "";
  // item.NoSeri = "";
  // item.NoPolisi = "";
  // item.BPKBPegawai = undefined;
  // // * END KENDARAAN

  // // * SERTIFIKAT
  // item.JenisSertifikat = undefined;
  // item.NoSertifikat = "";
  // item.Pegawai = undefined;
  // item.MasaBerlakuSertifikat = 0;
  // // * END SERTIFIKAT

  // // * REKANAN
  // item.rekanan = undefined;
  // item.produsenProduk = undefined;
  router.push({ name: 'module-asset-daftar-asset' })
}
const loadAwal = async () => {
  await listDropdown()
  await dataCombo()
  if (norecNoAsset.value != '') {
    isLoading.value = true
    await useApi().get('asset/get-detail-registrasiasset?norecAsset=' + norecNoAsset.value ).then(async (data) => {
      isLoading.value = false
      if(data.datas.length == 0)return
      var datas = data.datas[0];
      // * UMUM
      item.noRegisterAset = datas.noregisteraset;
      item.kdProduk = datas.idproduk;
      item.kodeBmn = datas.kodebmn;

      item.QtyAset = datas.qtyprodukaset;


      item.kdEksternal = datas.kodeexternal;
      item.kdAspak = " ";
      item.noaset = datas.noregisteraset_int;
      if(datas.tahunperolehan){
        let now =  H.formatDate(new Date(),' MM-DD')
        item.TahunPerolehan =new Date( datas.tahunperolehan + now)
      }
      // item.kdRs=datas.kdproduk;
      await produk({ query: datas.namaproduk })
      item.produk = { id: datas.idproduk, namaproduk: datas.namaproduk };
      // item.namaProduk=datas.namaproduk;
      if(datas.namaruanganasal){
        await fetchRuangan({ query: datas.namaruanganasal })
        d_Ruangan.value.forEach((element:any) => {
          if (element.id == datas.ruanganasalfk) {
            item.ruanganAsal = element
          }
        });

      }
      if(datas.ruangancurrent){
        await fetchRuangan({ query: datas.ruangancurrent })
        d_Ruangan.value.forEach((element:any) => {
          if (element.id == datas.ruangancurrenfk) {
            item.ruangan = element
          }
        });

      }




      item.TglRegistrasi = moment(datas.tglregisteraset).format('YYYY-MM-DD HH:mm');
      item.HargaPengadaan = datas.hargaperolehan;
      // * END UMUM

      // * ALAMAT
      // item.alamatLengkap="";
      // item.kodePos="";
      // item.desaKelurahan="";
      // item.kecamatan="";
      // item.kotaKabupaten="";
      // item.propinsi="";
      // * END ALAMAT

      // * KATEGORY
      if (datas.djpid) {
        d_DetailJenis.value.forEach((element:any) => {
          if (element.id == datas.djpid) {
            item.detailJenisProduk = element
          }
        });
      }
      if (datas.jpid) {
        d_Jenis.value.forEach((element:any) => {
          if (element.id == datas.jpid) {
            item.jenisProduk = element
          }
        })
      }
      if (datas.djpid) {
        d_SumberDana.value.forEach((element:any) => {
          if (element.id == datas.djpid) {
            item.asalproduk = element
          }
        });
      }
      if (datas.kaid) {
        d_KelompokAset.value.forEach((element:any) => {
          if (element.id == datas.kaid) {
            item.kelompokaset = element
          }
        });
      }
      if (datas.kaid) {
        d_KelompokAset.value.forEach((element:any) => {
          if (element.id == datas.kaid) {
            item.kelompokaset = element
          }
        });

      }
      if (datas.merkid) {
        d_merkProduk.value.forEach((element:any) => {
          if (element.id == datas.merkid) {
            item.merkProduk = element
          }
        });

      }
      if (datas.typeid) {
        d_TypeProduk.value.forEach((element:any) => {
          if (element.id == datas.typeid) {
            item.typeProduk = element
          }
        });

      }
      if (datas.idsupplier) {
        d_Rekanan.value.forEach((element:any) => {
          if (element.id == datas.idsupplier) {
            item.rekanan = element
          }
        });

      }


      // * END KATEGORY

      // * SPESIFIKASI
      //item.fungsiProduk="";
      // item.bahanProduk="";
      // item.typeProduk="";
      // item.warnaProduk="";
      // item.merkProduk="";
      item.Spesifikasi = datas.spesifikasi;
      item.Lebar = 0;
      item.Panjang = 0;
      item.Tinggi = 0;
      item.Listrik = 0;
      // item.Teknologir="";
      item.NoSeriSpek = datas.noseri;
      // item.sisaumur = datas.sisaumur

      item.UsiaPakai = 0;
      item.UsiaTeknis = 0;
      item.TglProduksi = new Date();
      // * END SPESIFIKASI

      // * SATUAN
      // item.satuanStandar="";
      // * END SATUAN

      // * KENDARAAN
      // item.NoMesin="";
      // item.NoBPKB="";
      // item.NoModel="";
      // item.NoRangka="";
      // item.NoSeri="";
      // item.NoPolisi="";
      // item.BPKBPegawai="";
      // * END KENDARAAN

      // * SERTIFIKAT
      // item.JenisSertifikat="";
      // item.NoSertifikat="";
      // item.Pegawai="";
      item.MasaBerlakuSertifikat = 0;
      // * END SERTIFIKAT

      // * REKANAN
      // item.item.produsenProduk= "";
      // * END REKANAN

      item.nilaiSisa = datas.nilaisisa;
      item.umurEkonomis = datas.umurasset;

      hitungPenyusutanFunc();
      historyPindahAssetFunc();
      DaftarJadwalKalibrasiPemeliharaan();

    })
  }
}
const hitungPenyusutanFunc = async () => {

  var data = {};
  var data2: any = [];
  var akumpenyusutanaing = 0
  var nilaipenyusutanaing = parseFloat(item.HargaPengadaan) / parseInt(item.umurEkonomis)
  var intTahun = parseInt(moment(item.TahunPerolehan).format('YYYY'))
  if(intTahun=='1970')return
  for (var i = 0; i < parseInt(item.umurEkonomis) + 1; i++) {
    if (i == 0) {
      data = {
        no: i + 1,
        eoy: i,
        hargaperolehan: parseFloat(item.HargaPengadaan),
        nilaisisa: parseFloat(item.nilaiSisa),
        lifetime: parseInt(item.umurEkonomis),
        nilaipenyusutan: 0,
        akumpenyusutan: akumpenyusutanaing,
        nilaibuku: parseFloat(item.HargaPengadaan) - akumpenyusutanaing,
        inttahun: intTahun,
        norec: ''
      }
    } else {
      intTahun = intTahun + 1
      akumpenyusutanaing = akumpenyusutanaing + nilaipenyusutanaing
      data = {
        no: i + 1,
        eoy: i,
        hargaperolehan: parseFloat(item.HargaPengadaan),
        nilaisisa: parseFloat(item.nilaiSisa),
        lifetime: parseInt(item.umurEkonomis),
        nilaipenyusutan: nilaipenyusutanaing,
        akumpenyusutan: akumpenyusutanaing,
        nilaibuku: parseFloat(item.HargaPengadaan) - akumpenyusutanaing,
        inttahun: intTahun,
        norec: ''
      }
    }

    data2.push(data)

  }
  await useApi().get('asset/get-data-penyusutan-asset?norecAsset=' + norecNoAsset.value).then((res) => {

    var datas = res.data;
    if(datas.length >0 ){
      for (var i = 0; i < datas.length; i++) {
      for (var j = 0; j < data2.length; j++) {
        if (parseInt(datas[i].eoy) == data2[j].eoy) {

          data2[j].status = datas[i].status
          data2[j].hargaperolehan = datas[i].hargaperolehan
          data2[j].nilaisisa = datas[i].nilaisisa
          data2[j].lifetime = datas[i].lifetime
          data2[j].nilaipenyusutan = datas[i].penyusutan
          data2[j].akumpenyusutan = datas[i].akumulasipenyusutan
          data2[j].nilaibuku = datas[i].nilaibuku
          data2[j].norec = datas[i].norec
        }
      }

    }
    }
   
    dataGridPenyusutan.value = data2
  });

}
const historyPindahAssetFunc = async () => {
  await useApi().get('asset/get-daftar-history-pindah-asset?norecAsset=' +norecNoAsset.value).then((data) => {

    if(data.data.length == 0)return
    dataGridHistoryAsset.value = data.data
  });

}
const produk = async (e: any) => {
  let search = e.query ? `?namaproduk=${e.query}` : ''
  await useApi().get(`logistik/get-combo-barang-logistik${search}`).then((response) => {
    d_Produk.value = response
  })
}
const saveKalibrasi = async () => {
  var objSave =
  {
    norec: item.norecKalibrasi ? item.norecKalibrasi : '',
    tglplanning: H.formatDate(item.jadwalKalibrasi,'YYYY-MM-DD HH:mm:ss'),
    keteranganlainnya: item.keteranganKalibrasi,
    noregisterassetfk: norecNoAsset.value,
    objectpegawaipjawabfk: item.staff.id,
  }
  await useApi().post(`asset/save-data-jadwal-kalibrasi`, objSave).then((response) => {

    DaftarJadwalKalibrasiPemeliharaan()
  })

}
const hitungPenyusutan = ()=>{
    hitungPenyusutanFunc()
}
const hapus =(e:any)=>{

    for (var i =  dataGridPenyusutan.value.length - 1; i >= 0; i--) {
        if ( dataGridPenyusutan.value[i].no == e.no) {
             dataGridPenyusutan.value.splice(i, 1);
        }
    }
   
}
loadAwal()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
  position: relative;
  font-size: 17px;
  display: block;
  margin-bottom: 3px;
  margin-top: 0px;
  font-weight: 600;
}

.btn-search {
  display: flex;
  align-items: center;
}
</style>
