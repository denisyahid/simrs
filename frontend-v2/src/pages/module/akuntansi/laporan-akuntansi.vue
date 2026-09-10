<template>
    <section>

        <ConfirmDialog />
        <TabView class="tabview-custom mt-3" :scrollable="true" @tab-click="klikTab3($event)">
          <TabPanel>
            <template #header>
              <span>Detail </span>
            </template>
              <div class="columns is-multiline">
              <div class="column is-12">
                  <VCard style="padding-bottom: 0px">
                      <div class="column c-title-x">
                          <h3 class="title is-5 mb-2 mr-1">Laporan Laba / Rugi</h3>
                      </div>
                      <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-9">
                              </div>
                              <div class="column is-2 is-pulled-right">
                                  <VField label="Periode">
                                      <VControl class="prime-auto">
                                          <Calendar inputId="range" v-model="item.bulan" :manualInput="false"
                                              class="w-100 mb-4 " :showIcon="true" view="month" dateFormat="MM-yy" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-1 mt-5 ">
                                  <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                      @click="fetchData()" :loading="isLoading">
                                  </VIconButton>
                              </div>
                              <div class="column is-12 mt-5-min">
                                  <VCard class="card-round-1">
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource, 'labarugi')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF()">
                                                          Export PDF
                                                      </VButton>

                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable ><template #body="slotProps">
                                            <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                          </template>
                                        </Column>
                                          <Column field="debet" :header="'Debit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.debet, '') }}
                                              </template>
                                          </Column>
                                          <Column field="kredit" :header="'Kredit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column>
                                          <ColumnGroup type="footer">
                                              <Row>
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.pendapatan, 'Pendapatan : Rp. ')" style="text-align:right"/>
                                                  <Column :footer="H.formatRupiah(item.beban, 'Beban : Rp. ')" style="text-align:right"/>

                                              </Row>
                                              <Row>
                                                  <Column
                                                      :footer="'Laba /Rugi Bulan ' + (H.formatDate(item.bulan, 'MMMM YYYY'))" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.labaRugi, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                          </div>
                      </div>
                  </VCard>
              </div>
              <div class="column is-12">
                  <VCard style="padding-bottom: 0px">
                      <div class="column c-title-x">
                          <h3 class="title is-5 mb-2 mr-1">Laporan Perubahan Modal</h3>
                      </div>
                      <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-12 mt-5-min">
                                  <VCard class="card-round-2">
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource2" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource2, 'perubahanmodal')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF2()">
                                                          Export PDF
                                                      </VButton>
                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable >
                                            <template #body="slotProps">
                                              <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                            </template>
                                          </Column>
                                          <Column field="debet" :header="'Debit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.debet, '') }}
                                              </template>
                                          </Column>
                                          <Column field="kredit" :header="'Kredit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column>
                                          <ColumnGroup type="footer">
                                              <Row>
                                                  <Column :footer="'Saldo'" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.saldoPerModal, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                              <Row>
                                                  <Column
                                                      :footer="'Laba /Rugi Bulan ' + (H.formatDate(item.bulan, 'MMMM YYYY'))" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.labaRugi, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                              <Row>
                                                  <Column :footer="'Saldo Akhir per ' + item.tglAkhirbulanTahun" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.saldoAkhir, 'Rp. ')" style="text-align:right" />
                                              </Row>
                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                          </div>
                      </div>
                  </VCard>
              </div>
              <div class="column is-12">
                  <VCard style="padding-bottom: 0px">
                      <div class="column c-title-x">
                          <h3 class="title is-5 mb-2 mr-1">Neraca</h3>
                      </div>
                      <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-12 mt-5-min">
                                  <VCard class="card-round-3">
                                      <h3 class="title is-5 mb-2 mr-1">AKTIVA</h3>
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource3" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource3, 'aktiva')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF3()">
                                                          Export PDF
                                                      </VButton>
                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable >
                                            <template #body="slotProps">
                                              <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                            </template>
                                          </Column>
                                          <Column field="debet" :header="'Debit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.debet, '') }}
                                              </template>
                                          </Column>
                                          <Column field="kredit" :header="'Kredit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column>
                                          <ColumnGroup type="footer">
                                              <Row>
                                                  <Column :footer="'Total AKTIVA'" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.SaldoAktiva, 'Rp. ')" style="text-align:right" />
                                              </Row>

                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                              <div class="column is-12 mt-2-min">
                                  <VCard class="card-round-3">
                                      <h3 class="title is-5 mb-2 mr-1">PASIVA</h3>
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource4" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource4, 'pasiva')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF4()">
                                                          Export PDF
                                                      </VButton>
                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable >
                                            <template #body="slotProps">
                                            <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                          </template>
                                        </Column>
                                          <Column field="debet" :header="'Debit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.debet, '') }}
                                              </template>
                                          </Column>
                                          <Column field="kredit" :header="'Kredit'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column>
                                          <ColumnGroup type="footer">
                                              <Row>
                                                  <Column :footer="'SubTotal'" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.saldoPasiva, 'Rp. ')" style="text-align:right" />
                                              </Row>
                                              <Row>
                                                  <Column
                                                      :footer="'Laba /Rugi Bulan ' + (H.formatDate(item.bulan, 'MMMM YYYY'))" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.labaRugi, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                              <Row>
                                                  <Column :footer="'Total PASIVA'" />
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.totalPasiva, 'Rp. ')"  style="text-align:right"/>
                                              </Row>
                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                          </div>
                      </div>
                  </VCard>
              </div>
              </div>
          </TabPanel>
          <TabPanel>
            <template #header>
              <span>Rekap </span>
            </template>
            <div class="columns is-multiline">
              <div class="column is-12">
                  <VCard style="padding-bottom: 0px">
                      <div class="column c-title-x">
                          <h3 class="title is-5 mb-2 mr-1">Laporan Laba / Rugi</h3>
                      </div>
                      <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-9">
                              </div>
                              <div class="column is-2 is-pulled-right">
                                  <VField label="Periode">
                                      <VControl class="prime-auto">
                                          <Calendar inputId="range" v-model="item.bulan" :manualInput="false"
                                              class="w-100 mb-4 " :showIcon="true" view="month" dateFormat="MM-yy" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-1 mt-5 ">
                                  <VIconButton type="button" color="success" circle raised icon="fas fa-search"
                                      @click="fetchData()" :loading="isLoading">
                                  </VIconButton>
                              </div>
                              <div class="column is-12 mt-5-min">
                                  <VCard class="card-round-1">
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource, 'labarugi')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF()">
                                                          Export PDF
                                                      </VButton>

                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable >
                                            <template #body="slotProps">
                                              <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                            </template>
                                          </Column>
                                          <Column field="debet" :header="'Total'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah( slotProps.data.kredit - slotProps.data.debet , '') }}
                                              </template>
                                          </Column>
                                          <!-- <Column field="kredit" :header="'Kredit'" sortable>
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column> -->
                                          <ColumnGroup type="footer">
                                              <!-- <Row>
                                                  <Column :footer="''" :colspan="2" />
                                                  <Column :footer="H.formatRupiah(item.pendapatan, 'Pendapatan : Rp. ')" />
                                                  <Column :footer="H.formatRupiah(item.beban, 'Beban : Rp. ')" />

                                              </Row> -->
                                              <Row>
                                                  <Column
                                                      :footer="'Laba /Rugi Bulan ' + (H.formatDate(item.bulan, 'MMMM YYYY'))" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.labaRugi, 'Rp. ')" style="text-align:right" />
                                              </Row>
                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                          </div>
                      </div>
                  </VCard>
              </div>
              <div class="column is-12">
                  <VCard style="padding-bottom: 0px">
                      <div class="column c-title-x">
                          <h3 class="title is-5 mb-2 mr-1">Laporan Perubahan Modal</h3>
                      </div>
                      <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-12 mt-5-min">
                                  <VCard class="card-round-2">
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource2" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource2, 'perubahanmodal')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF2()">
                                                          Export PDF
                                                      </VButton>
                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable >
                                            <template #body="slotProps">
                                              <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                            </template>
                                        </Column>
                                          <Column field="debet" :header="'Total'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.debet - slotProps.data.kredit, '') }}
                                              </template>
                                          </Column>
                                          <!-- <Column field="kredit" :header="'Kredit'" sortable>
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column> -->
                                          <ColumnGroup type="footer">
                                              <Row>
                                                  <Column :footer="'Saldo'" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.saldoPerModal, 'Rp. ')"  style="text-align:right"/>
                                              </Row>
                                              <Row>
                                                  <Column
                                                      :footer="'Laba /Rugi Bulan ' + (H.formatDate(item.bulan, 'MMMM YYYY'))" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.labaRugi, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                              <Row>
                                                  <Column :footer="'Saldo Akhir per ' + item.tglAkhirbulanTahun" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.saldoAkhir, 'Rp. ')" style="text-align:right" />
                                              </Row>
                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                          </div>
                      </div>
                  </VCard>
              </div>
              <div class="column is-12">
                  <VCard style="padding-bottom: 0px">
                      <div class="column c-title-x">
                          <h3 class="title is-5 mb-2 mr-1">Neraca</h3>
                      </div>
                      <div class="column is-12">
                          <div class="columns is-multiline">
                              <div class="column is-12 mt-5-min">
                                  <VCard class="card-round-3">
                                      <h3 class="title is-5 mb-2 mr-1">AKTIVA</h3>
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource3" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource3, 'aktiva')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF3()">
                                                          Export PDF
                                                      </VButton>
                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable >
                                            <template #body="slotProps">
                                              <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                            </template>
                                          </Column>
                                          <Column field="debet" :header="'Total'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.debet - slotProps.data.kredit, '') }}
                                              </template>
                                          </Column>
                                          <!-- <Column field="kredit" :header="'Kredit'" sortable>
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column> -->
                                          <ColumnGroup type="footer">
                                              <Row>
                                                  <Column :footer="'Total AKTIVA'" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.SaldoAktiva, 'Rp. ')" style="text-align:right" />
                                              </Row>

                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                              <div class="column is-12 mt-2-min">
                                  <VCard class="card-round-3">
                                      <h3 class="title is-5 mb-2 mr-1">PASIVA</h3>
                                      <DataTable v-model:filters="filtersTrans" :value="dataSource4" paginator :rows="50"
                                          dataKey="id" filterDisplay="row" :rowsPerPageOptions="[5, 10, 25, 50, 100, 1000]"
                                          :globalFilterFields="['namamap', 'nomap']" :class="`p-datatable-small`">
                                          <template #header>
                                              <div class="columns is-multiline">
                                                  <div class="column is-5">
                                                      <VButton type="button" icon="pi pi-file-excel" class="mr-3"
                                                          color="solid" outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportExcel(dataSource4, 'pasiva')">
                                                          Export Excel
                                                      </VButton>
                                                      <VButton type="button" icon="pi pi-file-pdf" class="mr-3" color="solid"
                                                          outlined circle raised v-tooltip-prime="'Export'"
                                                          @click="exportPDF4()">
                                                          Export PDF
                                                      </VButton>
                                                  </div>
                                                  <div class="column is-3 is-offset-4">
                                                      <VField>
                                                          <VControl icon="feather:search">
                                                              <input v-model="filtersTrans['global'].value"
                                                                  v-on:keyup.enter="fetchData()" type="text"
                                                                  class="input is-rounded" placeholder="Search" />
                                                          </VControl>
                                                      </VField>
                                                  </div>
                                              </div>
                                          </template>
                                          <template #empty style="text-align: center;"> No data found. </template>
                                          <Column field="nomap" header="No" style="width: 15%" />
                                          <Column field="namamap" header="Uraian" sortable >
                                            <template #body="slotProps">
                                              <span v-html="replaceDashes(slotProps.data.namamap)"  ></span>
                                            </template>
                                          </Column>
                                          <Column field="debet" :header="'Total'" sortable style="text-align:right">
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.debet -slotProps.data.kredit, '') }}
                                              </template>
                                          </Column>
                                          <!-- <Column field="kredit" :header="'Kredit'" sortable>
                                              <template #body="slotProps">
                                                  {{ H.formatRupiah(slotProps.data.kredit, '') }}
                                              </template>
                                          </Column> -->
                                          <ColumnGroup type="footer">
                                              <Row>
                                                  <Column :footer="'SubTotal'" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.saldoPasiva, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                              <Row>
                                                  <Column
                                                      :footer="'Laba /Rugi Bulan ' + (H.formatDate(item.bulan, 'MMMM YYYY'))" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.labaRugi, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                              <Row>
                                                  <Column :footer="'Total PASIVA'" />
                                                  <Column :footer="''" :colspan="1" />
                                                  <Column :footer="H.formatRupiah(item.totalPasiva, 'Rp. ')" style="text-align:right"/>
                                              </Row>
                                          </ColumnGroup>
                                      </DataTable>
                                  </VCard>
                              </div>
                          </div>
                      </div>
                  </VCard>
              </div>
              </div>
          </TabPanel>
        </TabView>

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
import Divider from 'primevue/divider';
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as XLSX from "xlsx";
import { useUserSession } from '/@src/stores/userSession'
import Button from 'primevue/button';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
const title = 'Laporan Akuntansi'
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
const isLoading2: any = ref(false)
const isLoading3: any = ref(false)
const isLoading4: any = ref(false)
const route = useRoute()
const router = useRouter()
const dataSource: any = ref([])
const dataSource2: any = ref([])
const dataSource3: any = ref([])
const dataSource4: any = ref([])
const isClosing2: any = ref(false)
const isClosing: any = ref(false)
const filtersTrans = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const filtersDetail = ref({ global: { value: null, matchMode: FilterMatchMode.CONTAINS }, });
const dataDetailJurnal: any = ref([])
const dataPopUp: any = ref([])
const data2: any = ref([])
const isLoadingpop: any = ref(false)
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
    ttlKredit: 0,
    bulan: new Date()

})
const currentPage: any = ref({
    limit: 20
})
const tittlenow: any = ref('')
const tittlebefore: any = ref('')
const getLastDayOfMonth = (year, month) => {
    // Create a Date object set to the next month's first day
    let firstDayOfNextMonth = new Date(year, month, 1);

    // Subtract one day to get the last day of the current month
    let lastDayOfMonth = new Date(firstDayOfNextMonth - 1);

    return lastDayOfMonth.getDate();
}
const fetchData = async () => {

    let sDebetAkhir = 0
    let sKreditAkhir = 0

    let bulan = H.formatDate(item.bulan, "YYYY-MM")
    let tglAwal1 = bulan + "-01"
    let tglAkhir1 = bulan + "-" + getLastDayOfMonth(bulan.substr(0, 4), bulan.substr(5, 2))
    tittlebefore.value = bulan.substr(0, 4) - 1
    let tgltgl = tittlebefore.value + "01"

    await initLaba(tglAwal1, tglAkhir1, tgltgl);
    await initPerubahanModal(tglAwal1, tglAkhir1, tgltgl);
    await initNeracaAktiva(tglAwal1, tglAkhir1, tgltgl);
    await initNeracaPasiva(tglAwal1, tglAkhir1, tgltgl);

    let c_set = {
        0: bulan,
    }
    H.cacheHelper().set('c_lapakun', c_set);

}
const initLaba = async (tglAwal1, tglAkhir1, tgltgl) => {
    let level = "&namalaporan='4','5','6'"
    let sDebet = 0; let sKredit = 0; let sSaldo = 0;
    isLoading.value = true
    const dat = await hitAPI(tglAwal1, tglAkhir1, tgltgl, level, false)
    isLoading.value = false
    for (let i = 0; i < dat.length; i++) {
        dat[i].no = i + 1
        if (dat[i].type == 1) {
            sDebet = sDebet + parseFloat(dat[i].debet);
            sKredit = sKredit + parseFloat(dat[i].kredit);
            dat[i].debet = 0;
            dat[i].kredit = 0;
        }
    }
    item.pendapatan = sKredit
    item.beban = sDebet
    item.labaRugi = sKredit - sDebet
    item.labaRugiFloat = sKredit - sDebet
    dataSource.value = dat
}
const initPerubahanModal = async (tglAwal1, tglAkhir1, tgltgl) => {
    let level = "&namalaporan='3'"
    let sDebet = 0; let sKredit = 0; let sSaldo = 0;
    item.tglAkhirbulanTahun = tglAkhir1
    isLoading2.value = true
    const dat2 = await hitAPI(tglAwal1, tglAkhir1, tgltgl, level, false)
    isLoading2.value = false
    for (var i = 0; i < dat2.length; i++) {
        dat2[i].no = i + 1
        sDebet = sDebet + parseFloat(dat2[i].debet);
        sKredit = sKredit + parseFloat(dat2[i].kredit);
    }
    item.saldoPerModal = sKredit - sDebet;
    let saldoAkhir = sKredit - sDebet + item.labaRugiFloat
    item.saldoAkhir = saldoAkhir;
    dataSource2.value = dat2
}
const initNeracaAktiva = async (tglAwal1, tglAkhir1, tgltgl) => {
    let level = "&namalaporan='1'"
    let sDebet = 0; let sKredit = 0; let sSaldo = 0;
    isLoading3.value = true
    const dat3 = await hitAPI(tglAwal1, tglAkhir1, tgltgl, level, false)
    isLoading3.value = false
    for (var i = 0; i < dat3.length; i++) {
        dat3[i].no = i + 1

        sDebet = sDebet + parseFloat(dat3[i].debet);
        sKredit = sKredit + parseFloat(dat3[i].kredit);

        dat3[i].saldo = parseFloat(dat3[i].debet) - parseFloat(dat3[i].kredit);
        if (dat3[i].type == 1) {
            sSaldo = sSaldo + parseFloat(dat3[i].saldo);
            dat3[i].saldo = 0;
        }
    }
    item.SaldoAktiva = sSaldo
    dataSource3.value = dat3;
}
const initNeracaPasiva = async (tglAwal1, tglAkhir1, tgltgl) => {
    let level = "&namalaporan='2','3'"
    let sDebet = 0; let sKredit = 0; let sSaldo = 0;
    isLoading4.value = true
    const dat4 = await hitAPI(tglAwal1, tglAkhir1, tgltgl, level, false)
    isLoading4.value = false
    for (var i = 0; i < dat4.length; i++) {
        dat4[i].no = i + 1
        sDebet = sDebet + parseFloat(dat4[i].debet);
        sKredit = sKredit + parseFloat(dat4[i].kredit);
        dat4[i].saldo = parseFloat(dat4[i].kredit) - parseFloat(dat4[i].debet);
        if (dat4[i].type == 1) {
            sSaldo = sSaldo + parseFloat(dat4[i].saldo);
            if (dat4[i].noaccount == '21.000') {
                dat4[i].saldo = 0;
            }
        }
    }

    item.saldoPasiva = sSaldo
    let sTotalPasiva = sSaldo + item.labaRugiFloat
    item.totalPasiva = sTotalPasiva
    dataSource4.value = dat4;
}
const hitAPI = async (tglAwal1, tglAkhir1, tgltgl, level, cetak, judul) => {
    if (cetak) {
        let blnstring = H.formatDate(item.bulan, 'MMMM YYYY')
        H.printBlade('akuntansi/get-data-aruskas-revmar23?tglAwal=' + tglAwal1
            + '&tglAkhir=' + tglAkhir1
            + "&tgltgl=" + tgltgl
            + "&reportdisplay=aruskas" + level
            + "&cetak=" + cetak + "&blnstring=" + blnstring
            + "&judul=" + judul)
    } else {
        const response = await useApi().get(
            '/akuntansi/get-data-aruskas-revmar23?tglAwal=' + tglAwal1
            + '&tglAkhir=' + tglAkhir1
            + "&tgltgl=" + tgltgl
            + "&reportdisplay=aruskas" + level
            + "&cetak=" + cetak
        )
        return response
    }

}
const exportExcel = (data, filename) => {
    H.exportExcel(data, filename)
}

