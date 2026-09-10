
<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <!-- <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header"> -->
                    <div class="form-header is-stuck stuck-header">
                        <div class="form-header-inner ">
                            <div class="left">
                                <h3>Pasien</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-registrasi-pasien-lama' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:save" :loading="isLoading" color="primary"
                                        raised @click="savePasien()" v-if="!isRegistrasi"> Save
                                    </VButton>

                                    <VButton type="button" icon="feather:arrow-right-circle" :loading="isLoading"
                                        color="primary" raised @click="registrasiPenunjang()" v-if="isRegistrasi">
                                        Registrasi
                                    </VButton>


                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="columns is-multiline">
                            <div class="column is-6 text-center">



                                <!-- masuk sini -->
                                <div class="form-fieldset">
                                    <div class="columns is-multiline">
                                        <div class="column is-12 text-center">
                                            <VField>
                                                <VControl>
                                                    <VFilePond v-if="files.length" v-bind:files="files"
                                                        class="profile-filepond" name="profile_filepond"
                                                        :chunk-retry-delays="[500, 1000, 3000]"
                                                        label-idle="<i class='lnil lnil-cloud-upload'></i>"
                                                        :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']"
                                                        :image-preview-height="140" :image-resize-target-width="140"
                                                        :image-resize-target-height="140" image-crop-aspect-ratio="1:1"
                                                        style-panel-layout="compact circle"
                                                        style-load-indicator-position="center bottom"
                                                        style-progress-indicator-position="right bottom"
                                                        style-button-remove-item-position="left bottom"
                                                        style-button-process-item-position="right bottom"
                                                        @addfile="onAddFile" @removefile="onRemoveFile" />
                                                    <VFilePond v-else class="profile-filepond" name="profile_filepond"
                                                        :chunk-retry-delays="[500, 1000, 3000]"
                                                        label-idle="<i class='lnil lnil-cloud-upload'></i>"
                                                        :accepted-file-types="['image/png', 'image/jpeg', 'image/gif']"
                                                        :image-preview-height="140" :image-resize-target-width="140"
                                                        :image-resize-target-height="140" image-crop-aspect-ratio="1:1"
                                                        style-panel-layout="compact circle"
                                                        style-load-indicator-position="center bottom"
                                                        style-progress-indicator-position="right bottom"
                                                        style-button-remove-item-position="left bottom"
                                                        style-button-process-item-position="right bottom"
                                                        @addfile="onAddFile" @removefile="onRemoveFile" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="fieldset-heading">
                                            <h4>Informasi Pasien</h4>
                                        </div><br> -->
                                        <div class="column is-12" style="margin-top: 10px;">
                                            <div class="fieldset-heading">
                                                <h4 class="required-field">Informasi Pasien</h4>
                                            </div>
                                        </div>
                                        <!-- <div class="column is-12"
                                            v-if="(kelompokUser == 'laboratorium' || kelompokUser == 'radiologi')">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock v-model="isPenunjang" label="Penunjang"
                                                        color="danger" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                        <div class="column is-12" v-if="kelompokUser == 'petugas-jenazah'">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock v-model="isJenazah" label="Jenazah" color="danger" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField id="nik" v-slot="{ field }">
                                                <VLabel class="required-field">NIK / PASSPORT / KITAS</VLabel>
                                                <VControl icon="feather:book" :loading="isLoadingNIK">
                                                    <VInput type="text" v-model="item.nik"
                                                        placeholder="Enter untuk pencarian Identitas"
                                                        class="is-rounded_Z" v-on:keyup.enter="cariBPJS('nik')" />
                                                    <p v-if="field?.errorMessage" class="help is-danger">
                                                        {{ field.errorMessage }}
                                                    </p>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField id="nobpjs" v-slot="{ field }" >
                                                <VLabel class="required-field">No BPJS</VLabel>
                                                <VControl icon="feather:book" :loading="isLoadingBPJS">
                                                    <VInput type="text" v-model="item.nobpjs"
                                                        placeholder="Enter untuk pencarian No BPJS" class="is-rounded_Z"
                                                        v-on:keyup.enter="cariBPJS('nobpjs')" />
                                                    <p v-if="field?.errorMessage" class="help is-danger">
                                                        {{ field.errorMessage }}
                                                    </p>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock class="mb-0 pb-0" v-model="item.isNoRM_Manual"
                                                        label="No. Rekam Medis Manual" color="primary" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6" v-if="item.isNoRM_Manual">
                                            <VField id="norm" v-slot="{ field }" label="No. Rekam Medis">
                                                <VControl icon="feather:book" :loading="isLoadingBPJS">
                                                    <VInput type="text" v-model="item.norm"
                                                        placeholder="Masukkan No. Rekam Medis Pasien"
                                                        class="is-rounded_Z" @keyup="normFormat(item.norm)" maxlength="8"/>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel class="required-field has-fullwidth">Nama Pasien</VLabel>
                                                <VControl icon="feather:user">
                                                    <!--? Attribute uppercase value -->
                                                    <!-- oninput="this.value = this.value.toUpperCase()" -->
                                                    <VInput type="text" v-model="item.namapasien"
                                                        placeholder="Nama Pasien" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel class="required-field">Tempat Lahir</VLabel>
                                                <VControl icon="feather:map-pin">
                                                    <VInput type="text" v-model="item.tempatlahir"
                                                        placeholder="Tempat Lahir" class="is-rounded_Z" style="text-transform: uppercase;"/>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel class="required-field">Tgl Lahir</VLabel>
                                                <VControl class="prime-auto">
                                                    <Calendar v-model="item.tgllahir" selectionMode="single"
                                                        :manualInput="true" class="w-100" :showIcon="true"
                                                        :showTime="false" hourFormat="24" :date-format="'dd-mm-yy'"
                                                        placeholder="dd-mm-yyyy" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel class="required-field">No HP/Ponsel</VLabel>
                                                <VControl icon="feather:phone">
                                                    <VInput type="text" v-model="item.nohp" placeholder="No HP"
                                                        class="is-rounded_Z" @keypress="onlyNumber($event)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel class="required-field">Jenis Kelamin</VLabel>
                                                <VControl>
                                                    <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                                                        <div class="column is-12" v-if="d_JK.length == 0">
                                                            <VPlaceloadText :lines="1" />
                                                        </div>
                                                        <div class="column is-4 p-0" v-for="items in d_JK"
                                                            :key="items.id">
                                                            <VRadio v-model="item.jenisKelamin" :value="items.id"
                                                                class="p-0 mb-3" :label="items.jeniskelamin" square color="primary" />
                                                        </div>
                                                    </div>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel class="required-field">Agama</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.agama" :options="d_Agama"
                                                        autocomplete="off" placeholder="Pilih data" :searchable="true"
                                                        :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel class="required-field">Kebangsaan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.kebangsaan"
                                                        :options="d_Kebangsaan" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Negara</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.negara" :options="d_Negara"
                                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <!--Fieldset-->
                                <div class="form-fieldset" v-if="isPenanggungJawab && isInfoTambahan">
                                    <div class="fieldset-heading">
                                        <h4 class="required-field">Penanggung Jawab Pasien</h4>
                                        <i style="color: red; font-size: 10pt;">(Wajib untuk pasien IGD)</i>
                                    </div>

                                    <div class="columns is-multiline" style="margin-top: 20px;">
                                        <div class="column is-12">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock v-model="item.isPenanggungJawabSama"
                                                        label="Penanggung Jawab Sama Dengan Pasien" color="warning" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel class="required-field">Nama Penanggung Jawab</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.penanggungJawabP" placeholder=""
                                                        class="is-rounded_Z" required />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>No RM Penanggung Jawab</VLabel>
                                                <VControl icon="feather:search">
                                                    <VInput type="text" v-model="item.nocmpj"
                                                        v-on:keyup.enter="fetchPasien(item.nocmpj)"
                                                        placeholder="No MR" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel class="required-field">Hubungan Dengan Pasien</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.hubunganP"
                                                        :options="d_HubunganPasien" autocomplete="off"
                                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                        track-by="value" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <div class="is-flex flex-col">
                                                <p class="required-field" style="text-align: left;">No Telepon</p>

                                                <VCheckbox v-model="item.nohppsama" class="ml-auto" true-value="Ya"
                                                    label="Sama" color="primary" circle style="padding: 0px;" />
                                            </div>
                                            <VField>
                                                <VControl icon="feather:phone">
                                                    <VInput type="text" v-model="item.telponP" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Bahasa Sehari-hari</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.bahasaP" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Umur</VLabel>
                                                <VControl icon="feather:bookmark">
                                                    <VInput type="text" v-model="item.umurP" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel class="required-field">Jenis Kelamin</VLabel>
                                                <VControl>
                                                    <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                                                        <div class="column is-12" v-if="d_JK.length == 0">
                                                            <VPlaceloadText :lines="1" />
                                                        </div>
                                                        <div class="column is-4 p-0" v-for="items in d_JK"
                                                            :key="items.id">
                                                            <VRadio v-model="item.jenisKelP" :value="items.id"
                                                                class="p-0 mb-3" :label="items.jeniskelamin" square color="primary" />
                                                        </div>
                                                    </div>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel class="" style="text-align: left;">Pekerjaan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.pekerjaanP"
                                                        :options="d_Pekerjaan" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <div class="is-flex flex-col">
                                                <p class="required-field" style="text-align: left;">Alamat
                                                    Penanggung
                                                    Jawab</p>
                                                <VCheckbox v-model="item.alamatpsama" class="ml-auto" true-value="Ya"
                                                    label="Sama" color="primary" circle style="padding: 0px;" />
                                            </div>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="item.alamatP" rows="4" oninput="this.value = this.value.toUpperCase()"
                                                        placeholder="Alamat Lengkap">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>

                                <!-- masuk sini -->

                            </div>
                            <div class="column is-6 text-center" style="margin-top: 135px;">

                                <!-- informasi tambahan -->
                                <div class="form-fieldset">
                                    <div class="fieldset-heading">
                                        <h4 class="required-field">Informasi Alamat </h4>
                                    </div>

                                    <div class="columns is-multiline" style="margin-top: 30px;">
                                        <div class="column is-9">
                                            <VField>
                                                <VLabel class="required-field">Alamat Lengkap</VLabel>
                                                <VControl>
                                                    <VTextarea v-model="item.alamat" rows="1" oninput="this.value = this.value.toUpperCase()"
                                                        placeholder="Alamat Lengkap">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-3">
                                            <VField>
                                                <VLabel>RT / RW</VLabel>
                                                <VControl>
                                                    <VInput type="text" v-model="item.rtrw" placeholder="RT / RW"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>



                                        <div class="column is-6">
                                            <VField class="is-rounded-select is-autocomplete-select">
                                                <VLabel class="item">Kelurahan</VLabel>
                                                <VControl icon="feather:search" class="prime-auto">
                                                    <AutoComplete v-model="item.desaKelurahan"
                                                        :suggestions="d_Kelurahan" @complete="fetchKelurahan"
                                                        :optionLabel="'namadesakelurahan'" :dropdown="true"
                                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                        :field="'namadesakelurahan'"
                                                        placeholder="ketik untuk mencari..."
                                                        @select="onKelurahanSelect">
                                                        <template #option="slotProps">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <table style="width:50%">
                                                                        <tr>
                                                                            <td><b>{{
                                                                                    slotProps.option.namadesakelurahan
                                                                                    }}</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>Kota/Kabupaten: {{
                                                                                slotProps.option.namakotakabupaten
                                                                                }}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </AutoComplete>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="is-rounded-select is-autocomplete-select">
                                                <VLabel class="item">Kecamatan</VLabel>
                                                <VControl icon="feather:search" class="prime-auto">
                                                    <AutoComplete v-model="item.kecamatan" :suggestions="d_Kecamatan"
                                                        @complete="fetchKecamatan($event)"
                                                        :optionLabel="'namakecamatan'" :dropdown="true" :minLength="3"
                                                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                        :field="'namakecamatan'" placeholder="ketik untuk mencari..."
                                                        @select="onKelurahanSelect">
                                                        <template #option="slotProps">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <table style="width:50%">
                                                                        <tr>
                                                                            <td><b>{{ slotProps.option.namakecamatan
                                                                                    }}</b></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </AutoComplete>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                                <VLabel class="item">Kota / Kabupaten</VLabel>
                                                <VControl icon="feather:search" class="prime-auto">
                                                    <AutoComplete v-model="item.kotaKabupaten"
                                                        :suggestions="d_KotaKabupaten"
                                                        @complete="fetchKabupaten($event)"
                                                        :optionLabel="'namakotakabupaten'" :dropdown="true"
                                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                        :field="'namakotakabupaten'"
                                                        placeholder="ketik untuk mencari..."
                                                        @select="onKelurahanSelect">
                                                        <template #option="slotProps">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <table style="width:50%">
                                                                        <tr>
                                                                            <td><b>{{
                                                                                    slotProps.option.namakotakabupaten
                                                                                    }}</b></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </AutoComplete>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="is-rounded-select is-autocomplete-select">
                                                <VLabel class="item">Provinsi</VLabel>
                                                <VControl icon="feather:search" class="prime-auto">
                                                    <AutoComplete v-model="item.provinsi" :suggestions="d_Provinsi"
                                                        @complete="fetchProvinsi($event)" :optionLabel="'namapropinsi'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'namapropinsi'"
                                                        placeholder="ketik untuk mencari..."
                                                        @select="onKelurahanSelect">
                                                        <template #option="slotProps">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-12">
                                                                    <table style="width:50%">
                                                                        <tr>
                                                                            <td><b>{{ slotProps.option.namapropinsi
                                                                                    }}</b></td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </AutoComplete>
                                                </VControl>
                                            </VField>
                                        </div>
                                        <!-- <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VLabel>Kelurahan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.desaKelurahan" :options="d_Kelurahan"
                                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                        :loading="isLoading" @select="changeDesa(item.desaKelurahan)" />
                                                </VControl>
                                            </VField>
                                        </div> -->
                                        <!-- <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VLabel>Kecamatan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.kecamatan" :options="d_Kecamatan"
                                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                        :loading="isLoading" @select="changeKecamatan(item.kecamatan)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VLabel>Kota Kabupaten</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.kotaKabupaten"
                                                        :options="d_KotaKabupaten" placeholder="Pilih data" :searchable="true"
                                                        :attrs="{ id }" :loading="isLoading"
                                                        @select="changeKota(item.kotaKabupaten)" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                                                <VLabel>Provinsi</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.provinsi" :options="d_Provinsi"
                                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                        @select="changeProvinsi(item.provinsi)" />
                                                </VControl>
                                            </VField>
                                        </div> -->



                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>Kode Pos </VLabel>
                                                <VControl icon="feather:airplay" :loading="isLoadingKodePos">
                                                    <VInput type="text" v-model="item.kodePos" placeholder="Kode Pos"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-12">
                                            <VField>
                                                <VLabel>Email </VLabel>
                                                <VControl icon="feather:mail">
                                                    <VInput type="email" v-model="item.email" placeholder="Email"
                                                        inputmode="email" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <VField>
                                                <VLabel class="required-field">Nama Ibu <i
                                                        style="color: red; font-size: 9pt;">(Pasien RJ)</i></VLabel>
                                                <VControl icon="pi pi-reddit">
                                                    <VInput type="text" v-model="item.namaIbu" placeholder="Nama Ibu"
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12" style="display: none !important">
                                            <VField>
                                                <VLabel>Kode Pasien Baru (Khusus Antrian Pasien Baru Kiosk)</VLabel>
                                                <VControl icon="pi pi-reddit">
                                                    <VInput type="text" v-model="item.kodepasienbaru"
                                                        placeholder="Kode Pasien Baru" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12" style="display: none !important">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock v-model="isInfoTambahan" label="Informasi Tambahan"
                                                        color="danger" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-fieldset" v-if="isInfoTambahan" style="margin-top: 10px;">
                                    <div class="fieldset-heading">
                                        <h4>Informasi Tambahan </h4>
                                    </div>

                                    <div class="columns is-multiline" style="margin-top: 30px;">
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Status Perkawinan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.statusPerkawinan"
                                                        :options="d_StatusPerkawinan" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Golongan Darah</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.golDar"
                                                        :options="d_GolonganDarah" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Pendidikan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.pendidikan"
                                                        :options="d_Pendidikan" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Pekerjaan</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.pekerjaan"
                                                        :options="d_Pekerjaan" placeholder="Pilih data"
                                                        :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-6">
                                            <VField class="cis-rounded-select_Z  is-autocomplete-select"
                                                v-slot="{ id }">
                                                <VLabel>Etnis</VLabel>
                                                <VControl icon="feather:search">
                                                    <Multiselect mode="single" v-model="item.suku" :options="d_Etnis"
                                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>No Asuransi Lain </VLabel>
                                                <VControl icon="feather:archive">
                                                    <VInput type="text" v-model="item.noAsuransiLain" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>No Telepon Rumah </VLabel>
                                                <VControl icon="feather:phone">
                                                    <VInput type="text" v-model="item.noTelepon" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Nama Ayah </VLabel>
                                                <VControl icon="feather:code">
                                                    <VInput type="text" v-model="item.namaAyah" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Nama Keluarga </VLabel>
                                                <VControl icon="feather:code">
                                                    <VInput type="text" v-model="item.namaKeluarga" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6">
                                            <VField>
                                                <VLabel>Nama Suami/Istri</VLabel>
                                                <VControl icon="feather:code">
                                                    <VInput type="text" v-model="item.namaSuamiIstri" placeholder=""
                                                        class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12" style="display: none !important">
                                            <VField>
                                                <VControl>
                                                    <VSwitchBlock v-model="isPenanggungJawab"
                                                        label="Isi Penanggung Jawab Pasien" color="warning" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-fieldset" v-if="isInfoTambahan" style="margin-top: 10px;">
                                    <div class="fieldset-heading">
                                        <h4>Riwayat Pengobatan Sebelumnya</h4>
                                    </div>

                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Rujukan dari RS</VLabel>
                                            <VControl>
                                                <VTextarea v-model="item.rujukanrs" rows="1" style="text-transform: uppercase;"
                                                    placeholder="Rujukan dari RS">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Penjaminan di RS Sebelumnya</VLabel>
                                            <VControl>
                                                <VTextarea v-model="item.penjaminanrs" rows="1" style="text-transform: uppercase;"
                                                    placeholder="Penjaminan di RS Sebelumnya">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-12">
                                        <VField>
                                            <VLabel>Riwayat Tambahan</VLabel>
                                            <VControl>
                                                <VTextarea v-model="item.riwayattambahan" rows="1" style="text-transform: uppercase;"
                                                    placeholder="Riwayat Tambahan">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                                <!-- informasi tambahan -->

                            </div>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useUserSession } from '/@src/stores/userSession'
