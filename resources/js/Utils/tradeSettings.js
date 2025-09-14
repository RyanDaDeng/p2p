// Trade Settings Utility Functions

export const getTradeSettings = () => {
    const saved = localStorage.getItem('tradeSettings');
    if (saved) {
        return JSON.parse(saved);
    }
    return {
        buyNotice: '',
        sellNotice: '',
        autoReply: ''
    };
};

export const saveTradeSettings = (settings) => {
    localStorage.setItem('tradeSettings', JSON.stringify(settings));
};

export const clearTradeSettings = () => {
    localStorage.removeItem('tradeSettings');
};