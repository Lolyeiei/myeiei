const state = {
    productID: ''
  }
  
  const getters = {
    fullName: (state, getters, rootState) => {
      return state.productID
    }
  }
  
  const actions = {
    setName: ({ commit, state }, payload) => {
      commit('SET_NEWS_CATE', payload.productID)
    }
  }
  
  const mutations = {
    SET_NEWS_CATE (state, payload) {
      state.productID = payload
    }, 
  }
  
  export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
  }
  