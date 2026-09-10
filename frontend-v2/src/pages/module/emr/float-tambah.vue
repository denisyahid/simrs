<template>
    <div role="button" class="goog-inline-block jfk-button jfk-button-flat jfk-button-narrow docs-homescreen-fab"
        tabindex="0" id="docs-homescreen-add" data-tooltip="Buat spreadsheet baru" aria-label="Buat spreadsheet baru"
        data-tooltip-class="docs-homescreen-tooltip" style="user-select: none; margin-right: 30px;"
        v-tooltip.bubble="'Tambah Pasien'">
        <div class="docs-homescreen-icon docs-homescreen-icon-medium">
            <div class="docs-homescreen-img docs-homescreen-fab-googlecolors-24"></div>
        </div>
    </div>
    <!-- <a href="javascript:" class="return-to-top"><i class="fas fa-plus-circle"></i></a>
    <VIconBox rounded :color="'warning'" class="h-avatar">
        <img alt="" src="/images/simrs/h_sprite58_grey_medium.svg">
    </VIconBox> -->
</template>
  
<style lang="scss">
#docs-homescreen-add {
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;
    border: none;
    bottom: 24px;
    cursor: pointer;
    height: 56px;
    position: fixed;
    -webkit-transition-duration: .25s;
    -webkit-transition-duration: .25s;
    -moz-transition-duration: .25s;
    -o-transition-duration: .25s;
    transition-duration: .25s;
    -webkit-transition-property: background-color,-webkit-box-shadow;
    right: 24px;
    -webkit-transition-property: background-color,box-shadow;
    -o-transition-property: background-color,box-shadow;
    transition-property: background-color,box-shadow;
    width: 56px;
    z-index: 2;
}
.docs-homescreen-fab {
    -webkit-box-shadow: 0px 6px 10px 0px rgba(0,0,0,.14), 0px 1px 18px 0px rgba(0,0,0,.12), 0px 3px 5px -1px rgba(0,0,0,.2);
    -moz-box-shadow: 0px 6px 10px 0px rgba(0,0,0,.14),0px 1px 18px 0px rgba(0,0,0,.12),0px 3px 5px -1px rgba(0,0,0,.2);
    box-shadow: 0px 6px 10px 0px rgba(0,0,0,.14), 0px 1px 18px 0px rgba(0,0,0,.12), 0px 3px 5px -1px rgba(0,0,0,.2);
    background-color: #fff;
}
.jfk-button-flat {
    -webkit-border-radius: 0;
    -moz-border-radius: 0;
    border-radius: 0;
    border: 1px solid transparent;
    font-size: 13px;
    font-weight: normal;
    height: 21px;
    line-height: 21px;
    margin-right: 1px;
    min-width: 0;
    padding: 0;
}
.jfk-button-narrow {
    min-width: 34px;
    padding: 0;
}
.jfk-button {
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    border-radius: 2px;
    cursor: default;
    font-size: 11px;
    font-weight: bold;
    text-align: center;
    white-space: nowrap;
    margin-right: 16px;
    height: 27px;
    line-height: 27px;
    min-width: 54px;
    outline: 0;
    padding: 0 8px;
}
.goog-inline-block {
    position: relative;
    display: -moz-inline-box;
    display: inline-block;
}
.jfk-button#docs-homescreen-add .docs-homescreen-icon {
    margin: auto;
    top: 16px;
}
.docs-homescreen-icon-medium {
    height: 24px;
    outline: none;
    width: 24px;
}
.docs-homescreen-icon {
    direction: ltr;
    text-align: left;
    overflow: hidden;
    position: relative;
    vertical-align: middle;
}
.docs-homescreen-fab-googlecolors-24 {
    left: -26px;
    top: -578px;
}

.docs-homescreen-img {
    height: 820px;
    position: absolute;
    width: 234px;
}
.docs-homescreen-img:before {
    content: url(/images/simrs/h_sprite58_grey_medium.svg);
}
.return-to-top {
    z-index: 999;
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: var(--info);
    width: 50px;
    height: 50px;
    display: block;
    text-decoration: none;
    -webkit-border-radius: 35px;
    -moz-border-radius: 35px;
    border-radius: 35px;
    // display: none;
    -webkit-transition: all 0.3s linear;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;

    box-shadow: 0px 6px 10px 0px rgba(0, 0, 0, .14), 0px 1px 18px 0px rgba(0, 0, 0, .12), 0px 3px 5px -1px rgba(0, 0, 0, .2);

}

.return-to-top i {
    color: #fff;
    margin: 0;
    position: relative;
    left: 13px;
    top: 12px;
    font-size: 25px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.return-to-top:hover {
    background: rgba(0, 0, 0, 0.62);
}

.return-to-top:hover i {
    color: #fff;
    top: 5px;
}
</style>
  
<script lang="ts">

</script>