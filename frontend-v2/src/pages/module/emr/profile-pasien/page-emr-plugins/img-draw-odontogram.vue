<template>
    <section style="display: inline-grid;">
      <VCard class="border-card info" :style="'width:' + props.width + 'px;height:' + props.height + 'px; background-image: url(' + props.imageSrc + '); background-size: cover;'">
        <canvas :id="props.elemenID" :height="props.height" :width="props.width"
          style="position: relative;left: -19px;top: -20px;" class="canvasGambar"></canvas>
      </VCard>
      <div class="column is-12">
        <div class="columns is-multiline">
            <div class="column is-2"></div>
          <div class="column is-8">
            <VButtons style="justify-content: center;">
              <!-- <VButton type="button" rounded outlined color="danger" raised icon="feather:trash" class="mt-2"
                @click="clearCanvas(props.elemenID)"> Clear
              </VButton> -->
              <VIconButton color="info" circle outlined raised icon="fas fa-trash" style="margin-top: 10px;"
                @click="clearCanvas(props.elemenID)" v-tooltip.bottom.left="'Hapus'">
              </VIconButton>
              <VIconButton color="info" circle outlined raised icon="fas fa-undo" style="margin-top: 10px;"
                @click="undoCanvas(props.elemenID)" v-tooltip.bottom.left="'Undo'">
              </VIconButton>
              <!-- <VButton type="button" rounded outlined color="warning" raised icon="lucide:arrow-left-circle" class="mt-2"
                @click="undoCanvas(props.elemenID)"> Undo
              </VButton> -->
              <VIconButton color="primary" circle outlined raised style="background-color: black;"
                @click="black()" v-tooltip.bottom.left="'Hitam'">
              </VIconButton>
              <VIconButton color="primary" circle outlined raised style="background-color: red;"
                @click="red()" v-tooltip.bottom.left="'Merah'">
              </VIconButton>
              <VIconButton color="primary" circle outlined raised style="background-color: yellow;"
                @click="yellow()" v-tooltip.bottom.left="'Kuning'">
              </VIconButton>
              <VIconButton color="primary" circle outlined raised style="background-color: green;"
                @click="green()" v-tooltip.bottom.left="'Hijau'">
              </VIconButton>
              <VIconButton color="primary" circle outlined raised style="background-color: purple;"
                @click="purple()" v-tooltip.bottom.left="'Ungu'">
              </VIconButton>
              <VIconButton color="primary" circle outlined raised style="background-color: pink;"
                @click="pink()" v-tooltip.bottom.left="'Pink'">
              </VIconButton>
              <!-- <VButton type="button" rounded outlined color="primary" raised icon="lucide:arrow-right-circle" class="mt-2"
                @click="redoCanvas(props.elemenID)"> Redo
              </VButton> -->
            </VButtons>
          </div>
          <div class="column is-2" style="display: none !important">
            <VField>
                <VControl>
                    <VInput type="text" v-model="text" placeholder="Ketik disini..." id="teks"/>
                </VControl>
            </VField>
            <!-- <VField class="is-autocomplete-select" v-slot="{ id }">
              <VControl>
                <Multiselect v-model="text2" :attrs="{ value }" placeholder="--Pilih--" label="label"
                  :options="([{ value: 1, label: 'Cavity' }, { value: 2, label: 'Missing' }, { value: 3, label: 'Impaksi' }, { value: 4, label: 'Gigi Goyang' }, { value: 5, label: 'Bridge' }
                  , { value: 6, label: 'Tumpatan' }, { value: 7, label: 'Belum Tumbuh' }, { value: 8, label: 'Sisa Akar' }, { value: 9, label: 'Crown' }, { value: 10, label: 'Karang Gigi' }
                  ])" :searchable="true" track-by="label" mode="single" autocomplete="off">
                </Multiselect>
              </VControl>
            </VField> -->
          </div>
          <div class="column is-12">
            <VButton icon="rem-100" class="ml-2" color="info" light dark-outlined @click="cavity()">
              Cavity
            </VButton>
            <VButton icon="rem-100" class="ml-2" color="info" light dark-outlined @click="missing()">
              Missing
            </VButton>
            <VButton icon="rem-100" class="ml-2" color="info" light dark-outlined @click="impaksi()">
              Impaksi
            </VButton>
            <VButton icon="rem-100" class="ml-2" color="info" light dark-outlined @click="gigigoyang()">
              Gigi Goyang
            </VButton>
            <VButton icon="rem-100" class="ml-2" color="info" light dark-outlined @click="bridge()">
              Bridge
            </VButton><br>
            <VButton icon="rem-100" class="ml-2 mt-2" color="info" light dark-outlined @click="tumpatan()">
              Tumpatan
            </VButton>
            <VButton icon="rem-100" class="ml-2 mt-2" color="info" light dark-outlined @click="belumtumbuh()">
              Belum Tumbuh
            </VButton>
            <VButton icon="rem-100" class="ml-2 mt-2" color="info" light dark-outlined @click="sisaakar()">
              Sisa Akar
            </VButton>
            <VButton icon="rem-100" class="ml-2 mt-2" color="info" light dark-outlined @click="crown()">
              Crown
            </VButton>
            <VButton icon="rem-100" class="ml-2 mt-2" color="info" light dark-outlined @click="karanggigi()">
              Karang Gigi
            </VButton>
          </div>
        </div>
      </div>
  
    </section>
  </template>
  <script lang="ts">
  import { ref, watch, onUnmounted, h, defineComponent } from 'vue'
  import sleep from '/@src/utils/sleep'
  import $ from "jquery";
  import * as H from '/@src/utils/appHelper'
  // const props = withDefaults(
  //     defineProps<{
  //         input?: any
  //     }>(),
  //     {
  //         input: {},
  //     }
  // )
  
  export default {
    props: {
      elemenID: String,
      height: String,
      width: String,
      imageSrc: String,
      valueImg: String,
      text: String
    },
    setup(props) {
      let history: string[] = [];
      let currentStep = -1;
      let color = "black"
      let gambar = ""
      let gambartext = ""
      const inputElement = ref<HTMLElement>()
      console.log('input element', inputElement)
      const d_allo: any = ref([{ value: 1, label: 'Cavity' }, { value: 2, label: 'Missing' }, { value: 3, label: 'Impaksi' }, { value: 4, label: 'Gigi Goyang' }, { value: 5, label: 'Bridge' }
      , { value: 6, label: 'Tumpatan' }, { value: 7, label: 'Belum Tumbuh' }, { value: 8, label: 'Sisa Akar' }, { value: 9, label: 'Crown' }, { value: 10, label: 'Karang Gigi' }
      ])
  
      const getPosition = (event: any, sigCanvas: any) => {
        const rect = sigCanvas.getBoundingClientRect();
        let X, Y;
  
        if (event.touches) {
          // Handle touch events
          X = event.touches[0].clientX - rect.left;
          Y = event.touches[0].clientY - rect.top;
        } else {
          // Handle mouse events
          X = event.clientX - rect.left;
          Y = event.clientY - rect.top;
        }
  
        return { X, Y };
      };
  
      const signcolor = (color: any) => {
        let sigCanvas: any = document.getElementById(props.elemenID);
        if (sigCanvas == null) return
        let context = sigCanvas.getContext("2d");
        context.strokeStyle = color;
      }
      const sign = () => {
        console.log('sign', color)
        color = "black"
        signcolor(color)
        let sigCanvas: any = document.getElementById(props.elemenID);
        // sigCanvas.height = 500
        // sigCanvas.width = 500
        if (sigCanvas == null) return
        let context = sigCanvas.getContext("2d");
        context.lineJoin = "round";
        context.lineWidth = 2;
        let is_touch_device = 'ontouchstart' in document.documentElement;
  
        if (is_touch_device) {
  
          let drawer: any = {
            isDrawing: false,
            touchstart: function (coors: any) {
              context.beginPath();
              context.moveTo(coors.x, coors.y);
              this.isDrawing = true;
            },
            touchmove: function (coors: any) {
              if (this.isDrawing) {
                context.lineTo(coors.x, coors.y);
                context.stroke();
              }
            },
            touchend: function (coors: any) {
              if (this.isDrawing) {
                this.touchmove(coors);
                this.isDrawing = false;
              }
            }
          };
  
  
          function draw(event: any) {
            let coors = {
              x: event.targetTouches[0].pageX,
              y: event.targetTouches[0].pageY
            };
  
            let obj = sigCanvas;
  
            if (obj.offsetParent) {
  
              do {
                coors.x -= obj.offsetLeft;
                coors.y -= obj.offsetTop;
              }
  
              while ((obj = obj.offsetParent) != null);
            }
  
  
            drawer[event.type](coors);
          }
  
  
          sigCanvas.addEventListener('touchstart', draw, false);
          sigCanvas.addEventListener('touchmove', draw, false);
          sigCanvas.addEventListener('touchend', draw, false);
  
          sigCanvas.addEventListener('mousedown', (e) => drawLine(e));
          sigCanvas.addEventListener('mousemove', (e) => drawLine(e));
          sigCanvas.addEventListener('touchstart', (e) => drawLine(e));
          sigCanvas.addEventListener('touchmove', (e) => drawLine(e));
  
  
          sigCanvas.addEventListener('touchmove', function (event: any) {
            event.preventDefault();
  
          }, false);
        } else {
  
          $("#" + props.elemenID).mousedown(function (mouseEvent: any) {
  
            let position = getPosition(mouseEvent, sigCanvas);
            context.moveTo(position.X, position.Y);
            context.beginPath();
            $(this).mousemove(function (mouseEvent: any) {
              drawLine(mouseEvent, sigCanvas, context);
            }).mouseup(function (mouseEvent: any) {
              finishDrawing(mouseEvent, sigCanvas, context);
            }).mouseout(function (mouseEvent: any) {
              finishDrawing(mouseEvent, sigCanvas, context);
            });
          });
  
        }
      }
      const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {
  
        let position = getPosition(mouseEvent, sigCanvas);
  
        // context.lineTo(position.X, position.Y);
        console.log('teks di ambil 1', document.getElementById('teks').value)
        // console.log('dropdown', document.getElementById('text2').value)
        if(document.getElementById('teks').value != '' && document.getElementById('teks').value != undefined){
          context.font= "bold 20px Arial";
          context.fillText(document.getElementById('teks').value,position.X, position.Y);
    
          // context.fillStyle('/images/simrs/logo_bali.png',position.X, position.Y);
          if(gambar == ''){
            document.getElementById('teks').value = '';
          } 
          console.log('text diambil 2', document.getElementById('teks').value)
        } else if(gambartext != ""){
          var base_image = new Image();
          base_image.src = gambar;
          base_image.onload = function(){
            context.drawImage(base_image, position.X-15, position.Y-15, 30, 30);
          }
        }else{
          context.lineTo(position.X, position.Y);
          gambar = ''
        }
  
        context.stroke();
      }
      const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
        drawLine(mouseEvent, sigCanvas, context);
  
        context.closePath();
        $(sigCanvas).unbind("mousemove")
          .unbind("mouseup")
          .unbind("mouseout");
  
        // save history
        saveState();
      }
  
      const saveState = () => {
        var sigCanvas: any = document.getElementById(props.elemenID);
        if (currentStep < history.length - 1) {
          history = history.slice(0, currentStep + 1);
        }
        history.push(sigCanvas.toDataURL());
        console.log('history', history)
        currentStep++;
        console.log('current step savestate', currentStep)
      };
  
      const black = () => {
        color = "black"
        console.log('klik', color)
        signcolor(color)
      }
  
      const red = () => {
        color = "red"
        console.log('klik', color)
        signcolor(color)
      }
  
      const yellow = () => {
        color = "yellow"
        console.log('klik', color)
        signcolor(color)
      }
  
      const green = () => {
        color = "green"
        console.log('klik', color)
        signcolor(color)
      }
  
      const purple = () => {
        color = "purple"
        console.log('klik', color)
        signcolor(color)
      }
  
      const pink = () => {
        color = "pink"
        console.log('klik', color)
        signcolor(color)
      }
  
      const karanggigi = () => {
        gambar = 'ΛΛΛ'
        document.getElementById('teks').value = 'ΛΛΛ'
      }
  
      const cavity = () => {
        gambar = 'O'
        document.getElementById('teks').value = 'O'
      }
  
      const missing = () => {
        gambar = 'X'
        document.getElementById('teks').value = 'X'
      }
  
      const impaksi = () => {
        gambar = 'I'
        document.getElementById('teks').value = 'I'
      }
  
      const gigigoyang = () => {
        gambar = 'Σ'
        document.getElementById('teks').value = 'Σ'
      }
  
      const bridge = () => {
        gambar = 'BR'
        document.getElementById('teks').value = 'BR'
      }
  
      const tumpatan = () => {
        gambar = '⬤'
        document.getElementById('teks').value = '⬤'
      }
  
      const belumtumbuh = () => {
        gambar = 'B'
        document.getElementById('teks').value = 'B'
      }
  
      const sisaakar = () => {
        gambar = '✔'
        document.getElementById('teks').value = '✔'
      }
  
      const crown = () => {
        gambar = 'CR'
        document.getElementById('teks').value = 'CR'
      }
  
      const clearCanvas = (canvas: any) => {
  
        var sigCanvas: any = document.getElementById(canvas);
        var context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        saveState();
      }
  
      const undoCanvas = (canvas: any) => {
        var sigCanvas: any = document.getElementById(canvas);
        var context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        if (currentStep > 0) {
          currentStep--;
          console.log('current step', currentStep)
          const previousState = history[currentStep];
          const img = new Image();
          img.onload = () => {
            context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
            context.drawImage(img, 0, 0);
          };
          img.src = previousState;
        }
        // saveState();
      };
      const redoCanvas = (canvas: any) => {
        var sigCanvas: any = document.getElementById(canvas);
        var context = sigCanvas.getContext("2d");
        context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
        currentStep++;
        const previousState = history[currentStep];
        const img = new Image();
        img.onload = () => {
          context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
          context.drawImage(img, 0, 0);
        };
        img.src = previousState;
        saveState();
      }
  
      const fetchSignature = async () => {
        await sleep(1000)
        sign()
        // await sleep(1000)
        loadGambar();
      }
  
      const loadGambar = async () => {
        let sigCanvas: any = document.getElementById(props.elemenID);
  
        if (sigCanvas && props.valueImg) {
            let context = sigCanvas.getContext("2d");
            context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
            let imagess = props.valueImg
            let background = new Image();
            background.src = imagess
            background.onload = function () {
                context.drawImage(background, 0, 0, sigCanvas.width, sigCanvas.height);
            }
        }
      }
      fetchSignature()
  
      return {
        fetchSignature,
        clearCanvas,
        finishDrawing,
        drawLine,
        sign,
        signcolor,
        getPosition,
        undoCanvas,
        karanggigi,
        cavity,
        missing,
        impaksi,
        gigigoyang,
        bridge,
        tumpatan,
        belumtumbuh,
        sisaakar,
        crown,
        black,
        purple,
        pink,
        red,
        yellow,
        green,
        redoCanvas,
        saveState,
        props
      };
    }
  }
  </script>
  