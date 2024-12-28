const Util = {
    redirect: () => {
        location.reload();
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

    }
};

export default Util;