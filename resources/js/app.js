require('./bootstrap')

import Vue from 'vue'

import App from './components/App'
import { Container, Header, Aside, Main, Input, Select, Option, CheckboxGroup, Checkbox, Slider, Button, Table, TableColumn, Loading, Pagination } from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css';
import lang from 'element-ui/lib/locale/lang/en'
import locale from 'element-ui/lib/locale'

locale.use(lang)
Vue.component(Container.name, Container)
Vue.component(Header.name, Header)
Vue.component(Aside.name, Aside)
Vue.component(Main.name, Main)
Vue.component(Input.name, Input)
Vue.component(Select.name, Select)
Vue.component(Option.name, Option)
Vue.component(CheckboxGroup.name, CheckboxGroup)
Vue.component(Checkbox.name, Checkbox)
Vue.component(Slider.name, Slider)
Vue.component(Button.name, Button)
Vue.component(Table.name, Table)
Vue.component(TableColumn.name, TableColumn)
Vue.use(Loading.directive)
Vue.component(Pagination.name, Pagination)

const app = new Vue({
  el: '#app',
  components: { App },
})
