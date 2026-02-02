import Vue from 'vue'
import Vuex from 'vuex'
import cart from './cart'
Vue.use(Vuex)
const createStore = () => {
  return new Vuex.Store({
    modules: {
      cart,
    },
    state: {
      //Data Central
    },
    mutations: {
      //Data Change
    },
  })
}
export default createStore