const exportPDF = () => {
    let level = "&namalaporan='4','5','6'"
    let bulan = H.formatDate(item.bulan, "YYYY-MM")
    let tglAwal1 = bulan + "-01"
    let tglAkhir1 = bulan + "-" + getLastDayOfMonth(bulan.substr(0, 4), bulan.substr(5, 2))
    tittlebefore.value = bulan.substr(0, 4) - 1
    let tgltgl = tittlebefore.value + "01"

    hitAPI(tglAwal1, tglAkhir1, tgltgl, level, true, 'LAPORAN OPERASIONAL / LABA - RUGI')

}
const exportPDF2 = () => {
    let level = "&namalaporan='3'"
    let bulan = H.formatDate(item.bulan, "YYYY-MM")
    let tglAwal1 = bulan + "-01"
    let tglAkhir1 = bulan + "-" + getLastDayOfMonth(bulan.substr(0, 4), bulan.substr(5, 2))
    tittlebefore.value = bulan.substr(0, 4) - 1
    let tgltgl = tittlebefore.value + "01"

    hitAPI(tglAwal1, tglAkhir1, tgltgl, level, true, 'LAPORAN PERUBAHAN MODAL')

}
const exportPDF3 = () => {
    let level = "&namalaporan='1'"
    let bulan = H.formatDate(item.bulan, "YYYY-MM")
    let tglAwal1 = bulan + "-01"
    let tglAkhir1 = bulan + "-" + getLastDayOfMonth(bulan.substr(0, 4), bulan.substr(5, 2))
    tittlebefore.value = bulan.substr(0, 4) - 1
    let tgltgl = tittlebefore.value + "01"

    hitAPI(tglAwal1, tglAkhir1, tgltgl, level, true, 'NERACA AKTIVA')

}
const exportPDF4 = () => {
    let level = "&namalaporan='2','3'"
    let bulan = H.formatDate(item.bulan, "YYYY-MM")
    let tglAwal1 = bulan + "-01"
    let tglAkhir1 = bulan + "-" + getLastDayOfMonth(bulan.substr(0, 4), bulan.substr(5, 2))
    tittlebefore.value = bulan.substr(0, 4) - 1
    let tgltgl = tittlebefore.value + "01"

    hitAPI(tglAwal1, tglAkhir1, tgltgl, level, true, 'NERACA PASIVA')

}
const replaceDashes = (text: any) => {
  return text.replace(/---/g, '&nbsp;&nbsp;&nbsp;')
}
let c = H.cacheHelper().get('c_lapakun');
if (c != undefined) {
    item.bulan[0] = new Date(c[0]);
}

fetchData()

</script>
<style lang="scss"></style>
