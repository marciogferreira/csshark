import { createContext, ReactNode, useEffect, useState } from 'react';
import Util from '../core/Util';
import Api from '../core/api';

type DataContext = {
    isLogged: boolean,
    setIsLogged: (data: any) => void,
    handleLogout: () => void,
    user: any,
    setUser: (data: any) => void,
    signIn: (data: any) => void,
}

const AuthContext = createContext({} as DataContext);

type DataProps = {
    children: ReactNode
}

export const AuthProvider = ({ children }: DataProps) => {

    const [isLogged, setIsLogged] = useState(false);
    const [user, setUser] = useState({ name: '', email: '', role: null });

    async function signIn(token: string) {
        await Util.setToken(token)
        setIsLogged(true);
    }

    async function handleLogout() {
        Util.removeToken();
        setIsLogged(false);
    }

    async function getUser() { 
        const response = await Api.post('me');
        setUser(response.data);
    }

    useEffect(() => {
        if(Util.getToken()) {
            setIsLogged(true);
            getUser();
        }
    }, []);

    return (
        <AuthContext.Provider value={{ 
            isLogged,
            setIsLogged,
            handleLogout,
            signIn,
            user,
            setUser
        }}>
            {children}
        </AuthContext.Provider>
    )
}

export default AuthContext;
