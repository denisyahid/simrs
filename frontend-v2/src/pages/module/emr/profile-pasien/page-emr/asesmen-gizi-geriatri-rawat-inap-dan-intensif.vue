<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
                            @simpan="simpan" @kembaliKeun="kembaliKeun" isHideST isHideCetak="true"></ButtonEmr>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <Fieldset :toggleable="true" legend="ANTROPOMETRI">
                            <div class="column is-12" v-for="(data) in Antropometri">
                                <h1>{{ data.title }}</h1>
                                <div class="columns is-multiline">
                                    <div class="column is-3" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>

                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle class="p-0" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true" legend="BIOKIMIA">
                            <!-- <div class="column is-one-quarter">
                                <VField> -->
                            <!-- <div class="columns is-mobile">
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.hemoglobin"
                                                    id="checkbox-hemoglobin-biometrik" />
                                                <label
                                                    for="checkbox-hemoglobin-biometrik"><span>Hemoglobin</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.urea"
                                                    id="checkbox-urea-biometrik" />
                                                <label for="checkbox-urea-biometrik"><span>Urea</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.kreatin"
                                                    id="checkbox-kreatin-biometrik" />
                                                <label for="checkbox-kreatin-biometrik"><span>Kreatinin</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.alt"
                                                    id="checkbox-alt-biometrik" />
                                                <label for="checkbox-alt-biometrik"><span>ALT / GPT</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.ast"
                                                    id="checkbox-ast-biometrik" />
                                                <label for="checkbox-ast-biometrik"><span>AST / GOT</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.gdp"
                                                    id="checkbox-gdp-biometrik" />
                                                <label for="checkbox-gdp-biometrik"><span>GDP</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.glukosa"
                                                    id="checkbox-glukosa-biomterik" />
                                                <label for="checkbox-glukosa-biomterik"><span>Glukosa 2JPP : 70 - 140
                                                        mg/dL</span></label>
                                            </VControl>
                                        </div>
                                        <br>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.biliburin"
                                                    id="checkbox-biliburin-biometrik" />
                                                <label for="checkbox-biliburin-biometrik"><span>Bilirubin Total :
                                                        0.3-1.2</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.kolesterol0"
                                                    id="checkbox-kolesterol-0-biometrik" />
                                                <label for="checkbox-kolesterol-0-biometrik"><span>Kolesterol total :
                                                        0.3-1.2</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.biliburindirect"
                                                    id="checkbox-biliburin-direct-biometrik" />
                                                <label for="checkbox-biliburin-direct-biometrik"><span>Bilirubin direct
                                                        &lt; 0.3</span></label>
                                            </VControl>
                                        </div>
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.Kolesterol200"
                                                    id="checkbox-kolesterol-200-biometrik" />
                                                <label for="checkbox-kolesterol-200-biometrik"><span>Kolesterol total:
                                                        &lt; 200</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.natrium"
                                                    id="checkbox-natrium-biometrik" />
                                                <label for="checkbox-natrium-biometrik"><span>Natrium (Na) : 136-145
                                                        mmol/L (*rendah/*tinggi)</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.kalium"
                                                    id="checkbox-kalium-biometrik" />
                                                <label for="checkbox-kalium-biometrik"><span>Kalium (K) : 3.50-5.10
                                                        mmol/L (*rendah/*tinggi)</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.clorida"
                                                    id="checkbox-clorida-ci-biomterik" />
                                                <label for="checkbox-clorida-ci-biomterik"><span>Clorida (CI) : 94-110
                                                        mmol/L (*rendah/*tinggi)</span></label>
                                            </VControl>
                                        </div>

                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.lain"
                                                    id="checkbox-lain-biomterik" />
                                                <label for="checkbox-lain-biomterik"><span>Lainnya...</span></label>
                                            </VControl>
                                        </div>
                                    </div> -->
                            <!-- </VField>
                            </div> -->
                            <div class="column is-12" v-for="(data) in BIOKIMIA">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox class="p-0" color="primary" circle
                                                    :true-value="detail.satuan" :label="detail.satuan"
                                                    v-model="input[detail.model]" />
                                            </VControl>
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input[detail.model_hasil]"
                                                            placeholder="Hasil..." />
                                                    </VControl>
                                                </div>
                                                <div class="column is-6">
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            v-model="input[detail.model_nm]"
                                                            placeholder="Nilai normal..." />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true" legend="Klinis/ fisik">
                            <div class="column is-12" v-for="(data) in KLINIS_FISIK">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true" legend="DIETARY/Riawayat gizi">
                            <div class="column is-12">
                            </div>
                            <div class="column is-one-quarter">
                                <h1>a.Polamakan</h1>
                                <br>
                                <VField>
                                    <p>Berapa kali pasien makan</p>
                                    <div class="columns is-mobile">
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.makan1"
                                                    id="checkbox-kurang-makan1" />
                                                <label for="checkbox-kurang-makan1"><span>0-1
                                                        kali</span></label>
                                            </VControl>
                                        </div>
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.makan2"
                                                    id="checkbox-kurang-makan2" />
                                                <label for="checkbox-kurang-makan2"><span>1-2
                                                        kali</span></label>
                                            </VControl>
                                        </div>
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.makan3"
                                                    id="checkbox-kurang-makan3" />
                                                <label for="checkbox-kurang-makan3"><span>2-3
                                                        kali</span></label>
                                            </VControl>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-one-quarter">
                                <p>Konsumsi bahan makanan spesifik untuk asupan protein: &lt; 1 porsi makanan
                                    sumber
                                    protein/produk susu (susu, keju, yogurt) sehari</p>
                                <VField>
                                    <div class="columns is-mobile">
                                        <!-- Checkbox for < 80% (kurang) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.bahanmakananya" value="ya"
                                                    id="checkbox-kurang-awal" />
                                                <label for="checkbox-kurang-awal"><span>ya </span></label>
                                            </VControl>
                                        </div>
                                        <!-- Checkbox for ≥ 80% (baik) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.bahanmakanantidak" value="tidak"
                                                    id="checkbox-baik-awal" />
                                                <label for="checkbox-baik-awal"><span>tidak</span></label>
                                            </VControl>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-one-quarter">
                                <p>&ge; 2 porsi kacang-kacangan/telur dalam seminggu</p>
                                <VField>
                                    <div class="columns is-mobile">
                                        <!-- Checkbox for < 80% (kurang) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.porsiya"
                                                    id="checkbox-kurang-porsiya" />
                                                <label for="checkbox-kurang-porsiya"><span>ya </span></label>
                                            </VControl>
                                        </div>
                                        <!-- Checkbox for ≥ 80% (baik) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.porsitidak"
                                                    id="checkbox-baik-porsitidak" />
                                                <label for="checkbox-baik-porsitidak"><span>tidak</span></label>
                                            </VControl>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-one-quarter">
                                <p>Daging ikan/unggas setiap hari</p>
                                <VField>
                                    <div class="columns is-mobile">
                                        <!-- Checkbox for < 80% (kurang) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.ikanya"
                                                    id="checkbox-kurang-ikanya" />
                                                <label for="checkbox-kurang-ikanya"><span>ya </span></label>
                                            </VControl>
                                        </div>
                                        <!-- Checkbox for ≥ 80% (baik) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.ikantidak"
                                                    id="checkbox-baik-ikantidak" />
                                                <label for="checkbox-baik-ikantidak"><span>tidak</span></label>
                                            </VControl>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-one-quarter">
                                <p>Konsumsi &ge; 2 porsi sayur atau buah setiap hari</p>
                                <VField>
                                    <div class="columns is-mobile">
                                        <!-- Checkbox for < 80% (kurang) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.sayurya"
                                                    id="checkbox-kurang-sayurya" />
                                                <label for="checkbox-kurang-sayurya"><span> Ya</span></label>
                                            </VControl>
                                        </div>
                                        <!-- Checkbox for ≥ 80% (baik) -->
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.sayurtidak"
                                                    id="checkbox-baik-sayurtidak" />
                                                <label for="checkbox-baik-sayurtidak"><span>
                                                        Tidak</span></label>
                                            </VControl>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-one-quarter">
                                <p>Berapa banyak cairan (air putih, jus, kopi,teh, susu) yang dikonsumsi
                                    perhari?</p>
                                <VField>
                                    <div class="columns is-mobile">
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.cairan3"
                                                    id="checkbox-tidak-ada-cairan3" />
                                                <label for="checkbox-tidak-ada-cairan3"><span> &lt; 3 cangkir
                                                    </span></label>
                                            </VControl>
                                        </div>
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.cairan35"
                                                    id="checkbox-telur-cairan35" />
                                                <label for="checkbox-telur-cairan35"><span> 3-5
                                                        cangkir</span></label>
                                            </VControl>
                                        </div>
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.cairan5"
                                                    id="checkbox-suspi-cairan5" />
                                                <label for="checkbox-suspi-cairan5"><span> &gt; 5 cangkir
                                                    </span></label>
                                            </VControl>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-one-quarter">
                                <h1>b.Cara pemberian makan</h1>
                                <br>
                                <VField>
                                    <div class="columns is-mobile">
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.tidakdapatmakan"
                                                    id="checkbox-kurang-tidakdapatmakan" />
                                                <label for="checkbox-kurang-tidakdapatmakan"><span>Tidak dapat
                                                        makan tanpa bantuan
                                                        orang lain</span></label>
                                            </VControl>
                                        </div>
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.makansendiri"
                                                    id="checkbox-kurang-makansendiri" />
                                                <label for="checkbox-kurang-makansendiri"><span>Makan sendiri
                                                        dengan beberapa
                                                        kesulitan</span></label>
                                            </VControl>
                                        </div>
                                        <div class="column is-half">
                                            <VControl expanded>
                                                <input type="checkbox" v-model="input.makansendirtanpa"
                                                    id="checkbox-kurang-makansendiritanpa" />
                                                <label for="checkbox-kurang-makansendiritanpa"><span>Makan
                                                        sendiri tanpa
                                                        kesulitan</span></label>
                                            </VControl>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-one-quarter">
                                <VField>
                                    <div class="nutrition-section">
                                        <h4>c. Asupan Makan</h4>
                                        <div class="form-group">
                                            <label>Asupan makan oral di rumah:</label>
                                            <div class="radio-group">
                                                <label><input type="radio" v-model="input.homeOralIntake"
                                                        value="<80%" /> &lt; 80% (kurang)</label>
                                                <label><input type="radio" v-model="input.homeOralIntake"
                                                        value="≥80%" /> ≥ 80% (baik)</label>
                                            </div>
                                        </div>

                                        <!-- Asupan makan awal di rumah sakit -->
                                        <div class="form-group">
                                            <label>Asupan makan awal di rumah sakit:</label>
                                            <div class="radio-group">
                                                <label><input type="radio" v-model="input.hospitalOralIntake"
                                                        value="<80%" /> &lt; 80% (kurang)</label>
                                                <label><input type="radio" v-model="input.hospitalOralIntake"
                                                        value="≥80%" /> ≥ 80% (baik)</label>
                                            </div>
                                        </div>

                                        <!-- Perubahan jenis/bentuk makanan -->
                                        <div class="form-group">
                                            <label>
                                                <input type="checkbox" v-model="input.foodChanges" />
                                                Ada perubahan jenis/bentuk makanan ke diet khusus:
                                            </label>
                                            <div v-if="input.foodChanges" class="special-diet-options">
                                                <label><input type="checkbox" v-model="input.bubur" value="bubur" />
                                                    Bubur</label>
                                                <label><input type="checkbox" v-model="input.saring" value="saring" />
                                                    Saring</label>
                                                <label><input type="checkbox" v-model="input.cair" value="cair" />
                                                    Cair</label>
                                                <label><input type="checkbox" v-model="input.susu" value="susu" />
                                                    Susu</label>
                                            </div>
                                        </div>
                                    </div>
                                </VField>
                            </div>
                            <div class="column is-12" v-for="(data) in pantangan">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12" v-for="(data) in alergi">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                            <div class="column is-12" v-for="(data) in suplemen">
                                <h1 class="emr mb-3">{{ data.title }}</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter" v-for="(detail) in data.value">
                                        <p>{{ detail.subTitle }}</p>
                                        <VField addons v-if="detail.type == 'textfiled'">
                                            <VControl expanded>
                                                <VInput type="text" class="input" v-model="input[detail.model]" />
                                            </VControl>
                                            <VControl class="field-addon-body">
                                                <VButton static>{{ detail.satuan }}</VButton>
                                            </VControl>
                                        </VField>

                                        <VField v-else-if="detail.type == 'textbox'">
                                            <VControl>
                                                <input v-model="input[detail.model]" class="input"
                                                    :placeholder="detail.subTitle + ' ...'" />
                                            </VControl>
                                        </VField>

                                        <VField v-else>
                                            <VControl raw subcontrol>
                                                <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                    :label="detail.satuan" color="primary" circle />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true"
                            legend="Riwayat personal (riwayatpenyakit , pekerjaan, aktivitas, kebiasaanhidup)">
                            <div class="column is-12">
                                <h1 class="emr mb-3">Riwayat personal (riwayatpenyakit , pekerjaan, aktivitas,
                                    kebiasaanhidup)</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayat_personal" class="input full-width"
                                                    :placeholder="'Isi Riwayat personal'" rows="4" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true" legend="DIAGNOSIS GIZI">
                            <div class="column is-12">
                                <h1 class="emr mb-3">DIAGNOSIS GIZI</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.diagnosa_gizi" class="input full-width"
                                                    :placeholder="'Isi diagnosa gizi'" rows="4" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true" legend="DIAGNOSIS GIZI">
                            <div class="column is-12">
                                <h1 class="emr mb-3">DIAGNOSIS MEDIS</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.diagnosa_MEDIS" class="input full-width"
                                                    :placeholder="'Isi diagnosa MEDIS'" rows="4" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true" legend="RENCANA TERAPI DIET">
                            <div class="column is-12">
                                <h1 class="emr mb-3">Jenis Diet</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.jenis_diet" class="input full-width"
                                                    :placeholder="'Isi diagnosa gizi'" rows="4" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <h1 class="emr mb-3">Tujuan Pemberian diet</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.tujuandiet" class="input full-width"
                                                    :placeholder="'Isi diagnosa gizi'" rows="4" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                                <div class="column is-12" v-for="(data) in DIET">
                                    <h1 class="emr mb-3">{{ data.title }}</h1>
                                    <div class="columns is-multiline pl-3 pr-3">
                                        <div class="column is-one-quarter" v-for="(detail) in data.value">
                                            <p>{{ detail.subTitle }}</p>
                                            <VField addons v-if="detail.type == 'textfiled'">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" v-model="input[detail.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ detail.satuan }}</VButton>
                                                </VControl>
                                            </VField>

                                            <VField v-else-if="detail.type == 'textbox'">
                                                <VControl>
                                                    <input v-model="input[detail.model]" class="input"
                                                        :placeholder="detail.subTitle + ' ...'" />
                                                </VControl>
                                            </VField>

                                            <VField v-else>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                        :label="detail.satuan" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-for="(data) in implemendiet">
                                    <h1 class="emr mb-3">{{ data.title }}</h1>
                                    <div class="columns is-multiline pl-3 pr-3">
                                        <div class="column is-one-quarter" v-for="(detail) in data.value">
                                            <p>{{ detail.subTitle }}</p>
                                            <VField addons v-if="detail.type == 'textfiled'">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" v-model="input[detail.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ detail.satuan }}</VButton>
                                                </VControl>
                                            </VField>

                                            <VField v-else-if="detail.type == 'textbox'">
                                                <VControl>
                                                    <input v-model="input[detail.model]" class="input"
                                                        :placeholder="detail.subTitle + ' ...'" />
                                                </VControl>
                                            </VField>

                                            <VField v-else>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                        :label="detail.satuan" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-for="(data) in bentukmakan">
                                    <h1 class="emr mb-3">{{ data.title }}</h1>
                                    <div class="columns is-multiline pl-3 pr-3">
                                        <div class="column is-one-quarter" v-for="(detail) in data.value">
                                            <p>{{ detail.subTitle }}</p>
                                            <VField addons v-if="detail.type == 'textfiled'">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" v-model="input[detail.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ detail.satuan }}</VButton>
                                                </VControl>
                                            </VField>

                                            <VField v-else-if="detail.type == 'textbox'">
                                                <VControl>
                                                    <input v-model="input[detail.model]" class="input"
                                                        :placeholder="detail.subTitle + ' ...'" />
                                                </VControl>
                                            </VField>

                                            <VField v-else>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                        :label="detail.satuan" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12" v-for="(data) in CARA_PEMBERIAN_DIET">
                                    <h1 class="emr mb-3">{{ data.title }}</h1>
                                    <div class="columns is-multiline pl-3 pr-3">
                                        <div class="column is-one-quarter" v-for="(detail) in data.value">
                                            <p>{{ detail.subTitle }}</p>
                                            <VField addons v-if="detail.type == 'textfiled'">
                                                <VControl expanded>
                                                    <VInput type="text" class="input" v-model="input[detail.model]" />
                                                </VControl>
                                                <VControl class="field-addon-body">
                                                    <VButton static>{{ detail.satuan }}</VButton>
                                                </VControl>
                                            </VField>

                                            <VField v-else-if="detail.type == 'textbox'">
                                                <VControl>
                                                    <input v-model="input[detail.model]" class="input"
                                                        :placeholder="detail.subTitle + ' ...'" />
                                                </VControl>
                                            </VField>

                                            <VField v-else>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[detail.model]" :true-value="detail.satuan"
                                                        :label="detail.satuan" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <h1 class="emr mb-3">Edukasi/konsultasi Gizi</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.edukasi_konsultasi" class="input full-width"
                                                    :placeholder="'Isi edukasi konsultasi'" rows="4" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <Fieldset :toggleable="true" legend="RENCANA MONITORING DAN EVALUASI">
                            <div class="column is-12">
                                <h1 class="emr mb-3">RENCANA MONITORING DAN EVALUASI</h1>
                                <div class="columns is-multiline pl-3 pr-3">
                                    <div class="column is-one-quarter">
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.rencana_monitoring" class="input full-width"
                                                    :placeholder="'Isi Rencana monitoring'" rows="4" />
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </Fieldset>
                        <div class="columns">
                        <div class="column is-8"></div>
                        <div class="column is-4">
                            <VField label="Garut">
                                <VDatePicker v-model="input.DTttd" mode="datetime" trim-weeks :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </template>
                                </VDatePicker>
                            </VField>
                            <div class="column" style="text-align:center;">
                                <h1>Tanda Tangan Gizi</h1>
                                <TandaTangan :elemenID="'TTDPerawat'" :width="'150'" :height="'150'" class="dek" />
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.DDPerawat" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" class="mt-2" />
                                </VControl>
                            </div>
                        </div>
                    </div>
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
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
        COLLECTION?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
        COLLECTION: 'AssesmenGiziGeriatriRI',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const fetchPegawai = async (filter: any) => {
    await useApi().get(`emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`).then((response) => {
        d_Pegawai.value = response
    })
}

let Antropometri = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Berat badan saat masuk rumah sakit",
                "model": "beratBadanMasuk",
                "type": "textfiled",
                "satuan": "Kg"
            },
            {
                "subTitle": "Tinggi badan ",
                "model": "tinggiBadan",
                "type": "textfiled",
                "satuan": "Cm"
            },
            {
                "subTitle": "Lingkar Lengan Atas(bila berat badan tidak bisa ditimbang)",
                "model": "lingkarLenganatas",
                "type": "textfiled",
                "satuan": "Cm"
            },
            {
                "subTitle": "Estimasi Tinggi Badan",
                "model": "estimasiTInggiBadan",
                "type": "textfiled",
                "satuan": "Cm"
            },
            {
                "subTitle": "Estimasi Berat Badan",
                "model": "estimasiBeratBadan",
                "type": "textfiled",
                "satuan": "Kg"
            },
            {
                "subTitle": "Berat badan ideal",
                "model": "beratBadanIdeal",
                "type": "textfiled",
                "satuan": "Kg"
            },
            {
                "subTitle": "IMT",
                "model": "IMT",
                "type": "textfiled",
                "satuan": "Kg/m2"
            },

        ],
    },

])
let KLINIS_FISIK = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Mual",
                "model": "mual",
                "type": "checkbox"
            },
            {
                "subTitle": "Muntah",
                "model": "muntah",
                "type": "checkbox"
            },
            {
                "subTitle": "Diare",
                "model": "diare",
                "type": "checkbox"
            },
            {
                "subTitle": "Konstipasi",
                "model": "konstipasi",
                "type": "checkbox"
            },
            {
                "subTitle": "Kesulitan mengunyah",
                "model": "kesulitanMengunyah",
                "type": "checkbox"
            },
            {
                "subTitle": "Kesulitan Menelan",
                "model": "kesulitanMenelan",
                "type": "checkbox"
            },
            {
                "subTitle": "Anoreksi",
                "model": "anoreksi",
                "type": "checkbox"
            },
            {
                "subTitle": "Sesak",
                "model": "sesak",
                "type": "checkbox"
            },
            {
                "subTitle": "Hamil/Menyusui",
                "model": "hamilMenyusui",
                "type": "checkbox"
            },
            {
                "subTitle": "Edema/Asites/Hipertensi",
                "model": "edemaAsitesHipertensi",
                "type": "checkbox"
            },
            {
                "subTitle": "Lainnya",
                "model": "lainnya",
                "type": "textfiled"
            },
        ],
    },

])
let DIETARY = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "ASI",
                "model": "asi",
                "type": "textfiled",
            },
            {
                "subTitle": "Bubur susu",
                "model": "buburSusu",
                "type": "checkbox",
            },
            {
                "subTitle": "Makan biasa/nasi",
                "model": "makanBiasanasi",
                "type": "textfiled",
            },
            {
                "subTitle": "Makanan pokok",
                "model": "makananPokok",
                "type": "textfiled",
            },
            {
                "subTitle": "Lauk",
                "model": "lauk",
                "type": "textfiled",
            },
            {
                "subTitle": "Sayur",
                "model": "sayur",
                "type": "textfiled",
            },
            {
                "subTitle": "Buah",
                "model": "buah",
                "type": "textfiled",
            },
        ],
    },

])
let DIAGNOSIS_NUTRISI = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Gagal tumbuh",
                "model": "gagalTumbuh",
                "type": "checkbox"
            },
            {
                "subTitle": "Malnutrisi berat",
                "model": "malmutrisiBerat",
                "type": "checkbox"
            },
            {
                "subTitle": "Kwashiorkor",
                "model": "kwashiorkor",
                "type": "checkbox"
            },
            {
                "subTitle": "Perawakan pendek",
                "model": "perawakanPendek",
                "type": "checkbox"
            },
            {
                "subTitle": "Malnutrisi sedang",
                "model": "malnutrisiSedang",
                "type": "checkbox"
            },
            {
                "subTitle": "Marasmus",
                "model": "marasmus",
                "type": "checkbox"
            },
            {
                "subTitle": "Obese",
                "model": "obese",
                "type": "checkbox"
            },
            {
                "subTitle": "Malnutrisi ringan",
                "model": "malnutrisiRingan",
                "type": "checkbox"
            },
            {
                "subTitle": "Kwashiorkor-Marasmus",
                "model": "kwashiorkorMarasmus",
                "type": "checkbox"
            },
            {
                "subTitle": "Gizi baik",
                "model": "giziBaik",
                "type": "checkbox"
            },
            {
                "subTitle": "Lainnya",
                "model": "lainnyaKlinis",
                "type": "textfiled"
            },
        ],
    },

])
let KEBUTUHAN_NUTRISI = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Energi",
                "model": "energi",
                "type": "textfiled",
                "satuan": "Kkal"
            },
            {
                "subTitle": "Protein",
                "model": "protein",
                "type": "textfiled",
                "satuan": "gr"
            },
            {
                "subTitle": "Cairan",
                "model": "cairan",
                "type": "textfiled",
                "satuan": "ml"
            },
            {
                "subTitle": "Densitas",
                "model": "densitas",
                "type": "textfiled",
            },
        ],
    },

])
let DIET = ref([
    {
        "title": "Kebutuhan Nutrisi",
        "value": [
            {
                "subTitle": "Energi",
                "model": "energi_diet",
                "type": "textfiled",
                "satuan": "kilokalori"
            },
            {
                "subTitle": "Protein",
                "model": "protein_diet",
                "type": "textfiled",
                "satuan": "gr"
            },
            {
                "subTitle": "Lemak",
                "model": "lemak_diet",
                "type": "textfiled",
                "satuan": "gr"
            },
            {
                "subTitle": "Karbohidrat",
                "model": "karbohidrat_diet",
                "type": "textfiled",
                "satuan": "gr"
            },
            {
                "subTitle": "Zat gizi lainnya",
                "model": "lainnya_diet",
                "type": "textfiled",
            },
        ],
    },

])
let implemendiet = ref([
    {
        "title": "Implementasi",
        "value": [
            {
                "subTitle": "Pemberian nutrisi sesuai dengan kebutuhan",
                "model": "nutrisi_kebutuhan_implemendiet",
                "type": "textfiled",
            },
            {
                "subTitle": "Pemberian nutrisi bertahap",
                "model": "nutrisi_bertahap_implemendiet",
                "type": "textfiled",
            },
        ],
    },

])
let bentukmakan = ref([
    {
        "title": "Bentuk makanan",
        "value": [
            {
                "subTitle": "Nasi",
                "model": "nasi_bentukmakan",
                "type": "checkbox",
            },
            {
                "subTitle": "Bubur/tim/cincang",
                "model": "bubur_bentukmakanan",
                "type": "checkbox",
            },
            {
                "subTitle": "Bubur saring",
                "model": "bubursaring_bentukmakanan",
                "type": "checkbox",
            },
            {
                "subTitle": "Cair/sonde/formula...",
                "model": "cair_bentukmakanan",
                "type": "checkbox",
            },
        ],
    },

])

let CARA_PEMBERIAN_DIET = ref([
    {
        "title": "Cara Pemberian",
        "value": [
            {
                "subTitle": "oral",
                "model": "oral_pemberian",
                "type": "checkbox",
            },
            {
                "subTitle": "Enteral (NGT/OGT/Gastrostomy/Jejenostomy, dll)",
                "model": "enteral_pemberian",
                "type": "checkbox",
            },
        ],
    },

])
let JENIS_NUTRISI = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Asi",
                "model": "asi_jenis_nutrisi",
                "type": "checkbox",
            },
            {
                "subTitle": "Nasi",
                "model": "nasi_jenis_nutrisi",
                "type": "checkbox",
            },
            {
                "subTitle": "Nasi Tim",
                "model": "nasi_tim_jenis_nutrisi",
                "type": "checkbox",
            },
            {
                "subTitle": "Bubur",
                "model": "bubur_jenis_nutrisi",
                "type": "checkbox",
            },
            {
                "subTitle": "Bubur Saring",
                "model": "bubur_saring_jenis_nutrisi",
                "type": "checkbox",
            },
            {
                "subTitle": "Bubur Susu",
                "model": "bubur_susu_jenis_nutrisi",
                "type": "checkbox",
            },
            {
                "subTitle": "Formula Standar",
                "model": "formula_standar_jenis_nutrisi",
                "type": "textfiled",
                "satuan": "klokalori"
            },
            {
                "subTitle": "Formula Khusus",
                "model": "formula_khusus_jenis_nutrisi",
                "type": "textfiled",
                "satuan": "klokalori"
            },
            {
                "subTitle": "Formula parenteral",
                "model": "formula_parenteral_jenis_nutrisi",
                "type": "checkbox",
            },
        ],
    },

])
let CARA_PEMBERIAN = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Oral",
                "model": "oral_cara_pemberian",
                "type": "checkbox",
            },
            {
                "subTitle": "Enteral",
                "model": "enteral_cara_pemberian",
                "type": "checkbox",
            },
            {
                "subTitle": "Parenteral",
                "model": "parenteral_cara_pemberian",
                "type": "checkbox",
            },
            {
                "subTitle": "Perifer",
                "model": "perifer_cara_pemberian",
                "type": "checkbox",
            },
            {
                "subTitle": "Sentral",
                "model": "sentral_cara_pemberian",
                "type": "checkbox",
            },
        ],
    },

])
let pantangan = ref([
    {
        "title": "d.Pantangan makanan:",
        "value": [
            {
                "subTitle": "Tidak ada",
                "model": "tidak_pantangan",
                "type": "checkbox",
            },
            {
                "subTitle": "Ada Jelaskan",
                "model": "ada_pantangan",
                "type": "textfiled",
            },
        ],
    },

])
let suplemen = ref([
    {
        "title": "f.Suplemen gizi:",
        "value": [
            {
                "subTitle": "Tanpa suplemen gizi",
                "model": "tanpaSuplemenGizi",
                "type": "checkbox",
            },
            {
                "subTitle": "Dengan suplemen gizi",
                "model": "denganSuplemenGizi",
                "type": "checkbox",
            },
        ],
    },

])
let alergi = ref([
    {
        "title": "e.Alergi makanan:",
        "value": [
            {
                "subTitle": "Tidak ada",
                "model": "tidak_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "Telur",
                "model": "telur_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "Susu sapi & produk olahannya",
                "model": "suspi_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "Kacang kedelai/tanah",
                "model": "kacang_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "Gluten/gandum",
                "model": "gluten_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "Udang",
                "model": "udang_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "Ikan",
                "model": "ikan_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "hazlenut/almond",
                "model": "hazelnut_alergi",
                "type": "checkbox",
            },
            {
                "subTitle": "Lainnya",
                "model": "lain_alergi",
                "type": "textfiled",
            },
        ],
    },

])
let MONITORING = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Gagal tumbuh",
                "model": "gagalTumbuh",
                "type": "checkbox"
            },
            {
                "subTitle": "Biokimia",
                "model": "biokimia_monitoring",
                "type": "textfiled"
            },
            {
                "subTitle": "Berat badan",
                "model": "berat_badan_monitoring",
                "type": "textfiled"
            },
            {
                "subTitle": "Reefeeding syndrome",
                "model": "reefeeding_monitoring",
                "type": "checkbox"
            },
            {
                "subTitle": "Overfeeding syndrome",
                "model": "Overfeeding_monitoring",
                "type": "checkbox"
            },
            {
                "subTitle": "Asupan makan",
                "model": "asupan_makan_monitoring",
                "type": "checkbox"
            },
        ],
    },

])
let BIOKIMIA = ref([
    {
        "title": "",
        "value": [
            {
                "subTitle": "Hemoglobin: *laki-laki : 13.5 – 17.5 g/dL *perempuan : 12.0 – 16.0 g/dL",
                "model": "hemoglobinBiokimia",
                "model_hasil": "hemoglobinBiokimia_hasil",
                "model_nm": "hemoglobinBiokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Urea: 0-50 mg/dL",
                "model": "urea_biokimia",
                "model_hasil": "urea_biokimia_hasil",
                "model_nm": "urea_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Kreatinin *laki-laki : 0.6 – 1.2 mg/dL *perempuan : 0.5 – 1 mg/dL",
                "model": "kreatinin_biokimia",
                "model_hasil": "kreatinin_biokimia_hasil",
                "model_nm": "kreatinin_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "ALT / GPT *laki-laki : < 45 U/L *perempuan : < 34 U/L",
                "model": "alt_biokimia",
                "model_hasil": "alt_biokimia_hasil",
                "model_nm": "alt_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "AST / GOT *laki-laki : < 35 U/L *perempuan : < 31 U/L",
                "model": "ast_biokimia",
                "model_hasil": "ast_biokimia_hasil",
                "model_nm": "ast_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "GDP",
                "model": "gdp_biokimia",
                "type": "textfiled"
            },
            {
                "subTitle": "Glukosa 2JPP : 70 – 140 mg/dL",
                "model": "glukosa_biokimia",
                "model_hasil": "glukosa_biokimia_hasil",
                "model_nm": "glukosa_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Bilirubin Total : 0.3 – 1.2",
                "model": "biliburintot_biokimia",
                "model_hasil": "biliburintot_biokimia_hasil",
                "model_nm": "biliburintot_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "BBilirubin direct < 0.3",
                "model": "biliburindirect_biokimia",
                "model_hasil": "biliburindirect_biokimia_hasil",
                "model_nm": "biliburindirect_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Kolesterol total : < 200",
                "model": "kolesteroltot_biokimia",
                "model_hasil": "kolesteroltot_biokimia_hasil",
                "model_nm": "kolesteroltot_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Natrium (Na) : 136 – 145 mmol/L (*rendah/*tinggi)",
                "model": "natrium_biokimia",
                "model_hasil": "natrium_biokimia_hasil",
                "model_nm": "natrium_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Kalium (K) : 3.50 – 5.10 mmol/L (*rendah/*tinggi)",
                "model": "kalium_biokimia",
                "model_hasil": "kalium_biokimia_hasil",
                "model_nm": "kalium_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Clorida (Cl) : 94 – 110 mmol/L (*rendah/*tinggi)",
                "model": "clorida_biokimia",
                "model_hasil": "clorida_biokimia_hasil",
                "model_nm": "clorida_biokimia_nm",
                "type": "checkbox"
            },
            {
                "subTitle": "Lainnya",
                "model": "lainnya_biokimia",
                "type": "textfiled"
            },
        ],
    },

])

const dataTTD: any = ref([])

const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                dataTTD.value = response[0]
                H.tandaTangan().set("TTDPerawat", dataTTD.value.TTDPerawat)
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    object['TTDPerawat'] = H.tandaTangan().get("TTDPerawat");
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    console.log(json)
    isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            NOREC_EMRPASIEN.value = response.norec_emr
            input.value.id = response.id
        }).catch((e: any) => {
            isLoading.value = false
        })
}


const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_pegawai.value = response
    })
}
// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
</script>
