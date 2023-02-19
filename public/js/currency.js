// Listing all the countrys name

let listPays = {
    "AED": "AE", "AFN": "AF", "XCD": "AG", "ALL": "AL", "AMD": "AM", "ANG": "AN", "AOA": "AO", "AQD": "AQ", "ARS": "AR", "AUD": "AU",
    "AZN": "AZ", "BAM": "BA", "BBD": "BB", "BDT": "BD", "XOF": "BE", "BGN": "BG", "BHD": "BH", "BIF": "BI", "BMD": "BM", "BND": "BN",
    "BOB": "BO", "BRL": "BR", "BSD": "BS", "NOK": "BV", "BWP": "BW", "BYR": "BY", "BZD": "BZ", "CAD": "CA", "CDF": "CD", "XAF": "CF",
    "CHF": "CH", "CLP": "CL", "CNY": "CN", "COP": "CO", "CRC": "CR", "CUP": "CU", "CVE": "CV", "CYP": "CY", "CZK": "CZ", "DJF": "DJ",
    "DKK": "DK", "DOP": "DO", "DZD": "DZ", "ECS": "EC", "EEK": "EE", "EGP": "EG", "ETB": "ET", "EUR": "FR", "FJD": "FJ", "FKP": "FK",
    "GBP": "GB", "GEL": "GE", "GGP": "GG", "GHS": "GH", "GIP": "GI", "GMD": "GM", "GNF": "GN", "GTQ": "GT", "GYD": "GY", "HKD": "HK",
    "HNL": "HN", "HRK": "HR", "HTG": "HT", "HUF": "HU", "IDR": "ID", "ILS": "IL", "INR": "IN", "IQD": "IQ", "IRR": "IR", "ISK": "IS",
    "JMD": "JM", "JOD": "JO", "JPY": "JP", "KES": "KE", "KGS": "KG", "KHR": "KH", "KMF": "KM", "KPW": "KP", "KRW": "KR", "KWD": "KW",
    "KYD": "KY", "KZT": "KZ", "LAK": "LA", "LBP": "LB", "LKR": "LK", "LRD": "LR", "LSL": "LS", "LTL": "LT", "LVL": "LV", "LYD": "LY",
    "MAD": "MA", "MDL": "MD", "MGA": "MG", "MKD": "MK", "MMK": "MM", "MNT": "MN", "MOP": "MO", "MRO": "MR", "MTL": "MT", "MUR": "MU",
    "MVR": "MV", "MWK": "MW", "MXN": "MX", "MYR": "MY", "MZN": "MZ", "NAD": "NA", "XPF": "NC", "NGN": "NG", "NIO": "NI", "NPR": "NP",
    "NZD": "NZ", "OMR": "OM", "PAB": "PA", "PEN": "PE", "PGK": "PG", "PHP": "PH", "PKR": "PK", "PLN": "PL", "PYG": "PY", "QAR": "QA",
    "RON": "RO", "RSD": "RS", "RUB": "RU", "RWF": "RW", "SAR": "SA", "SBD": "SB", "SCR": "SC", "SDG": "SD", "SEK": "SE", "SGD": "SG",
    "SKK": "SK", "SLL": "SL", "SOS": "SO", "SRD": "SR", "STD": "ST", "SVC": "SV", "SYP": "SY", "SZL": "SZ", "THB": "TH", "TJS": "TJ",
    "TMT": "TM", "TND": "TN", "TOP": "TO", "TRY": "TR", "TTD": "TT", "TWD": "TW", "TZS": "TZ", "UAH": "UA", "UGX": "UG", "USD": "US",
    "UYU": "UY", "UZS": "UZ", "VEF": "VE", "VND": "VN", "VUV": "VU", "YER": "YE", "ZAR": "ZA", "ZMK": "ZM", "ZWD": "ZW"
};

const selectList = document.querySelectorAll(".curreny_form select");
const fromDevise = document.querySelector(".deMontant select");
const toDevise = document.querySelector(".toMontant select");
const convertirButton = document.getElementById("convertir");

// console.log(fromDevise);
for (let i = 0; i < selectList.length; i++) {
    for (let deviseCode in listPays) {
        let selected;
        if (i == 0) {
            selected = deviseCode == "EUR" ? "selected" : "";
        } else if (i == 1) {
            selected = deviseCode == "USD" ? "selected" : "";
        }

        let optionBalise = `<option value="${deviseCode}" ${selected}>${deviseCode}</option>`;
        selectList[i].insertAdjacentHTML("beforeend", optionBalise);

        selectList[i].addEventListener("change", (event) => {
            afficheDrapeu(event.target);
        });
    }
}
// selecting drapeau as per country
function afficheDrapeu(element) {
    for (let changeCode in listPays) {
        if (changeCode == element.value) {
            let imgBalise = element.parentElement.parentElement.querySelector("img");
            imgBalise.src = `https://flagcdn.com/48x36/${listPays[changeCode].toLowerCase()}.png`;
        }
    }
}

// exchange icon interaction on click
const changeIcon = document.getElementById("icon");
changeIcon.addEventListener("click", () => {
    let initialCode = fromDevise.value;
    fromDevise.value = toDevise.value;
    toDevise.value = initialCode;
    afficheDrapeu(fromDevise);
    afficheDrapeu(toDevise);
    //when change the country code and the drapeu
    convertirDevise();
})

//Converting currency button
convertirButton.addEventListener("click", e => {
    e.preventDefault();
    convertirDevise();
})

function convertirDevise() {
    const inputMontant = document.getElementById("inputAmount").value;
    const tauxConvertirText = document.getElementById("tauxConvertir");
    if (inputMontant == "" || inputMontant == "0") {
        inputMontant = 1;
    }
    // API exchangerate-api.com
    let url = `https://v6.exchangerate-api.com/v6/e7ee5980c20408e58333386f/latest/${fromDevise.value}`;
    fetch(url).then(response => response.json()).then(result => {
        let echangeTaux = result.conversion_rates[toDevise.value];
        let totalEchTaux = (inputMontant * echangeTaux).toFixed(2);
        tauxConvertirText.innerText = `${inputMontant} ${fromDevise.value} = ${totalEchTaux} ${toDevise.value}`;
    }).catch(() => {
        tauxConvertirText.innerText = "Quelque chose a mal tourné";
    });
}

// The exchange rate on windows load
window.addEventListener("load", (event) =>{
    convertirDevise();
})

// That's all thank you 