import { useToaster } from '/@src/composable/toaster'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
useHead({
    title: 'Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.id as string
let ID_PASIEN_SET = ref()
let NOREC_RESERVASI = useRoute().query.norec_online as string
let alamat: any = useRoute().query.alamat as string
let namapasien: any = useRoute().query.namapasien as string
let tglLahir: any = useRoute().query.tgllahir as string
let jeniskelamin : any = useRoute().query.jeniskelamin as string
let norecEMR = useRoute().query.norecEMR as string

const date = ref(new Date())
const item: any = reactive({
    kecamatan: null,
    kotaKabupaten: null,
    provinsi: null,
    desaKelurahan: null,
    alamat : alamat,
    namapasien : namapasien,
    tgllahir : tglLahir ? new Date(tglLahir) : null,
    jenisKelamin : jeniskelamin,
    isPenanggungJawabSama : false
})
let d_JK: any = ref([])
let d_Agama: any = ref([])
let d_GolonganDarah: any = ref([])
let d_StatusPerkawinan: any = ref([])
let d_Pendidikan: any = ref([])
let d_Pekerjaan: any = ref([])
let d_Etnis: any = ref([])
let d_HubunganPasien: any = ref([])
let d_Kebangsaan: any = ref([])
let d_Negara: any = ref([])
let d_Kelurahan: any = ref([])
let d_Kecamatan: any = ref([])
let d_KotaKabupaten: any = ref([])
let d_Provinsi: any = ref([])
let isLoading = ref(false)
let isInfoTambahan = ref(true)
let isLoadingKodePos = ref(false)
let isLoadingNIK = ref(false)
let isDisabled = ref(false)
let isLoadingBPJS = ref(false)
let isPenanggungJawab = ref(true)
let isRegistrasi = ref(false)
let isPenunjang = ref(false)
let isJenazah = ref(true)
const files = ref([])
const fileFoto: any = ref(null)
const { y } = useWindowScroll()
const router = useRouter()
const route = useRoute()
const renderLoader: any = ref(false)
let STATUSPASIEN = useRoute().query.statuspasien as string
const isStuck = computed(() => {
    return y.value > 0
})
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
async function listDropdown() {
    const response = await useApi().get(
        `/registrasi/list-dropdown`)
    d_JK.value = []
    for (let x = 0; x < response.jk.length; x++) {
        const element = response.jk[x];
        if (element.jeniskelamin != '-') {
            d_JK.value.push(element)
        }
    }
    d_Agama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id, default: e } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e } })
    d_HubunganPasien.value = response.hubunganpasien.map((e: any) => { return { label: e.hubungankeluarga, value: e.id } })
    d_StatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id, default: e } })
    d_Pendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id, default: e } })
    d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id, default: e } })
    d_Etnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id, default: e } })
    d_Kebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id, default: e } })
    d_Negara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id, default: e } })
    // d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e } })
    d_Provinsi.value = response.provinsi.map((e: any) => { return { label: e.namapropinsi, value: e.id, default: e } })

    for (let x = 0; x < response.negara.length; x++) {
        const element = response.negara[x];
        if (element.namanegara.toLowerCase() == 'indonesia') {
            item.negara = element.id
            break
        }
    }
    for (let x = 0; x < response.kebangsaan.length; x++) {
        const element = response.kebangsaan[x];
        if (element.name.toLowerCase() == 'wni') {
            item.kebangsaan = element.id
            break
        }
    }
    if (ID_PASIEN) {
        ID_PASIEN_SET.value = ID_PASIEN
        pasienByID(ID_PASIEN)
    }
    if (route.query.noreservasi) {
        pasienReservasi(route.query)
    }
}

