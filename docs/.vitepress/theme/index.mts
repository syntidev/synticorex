import DefaultTheme from 'vitepress/theme'
import SyntiaWidget from './SyntiaWidget.vue'
import Layout from './Layout.vue'
import './custom.css'

export default {
  extends: DefaultTheme,
  Layout,
  enhanceApp({ app }) {
    app.component('SyntiaWidget', SyntiaWidget)
  }
}