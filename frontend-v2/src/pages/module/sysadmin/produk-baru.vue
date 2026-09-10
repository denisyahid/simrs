<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Produk</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-sysadmin-master-produk' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary"
                                        raised @click="saveProduk()" :disabled="isButtonDisabled"  v-if="!isRegistrasi"> Save
                                    </VButton>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h4>Informasi Produk</h4>
                            </div>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VField>
                                        <VLabel>Kode</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.kdproduk" placeholder="Kode"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VLabel>Kode Intern</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.kdproduk_intern" placeholder="Kode Intern"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VLabel>Kode Barcode</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.kdbarcode" placeholder="Kode Barcode"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VLabel>Kode BMN</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.kodebmn" placeholder="Kode BMN"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                
                                <div class="column is-7">
                                    <VField>
                                        <VLabel class="required-field">Nama Produk</VLabel>
                                        <VControl icon="feather:briefcase">
                                            <VInput type="text" v-model="item.namaproduk" placeholder="Nama Produk"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-5">
                                    <VField>
                                        <VLabel>Keterangan</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.keterangan" placeholder="Keterangan"
                                                class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-7">
                                    <VField>
                                        <VLabel>Deskripsi Produk</VLabel>
                                        <VControl icon="feather:bookmark">
                                            <VInput type="text" v-model="item.deskripsiproduk" placeholder="Deskripsi Produk" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                                

                                <div class="column is-5">
                                    <VField>
                                        <!-- <VLabel>Nama Eksternal</VLabel>
                                        <VControl icon="feather:box">
                                            <VInput type="text" v-model="item.namaexternal" placeholder="Nama Ekternal"
                                                class="is-rounded_Z" />
                                        </VControl> -->
                                        <VLabel>Generik</VLabel>
                                                <VControl>
                                                    <Multiselect mode="single" v-model="item.namaexternal"
                                                        :options="d_Generik" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" label="name" />
                                                </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Report Display</VLabel>
                                        <VControl icon="feather:database">
                                            <VInput type="text" v-model="item.reportdisplay"
                                                placeholder="Report Display" class="is-rounded_Z" />
                                        </VControl>
                                    </VField>
                                </div>
                        </div>

                                
                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <Accordion :activeIndex="0">
                                <AccordionTab header="Kategori">
                                    <div></div>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                >
                                                <VLabel class="required-field">Kelompok Produk</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <Multiselect mode="single" v-model="item.kelompokproduk"
                                                        :options="d_KelompokProduk" placeholder="Pilih data"
                                                        :searchable="true"
                                                        @select="changeKelompok(item.kelompokproduk)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6" v-if="item.kelompokproduk">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel class="required-field">Jenis Produk</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.jenisproduk"
                                                        :options="d_JenisProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }"
                                                        @select="changeJenis(item.jenisproduk)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6" v-if="item.jenisproduk">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel class="required-field">Detail Jenis Produk</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectdetailjenisprodukfk"
                                                        :options="d_DetailJenis" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" :loading="isLoading" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6" v-if="item.jenisproduk">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel class="required-field">Sumber Dana</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.sumberdana"
                                                        :options="d_SumberDana" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" :loading="isLoading" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Bentuk Obat</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectbentukprodukfk"
                                                        :options="d_BentukProduk" placeholder="Pilih data"  :disabled="isBentukObatDisabled"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Bentuk BMHP/AMHP</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectkategoryprodukfk"
                                                        :options="d_KategoryProduk" placeholder="Pilih data" :disabled="isBentukBMHPDisabled"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                       <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>level</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectlevelprodukfk"
                                                        :options="d_LevelProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Is Produk Intern</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.isprodukintern"
                                                        placeholder="Is Produk Intern" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Departemen">
                                    <div></div>
                                    <div class="column is-6">
                                        <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                            <VLabel>Departemen</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectdepartemenfk"
                                                    :options="d_Departemen" placeholder="Pilih data"
                                                    :searchable="true" :attrs="{ id }" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Spesifikasi">
                                    <div></div>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Bahan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectbahanprodukfk"
                                                        :options="d_BahanProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Type</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objecttypeprodukfk"
                                                        :options="d_TypeProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Warna</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectwarnaprodukfk"
                                                        :options="d_WarnaProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="column is-6">
                                            <VField>
                                                <VLabel>Kekuatan</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.kekuatan" placeholder="Kekuatan"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Merk Produk</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectmerkprodukfk"
                                                        :options="d_Merk" placeholder="Pilih data" :searchable="true"
                                                        :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Detail Obat</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectdetailobatfk"
                                                        :options="d_DetailObat" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Spesifikasi</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.spesifikasi"
                                                        placeholder="Spesifikasi" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Golongan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectgolonganprodukfk"
                                                        :options="d_GolonganProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Satuan">
                                    <div></div>
                                    <div class="column is-6">
                                        <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                            <VLabel>Satuan Standar</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectsatuanstandarfk"
                                                    :options="d_SatuanStandar" placeholder="Pilih data"
                                                    :searchable="true" :attrs="{ id }" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Gizi">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Qty Kalori</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.qtykalori"
                                                        placeholder="Quantity Kalori" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Qty Karbohidrat</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.qtykarbohidrat"
                                                        placeholder="Quantity Karbohidrat" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Qty Lemak</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.qtylemak"
                                                        placeholder="Quantity Lemak" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Qty Porsi</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.qtyporsi"
                                                        placeholder="Quantity Porsi" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Qty Protein</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.qtyprotein"
                                                        placeholder="Quantity Protein" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Penunjang">
                                    <div></div>
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Jenis Periksa Penunjang</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single"
                                                        v-model="item.objectjenisperiksapenunjangfk"
                                                        :options="d_JenisPeriksa" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Nilai Normal</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="text" v-model="item.nilainormal"
                                                        placeholder="Is Produk Intern" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Bahan Sample</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.bahansamplefk"
                                                        :options="d_BahanSample" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Lab Darah">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Golongan Darah</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.golongandarahfk"
                                                        :options="d_GolonganDarah" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Rhesus</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.rhesusfk"
                                                        :options="d_rhesus" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Farmasi">
                                    <div></div>
                                    <div class="columns is-multiline">

                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel> Kategory </VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectjenisgenerikfk"
                                                        :options="d_GeneralProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-6">
                                        
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                            <VLabel> Sub Kateogry</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectsubkategoryfk"
                                                    :options="d_subkategory" placeholder="Pilih data"
                                                    :searchable="true" :attrs="{ id }" />
                                            </VControl>
                                        </VField>
                                        </div>
                                        
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Bentuk Satuan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single"
                                                        v-model="item.objectdetailgolonganprodukfk"
                                                        :options="d_DetailGolonganProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-3">
                                            <VField>
                                                <VLabel>Kekuatan</VLabel>
                                                <VControl icon="feather:plus-circle">
                                                    <VInput type="number" v-model="item.kekuatan" placeholder="Kekuatan"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-3">
                                            <VField>
                                                <VLabel>Kode KFA</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.kodekfh" placeholder="Kode KFA"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="column is-6">
                                            <VField>
                                                <VLabel>Stok Minimum</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="number" v-model="item.stokminimum" placeholder="Stok Minimum"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Stok Maximum</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="number" v-model="item.stokmaximum" placeholder="Stok Maximum"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Stok Buffer</VLabel>
                                                <VControl icon="feather:user">
                                                    <VInput type="number" v-model="item.bufferstok" placeholder="Stok Buffer"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div> -->

                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk Fornas</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isfornas" class="p-0" color="primary" square />
                                                    <!-- <Multiselect mode="single" v-model="item.objectstatusprodukfk"
                                                        :options="d_StatusProduk" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" /> -->
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk Herbal</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isherbal" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <!-- <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Is ARV Donasi</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isarvdonasi" class="p-0" color="primary" square />
                                                    <Multiselect mode="single" v-model="item.isarvdonasi"
                                                        :options="d_jenis" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk Narkotika</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isnarkotika" class="p-0" color="primary" square />
                                                    <!-- <Multiselect mode="single" v-model="item.isnarkotika"
                                                        :options="d_jenis" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" /> -->
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk Psikotropika</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.ispsikotropika" class="p-0" color="primary" square />
                                                    <!-- <Multiselect mode="single" v-model="item.ispsikotropika"
                                                        :options="d_jenis" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" /> -->
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk Onkologi</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isonkologi" class="p-0" color="primary" square />
                                                    <Multiselect mode="single" v-model="item.isonkologi"
                                                        :options="d_jenis" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk OOT</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isoot" class="p-0" color="primary" square />
                                                    <!-- <Multiselect mode="single" v-model="item.isoot" :options="d_jenis"
                                                        placeholder="Pilih data" :searchable="true"
                                                        :attrs="{ id }" /> -->
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk Prekusor</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isprekusor" class="p-0" color="primary" square />
                                                    <!-- <Multiselect mode="single" v-model="item.isprekusor"
                                                        :options="d_Pegawai" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" /> -->
                                                </VControl>
                                            </VField>
                                        </div>
                                        
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk FloorStok</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isfloorstok" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produk Vaksin Donasi</VLabel>
                                                <VControl>
                                                    <VCheckbox v-model="item.isvaksindonasi" class="p-0" color="primary" square />
                                                    <Multiselect mode="single" v-model="item.isvaksindonasi"
                                                        :options="d_Pegawai" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Mapping Generik Astor BM">
                                    <div></div>
                                    <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Generik</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectgenerikfk"
                                                        :options="d_PPRAGenerik" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                </AccordionTab>
                                <AccordionTab header="Rekanan">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Produsen</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectprodusenprodukfk"
                                                        :options="d_Produsen" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Rekanan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.objectrekananfk"
                                                        :options="d_Rekanan" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Kelompok Produk BPJS">
                                    <div></div>
                                    <div class="column is-6">
                                        <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                            <VLabel>Kelompok Produk BPJS</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectkelompokprodukbpjsfk"
                                                    :options="d_KelompokProdukBPJS" placeholder="Pilih data"
                                                    :searchable="true" :attrs="{ id }" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </AccordionTab>
                                <AccordionTab header="Ruangan Spesialis">
                                    <div></div>
                                    <div class="column is-6">
                                        <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                            <VLabel>Ruangan Spesialis</VLabel>
                                            <VControl icon="feather:search">
                                                <Multiselect mode="single" v-model="item.objectRuanganSpesialisfk"
                                                    :options="d_ruanganSpesialis" placeholder="Pilih data"
                                                    :searchable="true" :attrs="{ id }" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </AccordionTab>
                            </Accordion>
                        </div>


                        <!--Fieldset-->


                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import { useToaster } from '/@src/composable/toaster'
