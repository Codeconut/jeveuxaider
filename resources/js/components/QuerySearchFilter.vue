<template>
  <div class="query-search-filter mr-4 mb-4">
    <div class="text-secondary text-xs uppercase font-semibold mb-2">
      {{ label }}
    </div>
    <el-input
      :ref="name"
      v-model="input"
      :placeholder="placeholder"
      clearable
    />
  </div>
</template>

<script>
import _ from 'lodash'

export default {
  name: 'QuerySearchFilter',
  props: {
    initialValue: {
      type: String,
      required: false,
      default: null,
    },
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    placeholder: {
      type: String,
      required: false,
      default: 'Choisir',
    },
  },
  data() {
    return {
      input: this.initialValue,
      debouncedInput: '',
    }
  },
  watch: {
    input: _.debounce(function (newVal) {
      if (!_.isNull(newVal)) {
        this.$emit('changed', { name: this.name, value: newVal })
      }
    }, 500),
  },
  mounted() {
    if (this.initialValue) {
      this.$refs[this.name].focus()
    }
  },
}
</script>

<style lang="sass">
.query-filter
  .el-tag.el-tag--info
    .el-select__tags-text
      display: inline-flex
      max-width: 120px
      overflow: hidden
</style>
