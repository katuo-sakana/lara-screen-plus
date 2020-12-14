// import './bootstrap'
window.axios = require('axios');
import Vue from 'vue'
import CaptureWrap from './components/CaptureWrap'

const app = new Vue({
  el: '#app',
  components: {
    CaptureWrap,
  }
})