import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';

useHead({
    title: 'Produk - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PRODUK = useRoute().query.id as string
let ID_PRODUK_SET = ref()
const date = ref(new Date())
const item: any = ref([])
const dataSource: any = ref([])
let d_JenisProduk: any = ref([])
let d_status: any = ref([])
let d_SatuanStandar: any = ref([])
let d_subkategory: any = ref([])
let d_Departemen: any = ref([])
let d_LevelProduk: any = ref([])
let d_BentukProduk: any = ref([])
let d_BahanProduk: any = ref([])
let d_DetailJenis: any = ref([])
// let d_Generik: any = ref([])
let d_BahanSample: any = ref([])
let d_GeneralProduk: any = ref([])
let d_TypeProduk: any = ref([])
let d_PPRAGenerik: any = ref([])
let d_WarnaProduk: any = ref([])
let d_Merk: any = ref([])
let d_GolonganDarah: any = ref([])
let d_rhesus: any = ref([])
let d_KategoryProduk: any = ref([])
let d_Pegawai: any = ref([])
let d_DetailGolonganProduk: any = ref([])
let d_KelompokProduk: any = ref([])
let d_Produsen: any = ref([])
let d_Rekanan: any = ref([])
let d_SumberDana: any = ref([])
let d_StatusProduk: any = ref([])
let d_JenisPeriksa: any = ref([])
let d_DetailObat: any = ref([])
let isLoading = ref(true)
let d_GolonganProduk: any = ref([])
let d_KelompokProdukBPJS: any = ref([])
let isRegistrasi = ref(false)
const isBentukObatDisabled = ref(false);
const isBentukBMHPDisabled = ref(false);
const { y } = useWindowScroll()
const router = useRouter()
const isButtonDisabled = ref(false);
const isStuck = computed(() => {
    return y.value > 30
})
const d_Generik = [
  {
    name: 'Generik',
    value: 'Generik',
  },
  {
    name: 'Non Generik',
    value: 'Non Generik',
  },
]

async function listDropdown() {

    const response = await useApi().get(
        `/sysadmin/master-produk-dropdown`)
    d_Departemen.value = response.namadepartemen.map((e: any) => { return { label: e.namadepartemen, value: e.id } })
    d_SatuanStandar.value = response.satuanstandar.map((e: any) => { return { label: e.satuanstandar, value: e.id } })
    d_subkategory.value = response.subkategory.map((e: any) => { return { label: e.subkategory, value: e.id } })
    d_KelompokProduk.value = response.kelompokproduk.map((e: any) => { return { label: e.kelompokproduk, value: e.id } })
    // d_KategoryProduk.value = response.kategoryproduk.map((e: any) => { return { label: e.kategoryproduk, value: e.id } })
    d_KategoryProduk.value = response.kategoryproduk.map((e) => {
    return { 
        label: e.kategoryproduk, 
        value: e.id, 
        kode: e.kode || ''
        };
    });
    d_LevelProduk.value = response.levelproduk.map((e: any) => { return { label: e.levelproduk, value: e.id } })
    // d_BentukProduk.value = response.namabentukproduk.map((e: any) => { return { label: e.namabentukproduk, value: e.id, kode: e.kode } })

    d_BentukProduk.value = response.namabentukproduk.map((e) => {
    return { 
        label: e.namabentukproduk, 
        value: e.id, 
        kode: e.kode || '' 
        };
    });

    d_TypeProduk.value = response.typeproduk.map((e: any) => { return { label: e.typeproduk, value: e.id } })
    d_PPRAGenerik.value = response.ppragenerik.map((e: any) => { return { label: e.namagenerik, value: e.id } })
    d_WarnaProduk.value = response.warnaproduk.map((e: any) => { return { label: e.warnaproduk, value: e.id } })
    d_Merk.value = response.merkproduk.map((e: any) => { return { label: e.merkproduk, value: e.id } })
    d_DetailObat.value = response.name.map((e: any) => { return { label: e.name, value: e.id } })
    d_BahanProduk.value = response.namabahanproduk.map((e: any) => { return { label: e.namabahanproduk, value: e.id } })
    d_BahanSample.value = response.namabahansample.map((e: any) => { return { label: e.namabahansample, value: e.id } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id } })
    d_rhesus.value = response.rhesus.map((e: any) => { return { label: e.rhesus, value: e.id } })
    d_Produsen.value = response.namaprodusenproduk.map((e: any) => { return { label: e.namaprodusenproduk, value: e.id } })
    d_Rekanan.value = response.namarekanan.map((e: any) => { return { label: e.namarekanan, value: e.id } })
    d_GeneralProduk.value = response.namagproduk.map((e: any) => { return { label: e.namagproduk, value: e.id } })
    d_GolonganProduk.value = response.golonganproduk.map((e: any) => { return { label: e.golonganproduk, value: e.id } })
    d_DetailGolonganProduk.value = response.detailgolonganproduk.map((e: any) => { return { label: e.detailgolonganproduk, value: e.id } })
    d_JenisPeriksa.value = response.jenisperiksa.map((e: any) => { return { label: e.jenisperiksa, value: e.id } })
    d_StatusProduk.value = response.statusproduk.map((e: any) => { return { label: e.statusproduk, value: e.id } })
    d_KelompokProdukBPJS.value = response.kelompokprodukbpjs.map((e: any) => { return { label: e.kelompokprodukbpjs, value: e.id } })
    d_SumberDana.value = response.asalproduk.map((e) => {
    return { 
        label: e.asalproduk, 
        value: e.id, 
        kode: e.kode || '' 
        };
    });
    if (ID_PRODUK) {
        ID_PRODUK_SET.value = ID_PRODUK
        produkByID(ID_PRODUK)
    }
    isLoading.value = false
}
async function produkByID(id: any) {
    isLoading.value = true
   
    await useApi().get(`/sysadmin/master-produk?id=${id}`).then((response)=>{
        // debugger
        let produk = response.data[0]
        item.value.kdproduk = produk.kdproduk
        item.value.kdproduk_intern = produk.kdproduk_intern
        item.value.kdbarcode = produk.kdbarcode
        item.value.kodebmn = produk.kodebmn
        item.value.namaproduk = produk.namaproduk
        item.value.deskripsiproduk = produk.deskripsiproduk
        item.value.namaexternal = produk.namaexternal
        item.value.keterangan = produk.keterangan
        item.value.kelompokproduk = produk.objectkelompokprodukfk
        item.value.reportdisplay = produk.reportdisplay
        changeKelompok(item.value.kelompokproduk)
        item.value.jenisproduk = produk.objectjenisprodukfk
        changeJenis(item.value.jenisproduk)
        item.value.objectdetailjenisprodukfk = produk.objectdetailjenisprodukfk
        item.value.sumberdana = produk.sumberdana ? produk.sumberdana: produk.objectsumberdanafk 
        item.value.objectkategoryprodukfk = produk.objectkategoryprodukfk
        item.value.objectgenerikfk = produk.objectgenerikfk
        item.value.objectjenisgenerikfk = produk.objectjenisgenerikfk
        item.value.objectlevelprodukfk = produk.objectlevelprodukfk
        item.value.isprodukintern = produk.isprodukintern
        item.value.objectdepartemenfk = produk.objectdepartemenfk
        item.value.objectbentukprodukfk = produk.objectbentukprodukfk
        item.value.objectbahanprodukfk = produk.objectbahanprodukfk
        item.value.objecttypeprodukfk = produk.objecttypeprodukfk
        item.value.objectwarnaprodukfk = produk.objectwarnaprodukfk
        item.value.objectmerkprodukfk = produk.objectmerkprodukfk
        item.value.objectdetailobatfk = produk.objectdetailobatfk
        item.value.objectgolonganprodukfk = produk.objectgolonganprodukfk
        item.value.objectdetailgolonganprodukfk = produk.objectdetailgolonganprodukfk
        item.value.spesifikasi = produk.spesifikasi
        item.value.kekuatan = produk.kekuatan
        item.value.kodekfh = produk.kodekfh
        item.value.objectsatuanstandarfk = produk.objectsatuanstandarfk
        item.value.objectsubkategoryfk = produk.objectsubkategoryfk
        item.value.qtykalori = produk.qtykalori
        item.value.qtykarbohidrat = produk.qtykarbohidrat
        item.value.qtylemak = produk.qtylemak
        item.value.qtyporsi = produk.qtyporsi
        item.value.qtyprotein = produk.qtyprotein
        item.value.objectjenisperiksapenunjangfk = produk.objectjenisperiksapenunjangfk
        item.value.bahansamplefk = produk.bahansamplefk
        item.value.kodebmn = produk.kodebmn
        item.value.nilainormal = produk.nilainormal
        item.value.golongandarahfk = produk.golongandarahfk
        item.value.isfornas = produk.isfornas
        item.value.isherbal = produk.isherbal
        item.value.isfloorstok = produk.isfloorstok
        item.value.rhesusfk = produk.rhesusfk
        item.value.isarvdonasi = produk.isarvdonasi
        item.value.isnarkotika = produk.isnarkotika
        item.value.ispsikotropika = produk.ispsikotropika
        item.value.isonkologi = produk.isonkologi
        item.value.isoot = produk.isoot
        item.value.isprekusor = produk.isprekusor
        item.value.objectprodusenprodukfk = produk.objectprodusenprodukfk
        item.value.objectrekananfk = produk.objectrekananfk
        item.value.objectkelompokprodukbpjsfk = produk.objectkelompokprodukbpjsfk
        item.value.stokminimum = produk.stokminimum?produk.stokminimum:0
        item.value.bufferstok = produk.bufferstok?produk.bufferstok:0
        item.value.stokmaximum = produk.stokmaximum?produk.stokmaximum:0

        // 'stokminimum': item.value.stokminimum ? item.value.stokminimum : 0,
        // 'bufferstok': item.value.bufferstok ? item.value.bufferstok : 0,
    
        // d_KelompokProduk.value.forEach((element:any) => {
        //     // debugger
        //     // console.log(element.value)
        //     // debugger
        //     if(element.value == produk.objectkelompokprodukfk){
        //         item.value.objectkelompokprodukfk = {label : element.kelompokproduk , value : element.value}
                
        //     }
        // });
        // debugger
    })
    // item.value.kdproduk = produk[0].kdproduk
    // item.value.kdproduk_intern = produk[0].kdproduk_intern
    // item.value.kdbarcode = produk[0].kdbarcode
    // item.value.kodebmn = produk[0].kodebmn
    // item.value.namaproduk = produk[0].namaproduk
    // item.value.deskripsiproduk = produk[0].deskripsiproduk
    // item.value.namaexternal = produk[0].namaexternal
    // item.value.keterangan = produk[0].keterangan
    // item.value.reportdisplay = produk[0].reportdisplay
    // // item.value.objectkelompokprodukfk = produk[0].objectkelompokprodukfk
    // item.value.objectdetailjenisprodukfk = produk[0].objectdetailjenisprodukfk
    // item.value.objectkategoryprodukfk = produk[0].objectkategoryprodukfk
    // item.value.objectjenisgenerikfk = produk[0].objectjenisgenerikfk
    // item.value.objectlevelprodukfk = produk[0].objectlevelprodukfk
    // item.value.isprodukintern = produk[0].isprodukintern
    // item.value.objectdepartemenfk = produk[0].objectdepartemenfk
    // item.value.objectbentukprodukfk = produk[0].objectbentukprodukfk
    // item.value.objectbahanprodukfk = produk[0].objectbahanprodukfk
    // item.value.objecttypeprodukfk = produk[0].objecttypeprodukfk
    // item.value.objectwarnaprodukfk = produk[0].objectwarnaprodukfk
    // item.value.objectdetailobatfk = produk[0].objectdetailobatfk
    // item.value.spesifikasi = produk[0].spesifikasi
    // item.value.objectgolonganprodukfk = produk[0].objectgolonganprodukfk
    // item.value.objectdetailgolonganprodukfk = produk[0].objectdetailgolonganprodukfk
    // item.value.objectsatuanstandarfk = produk[0].objectsatuanstandarfk
    // item.value.kekuatan = produk[0].kekuatan
    // item.value.objectmerkprodukfk = produk[0].objectmerkprodukfk
    // item.value.qtykalori = produk[0].qtykalori
    // item.value.qtykarbohidrat = produk[0].qtykarbohidrat
    // item.value.qtylemak = produk[0].qtylemak
    // item.value.qtyporsi = produk[0].qtyporsi
    // item.value.qtyprotein = produk[0].qtyprotein
    // item.value.objectjenisperiksapenunjangfk = produk[0].objectjenisperiksapenunjangfk
    // item.value.nilainormal = produk[0].nilainormal
    // item.value.bahansamplefk = produk[0].bahansamplefk
    // item.value.golongandarahfk = produk[0].golongandarahfk
    // item.value.rhesusfk = produk[0].rhesusfk
    // item.value.objectstatusprodukfk = produk[0].objectstatusprodukfk
    // item.value.isarvdonasi = produk[0].isarvdonasi
    // item.value.isnarkotika = produk[0].isnarkotika
    // item.value.ispsikotropika = produk[0].ispsikotropika
    // item.value.isonkologi = produk[0].isonkologi
    // item.value.isoot = produk[0].isoot
    // item.value.isprekusor = produk[0].isprekusor
    // item.value.isvaksindonasi = produk[0].isvaksindonasi
    // item.value.objectprodusenprodukfk = produk[0].objectprodusenprodukfk
    // item.value.objectrekananfk = produk[0].objectrekananfk
    isLoading.value = false
}
async function saveProduk() {

    console.log('ini tes',item.value.objectkelompokprodukbpjsfk);
    if (!item.value.namaproduk) {
        useToaster().error('Nama Produk harus di isi')
        return
    }
    if (!item.value.deskripsiproduk) {
        useToaster().error('Deskripsi Produk harus di isi')
        return
    }
    if (!item.value.kelompokproduk) {
        useToaster().error('Kelompok Produk harus di isi')
        return
    }
    if (!item.value.objectsatuanstandarfk) {
        useToaster().error('Satuan Standar harus di isi')
        return
    }
    if (item.value.jenisproduk == '97' && !item.value.objectdetailjenisprodukfk) {
        useToaster().error('Detail Jenis Produk harus di isi')
        return
    }

    if (item.value.jenisproduk == '97' && !item.value.objectkelompokprodukbpjsfk)
    {
       useToaster().error('Kelompok Produk BPJS Harus di Isi')
        return
    }    

    let json = {
        'struk' : {
            'nostruk' : item.value.nostruk,
            'noorderfk' : null,
            'kelompokprodukfk' :  item.value.kelompokproduk ?  item.value.kelompokproduk : null,
            'objectrekananfk' :   item.value.objectrekananfk ? item.value.objectrekananfk : null,
        },
        'produk': {
            'id': ID_PRODUK ? ID_PRODUK : '',
            'kdproduk': item.value.kdproduk ? item.value.kdproduk : null,
            'kdproduk_intern': item.value.kdproduk_intern ? item.value.kdproduk_intern : null,
            'kdbarcode': item.value.kdbarcode ? item.value.kdbarcode : null,
            'kodebmn': item.value.kodebmn ? item.value.kodebmn : null,
            'namaproduk': item.value.namaproduk,
            'deskripsiproduk': item.value.deskripsiproduk,
            'namaexternal': item.value.namaexternal ? item.value.namaexternal : null,
            'keterangan': item.value.keterangan ? item.value.keterangan : null,
            'reportdisplay': item.value.reportdisplay ? item.value.reportdisplay : null,
            // 'jenisproduk': item.value.objectdetailjenisprodukfk ? item.value.objectdetailjenisprodukfk : null,
            'objectdetailjenisprodukfk': item.value.objectdetailjenisprodukfk ? item.value.objectdetailjenisprodukfk : null,
            'sumberdana': item.value.sumberdana ? item.value.sumberdana : null,
            'objectkategoryprodukfk': item.value.objectkategoryprodukfk ? item.value.objectkategoryprodukfk : null,
            'objectkelompokprodukfk': item.value.kelompokproduk ? item.value.kelompokproduk : null,
            'objectjenisgenerikfk': item.value.objectjenisgenerikfk ? item.value.objectjenisgenerikfk : null,
            'objectlevelprodukfk': item.value.objectlevelprodukfk ? item.value.objectlevelprodukfk : null,
            'isprodukintern': item.value.isprodukintern ? item.value.isprodukintern : null,
            'objectdepartemenfk': item.value.objectdepartemenfk ? item.value.objectdepartemenfk : null,
            'objectbentukprodukfk': item.value.objectbentukprodukfk ? item.value.objectbentukprodukfk : null,
            'objectbahanprodukfk': item.value.objectbahanprodukfk ? item.value.objectbahanprodukfk : null,
            'objecttypeprodukfk': item.value.objecttypeprodukfk ? item.value.objecttypeprodukfk : null,
            'objectwarnaprodukfk': item.value.objectwarnaprodukfk ? item.value.objectwarnaprodukfk : null,
            'objectdetailobatfk': item.value.objectdetailobatfk ? item.value.objectdetailobatfk : null,
            'spesifikasi': item.value.spesifikasi ? item.value.spesifikasi : null,
            'objectgolonganprodukfk': item.value.objectgolonganprodukfk ? item.value.objectgolonganprodukfk : null,
            'objectdetailgolonganprodukfk': item.value.objectdetailgolonganprodukfk ? item.value.objectdetailgolonganprodukfk : null,
            'objectsubkategoryfk': item.value.objectsubkategoryfk ? item.value.objectsubkategoryfk : null,
            'objectsatuanstandarfk': item.value.objectsatuanstandarfk ? item.value.objectsatuanstandarfk : null,
            'kekuatan': item.value.kekuatan ? item.value.kekuatan : null,
            'kodekfh': item.value.kodekfh ? item.value.kodekfh : null,
            'objectmerkprodukfk': item.value.objectmerkprodukfk ? item.value.objectmerkprodukfk : null,
            'qtykalori': item.value.qtykalori ? item.value.qtykalori : null,
            'qtykarbohidrat': item.value.qtykarbohidrat ? item.value.qtykarbohidrat : null,
            'qtylemak': item.value.qtylemak ? item.value.qtylemak : null,
            'qtyporsi': item.value.qtyporsi ? item.value.qtyporsi : null,
            'qtyprotein': item.value.qtyprotein ? item.value.qtyprotein : null,
            'objectjenisperiksapenunjangfk': item.value.objectjenisperiksapenunjangfk ? item.value.objectjenisperiksapenunjangfk : null,
            'nilainormal': item.value.nilainormal ? item.value.nilainormal : null,
            'bahansamplefk': item.value.bahansamplefk ? item.value.bahansamplefk : null,
            'golongandarahfk': item.value.golongandarahfk ? item.value.golongandarahfk : null,
            'rhesusfk': item.value.rhesusfk ? item.value.rhesusfk : null,
            'isfornas': item.value.isfornas ? item.value.isfornas : null,
            'isherbal': item.value.isherbal ? item.value.isherbal : null,
            'isfloorstok': item.value.isfloorstok ? item.value.isfloorstok : null,
            'isarvdonasi': item.value.isarvdonasi ? item.value.isarvdonasi : null,
            'isnarkotika': item.value.isnarkotika ? item.value.isnarkotika : null,
            'ispsikotropika': item.value.ispsikotropika ? item.value.ispsikotropika : null,
            'isonkologi': item.value.isonkologi ? item.value.isonkologi : null,
            'isoot': item.value.isoot ? item.value.isoot : null,
            'isprekusor': item.value.isprekusor ? item.value.isprekusor : null,
            'isvaksindonasi': item.value.isvaksindonasi ? item.value.isvaksindonasi : null,
            'objectprodusenprodukfk': item.value.objectprodusenprodukfk ? item.value.objectprodusenprodukfk : null,
            'objectrekananfk': item.value.objectrekananfk ? item.value.objectrekananfk : null,
            'objectgenerikfk': item.value.objectgenerikfk ? item.value.objectgenerikfk : null,
            'objectkelompokprodukbpjsfk': item.value.objectkelompokprodukbpjsfk ? item.value.objectkelompokprodukbpjsfk : null,
            'stokminimum': item.value.stokminimum ? item.value.stokminimum : 0,
            'bufferstok': item.value.bufferstok ? item.value.bufferstok : 0,
            'stokmaximum': item.value.stokmaximum ? item.value.stokmaximum : 0,
            
        }
    }
    isLoading.value = true
    isRegistrasi.value = false
    isButtonDisabled.value = true
    await useApi().post(
        `/sysadmin/save-master-produk`, json).then((response: any) => {
            isLoading.value = false
            ID_PRODUK = response.data.id
            ID_PRODUK_SET.value = response.data.id
            isRegistrasi.value = true

        }).catch((e: any) => {
            isLoading.value = false
            console.clear()
            console.log(e)
        })
}
async function changeKelompok(e: any) {
    d_JenisProduk.value = []
    delete item.jenisproduk
    if (e) {
        isLoading.value = true
        await useApi().get(
            `/sysadmin/jenis-produk?id=${e}`)
            .then((response: any) => {
                isLoading.value = false
                if (response.length > 0) {
                    d_JenisProduk.value = response.map((e: any) => { return { label: e.jenisproduk, value: e.id } })
                    if (response.length == 1) {
                        item.jenisproduk = response[0].id
                    }
                }
            })
            .catch((error: any) => { isLoading.value = false })
    }

}
async function changeJenis(e: any) {
    d_DetailJenis.value = []
    delete item.detailjenisproduk
    if (e) {
        isLoading.value = true
        await useApi().get(
            `/sysadmin/detail-jenis-produk?id=${e}`)
            .then((response: any) => {
                isLoading.value = false
                if (response.length > 0) {
                    d_DetailJenis.value = response.map((e: any) => { return { label: e.detailjenisproduk, value: e.id } })
                    if (response.length == 1) {
                        item.detailjenisproduk = response[0].id
                    }
                }
            })
            .catch((error: any) => { isLoading.value = false })
    }

}

    watch(() => item.value.objectbentukprodukfk, (newValue) => {
    if (newValue) {
        isBentukBMHPDisabled.value = true;
    } else {
        isBentukBMHPDisabled.value = false;
    }
    });

    watch(() => item.value.objectkategoryprodukfk, (newValue) => {
    if (newValue) {
        isBentukObatDisabled.value = true;
    } else {
        isBentukObatDisabled.value = false;
    }
    });

watch(
    () => [
            item.value.objectdetailjenisprodukfk, 
            item.value.sumberdana,
            item.value.objectbentukprodukfk, 
            item.value.objectkategoryprodukfk
        ], // Pantau kedua input

        
    ([newDetailVal, newSumberDanaVal, newBentukVal, newKategoriVal]) => {

        if (!d_DetailJenis.value || d_DetailJenis.value.length === 0) {
           return;  
        }
        
        let kdprodukCombined = ""; // Variable sementara untuk menggabungkan kode

        // Ambil huruf pertama dari Detail Jenis Produk
        if (newDetailVal) {
            const selectedDetail = d_DetailJenis.value.find(
                (detail) => detail.value === newDetailVal
            );
            if (selectedDetail) {
                kdprodukCombined += selectedDetail.label.charAt(0).toUpperCase();
            }
            console.log("selectedDetail:", selectedDetail);
        }

        // Ambil huruf pertama dari Sumber Dana
        if (newSumberDanaVal) {
            const selectedSumberDana = d_SumberDana.value.find(
                (detail) => detail.value === newSumberDanaVal
            );
            if (selectedSumberDana) {
                kdprodukCombined += selectedSumberDana.kode;
            }
        }

        if (newBentukVal) {
            const selectedBentuk = d_BentukProduk.value.find(
                (detail) => detail.value === newBentukVal
            );
            if (selectedBentuk) {
                kdprodukCombined += selectedBentuk.kode;
            }
        }

        if (newKategoriVal) {
            const selectedKategori = d_KategoryProduk.value.find(
                (detail) => detail.value === newKategoriVal
            );
            if (selectedKategori) {
                kdprodukCombined += selectedKategori.kode;
            }
        }

        if (typeof ID_PRODUK !== 'undefined' && ID_PRODUK) {
        kdprodukCombined += ID_PRODUK; 
}
        item.value.kdproduk = kdprodukCombined || ""; 

    }
);


function resetForm() {

}

listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 800px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 780px;
    margin: 0 auto;
}
</style>
