class Request {
    constructor(url = "", data = null, method = "GET") {
        this.url = url;
        this.data = data;
        this.method = method;
    }
    
    setUrl(url) {
        this.url = url
        return this
    }

    setData(data) {
        this.data = data
        return this
    }

    setMethod(method) {
        this.method = method
        return this
    }

    async makeFormRequest() {
        try {
            const options = {
                method: this.method,
                body: this.data,
            }

            let response = await fetch(this.url, options)
            if(!response.ok) {
                throw new Error("Request Error")
            }
            return response.json();
        } catch (error) {
            alert(error.message)
            return false
        }
    }

}