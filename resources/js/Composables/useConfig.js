import { ref, computed } from 'vue';
import axios from 'axios';

// Store config data globally
const configData = ref(null);
const loading = ref(false);
const error = ref(null);

export function useConfig() {
    // Fetch all config data
    const fetchConfig = async (options = {}) => {
        if (configData.value && !options.force) {
            return configData.value;
        }
        
        loading.value = true;
        error.value = null;
        
        try {
            const response = await axios.get('/web/api/config');
            configData.value = response.data.data;
            return configData.value;
        } catch (err) {
            error.value = err.message;
            console.error('Failed to fetch config:', err);
            throw err;
        } finally {
            loading.value = false;
        }
    };
    
    // Get cryptocurrencies
    const cryptocurrencies = computed(() => {
        return configData.value?.cryptocurrencies || [];
    });
    
    // Get payment methods
    const paymentMethods = computed(() => {
        return configData.value?.payment_methods || [];
    });
    
    // Get fiat currencies
    const fiatCurrencies = computed(() => {
        return configData.value?.fiat_currencies || [];
    });
    
    // Get countries
    const countries = computed(() => {
        return configData.value?.countries || [];
    });
    
    // Helper functions
    const getCryptocurrencyByKey = (key) => {
        return cryptocurrencies.value.find(c => c.key === key);
    };
    
    const getPaymentMethodByValue = (value) => {
        // paymentMethods is an array of categories, each with methods array
        for (const category of paymentMethods.value) {
            const method = category.methods?.find(m => m.value === value);
            if (method) {
                return method;
            }
        }
        return null;
    };
    
    const getFiatCurrencyByCode = (code) => {
        return fiatCurrencies.value.find(c => c.code === code);
    };
    
    const getCountryByCode = (code) => {
        return countries.value.find(c => c.code === code);
    };
    
    // Format options for select components
    const getCryptocurrencyOptions = () => {
        return cryptocurrencies.value.map(crypto => ({
            value: crypto.key,
            label: `${crypto.currency} (${crypto.network})`
        }));
    };
    
    const getPaymentMethodOptions = () => {
        return paymentMethods.value.map(method => {
            const option = {
                value: method.value,
                label: method.label,
                icon: method.icon
            };
            if (method.badge) {
                option.badge = method.badge;
            }
            return option;
        });
    };
    
    const getFiatCurrencyOptions = () => {
        return fiatCurrencies.value.map(currency => ({
            value: currency.code,
            label: currency.label,
            symbol: currency.symbol
        }));
    };
    
    const getCountryOptions = () => {
        return countries.value.map(country => ({
            value: country.code,
            label: `${country.code} - ${country.name}`
        }));
    };
    
    // Get payment method details (compatibility function)
    const getPaymentMethodDetails = (value) => {
        return getPaymentMethodByValue(value);
    };
    
    return {
        // State
        configData,
        loading,
        error,
        
        // Methods
        fetchConfig,
        
        // Computed data
        cryptocurrencies,
        paymentMethods,
        fiatCurrencies,
        countries,
        
        // Helper functions
        getCryptocurrencyByKey,
        getPaymentMethodByValue,
        getPaymentMethodDetails,
        getFiatCurrencyByCode,
        getCountryByCode,
        
        // Option formatters
        getCryptocurrencyOptions,
        getPaymentMethodOptions,
        getFiatCurrencyOptions,
        getCountryOptions
    };
}