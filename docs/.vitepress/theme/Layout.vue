<template>
  <DefaultTheme.Layout>
    <template #layout-bottom>
      <SyntiaWidget />
    </template>
  </DefaultTheme.Layout>
</template>

<script setup>
import DefaultTheme from 'vitepress/theme'
import SyntiaWidget from './SyntiaWidget.vue'
</script>