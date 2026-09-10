<template>
  <section>
    <ConfirmDialog />
    <div class="columns is-multiline">
      <VCard style="padding-bottom: 0px">
        <div class="column c-title-x">
          <h3 class="title is-5 mb-2 mr-1">{{ title }}</h3>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-5">
              <VField label="Deskripsi">
                <VControl icon="feather:search">
                  <input v-model="item.filter" v-on:keyup.enter="fetchData()" type="text" class="input is-rounded"
                    placeholder="Deskripsi" />
                </VControl>
              </VField>
            </div>
            <div class="column is-3">
            </div>
            <div class="column is-3 is-pulled-right">
              <VField label="Periode">
                <VControl class="prime-auto">
                  <Calendar inputId="range" v-model="item.qFilterTgl" selectionMode="range" :manualInput="false"
                    class="w-100 mb-4 " :showIcon="true" date-format="dd-mm-yy" />
                </VControl>
              </VField>

            </div>
            <div class="column is-1 mt-5 ">
              <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                :loading="isLoading">
              </VIconButton>
              <!-- <VIconButton circle class="ml-2  is-pulled-" icon="fas fa-filter" raised bold
                              @click="modalFilter = true" v-tooltip.bubble="'Filter'">
                          </VIconButton>
                          <Badge :value="jmlFilter" v-if="jmlFilter > 0" severity="info" class="is-pulled-"
                              style="margin-left:-10px ;z-index: 100;  position: relative; "></Badge> -->
            </div>

            <div class="column is-12 mt-5-min">
              <VCard class="card-round-1">

                <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="10" dataKey="id"
                  filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]" :globalFilterFields="['kelompok']"
                  :class="`p-datatable-small`">
                  <template #header>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VButton type="button" icon="pi pi-file-excel" class="mr-3" color="info" outlined circle raised
                          v-tooltip-prime="'Export'" @click="exportExcel()">
                          Export Excel
                        </VButton>

                        <VButton type="button" icon="feather:plus" class="mr-3" color="success" circle raised
                          v-tooltip-prime="'Entry'" @click="entryJurnal()"> Entry
                        </VButton>
                      </div>
                      <div class="column is-3 is-offset-6">
                        <VField>
                          <VControl icon="feather:search">
                            <input v-model="filtersTrans['global'].value" v-on:keyup.enter="fetchData()" type="text"
                              class="input is-rounded" placeholder="Search" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </template>
                  <template #empty style="text-align: center;"> No data found. </template>
                  <Column :exportable="false" header="#" style="width:180px">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="pi pi-search" class="mr-3" color="info" circle outlined raised
                        v-tooltip-prime="'Detail Jurnal'" @click="DetailJurnal(slotProps.data)"
                        :loading="slotProps.data.isLoading">
                      </VIconButton>
                      <VIconButton type="button" icon="pi pi-save" class="mr-3" color="purple" circle outlined raised
                        v-tooltip-prime="'Posting'" @click="PostingJurnal(slotProps.data)"
                        :loading="slotProps.data.isLoading2">
                      </VIconButton>
                      <VIconButton type="button" icon="pi pi-undo" class="mr-3" color="danger" circle outlined raised
                        v-tooltip-prime="'Batal Posting'" @click="saveUnPostingJurnal(slotProps.data)"
                        :loading="slotProps.data.isLoading3">
                      </VIconButton>
                      <VIconButton type="button" icon="pi pi-check-circle" class="mr-3" color="success" circle outlined
                        raised v-tooltip-prime="'Perbaiki Jurnal'" @click="perbaikanJurnal2(slotProps.data)"
                        :loading="slotProps.data.isLoading4">
                      </VIconButton>
                      <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="warning" circle outlined raised
                        v-tooltip-prime="'Ubah'" @click="editJurnal(slotProps.data)" :loading="slotProps.data.isLoading5">
                      </VIconButton>
                      <VIconButton type="button" icon="pi pi-trash" class="mr-3" color="danger" circle outlined raised
                        v-tooltip-prime="'Hapus'" @click="dialogConfirm(slotProps.data)"
                        :loading="slotProps.data.isLoading6">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column v-for="col in columnTrans" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{ col.template != undefined ?
                        H.formatRupiah(slotProps.data[col.field], '')
                        : slotProps.data[col.field] }}</span>
                      <span v-else>

                        <VTag class="mr-1 mb-1" :color="slotProps.data[col.field] != null ? 'success' : 'solid'"
                          :label="slotProps.data[col.field]" />
                      </span>
                    </template>
                  </Column>


                  <ColumnGroup type="footer">
                    <Row>
                      <Column :footer="'Terdapat ' + dataSource.length + ' data.'" :colspan="columnTrans.length - 3" />
                      <Column :footer="'TOTAL'" />
                      <Column :footer="H.formatRupiah(item.ttlDebetGRID, 'Rp. ')" />
                      <Column :footer="H.formatRupiah(item.ttlKreditGRID, 'Rp. ')" />
                      <Column :footer="''" :colspan="2" />
                    </Row>
                  </ColumnGroup>
                </DataTable>
                <div class="column is-12 mt-3" v-if="valueProgress > 0">
                  <ProgressBar :value="valueProgress" style="height: 15px" />
                </div>
                <div class="column is-12 mt-4-min">
                  <div class="columns is-multiline">
                    <div class="column is-2">
                      <VButton rounded color="warning" class="" icon="feather:download" raised bold
                        @click="downloadTemplate()">
                        Download Template Excel
                      </VButton>
                    </div>
                    <div class="column is-3">
                      <!-- <FileUpload mode="basic" name="demo[]"
                        accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        :maxFileSize="1000000" chooseLabel="Upload" @select="onSelectedFiles"
                        @upload="onTemplatedUpload($event)" /> -->
                      <FileUpload name="demo[]" :multiple="false" @upload="onTemplatedUpload($event)" mode="advanced"
                        :showUploadButton="false" :showCancelButton="true" @select="onSelectedFiles" chooseLabel="Pilih"
                        cancelLabel="Batal"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                        :maxFileSize="50000000">
                        <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                          <div class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                            <div class="flex gap-2">
                              <Button @click="chooseCallback()" icon="pi pi-upload" rounded severity="info" class="mr-1"
                                outlined></Button>
                              <Button @click="uploadEvent(uploadCallback)" icon="pi pi-cloud-upload" rounded outlined
                                :loading="isLoadingUpload" severity="success"
                                :disabled="!files || files.length === 0"></Button>
                              <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger"
                                :disabled="!files || files.length === 0"></Button>
                            </div>
                            <ProgressBar :value="totalSizePercent" :showValue="false"
                              :class="['md:w-20rem h-1rem w-full md:ml-auto', { 'exceeded-progress-bar': totalSizePercent > 100 }]">
                              <span class="white-space-nowrap">{{ totalSize
                              }}B / 50Mb</span>
                            </ProgressBar>
                          </div>
                        </template>
                        <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                          <div v-if="files.length > 0">

                            <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                              <div :key="files[0].name + files[0].type + files[0].size"
                                class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                                <div>
                                  <i class="fas fa-file-excel shadow-2 mr-2" aria-hidden="true"></i>
                                  <!-- <img role="presentation"
                                                                                        :alt="file.name"
                                                                                        :src="'/images/avatars/svg/product.svg'" width="50"
                                                                                        height="50" class="shadow-2" /> -->
                                </div>
                                <span class="font-semibold">{{ files[0].name
                                }}</span>
                                <div class="ml-2">{{
                                  formatSize(files[0].size)
                                }}
                                  <Badge :value="valueProgress >= 99 ? 'Uploaded' : 'Pending'"
                                    :severity="valueProgress >= 99 ? 'success' : 'warning'" class="ml-2 mr-2" />
                                </div>

                                <Button icon="pi pi-times"
                                  @click="onRemoveTemplatingFile(files[0], removeFileCallback,0)" outlined rounded
                                  severity="danger" />
                              </div>
                            </div>
                          </div>
                        </template>
                        <template #empty>
                          <p>Drag atau drop files untuk mengupload.</p>
                        </template>
                      </FileUpload>
                    </div>
                  </div>
                  <div class="dataTable-bottom mt-2">
                    <div class="dataTable-info" style="font-style:italic"> *Note : Template
                      digunakan untuk menyamakan data, agar saat di upload tidak terjadi
                      kesalahan memasukkan data.
                    </div>
                  </div>


                </div>
              </VCard>
            </div>

          </div>
        </div>
      </VCard>
    </div>

    <Dialog v-model:visible="modalJurnal" modal :header="'Jurnal Detail'" :style="{ width: '70vw' }">
      <div class="columns is-multiline">

        <div class="column is-4">
          <VField>
            <VLabelText>No Junal</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.nojurnal }}
            </VLabel>

          </VField>
        </div>

        <div class="column is-4">
          <VField>
            <VLabelText>Tanggal</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.tanggal }}
            </VLabel>
          </VField>
        </div>

        <div class="column is-4">
          <VField>
            <VLabelText>Deskripsi</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.deskripsi }}
            </VLabel>
          </VField>
        </div>
        <div class="column is-12">
          <VCard class="card-round-4">
            <DataTable v-model:filters="filtersDetail" :value="dataPopUp" paginator :rows="5" dataKey="id"
              filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
              :globalFilterFields="['namaaccount', 'keteranganlainnya']" :class="`p-datatable-small`" :size="'small'">
              <template #header>
                <div class="columns is-multiline">
                  <div class="column is-3 is-offset-9">
                    <VField>
                      <VControl icon="feather:search">
                        <input v-model="filtersDetail['global'].value" v-on:keyup.enter="fetchData()" type="text"
                          class="input is-rounded" placeholder="Search" />
                      </VControl>
                    </VField>
                  </div>
                </div>

              </template>
              <template #empty style="text-align: center;"> No data found. </template>
              <Column v-for="col in columnPopUp" :field="col.field" :header="col.title" :style="'width:' + col.width">
                <template #body="slotProps">
                  <span v-if="col.tag == undefined">{{ col.template != undefined ?
                    H.formatRupiah(slotProps.data[col.field], '')
                    : slotProps.data[col.field] }}</span>
                  <span v-else>
                    <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                  </span>
                </template>
              </Column>
              <ColumnGroup type="footer">
                <Row>
                  <Column :footer="'Terdapat ' + dataPopUp.length + ' data.'" :colspan="columnPopUp.length + 1" />
                </Row>
              </ColumnGroup>˝
            </DataTable>
            <div class="columns is-multiline mb-2">
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status info">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL DEBIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlDebet,
                      'Rp.')
                  }}</small>

                </VCardCustom>
              </div>
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status danger">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL KREDIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlKredit,
                      'Rp.')
                  }}</small>

                </VCardCustom>
              </div>
            </div>
          </VCard>
        </div>

      </div>


    </Dialog>
    <Dialog v-model:visible="modalJurnalPost" modal :header="'Jurnal Detail'" :style="{ width: '70vw' }">
      <div class="columns is-multiline">
        <div class="column is-4">
          <VField>
            <VLabelText>No Junal</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.nojurnal }}
            </VLabel>
          </VField>
        </div>

        <div class="column is-4">
          <VField>
            <VLabelText>Tanggal</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.tanggal }}
            </VLabel>
          </VField>
        </div>

        <div class="column is-4">
          <VField>
            <VLabelText>Deskripsi</VLabelText>
            <VLabel>
              <i aria-hidden="true" class="bulet fas fa-circle"></i>
              {{ item.deskripsi }}
            </VLabel>
          </VField>
        </div>
        <div class="column is-12">
          <VCard class="card-round-4">
            <DataTable v-model:filters="filtersDetail" :value="dataPopUp" paginator :rows="5" dataKey="id"
              filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
              :globalFilterFields="['namaaccount', 'keteranganlainnya']" :class="`p-datatable-small`" :size="'small'">
              <template #header>
                <div class="columns is-multiline">
                  <div class="column is-3 is-offset-9">
                    <VField>
                      <VControl icon="feather:search">
                        <input v-model="filtersDetail['global'].value" type="text" class="input is-rounded"
                          placeholder="Search" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </template>
              <template #empty style="text-align: center;"> No data found. </template>
              <Column v-for="col in columnPopUp" :field="col.field" :header="col.title" :style="'width:' + col.width">
                <template #body="slotProps">
                  <span v-if="col.tag == undefined">{{ col.template != undefined ?
                    H.formatRupiah(slotProps.data[col.field], '')
                    : slotProps.data[col.field] }}</span>
                  <span v-else>
                    <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                  </span>
                </template>
              </Column>
              <ColumnGroup type="footer">
                <Row>
                  <Column :footer="'Terdapat ' + dataPopUp.length + ' data.'" :colspan="columnPopUp.length + 1" />
                </Row>
              </ColumnGroup>
            </DataTable>
            <div class="columns is-multiline mb-2">
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status info">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL DEBIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlDebet,
                      'Rp.')
                  }}</small>
                </VCardCustom>
              </div>
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status danger">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL KREDIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlKredit,
                      'Rp.')
                  }}</small>

                </VCardCustom>
              </div>
            </div>
            <div class="columns is-multiline mb-2">
              <div class="column is-2">
                <VButton type="button" icon="feather:save" class="w-100" color="success" circle raised
                  @click="savePostingJurnal()" :loading="isPostingJurnal"> Posting
                </VButton>
              </div>
              <div class="column is-2">
                <VButton type="button" icon="feather:printer" class="w-100" color="info" circle raised
                  @click="cetakPostingJurnal()"> Cetak
                </VButton>
              </div>
              <div class="column is-2">
                <VButton type="button" icon="feather:printer" class="w-100" color="info" circle raised
                  @click="cetakPostingJurnalDetail()"> Cetak Detail
                </VButton>
              </div>
              <div class="column is-2">
                <VButton type="button" icon="feather:printer" class="w-100" color="info" circle raised
                  @click="cetakPostingJurnalDetailSelisih()"> Cetak Selisih
                </VButton>
              </div>
              <div class="column is-2">
                <VButton type="button" icon="feather:check-circle" class="w-100" color="purple" circle raised
                  :loading="isLoadingpop" @click="perbaikanJurnal()"> Perbaiki Jurnal
                </VButton>
              </div>
              <div class="column is-2 mt-3" v-if="valueProgress > 0">
                <ProgressBar :value="valueProgress" style="height: 15px" />
              </div>
            </div>

          </VCard>
        </div>
      </div>
    </Dialog>
    <Dialog v-model:visible="modalJurnalEntry" modal :header="'Jurnal Entry'" :style="{ width: '90vw' }">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VField label="No Jurnal">
            <VControl icon="feather:search">
              <input v-model="item.nojurnal" type="text" class="input is-rounded" placeholder="No Jurnal" disabled />
            </VControl>
          </VField>
        </div>

        <div class="column is-3">
          <VField label="Tanggal">
            <VControl class="prime-auto">
              <Calendar v-model="item.tglEntry" selectionMode="single" :manualInput="true" class="w-100 is-rounded"
                :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" placeholder="yy-mm-dd HH:mm" />
            </VControl>
          </VField>
        </div>

        <div class="column is-6">
          <VField label="Deskripsi">
            <VControl icon="feather:search">
              <input v-model="item.deskripsi" type="text" class="input is-rounded" placeholder="Deskripsi" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VCard class="card-round-4">
            <p class="title-c"> RINCIAN JURNAL</p>
            <div class="columns is-multiline">
              <div class="column is-2">
                <VField label="Kode Akun " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="fa:bookmark" class="prime-auto-select">
                    <AutoComplete v-model="item.kdAkun" :suggestions="d_Akun" @complete="fetchAKun($event)"
                      :optionLabel="'noaccount'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'noaccount'" placeholder="ketik kode Akun" @item-select="item.namAkun = item.kdAkun" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Nama Akun " class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                  <VControl icon="fa:bookmark" class="prime-auto-select">
                    <AutoComplete v-model="item.namAkun" :suggestions="d_Akun" @complete="fetchAKun($event)"
                      :optionLabel="'namaaccount'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'namaaccount'" placeholder="ketik Nama Akun" @item-select="item.kdAkun = item.namAkun" />
                  </VControl>
                </VField>
              </div>

              <div class="column is-2">
                <VField label="Debit">
                  <VControl icon="fa:calculator">
                    <input v-model="item.debet" type="text" class="input is-rounded" placeholder="Debit" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField label="Kredit">
                  <VControl icon="fa:calculator">
                    <input v-model="item.kredit" type="text" class="input is-rounded" placeholder="Kredit" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mt-5">
                <VIconButton type="button" icon="pi pi-plus" class="mr-3" color="success" circle raised
                  v-tooltip-prime="'Tambah'" @click="tambah()">
                </VIconButton>
                <VIconButton type="button" icon="pi pi-refresh" class="mr-3" color="warning" circle raised
                  v-tooltip-prime="'Batal'" @click="batal()">
                </VIconButton>
              </div>
              <div class="column is-12">
                <DataTable v-model:filters="filtersDetail" :value="dataPopUp" paginator :rows="5" dataKey="id"
                  filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 100]"
                  :globalFilterFields="['namaaccount', 'keteranganlainnya']" :class="`p-datatable-small`" :size="'small'">
                  <template #header>
                    <div class="columns is-multiline">
                      <div class="column is-3 is-offset-9">
                        <VField>
                          <VControl icon="feather:search">
                            <input v-model="filtersDetail['global'].value" type="text" class="input is-rounded"
                              placeholder="Search" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </template>
                  <template #empty style="text-align: center;"> No data found. </template>
                  <Column :exportable="false" header="#" style="width:40px">
                    <template #body="slotProps">
                      <VIconButton type="button" icon="pi pi-trash" class="mr-3" color="danger" circle outlined raised
                        v-tooltip-prime="'Hapus'" @click="hapus(slotProps.data)">
                      </VIconButton>
                      <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                        v-tooltip-prime="'Edit'" @click="edit(slotProps.data)" :loading="slotProps.data.isLoading">
                      </VIconButton>
                    </template>
                  </Column>
                  <Column v-for="col in columnPopUp" :field="col.field" :header="col.title" :style="'width:' + col.width">
                    <template #body="slotProps">
                      <span v-if="col.tag == undefined">{{ col.template != undefined ?
                        H.formatRupiah(slotProps.data[col.field], '')
                        : slotProps.data[col.field] }}</span>
                      <span v-else>
                        <VTag class="mr-1 mb-1" :color="'success'" :label="slotProps.data[col.field]" />
                      </span>
                    </template>
                  </Column>
                  <ColumnGroup type="footer">
                    <Row>
                      <Column :footer="'Terdapat ' + dataPopUp.length + ' data.'" :colspan="columnPopUp.length + 1" />
                    </Row>
                  </ColumnGroup>
                </DataTable>
              </div>
            </div>
            <div class="columns is-multiline mb-2">
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status info">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL DEBIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlDebet,
                      'Rp.')
                  }}</small>
                </VCardCustom>
              </div>
              <div class="column is-6">
                <VCardCustom :style="'padding:5px 25px'">
                  <div class="label-status danger">
                    <i aria-hidden="true" class="fas fa-circle"></i>
                    <span class="ml-1">TOTAL KREDIT</span>
                  </div>
                  <small class="text-bold-custom">{{
                    H.formatRp(item.ttlKredit,
                      'Rp.')
                  }}</small>

                </VCardCustom>
              </div>
            </div>

          </VCard>
        </div>
      </div>
      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100 " light dark-outlined @click="modalJurnalEntry = false">
          Tutup
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
          class="ml-2" @click="SimpanPopUp()"> Simpan
        </VButton>
      </template>
    </Dialog>
  </section>
