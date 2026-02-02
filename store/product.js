export const state = () => ({
    products: [
        {
            id: 1,
            name: 'iPhone 14 Pro',
            category: 'Smart Phone',
            price: 999,
            description: 'Latest Apple smartphone with advanced camera system',
            image: 'https://images.unsplash.com/photo-1592286927505-1def25115558?w=400&h=400&fit=crop',
            rating: 4.8,
            stock: 15,
            specs: {
                storage: '256GB',
                ram: '6GB',
                display: '6.1 inch'
            }
        },
        {
            id: 2,
            name: 'Samsung Galaxy S23',
            category: 'Smart Phone',
            price: 899,
            description: 'Powerful Android device with excellent display',
            image: 'https://images.unsplash.com/photo-1511707267537-b85faf00021e?w=400&h=400&fit=crop',
            rating: 4.7,
            stock: 12,
            specs: {
                storage: '256GB',
                ram: '8GB',
                display: '6.1 inch'
            }
        },
        {
            id: 3,
            name: 'MacBook Pro 16"',
            category: 'IT',
            price: 2499,
            description: 'Professional laptop for developers and creators',
            image: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400&h=400&fit=crop',
            rating: 4.9,
            stock: 8,
            specs: {
                processor: 'M2 Max',
                ram: '16GB',
                storage: '512GB'
            }
        },
        {
            id: 4,
            name: 'Dell XPS 13',
            category: 'IT',
            price: 1299,
            description: 'Compact and powerful ultrabook',
            image: 'https://images.unsplash.com/photo-1588872657840-218e412ee5de?w=400&h=400&fit=crop',
            rating: 4.6,
            stock: 10,
            specs: {
                processor: 'Intel i7',
                ram: '16GB',
                storage: '512GB'
            }
        }
    ],
    categories: ['Smart Phone', 'IT'],
    cart: []
})

export const mutations = {
    ADD_TO_CART(state, product) {
        state.cart.push(product)
    },
    REMOVE_FROM_CART(state, productId) {
        state.cart = state.cart.filter(item => item.id !== productId)
    },
    CLEAR_CART(state) {
        state.cart = []
    }
}

export const actions = {
    addToCart({ commit }, product) {
        commit('ADD_TO_CART', product)
    },
    removeFromCart({ commit }, productId) {
        commit('REMOVE_FROM_CART', productId)
    },
    clearCart({ commit }) {
        commit('CLEAR_CART')
    }
}

export const getters = {
    productsByCategory: (state) => (category) => {
        return state.products.filter(product => product.category === category)
    },
    cartTotal: (state) => {
        return state.cart.reduce((total, item) => total + item.price, 0)
    },
    cartCount: (state) => state.cart.length
}