async function pasienReservasi(r: any) {
    item.namapasien = r.namapasien
    item.tgllahir = new Date(r.tgllahir)
    item.nohp = r.notelepon
    item.namapasien = r.namapasien
    item.jenisKelamin = r.objectjeniskelaminfk
    item.nik = r.noidentitas
}
async function pasienByID(id: any) {
    const response = await useApi().get(
        `/registrasi/pasien?id=${id}`)
    if (response.pasien) {
        let r = response.pasien
        item.nik = r.noidentitas
        item.nobpjs = r.nobpjs
        item.norm = r.norm
        item.namapasien = r.namapasien
        item.tempatlahir = r.tempatlahir
        item.tgllahir = new Date(r.tgllahir)
        item.jenisKelamin = r.objectjeniskelaminfk
        item.nohp = r.nohp
        item.agama = r.objectagamafk != null ? r.objectagamafk : undefined
        item.email = r.email
        item.namaIbu = r.namaibu
        item.statusPerkawinan = r.objectstatusperkawinanfk != null ? r.objectstatusperkawinanfk : undefined
        item.golDar = r.objectgolongandarahfk != null ? r.objectgolongandarahfk : undefined
        item.pendidikan = r.objectpendidikanfk != null ? r.objectpendidikanfk : undefined
        item.pekerjaan = r.objectpekerjaanfk != null ? r.objectpekerjaanfk : undefined
        item.suku = r.objectsukufk != null ? r.objectsukufk : undefined
        item.noAsuransiLain = r.noaditional
        item.noTelepon = r.notelepon
        item.namaAyah = r.namaayah
        item.namaKeluarga = r.namakeluarga
        item.namaSuamiIstri = r.namasuamiistri
        item.penanggungJawabP = r.penanggungjawab
        item.nocmpj = r.nocmpj
        item.hubunganP = r.hubungankeluargapj
        item.telponP = r.telponpenanggungjawab
        item.bahasaP = r.bahasa
        item.jenisKelP = r.jeniskelaminpenanggungjawab
        item.umurP = r.umurpenanggungjawab
        item.pekerjaanP = r.pekerjaanpenangggungjawab
        item.bahasaP = r.bahasa
        item.alamatP = r.alamatrmh
        item.rujukanrs = r.rujukanrs
        item.penjaminanrs = r.penjaminanrs
        item.riwayattambahan = r.riwayattambahan
        item.rtrw = r.rtrw
        item.kebangsaan = r.objectkebangsaanfk != null ? r.objectkebangsaanfk : undefined
        item.negara = r.objectnegarafk != null ? r.objectnegarafk : undefined
        item.alamat = r.alamatlengkap
        if (r.objectpropinsifk) {
            const response = await useApi().get(`/registrasi/list-dropdown-provinsi?limit=30`)
            d_Provinsi.value = response
            item.provinsi = undefined
            console.log('objectpropinsifk', r.objectpropinsifk)
            for(var y = 0; y < d_Provinsi.value.length; y++){
                console.log('d_provinsi', d_Provinsi.value[y].id)
                if(d_Provinsi.value[y].id == r.objectpropinsifk){
                    console.log('ini sama')
                    item.provinsi = d_Provinsi.value[y];
                }
            }
            console.log('propinsi', item.provinsi)
            // await changeProvinsi(r.objectpropinsifk)
        }
        if (r.objectkotakabupatenfk) {
            // await changeKota(r.objectkotakabupatenfk)
            const response = await useApi().get(`/registrasi/list-dropdown-kabupaten?limit=30&id=${r.objectkotakabupatenfk}`)
            d_KotaKabupaten.value = response
            item.kotaKabupaten = undefined
            for(var y = 0; y < d_KotaKabupaten.value.length; y++){
                if(d_KotaKabupaten.value[y].id == r.objectkotakabupatenfk){
                    item.kotaKabupaten = d_KotaKabupaten.value[y];
                }
            }
        }
        if (r.objectkecamatanfk) {
            // await changeKecamatan(r.objectkecamatanfk)
            const response = await useApi().get(`/registrasi/list-dropdown-kecamatan?limit=30&id=${r.objectkecamatanfk}`)
            d_Kecamatan.value = response
            item.kecamatan = undefined
            for(var y = 0; y < d_Kecamatan.value.length; y++){
                if(d_Kecamatan.value[y].id == r.objectkecamatanfk){
                    item.kecamatan = d_Kecamatan.value[y];
                }
            }


            // for(var y = 0; y < d_Kecamatan.length; y++){
            //     if(d_Kecamatan[y].id == r.objectkecamatanfk){
            //         item.kecamatan = {id: d_Kecamatan[y].id, namakecamatan: d_Kecamatan[y].namakecamatan}
            //     }
            // }
        }
        if (r.objectdesakelurahanfk) {
            // item.desaKelurahan = r.objectdesakelurahanfk
            const response = await useApi().get(`/registrasi/list-dropdown-desa?limit=30&id=${r.objectdesakelurahanfk}`)
            d_Kelurahan.value = response
            item.desaKelurahan = undefined
            for(var y = 0; y < d_Kelurahan.value.length; y++){
                console.log('d_kelurahan', d_Kelurahan.value[y].id_dk)
                if(d_Kelurahan.value[y].id_dk == r.objectdesakelurahanfk){
                    console.log('ini sama')
                    item.desaKelurahan = d_Kelurahan.value[y];
                }
            }
            
        }
        item.kodePos = r.kodepos
        if (r.isfoto) {
            let path = 'foto_pasien/' + r.nocmfk + '/' + r.filename
            let file: any = await H.getFileBE(path);
            fileFoto.value = file
            let img: any = await blobToBase64(file)
            console.log(img)
            files.value = [img]
        }

    }

}
const blobToBase64 = (blob: any) => {
    return new Promise((resolve, _) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.readAsDataURL(blob);
    });
}
async function savePasien() {
    console.log(H.formatDate(item.tgllahir, 'YYYY-MM-DD'))
    console.log(item.provinsi)
    console.log(item.desaKelurahan)
    console.log(item.kotaKabupaten)
    console.log(item.kecamatan)

    // console.log("Desa/Kelurahan:", item.desaKelurahan.id_dk);
    // console.log("Desa/Kelurahan:", item.provinsi.id);
    // console.log("gantian", item.desaKelurahan.objectpropinsifk);
    
    // console.log("Desa/Kelurahan:", item.kotaKabupaten.id);
    // console.log("Desa/Kelurahan:", item.kecamatan.id);
    // debugger
    if (!item.nik) { H.alert('warning', 'NIK harus di isi'); return }
    if (item.isNoRM_Manual) { 
        if (!item.norm){
            H.alert('warning', 'Anda memilih opsi No.RM Manual, No.RM harus di isi'); return
        }
    }
    if (!item.namapasien) { H.alert('warning', 'Nama harus di isi'); return }
    if (!item.tempatlahir) { H.alert('warning', 'Tempat Lahir harus di isi'); return }
    if (!item.tgllahir) { H.alert('warning', 'Tgl Lahir harus di isi'); return }
    if (!item.jenisKelamin) { H.alert('warning', 'Jenis Kelamin harus di isi'); return }
    if (!item.agama) { H.alert('warning', 'Agama harus di isi'); return }
    if (!item.kebangsaan) { H.alert('warning', 'Kebangsaan harus di isi'); return }
    // if (!item.nohp) { H.alert('warning', 'No HP harus di isi'); return }
    if (!item.alamat) { H.alert('warning', 'Alamat Lenglap harus di isi'); return }
    // if (!item.namaIbu) { H.alert('warning', 'Nama Ibu harus di isi'); return }
    if (!item.nohp) { H.alert('warning', 'No HP harus di isi'); return }
    if (!item.penanggungJawabP) { H.alert('warning', 'Nama Penanggung Jawab harus di isi'); return }
    if (!item.hubunganP) { H.alert('warning', 'Hubungan Penanggung Jawab harus di isi'); return }
    if (!item.telponP) { H.alert('warning', 'No Telepon Penanggung Jawab harus di isi'); return }
    // if (!item.umurP) { H.alert('warning', 'Umur Penanggung Jawab harus di isi'); return }
    if (!item.jenisKelP) { H.alert('warning', 'Jenis Kelamin Penanggung Jawab harus di isi'); return }
    if (!item.alamatP) { H.alert('warning', 'Alamat Penanggung Jawab harus di isi'); return }

    item.progress = cekProggress()
    let json = {
        'pasien': {
            'id': ID_PASIEN ? ID_PASIEN : '',
            'isPenunjang': kelompokUser == 'laboratorium' || kelompokUser == 'radiologi' ? isPenunjang.value : false,
            'isJenazah':  kelompokUser == 'petugas-jenazah' ? isJenazah.value : false,
            'isbayi': item.isbayi ? item.isbayi : false,
            'nocmfkibu': item.nocmfkibu ? item.nocmfkibu : null,
            'noidentitas': item.nik,
            'norm': item.norm,
            'nobpjs': item.nobpjs ? item.nobpjs : null,
            'namapasien': item.namapasien,
            'tempatlahir': item.tempatlahir,
            'tgllahir': H.formatDate(item.tgllahir, 'YYYY-MM-DD'),
            'objectjeniskelaminfk': item.jenisKelamin,
            'nohp': item.nohp,
            'objectagamafk': item.agama != undefined ? item.agama : null,
            'email': item.email != undefined ? item.email : null,
            'namaibu': item.namaIbu != undefined ? item.namaIbu : null,
            'kode_pasien_baru': item.kodepasienbaru != undefined ? item.kodepasienbaru : null,
            'objectstatusperkawinanfk': item.statusPerkawinan != undefined ? item.statusPerkawinan : null,
            'objectgolongandarahfk': item.golDar != undefined ? item.golDar : null,
            'objectpendidikanfk': item.pendidikan != undefined ? item.pendidikan : null,
            'objectpekerjaanfk': item.pekerjaan != undefined ? item.pekerjaan : null,
            'objectsukufk': item.suku != undefined ? item.suku : null,
            'noaditional': item.noAsuransiLain != undefined ? item.noAsuransiLain : null,
            'notelepon': item.noTelepon != undefined ? item.noTelepon : null,
            'namaayah': item.namaAyah != undefined ? item.namaAyah : null,
            'namakeluarga': item.namaKeluarga != undefined ? item.namaKeluarga : null,
            'namasuamiistri': item.namaSuamiIstri != undefined ? item.namaSuamiIstri : null,
            'penanggungjawab': item.penanggungJawabP != undefined ? item.penanggungJawabP : null,
            'nocmpj': item.nocmpj != undefined ? item.nocmpj : null,
            'hubungankeluargapj': item.hubunganP != undefined ? item.hubunganP : null,
            'telponpenanggungjawab': item.telponP != undefined ? item.telponP : null,
            'bahasa': item.bahasaP != undefined ? item.bahasaP : null,
            'jeniskelaminpenanggungjawab': item.jenisKelP != undefined ? item.jenisKelP : null,
            'umurpenanggungjawab': item.umurP != undefined ? item.umurP : null,
            'pekerjaanpenangggungjawab': item.pekerjaanP != undefined ? item.pekerjaanP : null,
            'alamatrmh': item.alamatP != undefined ? item.alamatP : null,
            'objectkebangsaanfk': item.kebangsaan != undefined ? item.kebangsaan : null,
            'objectnegarafk': item.negara != undefined ? item.negara : null,
            'progress': item.progress ? item.progress : 0,
            'isReservasi' : route.query.isReservasi ? true : false,
            'antrianpasienregistrasifk': NOREC_RESERVASI ? NOREC_RESERVASI : null,
            'norecEMR' : norecEMR ? norecEMR : null,
            'isIGD' : route.query.isIGD ? true : false,
            'rujukanrs': item.rujukanrs != undefined ? item.rujukanrs : null,
            'penjaminanrs': item.penjaminanrs != undefined ? item.penjaminanrs : null,
            'riwayattambahan': item.riwayattambahan != undefined ? item.riwayattambahan : null,
        },
        'alamat': {
            'alamatlengkap': item.alamat,
            'rtrw': item.rtrw != undefined ? item.rtrw : null,
            'objectpropinsifk': item.provinsi != undefined && item.provinsi != ''
            ? item.provinsi.id
            : (item.desaKelurahan != undefined && item.desaKelurahan != ''
                ? item.desaKelurahan.objectpropinsifk 
                : null),

            'objectkotakabupatenfk': item.kotaKabupaten != undefined && item.kotaKabupaten != ''
            ? item.kotaKabupaten.id
            : (item.desaKelurahan != undefined && item.desaKelurahan != ''
                ? item.desaKelurahan.objectkotakabupatenfk 
                : null),
                
            'objectkecamatanfk': item.kecamatan != undefined && item.kecamatan != ''
            ? item.kecamatan.id 
            : (item.desaKelurahan != undefined && item.desaKelurahan != ''
                ? item.desaKelurahan.objectkecamatanfk 
                : null),

            'objectdesakelurahanfk': item.desaKelurahan != undefined  && item.desaKelurahan != ''
            ? item.desaKelurahan.id_dk 
            : null,
            'kodepos': item.kodePos ? item.kodePos : null,
            
        }
        
    }
    isLoading.value = true
    isRegistrasi.value = false
    await useApi().post(
        `/registrasi/save-pasien`, json).then(async (response: any) => {

            if (fileFoto.value != null) {
                const formData = new FormData()
                formData.append('id', response.data.id)
                formData.append('file', fileFoto.value)
                useApi().postNoMessage('/registrasi/save-pasien-foto', formData)
            }
            isLoading.value = false
            ID_PASIEN = response.data.id
            ID_PASIEN_SET.value = response.data.id
            isRegistrasi.value = true
            if (ID_PASIEN) {
                registrasiPasien();
            } else if (!ID_PASIEN && isPenunjang.value === true) {
                registrasiPenunjang();
            }else if(route.query.isReservasi){
                registrasiPasien();
            }


        }).catch((e: any) => {
            isLoading.value = false
            console.clear()
            console.log(e)
        })
}
async function cariBPJS(params: any) {
    // // <---- JANGAN DI HAPUS RETURN NYA
    // Dibuka dulu KHUSUS INI
    // return;
    if (params == 'nik') {
        if(item.nik.length != 16){
            H.alert('info', 'Panjang NIK harus 16 digit')
            return
        }
        isLoadingNIK.value = true

        let json = {
            "url": `Peserta/nik/${item.nik}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
            "method": "GET",
            "data": null,
        }
        await useApi().post(
            `/bridging/bpjs/tools`, json).then((e: any) => {
                isLoadingNIK.value = false
                //e = await H.decryptBPJS(e);
                if (e.peserta) {
                    item.namapasien = e.peserta.nama
                    item.nobpjs = e.peserta.noKartu
                    if(e.peserta.mr.noTelepon != null){
                        item.nohp = e.peserta.mr.noTelepon
                    }
                    item.tgllahir = new Date(e.peserta.tglLahir)
                    if (e.peserta.sex.toUpperCase() === "L") {
                        item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                    }
                    if (e.peserta.sex.toUpperCase() === "P") {
                        item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                    }
                } else {
                    H.alert('info', e.metaData.message)
                    console.log(e.metaData.message)
                }
                // if (e.metaData.code == 200) {
                //     let data = e.response
                //     item.namapasien = data.peserta.nama
                //     item.nobpjs = data.peserta.noKartu
                //     item.tgllahir = new Date(data.peserta.tglLahir)
                //     if (data.peserta.sex.toUpperCase() === "L") {
                //         item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                //     }
                //     if (data.peserta.sex.toUpperCase() === "P") {
                //         item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                //     }
                // } else {
                //     H.alert('info', e.metaData.message)
                //     console.log(e.metaData.message)
                // }
            })

    }
    if (params == 'nobpjs') {
        isLoadingBPJS.value = true

        let json = {
            "url": `Peserta/nokartu/${item.nobpjs}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
            "method": "GET",
            "data": null,
            "encrypted": true
        }
        await useApi().post(
            `/bridging/bpjs/tools`, json).then(async (e: any) => {
                isLoadingBPJS.value = false
                e = await H.decryptBPJS(e);
                if (e.peserta) {
                    item.namapasien = e.peserta.nama
                    item.nik = e.peserta.nik
                    item.tgllahir = new Date(e.peserta.tglLahir)
                    item.nohp = e.peserta.mr.noTelepon
                    if (e.peserta.sex.toUpperCase() === "L") {
                        item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                    }
                    if (e.peserta.sex.toUpperCase() === "P") {
                        item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                    }
                } else {
                    H.alert('info', e.metaData.message)
                    console.log(e.metaData.message)
                }
                // if (e.metaData.code == '200') {
                //     var data = e.response
                //     item.namapasien = data.peserta.nama
                //     item.nobpjs = data.peserta.noKartu
                //     item.tgllahir = new Date(data.peserta.tglLahir)
                //     if (data.peserta.sex.toUpperCase() === "L") {
                //         item.jenisKelamin = 1//{ id: 1, jeniskelamin: "LAKI-LAKI" }
                //     }
                //     if (data.peserta.sex.toUpperCase() === "P") {
                //         item.jenisKelamin = 2//{ id: 2, jeniskelamin: "PEREMPUAN" }
                //     }
                // } else {
                //     H.alert('info', e.metaData.message)
                // }
            })

    }
}


