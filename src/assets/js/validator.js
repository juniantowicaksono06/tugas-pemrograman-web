class Validator {
    constructor() {
        this.errorStatus = false;
        this.messages = {
            'validEmail': 'Email tidak valid',
            'required': 'Input %s wajib diisi',
            'optional': "",
            'matches': 'Input %s tidak sama dengan %s',
            'numeric': 'Input %s harus berupa angka',
            'phoneNumber': 'Nomor Handphone tidak valid',
            'max': 'Input %s maksimal berupa %s karakter',
            'min': 'Input %s minimal berupa %s karakter',
            'validDate': 'Input %s formatnya harus \'Tahun-Bulan-Tanggal\''
        };
        this.storedMessages = {};
        this.inputNames = {};
    }

    optional(value) {
        return true;
    }

    validDate(dateString) {
        // Regular expression to check if date is in YYYY-MM-DD format
        const regex = /^\d{4}-\d{2}-\d{2}$/;
    
        // If the date string does not match the format, return false
        if (!regex.test(dateString)) {
            return false;
        }
    
        // Split the date string into components
        const [year, month, day] = dateString.split('-').map(Number);
    
        // Create a date object with the components
        const date = new Date(year, month - 1, day);
    
        // Check if the components create a valid date
        if (
            date.getFullYear() === year &&
            date.getMonth() === month - 1 &&
            date.getDate() === day
        ) {
            return true;
        } else {
            return false;
        }
    }

    phoneNumber(nomorHP) {
        const pattern = /\b(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})\b/;
        return pattern.test(nomorHP);
    }

    max(value, length) {
        return value.length <= length;
    }

    min(value, length) {
        return value.length >= length;
    }

    validEmail(email) {
        const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return regex.test(email);
    }

    matches(input, withValue) {
        return input === withValue;
    }

    required(input) {
        return input !== undefined && input !== null && input !== '';
    }

    numeric(input) {
        return !isNaN(input);
    }

    setMessages(messages) {
        Object.assign(this.messages, messages);
    }

    setInputName(input) {
        this.inputNames = input
    }

    validate(input, data) {
        this.storedMessages = {};
        for (const key in input) {
            if (input.hasOwnProperty(key)) {
                const rules = input[key];
                if (rules.toLowerCase().includes('required')) {
                    if (!data.hasOwnProperty(key) || !this.required(data[key])) {
                        let inputName = key
                        if(this.inputNames.hasOwnProperty(key)) {
                            inputName = this.inputNames[key]
                        }
                        const msg = this.messages['required'].replace('%s', inputName);
                        this.storedMessages[key] = {
                            'required': msg
                        };
                        continue;
                    }
                }
                const eachRule = rules.split('|');
                for (const rule of eachRule) {
                    let fn = rule;
                    let fnParameters = [data[key]];
                    const matches = rule.match(/\[(.*?)\]/);
                    const keys = [key];
                    if (matches) {
                        fn = rule.substr(0, rule.indexOf('['));
                        keys.push(matches[1]);
                        fnParameters.push(data.hasOwnProperty(matches[1]) ? data[matches[1]] : null);
                    } else if (rule.includes(':')) {
                        const value = rule.split(':');
                        fn = value[0];
                        fnParameters.push(value[1]);
                        keys.push(value[1]);
                    }
                    for(let i = 0; i < keys.length; i++) {
                        if(this.inputNames.hasOwnProperty(keys[i])) {
                            keys[i] = this.inputNames[keys[i]]
                        }
                    }
                    if (typeof this[fn] === 'function') {
                        const result = this[fn](...fnParameters);
                        if (!result) {
                            this.errorStatus = true;
                            const msg = vsprintf(this.messages[fn], keys);
                            if (!this.storedMessages.hasOwnProperty(key)) {
                                this.storedMessages[key] = {};
                            }
                            this.storedMessages[key][fn] = msg;
                        }
                    }
                }
            }
        }
        return Object.keys(this.storedMessages).length === 0;
    }

    getMessages() {
        return this.storedMessages;
    }
}
