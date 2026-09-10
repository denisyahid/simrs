<template>
    <div>
        <label :key="dataK">{{ arr }}</label>
        <!-- <div>{{ arr }}</div> -->
        <button @click="handleClick()">add</button>
    </div>
</template>
<script setup lang="ts">
import { defineComponent, getCurrentInstance, ref } from "vue";

let dataK: any = 1
// const { ctx: _this }: any = getCurrentInstance()
let arr = ref([1, 3])
function handleClick() {
    debugger
    arr.value.push(2)
    dataK = 2
    console.log(dataK)
    // _this.$forceUpdate()
}



</script>
