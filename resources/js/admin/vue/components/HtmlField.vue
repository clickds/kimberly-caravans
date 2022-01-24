<template>
  <div>
    <label :for="name">{{ label }}</label>
    <codemirror v-model="htmlContent" :options="cmOptions" />
    <input type="hidden" :name="name" v-model="htmlContent" />
    <a
      :href="assetsPageUrl"
      target="_blank"
      rel="noreferrer"
      class="underline text-endeavour"
    >Assets</a>
  </div>
</template>

<script>
import { codemirror } from 'vue-codemirror'
import 'codemirror/lib/codemirror.css'
import 'codemirror/mode/htmlmixed/htmlmixed.js'
import 'codemirror/theme/base16-dark.css'

export default {
  components: {
    codemirror
  },
  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    initialValue: {
      type: String,
    },
    assetsPageUrl: {
      type: String,
      required: true,
    },
  },
  data () {
    return {
      htmlContent: this.initialValue,
      cmOptions: {
        tabSize: 4,
        lineNumbers: true,
        line: true,
      }
    }
  },
}
</script>