</template>
<script setup lang="ts">
import { ref, computed, reactive, watch } from 'vue'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Sidebar from 'primevue/sidebar';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import { useRoute, useRouter } from 'vue-router'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ProgressBar from 'primevue/progressbar';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import { FilterMatchMode } from 'primevue/api'
import { useApi } from '/@src/composable/useApi'
import Panel from 'primevue/panel';
import Dialog from 'primevue/dialog';
import Badge from 'primevue/badge';
import FileUpload from 'primevue/fileupload';
import Calendar from 'primevue/calendar';
import moment from 'moment';
import sleep from '/@src/utils/sleep'
import AutoComplete from 'primevue/autocomplete';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button';
const title = 'Jurnal'
useHead({
  title: title + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const jmlFilter: any = ref(0)
const isLoadingUpload: any = ref(false)
const totalSize = ref(0);
const totalSizePercent = ref(0);
const confirm = useConfirm();
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const modalJurnal: any = ref(false)
const dataPOsting: any = ref([])
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const filtersDetail = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataDetailJurnal: any = ref([])
const dataPopUp: any = ref([])
const data2: any = ref([])
const isLoadingpop: any = ref(false)
const d_Akun: any = ref([])
const d_KelompokPasien: any = ref([])
const d_Departemen: any = ref([])
const d_Ruangan: any = ref([])
const d_Carabayar: any = ref([])
const d_DetailJenis: any = ref([])
const d_JenisJurnal: any = ref([])
const modalJurnalPost: any = ref(false)
const isPostingJurnal: any = ref(false)
const valueProgress: any = ref(0)
const dataExcel: any = ref({})
const modalJurnalEntry: any = ref(false)
const item: any = reactive({
  qFilterTgl: [
    new Date(),
    new Date()
  ],
  ttlDebet: 0,
  ttlKredit: 0

})
const currentPage: any = ref({
  limit: 20
})

const columnTrans: any = [
  {
    "field": "tgl",
    "title": "Tanggal",
    "width": "40px",
  },
  {
    "field": "nojurnal",
    "title": "No Jurnal",
    "width": "50px",
  },
  {
    "field": "kelompok",
    "title": "Desk Jurnal",
    "width": "150px"
  },
  {
    "field": "debet",
    "title": "Debit",
    "width": "80px",
    "template": "<span class='style-right'>{{formatRupiah('#: debet #', '')}}</span>"
  },
  {
    "field": "kredit",
    "title": "Kredit",
    "width": "80px",
    "template": "<span class='style-right'>{{formatRupiah('#: kredit #', '')}}</span>"
  },
  {
    "field": "posted",
    "title": "Posted",
    "width": "50px",
    "tag": true
  }

];

const columnPopUp: any = [
  {
    "field": "no",
    "title": "No",
    "width": "20px"
  },
  {
    "field": "noaccount",
    "title": "Kode",
    "width": "60px"
  },
  {
    "field": "namaaccount",
    "title": "Perkiraan",
    "width": "130px"
  },
  {
    "field": "keteranganlainnya",
    "title": "Keterangan",
    "width": "100px"
  },
  {
    "field": "hargasatuand",
    "title": "Debit",
    "width": "70px",
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template: "<span class='style-right'>{{formatRupiah('#: hargasatuand #', '')}}</span>"
  },
  {
    "field": "hargasatuank",
    "title": "Kredit",
    "width": "70px",
    // "aggregates": ["sum"],
    // "footerTemplate": "#=sum#",
    // "groupFooterTemplate": "#=sum#",
    template: "<span class='style-right'>{{formatRupiah('#: hargasatuank #', '')}}</span>"
  }
]


const loadDetail = async () => {

}
const fetchData = async () => {
  let limit: any = currentPage.value.limit
  let page: any = route.query.page ? route.query.page : 1

  let dari = '', sampai = '', search = ''

  if (item.filter) {
    search = item.filter
  }
  if (item.qFilterTgl[0]) {
    dari = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 00:00:00')
  }
  if (item.qFilterTgl[1]) {
    sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD 23:59:59')
  } else {
    sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD 23:59:59')
  }
  item.ttlDebet = 0
  item.ttlKredit = 0

  isLoading.value = true
  const response = await useApi().get(
    '/akuntansi/get-data-jurnal-umum-2018?tglAwal=' + dari
    + '&tglAkhir=' + sampai
    + '&keterangan=' + search
  )

  isLoading.value = false
  countTotalD(response)

  dataSource.value = response

  let c_set = {
    0: dari,
    1: sampai,
  }
  H.cacheHelper().set('c_jurnal', c_set);

}
const countTotalD = (response: any) => {
  let debetX: any = 0
  let kreditX: any = 0
  for (var i = response.length - 1; i >= 0; i--) {
    debetX = parseFloat(debetX) + parseFloat(response[i].debet)
    kreditX = parseFloat(kreditX) + parseFloat(response[i].kredit)
  }
  debetX = parseFloat(debetX).toFixed(2);
  kreditX = parseFloat(kreditX).toFixed(2);

  item.ttlDebetGRID = debetX;
  item.ttlKreditGRID = kreditX;

}
const countTotal = (response: any) => {
  let debetX: any = 0
  let kreditX: any = 0
  for (var i = response.length - 1; i >= 0; i--) {
    debetX = parseFloat(debetX) + parseFloat(response[i].hargasatuand)
    kreditX = parseFloat(kreditX) + parseFloat(response[i].hargasatuank)
  }
  item.ttlDebet = debetX
  item.ttlKredit = kreditX

}
const terapkanFilter = () => {
  fetchData()
  modalFilter.value = false
}
const DetailJurnal = async (dataSelected: any) => {
  dataSelected.isLoading = true
  item.srcPerkiraan = undefined;
  item.nojurnal = dataSelected.nojurnal
  item.tanggal = dataSelected.tgl
  item.deskripsi = dataSelected.kelompok
  dataPopUp.value = []
  const dat = await useApi().get(
    "/akuntansi/get-data-detail-jurnal?nojurnal=" + dataSelected.nojurnal
  )
  dataSelected.isLoading = false

  dataDetailJurnal.value = dat
  countTotal(dat)
  for (var i = dat.length - 1; i >= 0; i--) {
    dat[i].no = i + 1

  }
  dataPopUp.value = dat
  modalJurnal.value = true

}
const PostingJurnal = async (dataSelected: any) => {
  dataSelected.isLoading2 = true
  item.nojurnal = dataSelected.nojurnal
  item.tanggal = dataSelected.tgl
  item.deskripsi = dataSelected.kelompok
  item.posted = dataSelected.posted
  dataPopUp.value = []
  const dat = await useApi().get("/akuntansi/get-data-detail-jurnal-posting?nojurnal=" + dataSelected.nojurnal)
  dataSelected.isLoading2 = false


  for (var i = dat.length - 1; i >= 0; i--) {
    dat[i].no = i + 1
  }
  countTotal(dat)

  dataPOsting.value = dat
  dataPopUp.value = dat;
  modalJurnalPost.value = true
}
const savePostingJurnal = () => {
  let objSave: any =
  {
    nojurnal: item.nojurnal,
    keteranganlainnya: item.deskripsi,
    tglbuktitransaksi: H.formatDate(item.tanggal, 'DD-MMM-YYYY 00:00'),
    data: dataPOsting.value
  }
  isPostingJurnal.value = true
  useApi().post(
    `/akuntansi/save-posting-jurnalv1`, objSave).then((response: any) => {
      isPostingJurnal.value = false
      if (response.data != '') {
        for (let x = 0; x < dataSource.value.length; x++) {
          const element = dataSource.value[x];
          if (element.nojurnal == item.nojurnal)
            element.posted = response.data.noposting
        }
      }
    }).catch((e) => {
      isPostingJurnal.value = false
    })

}
const saveUnPostingJurnal = (dataSelected: any) => {
  dataSelected.isLoading3 = true
  var objSave =
  {
    nojurnal: dataSelected.nojurnal
  }

  useApi().post(
    `/akuntansi/save-unposting-jurnalv1`, objSave).then((response: any) => {
      dataSelected.isLoading3 = false

      for (let x = 0; x < dataSource.value.length; x++) {
        const element = dataSource.value[x];
        if (element.nojurnal == dataSelected.nojurnal)
          element.posted = null
      }

    }).catch((e) => {
      dataSelected.isLoading3 = false
    })
}
const perbaikanJurnal2 = async (dataSelected: any) => {

  if (dataSelected.posted == null) {
    dataSelected.isLoading4 = true
    item.nojurnal = dataSelected.nojurnal
    item.tanggal = dataSelected.tgl
    var angka = 0
    item.notif = 'Perbaiki Jurnal :  '
    var objSave: any =
    {
      nojurnal: item.nojurnal
    }
    valueProgress.value = 0;
    let n = 0
    const dat = await useApi().post("/akuntansi/save-bengkel-jurnal", objSave)
    angka = angka + 1
    n = (0 + 1) * 100 / 7
    valueProgress.value = n.toFixed(2);
    // item.notif1 = item.notif1 + angka +'/9. ' + 'count perbaikan = ' + e.data.data + ', '
    // if (e.data.data > 0) {
    //     item.notif = item.notif + angka +'. X , '
    // }else{
    item.notif = item.notif + angka + '. √ , '
    // }


    objSave = {
      nojurnal: item.nojurnal,
      tglAwal: moment(item.tanggal).format('YYYY-MM-DD 00:00:00'),
      tglAkhir: moment(item.tanggal).format('YYYY-MM-DD 23:59:59')
    }
    const e = await useApi().post("/akuntansi/save-hapus-double-jurnal", objSave)
    angka = angka + 1
    n = (1 + 1) * 100 / 7
    valueProgress.value = n.toFixed(2);
    // item.notif2 = item.notif2  + angka +'/9. ' + 'count double = ' + e.data.data + ', '
    if (e.data > 0) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }

    const data = await useApi().post('/general/save-jurnal-pelayananpasien_t', objSave)
    angka = angka + 1
    n = (2 + 1) * 100 / 7
    valueProgress.value = n.toFixed(2);
    // item.notif3 = item.notif3 + angka +'/9. ' + 'count Jurnal = ' + data.data.count + ', '
    if (data.data.count == 1000) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }
    angka = angka + 1
    if (data.data.countob == 100) {
      // item.notif4 = item.notif4 + angka +'/9. ' + 'count adj = ' + data.data.count2 + ', '
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }
    angka = angka + 1
    if (data.data.countnl == 100) {
      item.notif = item.notif + angka + '. X , '
    } else {
      // item.notif5 = item.notif5 + angka +'/9. ' + 'count OB = ' + data.data.count3 + ', '
      item.notif = item.notif + angka + '. √ , '
    }

    const data2 = await useApi().post('/general/save-jurnal-pembayaran_tagihan', objSave)
    n = (3 + 1) * 100 / 7
    valueProgress.value = n.toFixed(2);
    angka = angka + 1
    // item.notif6 = item.notif6 + angka +'/9. ' + 'count Penerimaan = ' + data.data.count + ', '
    if (data2.count == 200) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }
    angka = angka + 1
    // item.notif = item.notif8 + angka +'/9. ' + 'count deposit = ' + data.data.countDeposit  + ', '
    if (data2.countDeposit == 50) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }
    const data3 = await useApi().post('/general/save-jurnal-verifikasi_tarek', objSave)
    n = (4 + 1) * 100 / 7
    valueProgress.value = n.toFixed(2);
    angka = angka + 1
    // item.notif7 = item.notif7 + angka +'/9. ' + 'count verif = ' + data.data.count  + ', '
    if (data3.count == 1000) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }
    angka = angka + 1
    // item.notif = item.notif9 + angka +'/9. ' + 'count diskon = ' + data.data.countDiskon   + ', '
    if (data3.countDiskon == 100) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ . '
    }
    const data4 = await useApi().post('/general/save-jurnal-penerimaan-barang', objSave)
    n = (5 + 1) * 100 / 7
    valueProgress.value = n.toFixed(2);
    angka = angka + 1
    // item.notif6 = item.notif6 + angka +'/9. ' + 'count Penerimaan = ' + data.data.count + ', '
    if (data4.count == 100) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }
    const data5 = await useApi().post('/general/save-jurnal-beban_pelayanan', objSave)
    n = (6 + 1) * 100 / 7
    valueProgress.value = n.toFixed(2);
    angka = angka + 1
    // item.notif6 = item.notif6 + angka +'/9. ' + 'count Penerimaan = ' + data.data.count + ', '
    if (data5.count == 1000) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }

    angka = angka + 1
    if (data5.countppn == 1000) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }

    angka = angka + 1
    if (data5.countbebanOB == 100) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }

    angka = angka + 1
    if (data5.countbebanPSR == 100) {
      item.notif = item.notif + angka + '. X , '
    } else {
      item.notif = item.notif + angka + '. √ , '
    }
    dataSelected.isLoading4 = false
    fetchData()
    await sleep(2000)
    valueProgress.value = 0
  } else {
    H.alert('error', "Sudah Posting !")
    return;
  }

}
const entryJurnal = () => {

  item.nojurnal = '-'
  let sampai = ''
  let nows = H.formatDate(new Date(), 'HH:mm')
  if (item.qFilterTgl[1]) {
    sampai = H.formatDate(item.qFilterTgl[1], 'YYYY-MM-DD ' + nows)
  } else {
    sampai = H.formatDate(item.qFilterTgl[0], 'YYYY-MM-DD ' + nows)
  }
  item.tglEntry = new Date(sampai)
  item.deskripsi = ''

  item.kdAkun = ""
  item.debet = "0"
  item.kredit = "0"
  item.keterangan = ""
  item.ttlDebet = 0
  item.ttlKredit = 0
  data2.value = []
  dataPopUp.value = []

  modalJurnalEntry.value = true
}
const editJurnal = async (dataSelected: any) => {
  if (dataSelected.posted == null) {
    item.no = undefined

    item.nojurnal = dataSelected.nojurnal
    item.tglEntry = new Date(dataSelected.tgl)
    item.deskripsi = dataSelected.kelompok

    item.kdAkun = ""
    item.debet = "0"
    item.kredit = "0"
    item.keterangan = ""
    dataPopUp.value = []
    dataSelected.isLoading5 = true
    const dat = await useApi().get(
      "/akuntansi/get-data-detail-jurnal?nojurnal=" + dataSelected.nojurnal
    )
    dataSelected.isLoading5 = false
    for (var i = dat.length - 1; i >= 0; i--) {
      dat[i].no = i + 1
      dat[i].namaaccount2 = dat[i].namaaccount.replace('--- ', "")

    }
    data2.value = dat


    countTotal(dat)
    dataPopUp.value = data2.value


    modalJurnalEntry.value = true
  } else {
    H.alert('error', "Sudah Posting !")
    return;
  }
}
const fetchAKun = async (filter: any) => {
  let query = ''
  if (filter) {
    query = filter.query
  }
  const response = await useApi().get(`/akuntansi/get-data-combo-coa-part?name= ${query}&limit=10`)
  d_Akun.value = response
}
const hapus = (e: any) => {

  var data = {};
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == e.no) {

      data2.value.splice(i, 1);

      dataPopUp.value = data2.value
    }
  }
  countTotal(data2.value)
  item.no = ""
  item.kdAkun = ""
  item.debet = 0
  item.kredit = 0
  item.keterangan = ""

}
const edit = async (dataSelectedPopUp: any) => {

  item.no = dataSelectedPopUp.no
  dataSelectedPopUp.isLoading = true
  await fetchAKun({ query: dataSelectedPopUp.noaccount })
  dataSelectedPopUp.isLoading = false
  for (let x = 0; x < d_Akun.value.length; x++) {
    const element = d_Akun.value[x];
    if (element.id == dataSelectedPopUp.accountid) {
      item.kdAkun = element
      item.namAkun = element
      break
    }
  }

  item.debet = dataSelectedPopUp.hargasatuand
  item.kredit = dataSelectedPopUp.hargasatuank
  item.keterangan = dataSelectedPopUp.keteranganlainnya
}
const batal = () => {
  item.no = undefined
  item.kdAkun = ""
  item.debet = 0
  item.kredit = 0
  item.keterangan = ""
  item.namAkun = ''
}
const tambah = () => {
  if (item.kdAkun == undefined) {
    H.alert("error", "Pilih Akun terlabih dahulu!")
    return;
  }
  // if (item.keterangan == undefined) {
  //   H.alert("error", "Isi Keterangan terlebih dahulu!!")
  //   return;
  // }

  var nomor = 0
  if (dataPopUp.value.length == 0) {
    nomor = 1
  } else {
    nomor = data2.value.length + 1
  }
  // disabledRuangan=true;'--- '
  var namaakun = item.kdAkun.namaaccount
  if (parseFloat(item.debet) == 0) {
    namaakun = '--- ' + item.kdAkun.namaaccount
  }
  var data: any = {};
  if (item.no != undefined) {
    for (var i = data2.value.length - 1; i >= 0; i--) {
      if (data2.value[i].no == item.no) {
        data.no = item.no
        data.accountid = item.kdAkun.id
        data.noaccount = item.kdAkun.noaccount
        data.namaaccount = namaakun
        data.namaaccount2 = item.kdAkun.namaaccount
        data.hargasatuand = item.debet
        data.hargasatuank = item.kredit
        data.keteranganlainnya = item.keterangan ? item.keterangan : ''

        data2.value[i] = data;
        dataPopUp.value = data2.value

        countTotal(data2.value)
      }

    }

  } else {
    data = {
      no: nomor,
      accountid: item.kdAkun.id,
      noaccount: item.kdAkun.noaccount,
      namaaccount: namaakun,
      namaaccount2: item.kdAkun.namaaccount,
      hargasatuand: item.debet,
      hargasatuank: item.kredit,
      keteranganlainnya: item.keterangan ? item.keterangan : ''
    }
    data2.value.push(data)
    dataPopUp.value = data2.value
    countTotal(data2.value)
  }

  item.kdAkun = ""
  item.namAkun = ""
  item.debet = "0"
  item.kredit = "0"
  item.no = undefined


}
const SimpanPopUp = async () => {
  if (item.deskripsi == undefined) {
    H.alert("error", "Isi Deskripsi terlebih dahulu!!")
    return
  }
  if (data2.value.length == 0) {
    H.alert("error", "Pilih Nama Akun terlebih dahulu!!")
    return
  }
  var strukresep = {
    nojurnal: item.nojurnal,
    tglentry: moment(item.tglEntry).format('YYYY-MM-DD hh:mm:ss'),
    deskripsi: item.deskripsi
  }
  var objSave =
  {
    head: strukresep,
    detail: data2.value
  }
  isLoading.value = true
  useApi().post(
    `/akuntansi/save-entry-jurnal`, objSave).then((response: any) => {
      isLoading.value = false
      modalJurnalEntry.value = false
      fetchData()
    }).catch((e) => {
      isLoading.value = false
    })


}
const deleteJurnal = (e: any) => {
  if (e.posted != null) {
    H.alert("error", "Sudah posting")
    return
  }
  e.isLoading6 = true
  var objSave =
  {
    head: e.nojurnal
  }
  useApi().post(
    `/akuntansi/save-hapus-data-jurnal`, objSave).then((response: any) => {
      e.isLoading6 = false

      fetchData()
    }).catch((e) => {
      e.isLoading6 = false
    })

}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      deleteJurnal(e)
    },
    reject: () => { },
  })
}
const exportExcel = () => {
  let judul = 'JURNAL'
  let column = []
  for (let x = 0; x < columnTrans.length; x++) {
    const element = columnTrans[x];
    column.push(element.title)
  }
  const worksheet = XLSX.utils.aoa_to_sheet([

    [judul],
    [],
    column,
    ...dataSource.value.map((e: any) => [
      e.tgl,
      e.nojurnal,
      e.kelompok,
      parseFloat(e.debet).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
      parseFloat(e.kredit).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"),
      e.posted,
      '',
    ]),
    [],
    ['Total Debit :', 'Rp. ' + parseFloat(item.ttlDebetGRID).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")],
    ['Total Kredit :', 'Rp. ' + parseFloat(item.ttlKreditGRID).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,")],

  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 20 },
    { wch: 25 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
  ];
  worksheet['!cols'] = columnWidths;
  const cellRef = XLSX.utils.encode_cell({ r: 0, c: 0 });
  worksheet[cellRef] = { v: judul, s: { alignment: { horizontal: 'center', vertical: 'center' } } };

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };
  worksheet['!merges'] = [mergeTitle, mergeSubtitle1];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  H.saveAsExcelFile(excelBuffer, 'jurnal');
}
const downloadTemplate = () => {
  window.open(import.meta.env.VITE_API_BASE_URL + 'akuntansi/template-excel?token=' + useUserSession().token, '_blank');
  //  H.openFile('import/akuntansi/format_import_jurnal.xlsx');
  // window.open(import.meta.env.VITE_API_BASE_URL + 'sysadmin/master-harga-netto-produk-by-kelas-download?token=' + useUserSession().token, '_blank');
}
const onSelectedFiles = async (filez: any) => {

  const file = filez
    .files[filez
      .files.length - 1];

  if (file.type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
    H.alert('error', 'File yang diizinkan dalam bentuk format Excel.')
    return;
  }
  if (file) {

    const reader = new FileReader();

    reader.onload = (e: any) => {
      const bstr = e.target.result;
      const wb = XLSX.read(bstr, { type: 'binary' });
      const wsname = wb.SheetNames[0];
      const ws = wb.Sheets[wsname];
      const data = XLSX.utils.sheet_to_json(ws, { header: 1 });
      dataExcel.value = []

      const header: any = data[0];
      const dataArray = data.slice(1);

      const resultArray = dataArray.map((innerArray: any) => {
        const obj: any = {};
        header.forEach((key: any, index: any) => {
          obj[key] = innerArray[index];
        });
        return obj;
      });


      dataExcel.value = resultArray
    }
    reader.readAsBinaryString(file);
  }
}


const onRemoveTemplatingFile = (file: any, removeFileCallback: any, index: any) => {
  removeFileCallback(index);
  totalSize.value -= parseInt(formatSize(file.size));
  totalSizePercent.value = totalSize.value / 10;

  // dataSource.value = []
  valueProgress.value = 0
};
const onTemplatedUpload = (e: any) => {

}

const formatSize = (bytes: any) => {
  if (bytes === 0) return "0 B";
  const k = 1024;
  const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};
const uploadEvent = (callback: any) => {
  totalSizePercent.value = totalSize.value / 10;
  // callback()
  isLoadingUpload.value = true
  useApi().post(
    `/akuntansi/import-jurnal-manual-excel`, { data: dataExcel.value }).then((response: any) => {
      isLoadingUpload.value = false

      fetchData()
    }).catch((e) => {
      isLoadingUpload.value = false
    })
};
const perbaikanJurnal = async () => {
  if (item.posted == null) {
    isLoadingpop.value = true
    var angka = 0
    item.notif = ''
    var objSave: any =
    {
      nojurnal: item.nojurnal
    }
    let n = 0
    const dat = await useApi().post("/akuntansi/save-bengkel-jurnal", objSave)
    angka = angka + 1
    // item.notif = angka +'/5'
    item.notif = item.notif + angka + '/9. ' + 'count perbaikan = ' + dat.data + ', '
    n = (0 + 1) * 100 / 5
    valueProgress.value = n.toFixed(2);

    objSave = {
      nojurnal: item.nojurnal,
      tglAwal: moment(item.tanggal).format('YYYY-MM-DD 00:00:00'),
      tglAkhir: moment(item.tanggal).format('YYYY-MM-DD 23:59:59')
    }
    const e = await useApi().post("/akuntansi/save-hapus-double-jurnal", objSave)
    angka = angka + 1
    n = (1 + 1) * 100 / 5
    valueProgress.value = n.toFixed(2);
    item.notif = item.notif + angka + '/9. ' + 'count double = ' + e.data + ', '

    const data = await useApi().post('/general/save-jurnal-pelayananpasien_t', objSave)
    angka = angka + 1
    // item.notif = angka +'/5'
    item.notif = item.notif + angka + '/9. ' + 'count Jurnal = ' + data.count + ', '
    angka = angka + 1
    // item.notif = angka +'/5'
    item.notif = item.notif + angka + '/9. ' + 'count adj = ' + data.count2 + ', '
    angka = angka + 1
    // item.notif = angka +'/5'
    item.notif = item.notif + angka + '/9. ' + 'count OB = ' + data.count3 + ', '

    const data2 = await useApi().post('/general/save-jurnal-pembayaran_tagihan', objSave)
    n = (3 + 1) * 100 / 5
    valueProgress.value = n.toFixed(2);
    angka = angka + 1
    // item.notif = angka +'/5'
    item.notif = item.notif + angka + '/9. ' + 'count Penerimaan = ' + data2.count + ', '

    const data3 = await useApi().post('/general/save-jurnal-verifikasi_tarek', objSave)
    n = (4 + 1) * 100 / 5
    valueProgress.value = n.toFixed(2);
    angka = angka + 1
    // item.notif = angka +'/5'
    item.notif = item.notif + angka + '/9. ' + 'count verif = ' + data3.count + ', '
    angka = angka + 1
    item.notif = item.notif + angka + '/9. ' + 'count deposit = ' + data3.countDeposit + ', '
    angka = angka + 1
    item.notif = item.notif + angka + '/9. ' + 'count diskon = ' + data3.countDiskon + ', '
    fetchData()
    isLoadingpop.value = false
    await sleep(2000)
    valueProgress.value = 0
  } else {
    H.alert('error', "Sudah Posting !")
    return;
  }

}
const cetakPostingJurnal = ()=>{

}
const cetakPostingJurnalDetail = ()=>{

}
const cetakPostingJurnalDetailSelisih = ()=>{

}
let c = H.cacheHelper().get('c_jurnal');
if (c != undefined) {
  item.qFilterTgl[0] = new Date(c[0]);
  item.qFilterTgl[1] = new Date(c[1]);
}
fetchData()

</script>
<style lang="scss"></style>
