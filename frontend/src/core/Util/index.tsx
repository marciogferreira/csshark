const Util = {
    redirect: () => {
        // location.reload();
    },
    removeToken: () => {
        localStorage.removeItem("SYS@TOKEN_CSSHARK");
    },
    setToken: (token: string) => {
        localStorage.setItem("SYS@TOKEN_CSSHARK", token);
    },
    getToken: () => {
        const value = localStorage.getItem("SYS@TOKEN_CSSHARK");
        return value;
    },
    getTokenBearer: () => {
        
    },
    setUser: (token: string) => {
        localStorage.setItem("SYS@TOKEN_CSSHARK_USER", JSON.stringify(token));
    },
    getUser: () => {
        const value: any = localStorage.getItem("SYS@TOKEN_CSSHARK_USER");
        return JSON.parse(value);
    },
    removeUser: () => {
        localStorage.removeItem("SYS@TOKEN_CSSHARK_USER");
    },  
};

export default Util;