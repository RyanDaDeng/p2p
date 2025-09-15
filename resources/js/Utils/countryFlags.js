/**
 * SVG flag components map
 * Based on backend countries.php configuration
 * Returns SVG string for each country flag
 */
const COUNTRY_FLAG_SVG = {
    // China
    'cn': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#DE2910"/>
        <g fill="#FFDE00">
            <polygon points="4,3 4.6,4.8 6.5,4.8 5,6 5.5,7.8 4,6.6 2.5,7.8 3,6 1.5,4.8 3.4,4.8"/>
            <polygon points="8,1 8.2,1.6 8.8,1.6 8.3,2 8.5,2.6 8,2.2 7.5,2.6 7.7,2 7.2,1.6 7.8,1.6" transform="scale(0.7)"/>
            <polygon points="10,3 10.2,3.6 10.8,3.6 10.3,4 10.5,4.6 10,4.2 9.5,4.6 9.7,4 9.2,3.6 9.8,3.6" transform="scale(0.7)"/>
            <polygon points="10,6 10.2,6.6 10.8,6.6 10.3,7 10.5,7.6 10,7.2 9.5,7.6 9.7,7 9.2,6.6 9.8,6.6" transform="scale(0.7)"/>
            <polygon points="8,8 8.2,8.6 8.8,8.6 8.3,9 8.5,9.6 8,9.2 7.5,9.6 7.7,9 7.2,8.6 7.8,8.6" transform="scale(0.7)"/>
        </g>
    </svg>`,

    // United States
    'us': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#B22234"/>
        <g fill="white">
            <rect y="1.23" width="24" height="1.23"/>
            <rect y="3.69" width="24" height="1.23"/>
            <rect y="6.15" width="24" height="1.23"/>
            <rect y="8.62" width="24" height="1.23"/>
            <rect y="11.08" width="24" height="1.23"/>
            <rect y="13.54" width="24" height="1.23"/>
        </g>
        <rect width="9.6" height="8.62" fill="#3C3B6E"/>
    </svg>`,

    // United Kingdom
    'uk': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#012169"/>
        <path d="M0,0 L24,16 M24,0 L0,16" stroke="white" stroke-width="3"/>
        <path d="M0,0 L24,16 M24,0 L0,16" stroke="#C8102E" stroke-width="2"/>
        <path d="M12,0 v16 M0,8 h24" stroke="white" stroke-width="5"/>
        <path d="M12,0 v16 M0,8 h24" stroke="#C8102E" stroke-width="3"/>
    </svg>`,

    // Singapore
    'sg': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="8" fill="#EF3340"/>
        <rect y="8" width="24" height="8" fill="white"/>
        <g fill="white">
            <circle cx="6" cy="4" r="2.5" fill="white"/>
            <circle cx="7.5" cy="4" r="2.5" fill="#EF3340"/>
            <g transform="translate(5,3)">
                <polygon points="2,0 2.3,0.9 3.2,0.9 2.5,1.4 2.8,2.3 2,1.8 1.2,2.3 1.5,1.4 0.8,0.9 1.7,0.9" fill="white" transform="scale(0.3)"/>
                <polygon points="6,0 6.3,0.9 7.2,0.9 6.5,1.4 6.8,2.3 6,1.8 5.2,2.3 5.5,1.4 4.8,0.9 5.7,0.9" fill="white" transform="scale(0.3)"/>
                <polygon points="4,2 4.3,2.9 5.2,2.9 4.5,3.4 4.8,4.3 4,3.8 3.2,4.3 3.5,3.4 2.8,2.9 3.7,2.9" fill="white" transform="scale(0.3)"/>
                <polygon points="1,3 1.3,3.9 2.2,3.9 1.5,4.4 1.8,5.3 1,4.8 0.2,5.3 0.5,4.4 -0.2,3.9 0.7,3.9" fill="white" transform="scale(0.3)"/>
                <polygon points="7,3 7.3,3.9 8.2,3.9 7.5,4.4 7.8,5.3 7,4.8 6.2,5.3 6.5,4.4 5.8,3.9 6.7,3.9" fill="white" transform="scale(0.3)"/>
            </g>
        </g>
    </svg>`,

    // Hong Kong
    'hk': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#DE2910"/>
        <g fill="white" transform="translate(12,8)">
            <path d="M0,-4 C1,-3 2,-2 2,0 C2,2 1,3 0,4 C-1,3 -2,2 -2,0 C-2,-2 -1,-3 0,-4" fill="white"/>
            <g transform="rotate(0)">
                <path d="M0,-3.5 L0.5,-2 L-0.5,-2 Z" fill="#DE2910"/>
            </g>
            <g transform="rotate(72)">
                <path d="M0,-3.5 L0.5,-2 L-0.5,-2 Z" fill="#DE2910"/>
            </g>
            <g transform="rotate(144)">
                <path d="M0,-3.5 L0.5,-2 L-0.5,-2 Z" fill="#DE2910"/>
            </g>
            <g transform="rotate(216)">
                <path d="M0,-3.5 L0.5,-2 L-0.5,-2 Z" fill="#DE2910"/>
            </g>
            <g transform="rotate(288)">
                <path d="M0,-3.5 L0.5,-2 L-0.5,-2 Z" fill="#DE2910"/>
            </g>
        </g>
    </svg>`,

    // Japan
    'jp': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="white"/>
        <circle cx="12" cy="8" r="4.8" fill="#BC002D"/>
    </svg>`,

    // South Korea
    'kr': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="white"/>
        <g transform="translate(12,8)">
            <circle cx="0" cy="0" r="4" fill="#C60C30"/>
            <path d="M-2,0 A2,2 0 0,1 2,0 A2,2 0 0,0 -2,0" fill="#003478"/>
            <rect x="-5" y="-5" width="2" height="1" fill="black" transform="rotate(30)"/>
            <rect x="-5" y="4" width="2" height="1" fill="black" transform="rotate(30)"/>
            <rect x="3" y="-5" width="2" height="1" fill="black" transform="rotate(30)"/>
            <rect x="3" y="4" width="2" height="1" fill="black" transform="rotate(30)"/>
        </g>
    </svg>`,

    // Australia
    'au': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#012169"/>
        <path d="M0,0 L12,8 M12,0 L0,8" stroke="white" stroke-width="1.5"/>
        <path d="M0,0 L12,8 M12,0 L0,8" stroke="#E4002B" stroke-width="1"/>
        <path d="M6,0 v8 M0,4 h12" stroke="white" stroke-width="2.5"/>
        <path d="M6,0 v8 M0,4 h12" stroke="#E4002B" stroke-width="1.5"/>
        <g fill="white">
            <polygon points="12,10 12.3,10.9 13.2,10.9 12.5,11.4 12.8,12.3 12,11.8 11.2,12.3 11.5,11.4 10.8,10.9 11.7,10.9"/>
            <polygon points="18,12 18.15,12.45 18.6,12.45 18.25,12.7 18.4,13.15 18,12.9 17.6,13.15 17.75,12.7 17.4,12.45 17.85,12.45" transform="scale(0.7)"/>
            <polygon points="6,12 6.15,12.45 6.6,12.45 6.25,12.7 6.4,13.15 6,12.9 5.6,13.15 5.75,12.7 5.4,12.45 5.85,12.45" transform="scale(0.7)"/>
            <polygon points="21,8 21.15,8.45 21.6,8.45 21.25,8.7 21.4,9.15 21,8.9 20.6,9.15 20.75,8.7 20.4,8.45 20.85,8.45" transform="scale(0.7)"/>
            <polygon points="18,5 18.15,5.45 18.6,5.45 18.25,5.7 18.4,6.15 18,5.9 17.6,6.15 17.75,5.7 17.4,5.45 17.85,5.45" transform="scale(0.5)"/>
        </g>
    </svg>`,

    // Canada
    'ca': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="6" height="16" fill="#FF0000"/>
        <rect x="6" width="12" height="16" fill="white"/>
        <rect x="18" width="6" height="16" fill="#FF0000"/>
        <path d="M12,4 L11,8 L9,7 L10,9 L8,9 L10,10 L9,12 L12,10 L15,12 L14,10 L16,9 L14,9 L15,7 L13,8 Z" fill="#FF0000"/>
    </svg>`,

    // Germany
    'de': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="5.33" fill="black"/>
        <rect y="5.33" width="24" height="5.33" fill="#DD0000"/>
        <rect y="10.67" width="24" height="5.33" fill="#FFCE00"/>
    </svg>`,

    // France
    'fr': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="8" height="16" fill="#002395"/>
        <rect x="8" width="8" height="16" fill="white"/>
        <rect x="16" width="8" height="16" fill="#ED2939"/>
    </svg>`,

    // India
    'in': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="5.33" fill="#FF9933"/>
        <rect y="5.33" width="24" height="5.33" fill="white"/>
        <rect y="10.67" width="24" height="5.33" fill="#138808"/>
        <circle cx="12" cy="8" r="2" fill="#000080"/>
        <circle cx="12" cy="8" r="1.8" fill="white"/>
        <g fill="#000080" transform="translate(12,8)">
            <circle cx="0" cy="0" r="0.4"/>
            <g id="spoke">
                <line x1="0" y1="0" x2="0" y2="-1.8" stroke="#000080" stroke-width="0.15"/>
            </g>
            <use href="#spoke" transform="rotate(15)"/>
            <use href="#spoke" transform="rotate(30)"/>
            <use href="#spoke" transform="rotate(45)"/>
            <use href="#spoke" transform="rotate(60)"/>
            <use href="#spoke" transform="rotate(75)"/>
            <use href="#spoke" transform="rotate(90)"/>
            <use href="#spoke" transform="rotate(105)"/>
            <use href="#spoke" transform="rotate(120)"/>
            <use href="#spoke" transform="rotate(135)"/>
            <use href="#spoke" transform="rotate(150)"/>
            <use href="#spoke" transform="rotate(165)"/>
            <use href="#spoke" transform="rotate(180)"/>
            <use href="#spoke" transform="rotate(195)"/>
            <use href="#spoke" transform="rotate(210)"/>
            <use href="#spoke" transform="rotate(225)"/>
            <use href="#spoke" transform="rotate(240)"/>
            <use href="#spoke" transform="rotate(255)"/>
            <use href="#spoke" transform="rotate(270)"/>
            <use href="#spoke" transform="rotate(285)"/>
            <use href="#spoke" transform="rotate(300)"/>
            <use href="#spoke" transform="rotate(315)"/>
            <use href="#spoke" transform="rotate(330)"/>
            <use href="#spoke" transform="rotate(345)"/>
        </g>
    </svg>`,

    // Russia
    'ru': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="5.33" fill="white"/>
        <rect y="5.33" width="24" height="5.33" fill="#0039A6"/>
        <rect y="10.67" width="24" height="5.33" fill="#D52B1E"/>
    </svg>`,

    // Brazil
    'br': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#009739"/>
        <polygon points="12,2 22,8 12,14 2,8" fill="#FEDD00"/>
        <circle cx="12" cy="8" r="3" fill="#012169"/>
    </svg>`,

    // Mexico
    'mx': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="8" height="16" fill="#006341"/>
        <rect x="8" width="8" height="16" fill="white"/>
        <rect x="16" width="8" height="16" fill="#C8102E"/>
    </svg>`,

    // United Arab Emirates
    'ae': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="6" height="16" fill="#00732F"/>
        <rect x="6" width="18" height="5.33" fill="#009639"/>
        <rect x="6" y="5.33" width="18" height="5.33" fill="white"/>
        <rect x="6" y="10.67" width="18" height="5.33" fill="black"/>
    </svg>`,

    // Taiwan
    'tw': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#FE0000"/>
        <rect width="12" height="8" fill="#000095"/>
        <circle cx="6" cy="4" r="2" fill="white"/>
        <g fill="#000095" transform="translate(6,4)">
            <polygon points="0,-1.8 0.5,-0.5 1.8,0 0.5,0.5 0,1.8 -0.5,0.5 -1.8,0 -0.5,-0.5" fill="white"/>
        </g>
    </svg>`,

    // Thailand
    'th': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="2.67" fill="#ED1C24"/>
        <rect y="2.67" width="24" height="2.67" fill="white"/>
        <rect y="5.33" width="24" height="5.33" fill="#241D4F"/>
        <rect y="10.67" width="24" height="2.67" fill="white"/>
        <rect y="13.33" width="24" height="2.67" fill="#ED1C24"/>
    </svg>`,

    // Vietnam
    'vn': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="16" fill="#DA251D"/>
        <polygon points="12,4 13.2,7.6 16.8,7.6 13.8,9.8 15,13.4 12,11.2 9,13.4 10.2,9.8 7.2,7.6 10.8,7.6" fill="#FFFF00"/>
    </svg>`,

    // Philippines
    'ph': `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg">
        <rect width="24" height="8" fill="#0038A8"/>
        <rect y="8" width="24" height="8" fill="#CE1126"/>
        <polygon points="0,0 8,4 0,8" fill="white"/>
        <g fill="#FCD116">
            <circle cx="3" cy="4" r="1" fill="#FCD116"/>
            <polygon points="2,4 2.2,4.6 2.8,4.6 2.3,4.9 2.5,5.5 2,5.2 1.5,5.5 1.7,4.9 1.2,4.6 1.8,4.6" transform="scale(0.5) translate(1,5)"/>
            <polygon points="5,2 5.2,2.6 5.8,2.6 5.3,2.9 5.5,3.5 5,3.2 4.5,3.5 4.7,2.9 4.2,2.6 4.8,2.6" transform="scale(0.5) translate(1,1)"/>
            <polygon points="5,6 5.2,6.6 5.8,6.6 5.3,6.9 5.5,7.5 5,7.2 4.5,7.5 4.7,6.9 4.2,6.6 4.8,6.6" transform="scale(0.5) translate(1,3)"/>
        </g>
    </svg>`
};

// Map uppercase codes to lowercase for compatibility
Object.keys(COUNTRY_FLAG_SVG).forEach(key => {
    COUNTRY_FLAG_SVG[key.toUpperCase()] = COUNTRY_FLAG_SVG[key];
});
// Add GB as alias for UK
COUNTRY_FLAG_SVG['GB'] = COUNTRY_FLAG_SVG['UK'];
COUNTRY_FLAG_SVG['gb'] = COUNTRY_FLAG_SVG['uk'];

/**
 * Get SVG flag for country code
 * @param {string} countryCode - Two-letter country code (e.g., 'US', 'CN', 'GB')
 * @returns {string} SVG string or empty string if not found
 */
export function getCountryFlag(countryCode) {
    if (!countryCode || typeof countryCode !== 'string') {
        return '';
    }

    // Handle case variations
    const code = countryCode.toLowerCase();

    // Return the SVG from the map, or empty string if not found
    return COUNTRY_FLAG_SVG[code] || '';
}

/**
 * Get country flag with fallback
 * @param {string} countryCode - Two-letter country code
 * @param {string} fallback - Fallback SVG if flag cannot be found
 * @returns {string} SVG flag or fallback
 */
export function getCountryFlagWithFallback(countryCode, fallback = `<svg viewBox="0 0 24 16" xmlns="http://www.w3.org/2000/svg"><rect width="24" height="16" fill="#e0e0e0"/></svg>`) {
    const flag = getCountryFlag(countryCode);
    return flag || fallback;
}

/**
 * Common country codes for quick reference
 * This can be extended as needed
 */
export const COUNTRY_CODES = {
    // Asia
    CHINA: 'CN',
    JAPAN: 'JP',
    KOREA: 'KR',
    INDIA: 'IN',
    SINGAPORE: 'SG',
    THAILAND: 'TH',
    VIETNAM: 'VN',
    PHILIPPINES: 'PH',
    INDONESIA: 'ID',
    MALAYSIA: 'MY',
    HONG_KONG: 'HK',
    TAIWAN: 'TW',

    // Americas
    USA: 'US',
    CANADA: 'CA',
    MEXICO: 'MX',
    BRAZIL: 'BR',
    ARGENTINA: 'AR',
    COLOMBIA: 'CO',
    CHILE: 'CL',
    PERU: 'PE',
    VENEZUELA: 'VE',

    // Europe
    UK: 'GB',
    GERMANY: 'DE',
    FRANCE: 'FR',
    ITALY: 'IT',
    SPAIN: 'ES',
    NETHERLANDS: 'NL',
    BELGIUM: 'BE',
    SWITZERLAND: 'CH',
    AUSTRIA: 'AT',
    SWEDEN: 'SE',
    NORWAY: 'NO',
    DENMARK: 'DK',
    FINLAND: 'FI',
    POLAND: 'PL',
    CZECH: 'CZ',
    HUNGARY: 'HU',
    PORTUGAL: 'PT',
    GREECE: 'GR',
    TURKEY: 'TR',
    RUSSIA: 'RU',
    UKRAINE: 'UA',

    // Oceania
    AUSTRALIA: 'AU',
    NEW_ZEALAND: 'NZ',

    // Middle East & Africa
    UAE: 'AE',
    SAUDI_ARABIA: 'SA',
    ISRAEL: 'IL',
    EGYPT: 'EG',
    SOUTH_AFRICA: 'ZA',
    NIGERIA: 'NG',
    KENYA: 'KE',
    MOROCCO: 'MA',
    TUNISIA: 'TN',
};

/**
 * Test function to display some flags
 */
export function testFlags() {
    const testCodes = ['US', 'CN', 'GB', 'JP', 'DE', 'FR', 'BR', 'IN', 'AU', 'CA'];
    const results = {};
    testCodes.forEach(code => {
        results[code] = getCountryFlag(code);
    });
    return results;
}