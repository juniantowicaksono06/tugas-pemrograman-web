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
            'validDate': 'Input %s tidak valid',
            'greaterThan': 'Input %s harus lebih besar dari %s',
            'lessThan': 'Input %s harus lebih kecil dari %s'
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

    validateDate(date, format = 'YYYY-MM-DD') {
        const regex = /^\d{4}-\d{2}-\d{2}$/;
        
        if (!regex.test(date)) {
            return false;
        }
        
        const [year, month, day] = date.split('-').map(Number);
        const dateObj = new Date(year, month - 1, day);
        
        return (
            dateObj.getFullYear() === year &&
            dateObj.getMonth() === month - 1 &&
            dateObj.getDate() === day
        );
    }

    phoneNumber(nomorHP) {
        const pattern = /\b(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})\b/;
        return pattern.test(nomorHP);
    }

    greaterThan(value, modifier) {
        return parseInt(value) > parseInt(modifier)
    }

    lessThan(value, modifier) {
        return parseInt(value) < parseInt(modifier)
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
                let dataValue = data[key];
    
                // Handle array input
                const isArray = key.endsWith('[]');
                var keyName = key
                if (isArray) {
                    keyName = key.slice(0, -2);
                    dataValue = data[keyName] || [];
                    if (!Array.isArray(dataValue)) {
                        dataValue = [dataValue];
                    }
                } else {
                    dataValue = [dataValue];
                }
    
                // Validate each item in the array or single value
                var x = 0;
                for (const value of dataValue) {
                    if (rules.toLowerCase().includes('required')) {
                        if (!this.required(value)) {
                            let inputName = key;
                            if (this.inputNames.hasOwnProperty(key)) {
                                inputName = this.inputNames[key];
                            }
                            const msg = this.messages['required'].replace('%s', inputName);
                            if (!this.storedMessages.hasOwnProperty(keyName)) {
                                this.storedMessages[keyName] = {};
                            }
                            if(isArray){
                                var errorKey = `${keyName}${x}`
                                if(!this.storedMessages[keyName].hasOwnProperty('required')) {
                                    this.storedMessages[keyName]['required'] = {};
                                }
                                this.storedMessages[keyName]['required'][errorKey] = msg
                                x++;
                            }
                            else {
                                this.storedMessages[keyName]['required'] = msg;
                            }
                            continue;
                        }
                    }
    
                    const eachRule = rules.split('|');
                    for (const rule of eachRule) {
                        let fn = rule;
                        let fnParameters = [value];
                        const matches = rule.match(/\[(.*?)\]/);
                        const keys = [key];
                        if (matches) {
                            fn = rule.substr(0, rule.indexOf('['));
                            keys.push(matches[1]);
                            fnParameters.push(data.hasOwnProperty(matches[1]) ? data[matches[1]] : null);
                        } else if (rule.includes(':')) {
                            const valueParts = rule.split(':');
                            fn = valueParts[0];
                            fnParameters.push(valueParts[1]);
                            keys.push(valueParts[1]);
                        }
                        for (let i = 0; i < keys.length; i++) {
                            if (this.inputNames.hasOwnProperty(keys[i])) {
                                keys[i] = this.inputNames[keys[i]];
                            }
                        }
                        if (typeof this[fn] === 'function') {
                            const result = this[fn](...fnParameters);
                            if (!result) {
                                this.errorStatus = true;
                                const msg = vsprintf(this.messages[fn], keys);
                                if (!this.storedMessages.hasOwnProperty(keyName)) {
                                    this.storedMessages[keyName] = {};
                                }
                                if(isArray){
                                    var errorKey = `${keyName}${x}`
                                    if(!this.storedMessages[keyName].hasOwnProperty(fn)) {
                                        this.storedMessages[keyName][fn] = {};
                                    }
                                    this.storedMessages[keyName][fn][errorKey] = msg
                                }
                                else {
                                    this.storedMessages[keyName][fn] = msg;
                                }
                            }
                        }
                    }
                    x++;
                }
            }
        }
        return Object.keys(this.storedMessages).length === 0;
    }

    getMessages() {
        return this.storedMessages;
    }
}