// async function fetchDesa(event: any) {
//     let query = event == '' ? '' : event.query;
//     isLoading.value = true

//     const response = await useApi().get(
//         `/registrasi/desa-kelurahan-paging?namadesakelurahan=${query}`)
//     isLoading.value = false
//     d_Kelurahan.value = response
//     return response.map((item: any) => {
//         return { value: item, label: item.namadesakelurahan }
//     })
// }
// async function fetchKecamatan(event: any) {
//     let query = event == '' ? '' : event;
//     isLoading.value = true

//     const response = await useApi().get(
//         `/registrasi/kecamatan-paging?namakecamatan=${query}`)
//     isLoading.value = false
//     return response.map((item: any) => {
//         return { value: item, label: item.namakecamatan }
//     })
// }
async function changeProvinsi(event: any) {
    d_KotaKabupaten.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kotakabupaten?provfk=${query}`)
    isLoading.value = false

    d_KotaKabupaten.value = response.kotakabupaten.map((e: any) => { return { label: e.namakotakabupaten, value: e.id, default: e } })

}
async function changeKota(event: any) {
    d_Kecamatan.value = []
    let query = event == '' ? '' : event;
    isLoading.value = true

    const response = await useApi().get(
        `/registrasi/kecamatan?kotafk=${query}`)
    isLoading.value = false

    d_Kecamatan.value = response.kecamatan.map((e: any) => { return { label: e.namakecamatan, value: e.id, default: e } })

}
// async function changeKecamatan(event: any) {
//     d_Kelurahan.value = []
//     let query = event == '' ? '' : event;
//     isLoading.value = true

//     const response = await useApi().get(
//         `/registrasi/desakelurahan?kecfk=${query}`)
//     isLoading.value = false

//     d_Kelurahan.value = response.desa.map((e: any) => { return { label: e.namadesakelurahan, value: e.id, default: e } })

// }
function changeDesa(event: any) {
    // if (event.objectkecamatanfk)
    //     item.kecamatan = { id: event.objectkecamatanfk, namakecamatan: event.namakecamatan }
    // if (event.objectkotakabupatenfk)
    //     item.kotaKabupaten = { id: event.objectkotakabupatenfk, namakotakabupaten: event.namakotakabupaten }
    // if (event.objectpropinsifk)
    //     item.propinsi = { id: event.objectpropinsifk, namapropinsi: event.namapropinsi }
    // if (event.kodepos)
    item.kodePos = event.kodepos
}
function cekProggress() {
    var countALL = 0
    var data = 0
    countALL = countALL + 1
    if (item.nik) { data = data + 1 }
    countALL = countALL + 1
    if (item.nobpjs) { data = data + 1 }
    countALL = countALL + 1
    if (item.namapasien) { data = data + 1 }
    countALL = countALL + 1
    if (item.tempatlahir) { data = data + 1 }
    countALL = countALL + 1
    if (item.tgllahir) { data = data + 1 }
    countALL = countALL + 1
    if (item.jenisKelamin) { data = data + 1 }
    countALL = countALL + 1
    if (item.nohp) { data = data + 1 }
    countALL = countALL + 1
    if (item.agama) { data = data + 1 }
    countALL = countALL + 1
    if (item.email) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaIbu) { data = data + 1 }
    countALL = countALL + 1
    if (item.statusPerkawinan) { data = data + 1 }
    countALL = countALL + 1
    if (item.golDar) { data = data + 1 }
    countALL = countALL + 1
    if (item.pendidikan) { data = data + 1 }
    countALL = countALL + 1
    if (item.pekerjaan) { data = data + 1 }
    countALL = countALL + 1
    if (item.suku) { data = data + 1 }
    countALL = countALL + 1
    if (item.noAsuransiLain) { data = data + 1 }
    countALL = countALL + 1
    if (item.noTelepon) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaAyah) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaKeluarga) { data = data + 1 }
    countALL = countALL + 1
    if (item.namaSuamiIstri) { data = data + 1 }
    countALL = countALL + 1
    if (item.kebangsaan) { data = data + 1 }
    countALL = countALL + 1
    if (item.negara) { data = data + 1 }
    countALL = countALL + 1
    if (item.alamat) { data = data + 1 }
    countALL = countALL + 1
    if (item.provinsi) { data = data + 1 }
    countALL = countALL + 1
    if (item.kotaKabupaten) { data = data + 1 }
    countALL = countALL + 1
    if (item.kecamatan) { data = data + 1 }
    countALL = countALL + 1
    if (item.desaKelurahan) { data = data + 1 }
    countALL = countALL + 1
    if (item.kodePos) { data = data + 1 }


    return data / countALL * 100
}
function resetForm() {

}
function registrasiPasien() {
    if (route.query.noreservasi) {
        router.push({
            name: 'module-registrasi-registrasi-ruangan',
            query: {
                nocmfk: ID_PASIEN_SET.value,
                noreservasi: route.query.noreservasi,
                norec_online: route.query.norec_online,
                tanggalreservasi: route.query.tanggalreservasi,
                ruangan: route.query.ruangan,
                dokter: route.query.dokter,
                dokter_name: route.query.dokter_name,
                kelompok: route.query.kelompok,
                statuspasien: STATUSPASIEN ? STATUSPASIEN : 'BARU',
            },
        })
    } else {
        router.push({
            name: 'module-registrasi-registrasi-ruangan',
            query: {
                nocmfk: ID_PASIEN_SET.value,
                statuspasien: STATUSPASIEN ? STATUSPASIEN : 'BARU',
            },
        })
    }

}

function registrasiPenunjang() {
    if (route.query.noreservasi) {
        router.push({
            name: 'module-registrasi-registrasi-ruangan-lab',
            query: {
                nocmfk: ID_PASIEN_SET.value,
                noreservasi: route.query.noreservasi,
                norec_online: route.query.norec_online,
                tanggalreservasi: route.query.tanggalreservasi,
                ruangan: route.query.ruangan,
                dokter: route.query.dokter,
                dokter_name: route.query.dokter_name,
                kelompok: route.query.kelompok,
                statuspasien: STATUSPASIEN ? STATUSPASIEN : 'BARU',
            },
        })
    } else {
        router.push({
            name: 'module-registrasi-registrasi-ruangan-lab',
            query: {
                nocmfk: ID_PASIEN_SET.value,
                statuspasien: STATUSPASIEN ? STATUSPASIEN : 'BARU',
            },
        })
    }

}
const onAddFile = (error: any, fileInfo: any) => {
    if (error) {
        console.error(error)
        return
    }

    const _file = fileInfo.file as File
    if (_file) {
        fileFoto.value = _file
    }
}

function onlyNumber(evt: any) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
        evt.preventDefault();
    } else {
        return true;
    }
}

// watch(
//     () => item.value.produk,
//     (newVal) => {
//         console.log('Selected value:', newVal); // Debug log
//         if (newVal) {
//             item.value.kecamatan = newVal.namakecamatan;
//             item.value.kotaKabupaten = newVal.namakotakabupaten;
//             item.value.provinsi = newVal.namapropinsi;
//         } else {
//             item.value.kecamatan = null;
//             item.value.kotaKabupaten = null;
//             item.value.provinsi = null;
//         }
//     }
// );

const fetchKelurahan = async (filter: any) => {
    const response = await useApi().get(`/registrasi/list-dropdown-desa?namadesakelurahan=${filter.query}&limit=30`)
    d_Kelurahan.value = response
    console.log('ganti',response)
}
const fetchKecamatan = async (filter: any) => {
    const response = await useApi().get(`/registrasi/list-dropdown-kecamatan?namakecamatan=${filter.query}&limit=30`)
    d_Kecamatan.value = response
}
const fetchKabupaten = async (filter: any) => {
    const response = await useApi().get(`/registrasi/list-dropdown-kabupaten?namakotakabupaten=${filter.query}&limit=30`)
    d_KotaKabupaten.value = response
}
const fetchProvinsi = async (filter: any) => {
    const response = await useApi().get(`/registrasi/list-dropdown-provinsi?namapropinsi=${filter.query}&limit=30`)
    d_Provinsi.value = response
}
const onKelurahanSelect = (event) => {
  const selectedKelurahan = event.value;
  item.value.kecamatan = selectedKelurahan.objectkecamatanfk;
  item.value.kotaKabupaten = selectedKelurahan.objectkotakabupatenfk;
  item.value.provinsi = selectedKelurahan.objectpropinsifk;
};

watch(() => item.desaKelurahan, (newVal) => {
  if (newVal) {
    // If a Kelurahan is selected, automatically set the corresponding Kecamatan, Kabupaten, and Provinsi
    console.log(newVal)
    item.kecamatan = {id: newVal.objectkecamatanfk, namakecamatan: newVal.namakecamatan};
    item.kotaKabupaten = {id: newVal.objectkotakabupatenfk, namakotakabupaten: newVal.namakotakabupaten};
    item.provinsi = {id: newVal.objectpropinsifk, namapropinsi: newVal.namapropinsi};
  }
});

const fetchPasien = async (nocmpj: any) => {
    renderLoader.value = true
    await useApi().get(`/farmasi/get-pasien-pj?nocm=${nocmpj}`).then((response) => {
        if (response) {
            item.penanggungJawabP = response.namapasien
            item.telponP = response.notelepon ? response.notelepon : '-'
            item.jenisKelP = response.jkid
            item.alamatP = response.alamatlengkap
        } else {
            useToaster().error('Pasien Tidak Ditemukan')
        }
        renderLoader.value = false
    })
}

const onRemoveFile = (error: any, fileInfo: any) => {
    if (error) {
        console.error(error)
        return
    }

    console.log(fileInfo)

    fileFoto.value = null
}

const normFormat = (val) => {
  // if(val.length >= 8) return;
  let rules = {
    2: '.',
    5: '.',
  }
  // console.log(val);

  let formatter = val.split('')
  for (let key in rules) {
    // console.log('dari sppit',key);
    let i = Number(key);
    if (formatter.length >= (i + 1) && formatter[i] !== rules[i]) {
      formatter[i - 1] = formatter[i - 1] + rules[i];
    }
    // console.log("FORMATTERR", formatter);

  }
  let formatted = formatter.join('');
  item.norm = formatted;
}

watch(
    () => item.nohp,
    (newVal) => {
        if(item.nohppsama == "Ya") {
            item.telponP = newVal
        }
    }

)
watch(
    () => item.alamat,
    (newVal) => {
        if(item.alamatpsama == "Ya") {
            item.alamatP = newVal
        }
    }

)

watch(
    () => item.alamatpsama,
    () => {
        if(item.alamatpsama == "Ya") {
            item.alamatP = item.alamat
        }else {
            item.alamatP = null
        }
    }
)
watch(
    () => item.nohppsama,
    () => {
        if(item.nohppsama == "Ya") {
            item.telponP = item.nohp
        }else {
            item.telponP = null;
        }
    }
    
)

onMounted(() => {
    listDropdown()
})
// watch(
//     [item.nohp, item.alamat], ([newHpP, oldHpP], [newAlP, oldAlp]) => {

//     }
// )

watch(
  () => item.isPenanggungJawabSama,
  () => {
    if(item.isPenanggungJawabSama == true){
        item.penanggungJawabP = item.namapasien
        item.hubunganP = 1
        item.telponP = item.nohp
        item.alamatP = item.alamat
        item.jenisKelP = item.jenisKelamin
    } else{
        item.penanggungJawabP = undefined
        item.hubunganP = undefined
        item.telponP = undefined
        item.alamatP = undefined
        item.jenisKelP = undefined
        item.alamatpsama = false
        item.nohppsama = false
    }
})

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 100%;
    margin: 0 auto;
}

.form-fieldset {
    padding: 20px 0;
    max-width: 100%;
    margin: 0 auto;
}

.view-wrapper.has-top-nav .is-navbar-lg {
    margin-top: 135px;
}
</style>
